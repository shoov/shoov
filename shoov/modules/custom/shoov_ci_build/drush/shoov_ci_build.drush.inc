<?php

/**
 * @file
 * Create a CI build item.
 */


/**
 * Implements hook_drush_command().
 */
function shoov_ci_build_drush_command() {
  $items = array();

  $items['create-ci-build-item'] = array(
    'description' => 'Create a CI build item.',
  );

  $items['check-nodejs-server-health'] = array(
    'description' => 'Check nodejs servers are working.',
    'options' => array(
      'email' => 'The email address to send message.',
    ),
  );

  $items['disable-ci-builds'] = array(
    'description' => 'disables CI Builds of repositories without .shoov.yml',
    'options' => array(
      'range' => 'The range of CI Builds to check.',
    ),
  );

  return $items;
}

/**
 * Implements drush_hook_command().
 *
 * Command callback for "create-ci-build-item".
 */
function drush_shoov_ci_build_create_ci_build_item() {
  drush_shoov_ci_build_release_stuck_items();

  if (!$messages = shoov_ci_build_get_ci_builds()) {
    drush_log('No items found for processing');
    return;
  }

  $count = count($messages);
  drush_log(dt('Found @count items for processing', array('@count' => $count)));


  // Send requests.
  $delta = 1;
  foreach ($messages as $message) {
    $nodejs_server_url = shoov_ci_build_get_nodejs_server_url($message);

    $account = user_load($message->uid);

    $url = array(
      $nodejs_server_url,
      'ci',
      $message->mid,
      shoov_restful_get_user_token($account),
    );

    $url = implode('/', $url);

    drupal_http_request($url);

    $params = array(
      '@delta' => $delta,
      '@count' => $count,
      '@id' => $message->mid,
      '@url' => $url,
    );

    drush_log(dt('@delta / @count) Process message ID @id, sent request to @url', $params));
    $delta++;

    // @todo: Remove this hack.
    // Sleep for 1 second, to prevent INSERT errors when there are multiple
    // builds.
    sleep(variable_get('shoov_ci_build_create_build_item_sleep', 1));
  }
}

/**
 * Release items that are marked in progress for too long.
 */
function drush_shoov_ci_build_release_stuck_items() {
  if (!$messages = shoov_ci_build_get_stuck_ci_builds()) {
    drush_log('No stuck items found for processing');
    return;
  }

  $delta = 1;
  $count = count($messages);
  foreach ($messages as $message) {
    $wrapper = entity_metadata_wrapper('message', $message);
    $wrapper->field_ci_build_status->set('queue');
    // Set start time to "now", so it won't be processed again as a stuck
    // build item.
    $wrapper->field_ci_build_timestamp->set(time());
    $wrapper->save();

    $params = array(
      '@delta' => $delta,
      '@count' => $count,
      '@id' => $message->mid,
    );

    drush_log(dt('@delta / @count) Re-queued message ID @id after being stuck.', $params));
    $delta++;
  }
}

/**
 * Check all existing servers status. If one of them is down - send email to
 * the site admin.
 */
function drush_shoov_ci_build_check_nodejs_server_health() {
  $servers = array();
  $servers['primary_server'] = variable_get('shoov_nodejs_server', 'http://localhost:3000');
  $servers['secondary_server'] = variable_get('shoov_nodejs_server_secondary', 'http://localhost:3001');

  foreach($servers as $key => $uri) {
    $result = drupal_http_request($uri);
    $status_code = $result->code;

    if ($status_code == 200) {
      continue;
    }

    // Send email to admin.
    $arguments = array(
      '@server' => $key,
      '@url' => $uri,
      );
    $message = message_create('server_is_down', array('arguments' => $arguments));
    $message->uid = 1;

    $options = array(
      'mail' => drush_get_option('email', variable_get('site_mail', ini_get('sendmail_from'))),
    );
    drush_print(format_string('The @server is down. Url is @url', $arguments));
    message_notify_send_message($message, $options);
  }
}

/**
 * Disable CI Builds of repositories that don't have .shoov.yml file in it.
 */
function drush_shoov_ci_build_disable_ci_builds() {
  $range = drush_get_option('range', 50);
  $nid = variable_get('shoov_ci_build_last_checked_for_config_file', 0);
  $query = new EntityFieldQuery();
  $query
    ->entityCondition('entity_type', 'node')
    ->entityCondition('bundle', 'ci_build')
    ->propertyOrderBy('nid', 'ACS')
    ->fieldCondition('field_ci_build_enabled', 'value', TRUE)
    ->addTag('DANGEROUS_ACCESS_CHECK_OPT_OUT');
  if ($nid) {
    $query->propertyCondition('nid', $nid, '>');
  }
  $result = $query
    ->range(0, $range)
    ->execute();
  if (empty($result['node'])) {
    drush_print('No CI Builds items left to check');
    variable_set('shoov_ci_build_last_checked_for_config_file', 0);
    return;
  }
  $ids = array_keys($result['node']);
  $nodes = node_load_multiple($ids);

  // Iterate though CI Build items.
  foreach ($nodes as $node) {
    $ci_build_id = $node->nid;
    $wrapper = entity_metadata_wrapper('node', $node);

    // Check config file exists for the new CI Build.
    if (shoov_repository_config_file_exists($wrapper->og_repo->value(), $wrapper->field_git_branch->value())) {
      continue;
    }
    $wrapper->field_ci_build_enabled->set(FALSE);
    $wrapper->save();
    $arguments = array(
      '@build_id' => $ci_build_id,
      '@title' => $node->title
    );
    drush_print(dt('CI Build @build_id (@title) has been disabled.', $arguments));
  }
  variable_set('shoov_ci_build_last_checked_for_config_file', end($ids));
  $params = array(
    '@start' => reset($ids),
    '@end' => end($ids),
  );
  drush_print(dt('Process builds from id @start to id @end.', $params));
}
