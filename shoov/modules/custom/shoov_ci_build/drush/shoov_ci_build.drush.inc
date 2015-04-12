<?php

/**
 * @file
 * Create a CI build item.
 */


/**
 * Implements hook_drush_command().
 */
function shoov_ci_build_drush_command() {
  $items = array();

  $items['create-ci-build-item'] = array(
    'description' => 'Create a CI build item.',
  );

  return $items;
}

/**
 * Implements drush_hook_command().
 *
 * Command callback for csv2sql.
 */
function drush_create_ci_build_item() {
  if (!$messages = shoov_ci_build_get_ci_builds()) {
    drush_log('No items found for processing');
    return;
  }

  $count = count($messages);
  drush_log(dt('Found @count items for processing', array('@count' => $count)));

  $server_url = variable_get('shoov_build_pr_server', 'http://localhost:3000');
  if (!$server_url) {
    throw new Exception('Shoov build server is not setup.');
  }

  $access_token = shoov_restful_get_user_token();

  // Send requests.
  foreach ($messages as $delta => $message) {
    $url = array(
      $server_url,
      'ci',
      $message->mid,
      $access_token,
    );

    $url = implode('/', $url);

    drupal_http_request($url);

    $params = array(
      '@delta' => $delta,
      '@count' => $count,
      '@id' => $message->id,
    );

    drush_log(dt('@delta / @count) Process message ID @id', $params));
  }
}
