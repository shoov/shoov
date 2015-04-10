<?php

$plugin = array(
  'label' => t('CI-Builds'),
  'resource' => 'ci-builds',
  'name' => 'ci_builds__1_0',
  'entity_type' => 'node',
  'bundle' => 'ci_build',
  'description' => t('Export the "CI-Builds" content type.'),
  'class' => 'ShoovCiBuildsResource',
  'authentication_types' => TRUE,
);
