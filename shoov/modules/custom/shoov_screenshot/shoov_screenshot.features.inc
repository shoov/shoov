<?php
/**
 * @file
 * shoov_screenshot.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function shoov_screenshot_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "strongarm" && $api == "strongarm") {
    return array("version" => "1");
  }
}

/**
 * Implements hook_image_default_styles().
 */
function shoov_screenshot_image_default_styles() {
  $styles = array();

  // Exported image style: screenshot_medium.
  $styles['screenshot_medium'] = array(
    'label' => 'Medium',
    'effects' => array(
      5 => array(
        'name' => 'image_scale',
        'data' => array(
          'width' => 600,
          'height' => '',
          'upscale' => 0,
        ),
        'weight' => 1,
      ),
    ),
  );

  // Exported image style: screenshot_small.
  $styles['screenshot_small'] = array(
    'label' => 'Small',
    'effects' => array(
      6 => array(
        'name' => 'image_scale',
        'data' => array(
          'width' => 400,
          'height' => '',
          'upscale' => 0,
        ),
        'weight' => 1,
      ),
    ),
  );

  return $styles;
}

/**
 * Implements hook_node_info().
 */
function shoov_screenshot_node_info() {
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
