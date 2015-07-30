<?php
/**
 * @file
 * Triggering the process of updating CI Build Items with the start and the
 * end timestamps.
 */

// Get tha last message id.
$mid = drush_get_option('mid', 0);

// Get the nuber of messages to be processed.
$batch = drush_get_option('batch', 5);

// Get allowed memory limit.
$memory_limit = drush_get_option('memory_limit', 500);

$i = 0;

$base_query = new EntityFieldQuery();
$base_query
  ->entityCondition('entity_type', 'message')
  ->entityCondition('bundle', 'ci_build')
  ->propertyOrderBy('mid', 'DESC');
if ($mid) {
  $base_query->propertyCondition('mid', $mid, '<');
}



$query_count = clone $base_query;
$count = $query_count->count()->execute();
if (!$count) {
  drush_log('No messages were found.', 'error');
  return;
}

while ($i < $count) {

// Free up memory.
  drupal_static_reset();
  $query = clone $base_query;
  if ($mid) {
    $query
      ->propertyCondition('mid', $mid, '<');
  }
  $result = $query
    ->range(0, $batch)
    ->execute();

  if (empty($result['message'])) {
    return;
  }

  $ids = array_keys($result['message']);
  $messages = message_load_multiple($ids);

  foreach ($messages as $message) {
    $wrapper = entity_metadata_wrapper('message', $message);
    if (!$wrapper->field_ci_build_start_timestamp->value() && !$wrapper->field_ci_build_end_timestamp->value()) {
      if (in_array($wrapper->field_ci_build_status->value(), array('in_progress', 'In progress'))) {
        $wrapper->field_ci_build_start_timestamp->set($wrapper->field_ci_build_schedule->value());
      }
      elseif (in_array(strtolower($wrapper->field_ci_build_status->value()), array('error', 'done'))) {
        $wrapper->field_ci_build_start_timestamp->set($wrapper->field_ci_build_schedule->value());
        $wrapper->field_ci_build_end_timestamp->set($wrapper->field_ci_build_schedule->value() + 60);
      }
      $wrapper->save();
    }
  }

  $i += $batch;
  $mid = end($ids);

  $params = array(
    '@start' => reset($ids),
    '@end' => end($ids),
    '@iterator' => $i,
    '@max' => $count,
  );

  drush_print(dt('Process messages from id @start to id @end. Batch state: @iterator/@max', $params));

  if (round(memory_get_usage()/1048576) >= $memory_limit) {
    $params = array(
      '@memory' => round(memory_get_usage()/1048576),
      '@max_memory' => memory_get_usage(TRUE)/1048576,
    );
    drush_log(dt('Stopped before out of memory. Start process from the node ID @nid', array('@nid' => end($ids))), 'error');
    return;
  }
}
