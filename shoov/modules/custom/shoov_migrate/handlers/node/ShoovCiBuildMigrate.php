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
    '_git_branch',
    '_git_commit',
    '_enabled'
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
      ->addFieldMapping('field_git_branch', '_git_branch');

    $this
      ->addFieldMapping('field_git_commit', '_git_commit');

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

  public function getUidFromRepo($repo_id) {
    $repo_node = node_load($repo_id['destid1']);
    return $repo_node->uid;
  }
}