<?php

$plugin = array(
  'label' => t('Access token authentication'),
  'resource' => 'github_auth',
  'name' => 'github_auth__1_0',
  'entity_type' => 'restful_token_auth',
  'bundle' => 'access_token',
  'description' => t('Export the access token authentication resource.'),
  'class' => 'ShoovGithubAuthAuthentication',
  // We will implement hook_menu() with custom settings.
  'menu_item' => 'auth/github',
  // Don't list this resource in /api.
  'discoverable' => FALSE,
);
