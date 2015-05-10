<?php

$plugin = array(
  'label' => t('CI-Incidents'),
  'resource' => 'ci-incidents',
  'name' => 'ci_incidents__1_0',
  'entity_type' => 'node',
  'bundle' => 'ci_incident',
  'description' => t('Export the "CI-Incidents" content type.'),
  'class' => 'ShoovCiIncidentsResource',
  'authentication_types' => TRUE,
);
