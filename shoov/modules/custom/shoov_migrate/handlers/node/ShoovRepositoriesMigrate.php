<?php

/**
 * @file
 * Contains \ShoovRepositoriesMigrate.
 */

class ShoovRepositoriesMigrate extends \ShoovMigrateNode {

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

  public function __construct($arguments = array()) {
    parent::__construct($arguments);

    // Make Repository as Group.
    $this
      ->addFieldMapping(OG_GROUP_FIELD)
      ->defaultValue(TRUE);

    // Group is private by default.
    $this
      ->addFieldMapping(OG_ACCESS_FIELD)
      ->defaultValue(TRUE);

    // Map Github Id.
    $this->addFieldMapping('field_github_id', '_github_id');

    // Map users to their repositories.
    $this
      ->addFieldMapping('uid', '_user_id')
      ->sourceMigration('ShoovUsersMigrate');
  }
}
