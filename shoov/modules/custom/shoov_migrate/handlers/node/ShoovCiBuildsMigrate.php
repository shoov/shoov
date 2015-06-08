<?php

/**
 * @file
 * Contains \ShoovCiBuildsMigrate.
 */

class ShoovCiBuildsMigrate extends \ShoovMigrateNode {

  public $entityType = 'node';
  public $bundle = 'ci_build';

  public $fields = array(
    '_repository',
    '_git_branch',
    '_git_commit',
    '_enabled'
  );

  public $dependencies = array(
    'ShoovRepositoriesMigrate',
  );

  public function __construct() {
    parent::__construct();

    // Map Git Branch.
    $this
      ->addFieldMapping('field_git_branch', '_git_branch');

    // Map Git Commit.
    $this
      ->addFieldMapping('field_git_commit', '_git_commit');

    // Map Status of Ci Build.
    $this
      ->addFieldMapping('field_ci_build_enabled', '_enabled');

    // Map Ci Build with Repository.
    $this
      ->addFieldMapping('og_repo', '_repository')
      ->sourceMigration('ShoovRepositoriesMigrate');

    // Map Ci Build with User.
    $this
      ->addFieldMapping('uid', '_repository')
      ->sourceMigration('ShoovRepositoriesMigrate')
      ->callbacks(array($this, 'getUidFromRepo'));

  }
}
