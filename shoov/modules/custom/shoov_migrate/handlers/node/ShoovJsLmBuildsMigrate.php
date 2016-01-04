<?php

/**
 * @file
 * Contains \ShoovJsLmBuildsMigrate.
 */

class ShoovJsLmBuildsMigrate extends \ShoovMigrateNode {

  public $entityType = 'node';
  public $bundle = 'js_lm_build';

  public $fields = array(
    '_js_lm',
    '_url',
    '_token'
  );

  public $dependencies = array(
    'ShoovJsLmMigrate',
  );

  public function __construct() {
    parent::__construct();

    // Map URL.
    $this
      ->addFieldMapping('field_js_lm_url', '_url');

    // Map token.
    $this
      ->addFieldMapping('field_js_lm_build_token', '_token');

    // Map Lm Build with JS LM.
    $this
      ->addFieldMapping('js_lm', '_js_lm')
      ->sourceMigration('ShoovJsLmMigrate');
  }
}
