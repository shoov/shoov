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

    // Map User.
    $this
      ->addFieldMapping('uid', '_ci_build')
      ->sourceMigration('ShoovCiBuildsMigrate')
      ->callbacks(array($this, 'getUidFromCiBuild'));

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
   * Implements Callback function.
   *
   * Return the author ID of the specific CI Build.
   *
   * @param $ci_build_id
   *  Node ID of repository.
   * @return mixed
   *  Author ID of CI Build.
   */
  protected function getUidFromCiBuild($ci_build_id) {
      $ci_build_node = node_load($ci_build_id['destid1']);
      return $ci_build_node->uid;
  }

}
