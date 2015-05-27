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
  );

  public function __construct() {
    parent::__construct();
    $this
      ->addFieldMapping(OG_GROUP_FIELD)
      ->defaultValue(TRUE);

    // Group is private by default.
    $this
      ->addFieldMapping(OG_ACCESS_FIELD)
      ->defaultValue(TRUE);

    $this
      ->addFieldMapping('field_github_id', '_github_id');

    // Set the admin as the group owner.
    $this
      ->addFieldMapping('uid')
      ->defaultValue(1);
  }
}
