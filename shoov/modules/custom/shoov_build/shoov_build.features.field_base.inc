<?php
/**
 * @file
 * shoov_build.features.field_base.inc
 */

/**
 * Implements hook_field_default_field_bases().
 */
function shoov_build_field_default_field_bases() {
  $field_bases = array();

  // Exported field_base: 'field_browser'
  $field_bases['field_browser'] = array(
    'active' => 1,
    'cardinality' => 1,
    'deleted' => 0,
    'entity_types' => array(),
    'field_name' => 'field_browser',
    'foreign keys' => array(),
    'indexes' => array(
      'value' => array(
        0 => 'value',
      ),
    ),
    'locked' => 0,
    'module' => 'list',
    'settings' => array(
      'allowed_values' => array(
        'chrome' => 'Chrome',
        'firefox' => 'Firefox',
        'phantomjs' => 'Phantomjs',
        'ie' => 'Internet Explorer',
      ),
      'allowed_values_function' => '',
    ),
    'translatable' => 0,
    'type' => 'list_text',
  );

  return $field_bases;
}
