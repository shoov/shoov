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
    'ShoovCiBuildMigrate',
  );

  public function __construct() {
    parent::__construct();
    $this->description = t('Import Ci Items from a CSV file.');

    $this->addFieldMapping('field_ci_build', '_ci_build')
      ->sourceMigration('ShoovCiBuildMigrate');

    $this->addFieldMapping('field_ci_build_status', '_status');

    $this->addFieldMapping('field_ci_build_timestamp', '_start_time');

  }
}
