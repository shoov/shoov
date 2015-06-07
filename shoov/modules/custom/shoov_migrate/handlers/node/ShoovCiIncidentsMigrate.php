<?php

/**
 * @file
 * Contains \ShoovCiIncidentsMigrate.
 */

class ShoovCiIncidentsMigrate extends \ShoovMigrateNode {

  public $entityType = 'node';
  public $bundle = 'ci_incident';

  public $fields = array(
    '_failing_build',
    '_fixed_build',
    '_repository',
    '_ci_build',
    '_build_error',
  );

  public $dependencies = array(
    'ShoovUsersMigrate',
    'ShoovCiBuildsMigrate',
    'ShoovRepositoriesMigrate',
    'ShoovCiBuildMessagesMigrate'
  );

  public function __construct() {
    parent::__construct();

    // Map Failing Build.
    $this
      ->addFieldMapping('field_failing_build', '_failing_build')
      ->sourceMigration('ShoovCiBuildMessagesMigrate');

    // Map Fixed Build.
    // TODO: Skip because the data is empty.
//    $this
//      ->addFieldMapping('field_fixed_build', '_fixed_build')
//      ->sourceMigration('ShoovCiBuildMessagesMigrate');

    // Map Repository.
    $this
      ->addFieldMapping('og_repo', '_repository')
      ->sourceMigration('ShoovRepositoriesMigrate');

    // Map CI Build.
    $this
      ->addFieldMapping('field_ci_build', '_ci_build')
      ->sourceMigration('ShoovCiBuildsMigrate');

    // Map Build Error.
    $this
      ->addFieldMapping('field_ci_build_error', '_build_error')
      ->defaultValue(TRUE);

    // Map Ci Build with User.
    $this
      ->addFieldMapping('uid', '_repository')
      ->sourceMigration('ShoovRepositoriesMigrate')
      ->callbacks(array($this, 'getUidFromRepo'));

  }
}
