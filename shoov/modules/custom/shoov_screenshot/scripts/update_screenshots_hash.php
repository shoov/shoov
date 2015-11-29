<?php
/**
 * @file
 * Contains drush script that update existing screenshots with hash and removes
 * duplicated items.
 */
// Get UI Build id to start with.
$nid = drush_get_option('nid', 0);
// Get the number of nodes to be processed.
$batch = drush_get_option('batch', 10);
// Get allowed memory limit.
$memory_limit = drush_get_option('memory_limit', 500);
$i = 0;

$base_query = new EntityFieldQuery();
$base_query
  ->entityCondition('entity_type', 'node')
  ->entityCondition('bundle', 'ui_build')
  ->propertyOrderBy('nid', 'ACS');
if ($nid) {
  $base_query->propertyCondition('nid', $nid, '>');
}

$query_count = clone $base_query;
$count = $query_count->count()->execute();

if (!$count) {
  drush_log('No UI Build items were found.', 'error');
  return;
}

while ($i < $count) {
  // Free up memory.
  drupal_static_reset();
  // Get UI Build items.
  $builds_query = clone $base_query;
  $result = $builds_query
    ->range(0, $batch)
    ->execute();
  if (empty($result['node'])) {
    return;
  }
  $ids = array_keys($result['node']);

  // Iterate though UI Build items.
  foreach ($ids as $build_id) {

    // Get screenshots of the UI Build.
    $screenshots_query = new EntityFieldQuery();
    $result = $query_screenshots
      ->entityCondition('entity_type', 'node')
      ->entityCondition('bundle', 'screenshot')
      ->propertyOrderBy('nid', 'ACS')
      ->fieldCondition('field_build', 'target_id', $build_id)
      ->addTag('DANGEROUS_ACCESS_CHECK_OPT_OUT')
      ->execute();

    if (empty($result['node'])) {
      drush_print(dt("No screenshots for UI build @build_id were found.", array('@build_id' => $build_id)));
      continue;
    }

    // List of unique hashes for the current UI Build.
    $hashes = array();
    $screenshots = node_load_multiple(array_keys($result['node']));
    foreach ($screenshots as $screenshot) {
      $wrapper = entity_metadata_wrapper('node', $screenshot);
      if (!$wrapper->field_screenshot_hash->value()) {
        // Set hash for the screenshot.
        $files = array();
        $files[]['id'] = $wrapper->field_baseline_image->value()['fid'];
        $files[]['id'] = $wrapper->field_regression_image->value()['fid'];
        $files[]['id'] = $wrapper->field_diff_image->value()['fid'];

        $hash = shoov_screenshot_create_hash($files, $build_id);
        $wrapper->field_screenshot_hash->set($hash);
        $wrapper->save();
      }

      if (in_array($wrapper->field_screenshot_hash->value(), $hashes)) {
        // Identical screenshot already exists. Delete duplicate.
        drush_print(dt('Screenshot @id will be deleted as duplication.', array('@id' => $screenshot->nid)));
        node_delete($screenshot->nid);
      }
      else {
        // Screenshot is unique for the current UI Build.
        // Add hash to the list of unique hashes.
        $hashes[] = $wrapper->field_screenshot_hash->value();
      }
    }
  }

  $i += count($ids);
  $nid = end($ids);
  $params = array(
    '@start' => reset($ids),
    '@end' => end($ids),
    '@iterator' => $i,
    '@max' => $count,
  );
  drush_print(dt('Process builds from id @start to id @end. Batch state: @iterator/@max', $params));
  if (round(memory_get_usage()/1048576) >= $memory_limit) {
    $params = array(
      '@memory' => round(memory_get_usage()/1048576),
      '@max_memory' => memory_get_usage(TRUE)/1048576,
    );
    drush_log(dt('Stopped before out of memory. Start process from the node ID @nid', array('@nid' => end($ids))), 'error');
    return;
  }
}
