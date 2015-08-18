<?php


/**
 * Edit button for a message instance.
 */
class shoov_ci_build_trigger_button extends views_handler_field_entity {

  /**
   * {@inheritdoc}
   */
  function render($values) {
    // Add library to have the link that using AJAX.
    drupal_add_library('system', 'drupal.ajax');
    $message = message_load($values->mid);
    if (message_ui_access_control('trigger', $message)) {
      return l(t('Trigger'), 'ci_build/' . $message->mid . '/trigger/nojs',
        array('attributes' => array('class' => 'use-ajax'))
      );
    }
  }
}
