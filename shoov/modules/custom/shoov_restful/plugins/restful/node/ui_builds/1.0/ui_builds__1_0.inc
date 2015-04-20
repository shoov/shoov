<?php

$plugin = array(
  'label' => t('UI Builds'),
  // @todo: Change resource name.
  'resource' => 'builds',
  'name' => 'ui_builds__1_0',
  'entity_type' => 'node',
  'bundle' => 'ui_build',
  'description' => t('Export the "UI Builds" content type.'),
  'class' => 'ShoovUiBuildsResource',
  'authentication_types' => TRUE,
);
