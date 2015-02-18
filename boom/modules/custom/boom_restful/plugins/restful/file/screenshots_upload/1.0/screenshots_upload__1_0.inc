<?php

if (variable_get('restful_file_upload', FALSE)) {
  $plugin = array(
    'label' => t('Screenshots upload'),
    'description' => t('A file upload wrapped with RESTful.'),
    'resource' => 'screenshots',
    'class' => 'BoomScreenshotsUploadResource',
    'entity_type' => 'file',
    'authentication_types' => TRUE,
    // We will implement hook_menu() with custom settings.
    'menu_item' => variable_get('restful_hook_menu_base_path', 'api') . '/screenshots-upload',
    // Set the default validators, scheme, and replace as used in
    // file_save_upload().
    'options' => array(),
  );
}
