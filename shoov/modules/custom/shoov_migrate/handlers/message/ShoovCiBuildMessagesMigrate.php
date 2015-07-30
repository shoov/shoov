<?php

/**
 * @file
 * Contains \ShoovCiBuildMessagesMigrate.
 */

class ShoovCiBuildMessagesMigrate extends ShoovMigrateMessage {

  /**
   * Map the field and properties to the CSV header.
   */
  public $fields = array(
    '_ci_build',
    '_status',
    '_start_time'
  );

  public $entityType = 'message';
  public $bundle = 'ci_build';

  public $dependencies = array(
    'ShoovCiBuildsMigrate',
  );

  public function __construct() {
    parent::__construct();
    $this->description = t('Import Ci Build messages from a CSV file.');

    // Map CI Build.
    $this
      ->addFieldMapping('field_ci_build', '_ci_build')
      ->sourceMigration('ShoovCiBuildsMigrate');

    // Map Status.
    $this->addFieldMapping('field_ci_build_status', '_status');

    // Map Start Time.
    $this->addFieldMapping('field_ci_build_timestamp', '_start_time');

    // Map Log.
    $this->addFieldMapping('field_ci_build_log', '_log');
  }

  /**
   * Overrides Migrate::prepare().
   *
   * Map the user based on the CI build content type.
   */
  public function prepare($entity, $row) {
    $wrapper = entity_metadata_wrapper('message', $entity);
    $entity->uid = $wrapper->field_ci_build->author->getIdentifier();
  }
}
