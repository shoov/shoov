<?php

$plugin = array(
  'label' => t('CI-Build items'),
  'resource' => 'ci-build-items',
  'name' => 'ci_build_items__1_0',
  'entity_type' => 'message',
  'bundle' => 'ci_build',
  'description' => t('Export the "CI-Builds" message type.'),
  'class' => 'ShoovCiBuildItemsResource',
  'authentication_types' => TRUE,
);
