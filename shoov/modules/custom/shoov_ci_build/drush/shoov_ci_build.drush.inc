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

  $items['update-ci-build-item-timestamp'] = array(
    'description' => 'Update new fields start_timestamp and end_timestamp',
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
    $wrapper->field_ci_build_schedule->set(time());
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
 * Implements drush_hook_command().
 *
 * Command callback for "update-ci-build-item-timestamp".
 */
function drush_shoov_ci_build_update_ci_build_item_timestamp() {
  $messages = message_load_multiple(FALSE, array('type' => 'ci_build'));

  foreach ($messages as $message) {
    $wrapper = entity_metadata_wrapper('message', $message);
    $params = array(
      '@id' => $message->mid,
    );
    if (!$wrapper->field_ci_build_start_timestamp->value() && !$wrapper->field_ci_build_end_timestamp->value()) {
      if (in_array($wrapper->field_ci_build_status->value(), array('in_progress', 'In progress'))) {
        $wrapper->field_ci_build_start_timestamp->set($wrapper->field_ci_build_schedule->value());
        $params += array('@start' => $wrapper->field_ci_build_start_timestamp->value());
        $log = format_string('Message @id was updated with start timestamp @start', $params);
      }
      elseif (in_array(strtolower($wrapper->field_ci_build_status->value()), array('error', 'done'))) {
        $wrapper->field_ci_build_start_timestamp->set($wrapper->field_ci_build_schedule->value());
        $wrapper->field_ci_build_end_timestamp->set($wrapper->field_ci_build_schedule->value() + 60);
        $params += array(
          '@start' => $wrapper->field_ci_build_start_timestamp->value(),
          '@end' => $wrapper->field_ci_build_end_timestamp->value(),
        );
        $log = format_string('Message @id was updated with start timestamp @start', $params);
      }
      $wrapper->save();

      if ($log) {
        // Log message update.
        drush_log($log);
      }
    }
  }
}
