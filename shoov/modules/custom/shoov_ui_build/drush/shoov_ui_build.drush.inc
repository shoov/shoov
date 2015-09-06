<?php

/**
 * @file
 * Drush commands for UI Builds.
 */

/**
 * Implements hook_drush_command().
 */
function shoov_ui_build_drush_command() {
  $items = array();

  $items['delete-old-ui-builds'] = array(
    'description' => 'Delete old UI builds and screenshots.',
  );

  return $items;
}

/**
 * Command callback for "delete-old-ui-builds".
 *
 * Deletes ui-builds that are older than one month, meanwhile keeping 10 builds
 * in each repository.
 */
function drush_shoov_ui_build_delete_old_ui_builds() {
  $range = 50;

  // Get the count of all the "repository" entities.
  $query = new EntityFieldQuery();
  $count = $query
    ->entityCondition('entity_type', 'node')
    ->entityCondition('bundle', 'repository')
    ->propertyCondition('status', NODE_PUBLISHED)
    ->count()
    ->execute();

  // Go through the repositories in a batches of 50.
  for($start=0;$start<=$count;$start=$start+$range) {
    $query = new EntityFieldQuery();
    $result = $query
      ->entityCondition('entity_type', 'node')
      ->entityCondition('bundle', 'repository')
      ->propertyCondition('status', NODE_PUBLISHED)
      ->propertyOrderBy('nid', 'ASC')
      ->range($start, $range)
      ->execute();

    if (empty($result['node'])) {
      // Stop if there's not repositories.
      drush_log('No repositories found', 'success');
      return;
    }

    $ids = array_keys($result['node']);
    $repos = node_load_multiple($ids);
    foreach ($repos as $repo) {
      drush_print(format_string('Checking Repository "@label"', array('@label' => $repo->title)));

      $ui_build_query = new EntityFieldQuery();
      $ui_build_results = $ui_build_query
        ->entityCondition('entity_type', 'node')
        ->entityCondition('bundle', 'ui_build')
        ->propertyCondition('status', NODE_PUBLISHED)
        ->fieldCondition('og_repo', 'target_id', $repo->nid)
        ->addTag('DANGEROUS_ACCESS_CHECK_OPT_OUT')
        ->execute();

      if (count($ui_build_results['node']) < 1) {
        // Don't delete anything if we have less than 10 builds in this repository.
        drush_log('>> No more than 10 builds in this repository', 'success');
        // Print empty line between repositories.
        drush_print('');
        continue;
      }

      $build_ids = array_keys($ui_build_results['node']);
      $ui_builds = node_load_multiple($build_ids);
      // Delete every build that is older than 30 days.
      foreach ($ui_builds as $key => $ui_build) {
        $ui_build_wrapper = entity_metadata_wrapper('node', $ui_build->nid);
        drush_print(format_string('>> Checking build "@label" (@id)', array(
          '@label' => $ui_build_wrapper->label(),
          '@id' => $ui_build_wrapper->getIdentifier(),
          )
        ));

        if ($ui_build_wrapper->created->value() > strtotime('-1 month') || count($ui_builds) < 10) {
          // Build is not old enough or there's not enough builds left in the
          // repository.
          drush_log('>>> Build is not going to be deleted (Not old enough or there\'s no enough builds)', 'success');
          continue;
        }

        drush_log('>>> Build is older than one month and is going to be deleted with all it\'s screenshots', 'success');
        // Get all screen-shots in this build.

        $sh_query = new EntityFieldQuery();
        $sh_results = $sh_query
          ->entityCondition('entity_type', 'node')
          ->entityCondition('bundle', 'screenshot')
          ->propertyCondition('status', NODE_PUBLISHED)
          ->fieldCondition('field_build', 'target_id', $repo->nid)
          ->addTag('DANGEROUS_ACCESS_CHECK_OPT_OUT')
          ->execute();

        // Delete build.
        node_delete($ui_build_wrapper->getIdentifier());
        // We need to keep track of how many builds left in the repository.
        unset($ui_builds[$key]);

        if (empty($sh_results['node'])) {
          // If there's no screenshots, continue to the next build.
          continue;
        }

        // Delete all screenshots.
        $screenshot_ids = array_keys($ui_build_results['node']);
        node_delete_multiple($screenshot_ids);
      }

      // Print empty line between repositories.
      drush_print('');
    }
  }
}
