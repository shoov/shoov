<?php

if (variable_get('restful_file_upload', FALSE)) {
  $plugin = array(
    'label' => t('JS Live Monitor Incident upload'),
    'description' => t('A file upload wrapped with RESTful.'),
    'resource' => 'js_lm_incidents_upload',
    'class' => 'ShoovJsLmIncidentsUploadResource',
    'entity_type' => 'file',
    'authentication_types' => FALSE,
    // Set the default validators, scheme, and replace as used in
    // file_save_upload().
    'options' => array(
      // Use our "piped" stream wrapper.
      'scheme' => 'piped',
    ),
  );
}
