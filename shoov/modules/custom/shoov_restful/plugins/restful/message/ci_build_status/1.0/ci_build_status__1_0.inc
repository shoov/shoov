<?php

$plugin = array(
  'label' => t('CI-Build status'),
  'resource' => 'ci-build-status',
  'name' => 'ci_build_status__1_0',
  'entity_type' => 'node',
  'bundle' => 'ci_build',
  'description' => t('Export the "CI-Builds" status image.'),
  'class' => 'ShoovCiBuildStatusResource',
  'authentication_types' => TRUE,
);
