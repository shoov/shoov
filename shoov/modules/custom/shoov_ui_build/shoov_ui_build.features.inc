<?php
/**
 * @file
 * shoov_ui_build.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function shoov_ui_build_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "strongarm" && $api == "strongarm") {
    return array("version" => "1");
  }
}

/**
 * Implements hook_node_info().
 */
function shoov_ui_build_node_info() {
  $items = array(
    'ui_build' => array(
      'name' => t('UI Build'),
      'base' => 'node_content',
      'description' => t('UI Build is a collection of screenshots from the same git commit, and browser capabilities'),
      'has_title' => '1',
      'title_label' => t('Title'),
      'help' => '',
    ),
  );
  drupal_alter('node_info', $items);
  return $items;
}
