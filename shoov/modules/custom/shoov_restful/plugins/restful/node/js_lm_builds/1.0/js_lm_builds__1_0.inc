<?php

$plugin = array(
  'label' => t('JS Live Monitor Builds'),
  'resource' => 'js-lm-builds',
  'name' => 'js_lm_builds__1_0',
  'entity_type' => 'node',
  'bundle' => 'js_lm_build',
  'description' => t('Export the "JS Live Monitor Builds" content type.'),
  'class' => 'ShoovJsLmBuildsResource',
  'authentication_types' => TRUE,
);
