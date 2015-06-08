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
    $this->description = t('Import CI Incidents Error messages from a CSV file.');

    // Map CI Incident.
    $this
      ->addFieldMapping('field_ci_incident', '_ci_incident')
      ->sourceMigration('ShoovCiIncidentsMigrate');
  }

  /**
   * Overrides Migrate::prepare().
   *
   * Map the user based on the CI incident content type.
   */
  public function prepare($entity, $row) {
    $wrapper = entity_metadata_wrapper('message', $entity);
    $entity->uid = $wrapper->field_ci_incident->author->getIdentifier();
  }
}
