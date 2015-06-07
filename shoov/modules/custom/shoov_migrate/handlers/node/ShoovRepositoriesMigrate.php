<?php

/**
 * @file
 * Contains \ShoovRepositoriesMigrate.
 */

class ShoovRepositoriesMigrate extends \ShoovMigrateBase {

  public $entityType = 'node';
  public $bundle = 'repository';

  public $fields = array(
    '_github_id',
    '_private',
    '_user_id'
  );

  public $dependencies = array(
    'ShoovUsersMigrate',
  );

  public function __construct() {
    parent::__construct();

    // Make Repository as Group.
    $this
      ->addFieldMapping(OG_GROUP_FIELD)
      ->defaultValue(TRUE);

    // Group is private by default.
    $this
      ->addFieldMapping(OG_ACCESS_FIELD, '_private')
      ->defaultValue(TRUE);

    // Map Github Id.
    $this->addFieldMapping('field_github_id', '_github_id');

    // Map users to their repositories.
    $this
      ->addFieldMapping('uid', '_user_id')
      ->sourceMigration('ShoovUsersMigrate');
  }
}
