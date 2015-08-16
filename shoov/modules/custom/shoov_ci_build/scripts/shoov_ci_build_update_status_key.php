<?php

/**
 * @file
 * Update CI build items with status token.
 */

// Get tha last message id.
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
  ->addTag('empty_status_token')
  ->addTag('DANGEROUS_ACCESS_CHECK_OPT_OUT')
  ->propertyCondition('nid', $nid, '>')
  ->propertyOrderBy('nid', 'ASC');

$query_count = clone $base_query;
$count = $query_count->count()->execute();

if (!$count) {
  drush_log('No CI Builds were found.', 'warning');
  return;
}

while ($i < $count) {
// Free up memory.
  drupal_static_reset();
  $query = clone $base_query;
  $result = $query
    ->range(0, $batch)
    ->execute();
  if (empty($result['node'])) {
    return;
  }
  $ids = array_keys($result['node']);
  $nodes = node_load_multiple($ids);
  foreach ($nodes as $node) {
    $wrapper = entity_metadata_wrapper('node', $node);
    drush_print($node->nid);
    if (isset($wrapper->field_status_ttoken)) {
      continue;
    }
    $wrapper->field_status_token->set(drupal_random_key());
    $wrapper->save();
  }
  $i += $batch;
  $nid = end($ids);
  $params = array(
    '@start' => reset($ids),
    '@end' => end($ids),
    '@iterator' => $i,
    '@max' => $count,
  );
  drush_print(dt('Process node from id @start to id @end. Batch state: @iterator/@max', $params));
  if (round(memory_get_usage()/1048576) >= $memory_limit) {
    $params = array(
      '@memory' => round(memory_get_usage()/1048576),
      '@max_memory' => memory_get_usage(TRUE)/1048576,
    );
    drush_log(dt('Stopped before out of memory. Start process from the node ID @nid', array('@nid' => end($ids))), 'error');
    return;
  }
}
