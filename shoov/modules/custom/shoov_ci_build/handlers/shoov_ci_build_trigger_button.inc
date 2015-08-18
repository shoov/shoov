<?php

/**
 * Edit button for a message instance.
 */
class shoov_ci_build_trigger_button extends views_handler_field_entity {

  function render($values) {
    $message = message_load($values->mid);

    if (message_ui_access_control('trigger', $message)) {
      return l(t('Trigger'), 'ci_build/' . $message->mid . '/trigger');
    }
  }
}
