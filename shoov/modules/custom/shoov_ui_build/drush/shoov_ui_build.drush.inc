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
    return;
  }

  $ids = array_keys($result['node']);
  $repos = node_load_multiple($ids);
  foreach ($repos as $repo) {
    drush_print('Checking Repository ID ' . $repo->nid);

    $ui_build_query = new EntityFieldQuery();
    $ui_build_result = $ui_build_query
      ->entityCondition('entity_type', 'node')
      ->entityCondition('bundle', 'ui_build')
      ->propertyCondition('status', NODE_PUBLISHED)
      ->fieldCondition('og_repo', 'target_id', 1)
      ->propertyOrderBy('nid', 'ASC')
      ->execute();

    var_dump($repo->nid);
    var_dump($ui_build_result);

    if (empty($ui_build_result['node'])) {
      continue;
    }

    $ui_build_ids = array_keys($ui_build_result['node']);
    $ui_builds = node_load_multiple($ui_build_ids);
    foreach ($ui_builds as $ui_build) {
      drush_print($ui_build->nid);
    }
  }
}
