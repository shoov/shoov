<?php

/**
 * @file
 * Contains \ShoovJsLmMigrate.
 */

class ShoovJsLmMigrate extends \ShoovMigrateNode {

  public $entityType = 'node';
  public $bundle = 'js_lm';

  public function __construct() {
    parent::__construct();
  }
}
