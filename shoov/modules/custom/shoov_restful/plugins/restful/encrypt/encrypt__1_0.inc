<?php

$plugin = array(
  'label' => t('Encrypt'),
  'resource' => 'encrypt',
  'name' => 'encrypt__1_0',
  'entity_type' => 'node',
  'bundle' => 'ci_build',
  'description' => t('An encryption service that encrypts a key and value of a specific CI build.'),
  'class' => 'ShoovEncryptResource',
  'authentication_types' => TRUE,
);
