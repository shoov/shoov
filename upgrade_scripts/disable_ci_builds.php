<?php
/**
 * @file
 * Contains drush script that disables CI Builds of repositories that don't
 * have .shoov.yml file in it.
 */
// Get CI Build id to start with.
$nid = drush_get_option('nid', 0);
// Get the number of nodes to be processed.
$batch = drush_get_option('batch', 50);
// Get allowed memory limit.
$memory_limit = drush_get_option('memory_limit', 500);
$i = 0;

$base_query = new EntityFieldQuery();
$base_query
  ->entityCondition('entity_type', 'node')
  ->entityCondition('bundle', 'ci_build')
  ->propertyOrderBy('nid', 'ACS')
  ->fieldCondition('field_ci_build_enabled', 'value', TRUE)
  ->addTag('DANGEROUS_ACCESS_CHECK_OPT_OUT');
if ($nid) {
  $base_query->propertyCondition('nid', $nid, '>');
}

$query_count = clone $base_query;
$count = $query_count->count()->execute();

if (!$count) {
  drush_log('No CI Build items were found.', 'error');
  return;
}

while ($i < $count) {
  // Free up memory.
  drupal_static_reset();

  // Get CI Build items.
  $builds_query = clone $base_query;
  if ($nid) {
    $builds_query->propertyCondition('nid', $nid, '>');
  }
  $result = $builds_query
    ->range(0, $batch)
    ->execute();
  if (empty($result['node'])) {
    return;
  }
  $ids = array_keys($result['node']);

  // Iterate though CI Build items.
  foreach ($ids as $ci_build_id) {
    $node = node_load($ci_build_id);
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

  $i += count($ids);
  $nid = end($ids);
  $params = array(
    '@start' => reset($ids),
    '@end' => end($ids),
    '@iterator' => $i,
    '@max' => $count,
  );
  drush_print(dt('Process CI builds from id @start to id @end. Batch state: @iterator/@max', $params));
  if (round(memory_get_usage()/1048576) >= $memory_limit) {
    $params = array(
      '@memory' => round(memory_get_usage()/1048576),
      '@max_memory' => memory_get_usage(TRUE)/1048576,
    );
    return;
  }
}
