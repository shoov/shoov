<?php

/**
 * @file
 * Contains \ShoovCiItemsMigrate.
 */

class ShoovCiItemsMigrate extends ShoovMigrateMessage {

  /**
   * Map the field and properties to the CSV header.
   */
  public $fields = array(
    '_ci_build',
    '_status',
    '_start_time'
  );

  public $entityType = 'message';
  public $bundle = 'ci_item';

  public function __construct() {
    parent::__construct();
    $this->description = t('Import Ci Items from a CSV file.');



  }
}
