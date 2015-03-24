<?php

$plugin = array(
  'label' => t('Companies'),
  'resource' => 'companies',
  'name' => 'companies__1_0',
  'entity_type' => 'node',
  'bundle' => 'company',
  'description' => t('Export the "Company" content type.'),
  'class' => 'ShoovCompaniesResource',
  'authentication_types' => TRUE,
);
