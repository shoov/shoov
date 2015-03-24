<?php

$plugin = array(
  'label' => t('Screenshots'),
  'resource' => 'screenshots',
  'name' => 'screenshots__1_0',
  'entity_type' => 'node',
  'bundle' => 'screenshot',
  'description' => t('Export the "Screenshot" content type.'),
  'class' => 'ShoovScreenshotsResource',
  'authentication_types' => TRUE,
  // @todo: Remove.
  'authentication_optional' => variable_get('restful_file_upload_allow_anonymous_user', FALSE),
);
