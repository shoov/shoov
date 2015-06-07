<?php

/**
 * @file
 * Contains \ShoovCiIncidentErrorMessagesMigrate.
 */

class ShoovCiIncidentErrorMessagesMigrate extends ShoovMigrateMessage {

  /**
   * Map the field and properties to the CSV header.
   */
  public $fields = array(
    '_ci_incident',
  );

  public $entityType = 'message';
  public $bundle = 'ci_incident_error';

  public $dependencies = array(
    'ShoovCiIncidentsMigrate',
  );

  public function __construct() {
    parent::__construct();
    $this->description = t('Import CI Incidents messages from a CSV file.');

    // Map User.
    $this
      ->addFieldMapping('uid', '_ci_incident')
      ->sourceMigration('ShoovCiIncidentsMigrate')
      ->callbacks(array($this, 'getUidFromCiIncident'));

    // Map CI Incident.
    $this
      ->addFieldMapping('field_ci_incident', '_ci_incident')
      ->sourceMigration('ShoovCiIncidentsMigrate');
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
