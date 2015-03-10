<?php
/**
 * @file
 * boom_screenshot.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function boom_screenshot_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "strongarm" && $api == "strongarm") {
    return array("version" => "1");
  }
}

/**
 * Implements hook_node_info().
 */
function boom_screenshot_node_info() {
  $items = array(
    'screenshot' => array(
      'name' => t('Screenshot'),
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
