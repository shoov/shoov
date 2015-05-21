<?php

/**
 * @file
 * Contains \ShoovRepositoriesMigrate.
 */

class ShoovRepositoriesMigrate extends \ShoovMigrateBase {

  public $entityType = 'node';
  public $bundle = 'repository';

  public function __construct() {
    parent::__construct();
    $this
      ->addFieldMapping(OG_GROUP_FIELD)
      ->defaultValue(TRUE);

    // Group is private by default.
    $this
      ->addFieldMapping(OG_ACCESS_FIELD)
      ->defaultValue(TRUE);

    // Group belong to the admin by default.
    $this
      ->addFieldMapping('uid')
      ->defaultValue('1');
  }
}
