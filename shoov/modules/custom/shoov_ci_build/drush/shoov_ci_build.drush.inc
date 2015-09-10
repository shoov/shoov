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

  $items['release-failed-items'] = array(
    'description' => 'Release items that fail for long time.',
    'options' => array(
      'nid' => 'The last executed node id.',
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
 * Release items that are marked in progress for too long.
 */
function drush_shoov_ci_build_release_failed_items() {

  $range = 50;

  // Get CI incidents of the enabled CI Builds, that haven't been fixed for more than 3 days.
  $query = new EntityFieldQuery();
  $result = $query
    ->entityCondition('entity_type', 'node')
    ->entityCondition('bundle', 'ci_incident')
    ->propertyCondition('status', NODE_PUBLISHED)
    // Incident was created more than 3 days ago.
    ->propertyCondition('created', strtotime('-3 days'), '<')
    // CI Build is still enabled.
    ->addTag('ci_build_enabled')
    // CI Build wasn't fixed yet.
    ->addTag('fixed_build_empty')
    // Take the oldest ones first. Like this if message will be sent for current
    // Builds, during the next run we'll handle the others.
    ->propertyOrderBy('changed', 'ASC')
    // Skip access check.
    ->addTag('DANGEROUS_ACCESS_CHECK_OPT_OUT')
    ->range(0, $range)
    ->execute();

  if (empty($result['node'])) {
    drush_log('No long time failing incidents were found.', 'success');
    return;
  }
  $ids = array_keys($result['node']);
  $nodes = node_load_multiple($ids);

  foreach ($nodes as $node) {

    // Incident wrapper.
    $wrapper = entity_metadata_wrapper('node', $node);

    // CI Build wrapper
    $build_wrapper = $wrapper->field_ci_build;

    if (!$build_wrapper->field_ci_build_enabled->value()) {
      // Build is already disabled.
      $wrapper->field_reminders_sent->set(0);
      $wrapper->field_ci_build_system_disabled->set(1);
      $wrapper->save();
      continue;
    }

    $timestamp = $node->created;
    $params = array(
      '@build' => $build_wrapper->label(),
    );

    if ($arguments = drush_shoov_ci_build_third_message_should_be_sent($timestamp, $wrapper)) {
      // Build is failing more then 7 days: increase reminder counter,
      // disable the CI Build.
      $wrapper->field_reminders_sent->set(0);
      $wrapper->field_ci_build_system_disabled->set(1);
      // Disable the CI Build.
      $build_wrapper->field_ci_build_enabled->set(0);
      $build_wrapper->save();
      $params['@days'] = 7;
    }
    elseif ($arguments = drush_shoov_ci_build_second_message_should_be_sent($timestamp, $wrapper)) {
      // Build is failing more then 6 days: increase reminder counter.
      $wrapper->field_reminders_sent->set(2);
      $params['@days'] = 6;
    }
    elseif ($arguments = drush_shoov_ci_build_first_message_should_be_sent($timestamp, $wrapper)) {
      // Build is failing more than 3 days: increase reminder counter.
      $wrapper->field_reminders_sent->set(1);
      $params['@days'] = 3;
    }

    if ($arguments) {
      // Incident was changed and should be saved. Message should be sent.
      $wrapper->save();
      drush_shoov_ci_build_send_reminder($build_wrapper->nid->value(), $arguments);
      drush_log(dt('Notification that CI build @build is failing more than @days days has been sent.', $params), 'success');
    }
  }
}

/**
 * Send message about failing CI Build to user.
 *
 * @param $build_id
 *  CI Build node ID.
 * @param array $arguments
 *  Message arguments
 */
function drush_shoov_ci_build_send_reminder($build_id, $arguments = array()) {
  $build = node_load($build_id);
  // Create a message.
  $reminder = message_create('ci_build_failing_reminder', array('uid' => $build->uid, 'arguments' => $arguments));
  $reminder_wrapper = entity_metadata_wrapper('message', $reminder);
  $reminder_wrapper->field_ci_build->set($build->nid);
  $reminder_wrapper->save();

  $message = message_load($reminder_wrapper->mid->value());
  message_notify_send_message($message);
}

/**
 * Check if Build is failing more than 3 days and the 1st notification should
 * be sent.
 *
 * @param $timestamp
 *  Node creation timestamp.
 * @param $wrapper
 *  Incident node's wrapper object.
 *
 * @return array|bool
 *  Returns array of arguments for the message. If notification should not be
 *  sent - returns FALSE.
 */
function drush_shoov_ci_build_first_message_should_be_sent($timestamp, $wrapper) {
  if (!$timestamp < strtotime('-6 days') && !$wrapper->field_reminders_sent->value() < 1) {
    return FALSE;
  }
  $arguments = array(
    '@{failing_days}' => 3,
    '@{action}' => t('will be disabled automatically in 4 days'),
  );

  return $arguments;
}

/**
 * Check if Build is failing more than 6 days and the 2nd notification should
 * be sent.
 *
 * @param $timestamp
 *  Node creation timestamp.
 * @param $wrapper
 *  Incident node's wrapper object.
 *
 * @return array|bool
 *  Returns array of arguments for the message. If notification should not be
 *  sent - returns FALSE.
 */
function drush_shoov_ci_build_second_message_should_be_sent($timestamp, $wrapper) {
  if (!$timestamp < strtotime('-7 days') && !$wrapper->field_reminders_sent->value() < 2) {
    return FALSE;
  }
  $arguments = array(
    '@{failing_days}' => 6,
    '@{action}' => t('will be disabled automatically in 1 days'),
  );

  return $arguments;
}

/**
 * Check if Build is failing more than 7 days and the 3d notification should
 * be sent.
 *
 * @param $timestamp
 *  Node creation timestamp.
 * @param $wrapper
 *  Incident node's wrapper object.
 *
 * @return array|bool
 *  Returns array of arguments for the message. If notification should not be
 *  sent - returns FALSE.
 */
function drush_shoov_ci_build_third_message_should_be_sent($timestamp, $wrapper) {
  if (!$timestamp < strtotime('-8 days') && !$wrapper->field_reminders_sent->value() < 3) {
    return FALSE;
  }
  $arguments = array(
    '@{failing_days}' => 7,
    '@{action}' => t('has been disabled automatically'),
  );

  return $arguments;
}
