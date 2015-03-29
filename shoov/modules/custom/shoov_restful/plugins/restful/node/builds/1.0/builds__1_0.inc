<?php

$plugin = array(
  'label' => t('Builds'),
  'resource' => 'builds',
  'name' => 'builds__1_0',
  'entity_type' => 'node',
  'bundle' => 'build',
  'description' => t('Export the "Builds" content type.'),
  'class' => 'ShoovBuildsResource',
  'authentication_types' => TRUE,
);
