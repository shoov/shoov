<?php

// Get tha last message id.
$mid = drush_get_option('mid', 0);

// Get the nuber of messages to be processed.
$batch = drush_get_option('batch', 250);

$query = new EntityFieldQuery();
$query
  ->entityCondition('entity_type', 'message')
  ->entityCondition('bundle', 'ci_build')
  ->propertyCondition('mid', $mid, '>')
  ->propertyOrderBy('mid', 'DESC')
  ->range(0, $batch);

$query_count = clone $query;
$count = $query_count->count();
if ($count) {
  drush_log(dt('@count messages were found', array('@count' => $message->mid)), 'success');
}
else {
  drush_log('No messages were found.', 'error');
}

$result = $query->execute();

$messages = message_load_multiple(array_keys($result['message']));

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
  
  drush_log(dt('The last mid was @number', array('@number' => $message->mid)));
}
