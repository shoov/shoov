<?php
/**
 * @file
 * shoov_ci_build.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function shoov_ci_build_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "strongarm" && $api == "strongarm") {
    return array("version" => "1");
  }
}

/**
 * Implements hook_node_info().
 */
function shoov_ci_build_node_info() {
  $items = array(
    'ci_build' => array(
      'name' => t('CI Build'),
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
