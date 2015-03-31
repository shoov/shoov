<?php
/**
 * @file
 * shoov_user.features.field_instance.inc
 */

/**
 * Implements hook_field_default_field_instances().
 */
function shoov_user_field_default_field_instances() {
  $field_instances = array();

  // Exported field_instance: 'user-user-field_github_access_token'
  $field_instances['user-user-field_github_access_token'] = array(
    'bundle' => 'user',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'module' => 'text',
        'settings' => array(),
        'type' => 'text_default',
        'weight' => 1,
      ),
    ),
    'entity_type' => 'user',
    'field_name' => 'field_github_access_token',
    'label' => 'Github access token',
    'required' => 1,
    'settings' => array(
      'text_processing' => 0,
      'user_register_form' => 1,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'text',
      'settings' => array(
        'size' => 60,
      ),
      'type' => 'text_textfield',
      'weight' => 8,
    ),
  );

  // Translatables
  // Included for use with string extractors like potx.
  t('Github access token');

  return $field_instances;
}
