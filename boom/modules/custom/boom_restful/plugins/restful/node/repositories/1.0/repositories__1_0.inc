<?php

$plugin = array(
  'label' => t('Repositories'),
  'resource' => 'repositories',
  'name' => 'repositories__1_0',
  'entity_type' => 'node',
  'bundle' => 'repository',
  'description' => t('Export the "Repository" content type.'),
  'class' => 'BoomRepositoriesResource',
  'authentication_types' => TRUE,
);
