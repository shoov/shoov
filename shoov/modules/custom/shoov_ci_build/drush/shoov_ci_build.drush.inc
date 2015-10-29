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
    'description' => 'Check nodejs servers are working.'
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
      'mail' => variable_get('site_mail', ini_get('sendmail_from')),
    );
    drush_print(format_string('The @server is down. Url is @url', $arguments));
    message_notify_send_message($message, $options);

  }
}
