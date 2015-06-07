<?php

/**
 * @file
 * Contains \ShoovMigrateMessage.
 */

abstract class ShoovMigrateMessage extends Migration {

  public function __construct() {
    parent::__construct();

    // Make sure we can use it for messages only.
    if ($this->entityType != 'message') {
      throw new Exception('\ShoovMigrateMessage supports only messages.');
    }

    $this->description = t('Import @type - @bundle from SQL table', array('@type' => $this->entityType, '@bundle' => $this->bundle));

    $this->fields = !empty($this->fields) ? $this->fields : array();
    $sql_fields[] = '_unique_id';

    // Rebuild the csv columns array.
    $this->fields = array_merge($sql_fields, $this->fields);

    // Create a map object for tracking the relationships between source rows
    $key = array(
      '_unique_id' => array(
        'type' => 'varchar',
        'length' => 255,
        'not null' => TRUE,
      ),
    );

    $destination_handler = new MigrateDestinationEntityAPI($this->entityType, $this->bundle);
    $this->map = new MigrateSQLMap($this->machineName, $key, $destination_handler->getKeySchema($this->entityType));

    // Create a MigrateSource object.
    $sql_table = (isset($this->sqlTable)) ? '_raw_' . $this->sqlTable : '_raw_msg_' . $this->bundle;

    $query = db_select($sql_table, 't')
      ->fields('t')
      ->orderBy('__id');
    $this->source = new MigrateSourceSQL($query, $this->fields);

    $this->destination = new MigrateDestinationMessage($this->bundle, array('text_format' => 'filtered_html'));
  }

  /**
   * Return the migrate directory.
   *
   * @return string
   *   The migrate directory.
   */
  protected function getMigrateDirectory() {
    return variable_get('shoov_migrate_directory', FALSE) ? variable_get('shoov_migrate_directory') : drupal_get_path('module', 'shoov_migrate');
  }

  /**
   * Implements Callback function.
   *
   * Return the author ID of the specific CI Incident.
   *
   * @param $ci_incident_id
   *  Node ID of CI Incident.
   * @return mixed
   *  Author ID of CI Incident.
   */
  protected function getUidFromCiIncident($ci_incident_id) {
    $ci_incident_node = node_load($ci_incident_id['destid1']);
    return $ci_incident_node->uid;
  }
}
