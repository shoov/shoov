<?php

/**
 * This script related to issue #116.
 * It is update all old CI builds items to new interval value.
 */

drush_print("Start updating interval for next CI builds:");

$query = new EntityFieldQuery();
$results = $query
  ->entityCondition('entity_type', 'node')
  ->entityCondition('bundle', 'ci_build')
  ->execute();

foreach ($results['node'] as $node) {
  $wrapper = entity_metadata_wrapper('message', $node->nid);

  if (!$wrapper->field_ci_build_interval->value()) {
    // Set default value 3 Minutes.
    $wrapper->field_ci_build_interval->set('180');
    $wrapper->save();
  }

  drush_print("CI build ID !id updated.", array('!id' => $node->nid));
}

drush_print("Script have finished.");


