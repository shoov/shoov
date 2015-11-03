<?php
/**
 * @file
 * shoov_repository.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function shoov_repository_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "page_manager" && $api == "pages_default") {
    return array("version" => "1");
  }
  if ($module == "strongarm" && $api == "strongarm") {
    return array("version" => "1");
  }
}

/**
 * Implements hook_node_info().
 */
function shoov_repository_node_info() {
  $items = array(
    'repository' => array(
      'name' => t('Repository'),
      'base' => 'node_content',
      'description' => '',
      'has_title' => '1',
      'title_label' => t('Title'),
      'help' => '',
    ),
  );
  drupal_alter('node_info', $items);
  return $items;
}
