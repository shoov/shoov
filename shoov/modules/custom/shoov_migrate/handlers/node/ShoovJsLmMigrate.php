<?php

/**
 * @file
 * Contains \ShoovJsLmMigrate.
 */

class ShoovJsLmMigrate extends \ShoovMigrateNode {

  public $entityType = 'node';
  public $bundle = 'js_lm';

  public $fields = array(
    '_user_id'
  );

  public $dependencies = array(
    'ShoovUsersMigrate',
  );

  public function __construct($arguments = array()) {
    parent::__construct($arguments);

    // Make JS Live monitor as Group.
    $this
      ->addFieldMapping(OG_GROUP_FIELD)
      ->defaultValue(TRUE);

    // Map user.
    $this
      ->addFieldMapping('uid', '_user_id')
      ->sourceMigration('ShoovUsersMigrate');
  }

}
