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

  $query = new EntityFieldQuery();
  $result = $query
    ->entityCondition('entity_type', 'node')
    ->entityCondition('bundle', 'ci_incident')
    ->propertyCondition('status', NODE_PUBLISHED)
    ->propertyCondition('created', time() - 3*24*60*60, '<')
    ->fieldCondition('field_ci_build_system_disabled', 'value', FALSE)
    ->addTag('fixed_build_empty')
    ->propertyOrderBy('nid', 'ASC')
    ->addTag('DANGEROUS_ACCESS_CHECK_OPT_OUT')
    ->range(0, $range)
    ->execute();

  if (empty($result['node'])) {
    return;
  }

  $ids = array_keys($result['node']);
  $nodes = node_load_multiple($ids);

  foreach ($nodes as $node) {
    drush_print($node->nid);

    // Incident wrapper.
    $wrapper = entity_metadata_wrapper('node', $node);

    // Failing CI Build.
    $build = $wrapper->field_ci_build->value();

    // CI Build wrapper
    $build_wrapper = entity_metadata_wrapper('node', $build);

    if (!$build_wrapper->field_ci_build_enabled->value()) {
      // Build is already disabled.
      $wrapper->field_reminders_sent->set(0);
      $wrapper->field_ci_build_system_disabled->set(1);
      $wrapper->save();
      continue;
    }

    $timestamp = $node->created;

    if ($timestamp < time() - 6*24*60*60 && $wrapper->field_reminders_sent->value() < 1) {
      // Build is failing more than 3 days and the 1st reminder hasn't been sent yet:
      // - send first reminder;
      // - increase reminder counter.

      $arguments = array(
        '@{failing_days}' => 3,
        '@{action}' => t('will be disabled in 4 days'),
      );

      $reminder = message_create('ci_build_failing_reminder', array('uid' => $node->uid, 'arguments' => $arguments));

      $reminder_wrapper = entity_metadata_wrapper('message', $reminder);
      $reminder_wrapper->field_ci_build->set($build->nid);
      $reminder_wrapper->save();

      print_r(message_load($reminder_wrapper->mid->value()));
      $message = message_load($reminder_wrapper->mid->value());
      message_notify_send_message($message);

      $wrapper->field_reminders_sent->set(1);
      $wrapper->save();
      continue;
    }

//    if ($timestamp > time() - 7*24*60*60 && $wrapper->field_reminders_count->value() < 2) {
//      // Build is failing more then 6 days and the 2nd reminder hasn't been sent yet:
//      // - send second reminder;
//      // - increase reminder counter.
//
//
//      $wrapper->field_reminders_count->set(2);
//      $wrapper->save();
//      continue;
//    }
//
//    if ($timestamp > time() - 8*24*60*60 && $wrapper->field_reminders_count->value() < 3) {
//      // Build is failing more then 7 days and the last reminder hasn't been sent yet:
//      // - send the last reminder;
//      // - increase reminder counter;
//      // - disable the build.
//
//
//      $wrapper->field_reminders_count->set(0);
//      $wrapper->field_ci_build_system_disabled->set(1);
//      $wrapper->field_ci_build_enabled->set(0);
//      $wrapper->save();
//      continue;
//    }
  }
}
