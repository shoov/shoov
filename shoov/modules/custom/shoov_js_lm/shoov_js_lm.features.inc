<?php
/**
 * @file
 * shoov_js_lm.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function shoov_js_lm_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "strongarm" && $api == "strongarm") {
    return array("version" => "1");
  }
}

/**
 * Implements hook_node_info().
 */
function shoov_js_lm_node_info() {
  $items = array(
    'js_lm' => array(
      'name' => t('JS Live Monitor'),
      'base' => 'node_content',
      'description' => '',
      'has_title' => '1',
      'title_label' => t('Title'),
      'help' => '',
    ),
    'js_lm_build' => array(
      'name' => t('JS Live Monitor Build'),
      'base' => 'node_content',
      'description' => '',
      'has_title' => '1',
      'title_label' => t('Title'),
      'help' => '',
    ),
    'js_lm_incident' => array(
      'name' => t('JS Live Monitor Incident'),
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
