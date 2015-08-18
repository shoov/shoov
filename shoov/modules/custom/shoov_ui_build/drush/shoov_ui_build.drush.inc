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
 * Implements drush_hook_command().
 *
 * Command callback for "delete-old-ui-builds".
 */
function drush_shoov_ui_build_delete_old_ui_builds() {

  $query = new EntityFieldQuery();
  $result = $query
    ->entityCondition('entity_type', 'node')
    ->entityCondition('bundle', 'repository')
    ->propertyCondition('status', NODE_PUBLISHED)
    ->propertyOrderBy('nid', 'ASC')
    ->execute();

  if (empty($result['node'])) {
    // Stop if there's not repositories.
    drush_print('No repositories found.');
    return;
  }

  $ids = array_keys($result['node']);
  $repos = node_load_multiple($ids);
  foreach ($repos as $repo) {
    drush_print('Checking Repository "' . $repo->title . '"');

    // Need to use db_select() because field condition override in OG doesn't
    // work in a Drush command.
    $ui_build_query = db_select('og_membership', 'm');
    $ui_build_query->leftJoin('node', 'n', 'm.etid = n.nid');
    $ui_build_results = $ui_build_query->fields('n', array('nid'))
      ->condition('m.gid', $repo->nid)
      ->condition('m.entity_type', 'node')
      ->condition('n.type', 'ui_build')
      ->execute()
      ->fetchAll();

    if (count($ui_build_results) < 10) {
      // Don't delete anything if we have less than 10 builds in this repository.
      drush_print('There is no more than 10 builds in this repository.');
      continue;
    }

    // Delete every build that is older than 30 days.
    foreach ($ui_build_results as $key => $ui_build) {
      $ui_build_wrapper = entity_metadata_wrapper('node', $ui_build->nid);

      if ($ui_build_wrapper->created->value() > strtotime('-1 month') || count($ui_build_results) < 10) {
        // Build is not old enough or there's not enough builds left in the
        // repository.
        continue;
      }

      drush_print('Build titled "' . $ui_build_wrapper->label() . '"('  . $ui_build_wrapper->getIdentifier() . ') is older than one month and is going to be deleted');
      // Get all screen-shots in this build.
      $sh_results = db_select('field_data_field_build', 'f')
        ->fields('f', array('entity_id'))
        ->condition('f.field_build_target_id', $ui_build->nid)
        ->execute()
        ->fetchAll();

      // Delete build.
      node_delete($ui_build_wrapper->getIdentifier());
      // We need to keep track of how many builds left in the repository.
      unset($ui_build_results[$key]);

      if (empty($sh_results)) {
        // If there's no screenshots, continue to the next build.
        continue;
      }

      // Delete all screenshots for this build.
      foreach ($sh_results as $screenshot) {
        node_delete($screenshot->entity_id);
      }
    }
  }
}
