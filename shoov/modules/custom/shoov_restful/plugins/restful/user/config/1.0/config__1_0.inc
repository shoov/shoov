<?php

$plugin = array(
  'label' => t('User config'),
  'description' => t('Export the user config, to be used to create a ~/.shoov.json file.'),
  'resource' => 'config',
  'class' => 'ShoovConfigResource',
  'entity_type' => 'user',
  'bundle' => 'user',
  'authentication_types' => TRUE,
  // Add a passthrough formatter.
  'formatter' => 'simple',
);
