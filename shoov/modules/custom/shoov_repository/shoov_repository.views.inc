<?php
/**
 * @file
 * Integration with the Views module.
 */

/**
 * Implements hook_views_data().
 */
function shoov_repository_views_data() {
  $data['users']['subscribe'] = array(
    'title' => t('Subscribe user to the CI Build'),
    'help' => t('Link to subscribe/unsubscribe user.'),
    'field' => array(
      'handler' => 'shoov_repository_subscribe_user',
    ),
  );
  return $data;
}
