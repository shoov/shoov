<?php

/**
 * @file
 * Contains \ShoovCiBuildMigrate.
 */

class ShoovCiBuildMigrate extends \ShoovMigrateBase {

  public $entityType = 'node';
  public $bundle = 'ci_build';

  public $fields = array(
    '_repository',
    '_author',
  );

  public $dependencies = array(
    'ShoovRepositoriesMigrate',
  );


  public function __construct() {
    parent::__construct();


    $this
      ->addFieldMapping(OG_AUDIENCE_FIELD, '_repository')
      ->sourceMigration('ShoovRepositoriesMigrate');

    $this
      ->addFieldMapping('uid', '_author')
      ->sourceMigration('ShoovUsersMigrate');
  }
}
