<?php

/**
 * @file
 * Support for message destinations.
 */

/**
 * Destination class implementing migration into messages.
 */
class MigrateDestinationMessage extends MigrateDestinationEntity {
  static public function getKeySchema() {
    return array(
      'mid' => array(
        'type' => 'int',
        'unsigned' => TRUE,
        'description' => 'ID of destination message',
      ),
    );
  }

  /**
   * Return an options array for message destinations.
   *
   * @param string $language
   *  Default language for nodes created via this destination class.
   * @param string $text_format
   *  Default text format for nodes created via this destination class.
   */
  static public function options($language, $text_format) {
    return compact('language', 'text_format');
  }

  /**
   * Basic initialization
   *
   * @param string $bundle
   *  A.k.a. the type (ci_build, ci_incident_error, etc.) of the message.
   * @param array $options
   *  Options applied to messages.
   */
  public function __construct($bundle, array $options = array()) {
    parent::__construct('message', $bundle, $options);
  }

  /**
   * Returns a list of fields available to be mapped for the message type (bundle)
   *
   * @param Migration $migration
   *  Optionally, the migration containing this destination.
   * @return array
   *  Keys: machine names of the fields (to be passed to addFieldMapping)
   *  Values: Human-friendly descriptions of the fields.
   */
  public function fields($migration = NULL) {
    $fields = array();

    $fields['mid'] = t('Message: The Unique ID of the message.');
    $fields['type'] = t('Message: Reference to a message a type.');
    $fields['uid'] = t('Message: The user ID of the acting user.');
    $fields['arguments'] = t('Serialized array with the arguments');
    $fields['timestamp'] = t('When the message instance was recorded.');
    $fields['language'] = t('The language of this message.');

    // Then add in anything provided by handlers
    $fields += migrate_handler_invoke_all('Entity', 'fields', $this->entityType, $this->bundle, $migration);
    $fields += migrate_handler_invoke_all('Message', 'fields', $this->entityType, $this->bundle, $migration);

    return $fields;
  }

  /**
   * Delete a batch of messages at once.
   *
   * @param $mids
   *  Array of node IDs to be deleted.
   */
  public function bulkRollback(array $mids) {
    migrate_instrument_start('message_delete_multiple');
    $this->prepareRollback($mids);
    message_delete_multiple($mids);
    $this->completeRollback($mids);
    migrate_instrument_stop('message_delete_multiple');
  }

  /**
   * Import a single message.
   *
   * @param $message
   *  Message object to build. Prefilled with any fields mapped in the Migration.
   * @param $row
   *  Raw source data object - passed through to prepare/complete handlers.
   * @return array
   *  Array of key fields (nid only in this case) of the node that was saved if
   *  successful. FALSE on failure.
   */
  public function import(stdClass $message, stdClass $row) {
    $msg_object = message_create($this->getBundle(), array('uid' => $message->uid));

    $wrapper = entity_metadata_wrapper('message', $msg_object);

    if ($this->getBundle() == 'ci_build') {
      $wrapper->field_ci_build->set($message->field_ci_build['destid1']);
      $wrapper->field_ci_build_status->set(strtolower($message->field_ci_build_status));
      $wrapper->field_ci_build_timestamp->set(strtotime($message->field_ci_build_timestamp));
      $wrapper->field_ci_build_log->value = $message->field_ci_build_log;
    }
    elseif ($this->getBundle() == 'ci_incident_error') {
      $incident_id = $message->field_ci_incident['destid1'];
      $wrapper->field_ci_incident->set($incident_id);

      // We need replace @repo and @branch attributes. To do this we need to get
      // CI Build for this CI Incident.
      $ci_build_node = node_load($message->field_ci_incident['destid1']);
      $ci_build_wrapper = entity_metadata_wrapper('node', $ci_build_node);
      $arguments = array(
        '@repo' => $ci_build_wrapper->og_repo->label(),
        '@branch' => $ci_build_wrapper->field_ci_build->field_git_branch->value(),
        '@url' => variable_get('shoov_client_url', 'http://localhost:9000') . '/#/ci-incidents/' . $incident_id,
      );
      $wrapper->arguments->set($arguments);
    }
    elseif ($this->getBundle() == 'ci_incident_fixed') {
      $incident_id = $message->field_ci_incident['destid1'];
      $wrapper->field_ci_incident->set($incident_id);

      // We need replace @repo and @branch attributes. To do this we need to get
      // CI Build for this CI Incident.
      $ci_build_node = node_load($message->field_ci_incident['destid1']);
      $ci_build_wrapper = entity_metadata_wrapper('node', $ci_build_node);
      $arguments = array(
        '@repo' => $ci_build_wrapper->og_repo->label(),
        '@branch' => $ci_build_wrapper->field_ci_build->field_git_branch->value(),
        '@url' => variable_get('shoov_client_url', 'http://localhost:9000') . '/#/ci-incidents/' . $incident_id,
      );
      $wrapper->arguments->set($arguments);
    }

    $msg_object->save();

    $return = array($msg_object->mid);

    return $return;
  }
}
