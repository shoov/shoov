<?php
/**
 * @file
 * integration with the views module.
 */

/**
 * Implements hook_views_data().
 */
function shoov_ci_build_views_data() {
  $data['message']['trigger'] = array(
    'title' => t('Trigger message'),
    'help' => t('Link to send the message to nodejs server.'),
    'field' => array(
      'handler' => 'shoov_ci_build_trigger_button',
    ),
  );
  return $data;
}

/**
 * Implements hook_views_default_views_alter().
 */
function shoov_ci_build_views_default_views_alter(&$views) {
  if (!in_array('message', array_keys($views))) {
    return;
  }

  $handler =& $views['message']->display['default']->handler;

  /* Field: Message: Edit message */
  $handler->display->display_options['fields']['trigger']['id'] = 'edit';
  $handler->display->display_options['fields']['trigger']['table'] = 'message';
  $handler->display->display_options['fields']['trigger']['field'] = 'edit';
  $handler->display->display_options['fields']['trigger']['label'] = '';
  $handler->display->display_options['fields']['trigger']['exclude'] = TRUE;
  $handler->display->display_options['fields']['trigger']['element_label_colon'] = FALSE;
}
