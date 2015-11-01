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

/**
 * Implements hook_views_default_views_alter().
 *
 * Add link field to the view of messages to send the item to the nodejs server.
 */
function shoov_repository_views_default_views_alter(&$views) {

  if (!in_array('users', array_keys($views))) {
    return;
  }
  $handler =& $views['users']->display['default']->handler;

  /* Field: User: Subscribe user */
  $handler->display->display_options['fields']['subscribe']['id'] = 'edit';
  $handler->display->display_options['fields']['subscribe']['table'] = 'users';
  $handler->display->display_options['fields']['subscribe']['field'] = 'edit';
  $handler->display->display_options['fields']['subscribe']['label'] = '';
  $handler->display->display_options['fields']['subscribe']['exclude'] = TRUE;
  $handler->display->display_options['fields']['subscribe']['element_label_colon'] = FALSE;
}
