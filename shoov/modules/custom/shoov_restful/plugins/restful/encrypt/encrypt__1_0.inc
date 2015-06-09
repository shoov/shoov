<?php

$plugin = array(
  'label' => t('Encrypt'),
  'resource' => 'encrypt',
  'name' => 'encrypt__1_0',
  'description' => t('An encryption service that encrypts a key and value of a specific CI build.'),
  'class' => 'ShoovEncryptResource',
  'authentication_types' => TRUE,
);
