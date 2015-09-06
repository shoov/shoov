<?php

/**
 * @file
 * Contains shoov_ci_build_trigger_button.
 */

class shoov_ci_build_send_to_server_link extends views_handler_field_entity {

  /**
   * {@inheritdoc}
   */
  function render($values) {
    $message = message_load($values->mid);
    if ($message->type != 'ci_build') {
      // Not CI Build messages can't be triggered.
      return;
    }
    // Add library to have the link that using AJAX.
    drupal_add_library('system', 'drupal.ajax');
    if (message_ui_access_control('trigger', $message)) {
      return l(t('Trigger'), 'ci_build/' . $message->mid . '/trigger/nojs',
        array('attributes' => array('class' => 'use-ajax'))
      );
    }
  }
}
