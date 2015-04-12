<?php
/**
 * @file
 * Shoov profile.
 */

/**
 * Implements hook_form_FORM_ID_alter().
 *
 * Allows the profile to alter the site configuration form.
 */
function shoov_form_install_configure_form_alter(&$form, $form_state) {
  // Pre-populate the site name with the server name.
  $form['site_information']['site_name']['#default_value'] = $_SERVER['SERVER_NAME'];
}

/**
 * Implements hook_install_tasks().
 */
function shoov_install_tasks() {
  $tasks = array();

  $tasks['shoov_setup_variables'] = array(
    'display_name' => st('Set Variables'),
    'display' => FALSE,
  );

  $tasks['shoov_setup_og_permissions'] = array(
    'display_name' => st('Set Blocks'),
    'display' => FALSE,
  );

  // Run this as the last task!
  $tasks['shoov_setup_rebuild_permissions'] = array(
    'display_name' => st('Rebuild permissions'),
    'display' => FALSE,
  );

  return $tasks;
}

/**
 * Task callback; Set variables.
 */
function shoov_setup_variables() {
  $variables = array(
    // Features default export path.
    'features_default_export_path' => 'profiles/shoov/modules/custom',
    // Mime-mail.
    'mimemail_format' => 'full_html',
    'mimemail_sitestyle' => FALSE,
    'mimemail_name' => 'Shoov',
    'mimemail_mail' => 'info@shoov.com',
    // jQuery versions.
    'jquery_update_jquery_version' => '1.10',
    'jquery_update_jquery_admin_version' => '1.5',

    // Enable restful files upload.
    'restful_file_upload' => TRUE,

    // Set access token expiration to future date.
    'restful_token_auth_expiration_period' => 'P10Y',
  );

  foreach ($variables as $key => $value) {
    variable_set($key, $value);
  }
}

/**
 * Task callback; Setup OG permissions.
 *
 * We do this here, late enough to make sure all group-content were
 * created.
 */
function shoov_setup_og_permissions() {

}

/**
 * Task callback; Setup blocks.
 */
function shoov_setup_blocks() {
  $default_theme = variable_get('theme_default', 'bartik');

  $blocks = array(
    array(
      'module' => 'system',
      'delta' => 'user-menu',
      'theme' => $default_theme,
      'status' => 1,
      'weight' => 0,
      'region' => 'header',
      'pages' => '',
      'title' => '<none>',
      'cache' => DRUPAL_NO_CACHE,
    ),
  );

  drupal_static_reset();
  _block_rehash($default_theme);
  foreach ($blocks as $record) {
    $module = array_shift($record);
    $delta = array_shift($record);
    $theme = array_shift($record);
    db_update('block')
      ->fields($record)
      ->condition('module', $module)
      ->condition('delta', $delta)
      ->condition('theme', $theme)
      ->execute();
  }
}

/**
 * Task callback; Rebuild permissions (node access).
 *
 * Setting up the platform triggers the need to rebuild the permissions.
 * We do this here so no manual rebuild is necessary when we finished the
 * installation.
 */
function shoov_setup_rebuild_permissions() {
  node_access_rebuild();
}
