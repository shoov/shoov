<?php

/**
 * @file
 * Contains \ShoovUiBuildMigrate.
 */

class ShoovUiBuildMigrate extends \ShoovMigrateBase {

  public $entityType = 'node';
  public $bundle = 'ui_build';

  public $fields = array(
    '_repository',
    '_browser',
    '_git_commit',
    '_git_branch',
    '_directory_prefix',
    '_pull_request',
    '_pull_request_status',
    '_pr_branch_name'
  );

  public $dependencies = array(
    'ShoovRepositoriesMigrate'
  );

  public function __construct() {
    parent::__construct();

    // Map User.
    $this
      ->addFieldMapping('uid', '_repository')
      ->sourceMigration('ShoovRepositoriesMigrate')
      ->callbacks(array($this, 'getUidFromRepo'));

    // Map Repository.
    $this
      ->addFieldMapping('og_repo', '_repository')
      ->sourceMigration('ShoovRepositoriesMigrate');

    // Map Browser.
    $this->addFieldMapping('field_browser', '_browser');

    // Map Git Commit.
    $this->addFieldMapping('field_git_commit', '_git_commit');

    // Map Git Branch.
    $this->addFieldMapping('field_git_branch', '_git_branch');

    // Map Directory prefix.
    $this->addFieldMapping('field_directory_prefix', '_directory_prefix');

    // Map Pull Request.
    $this->addFieldMapping('field_pull_request', '_pull_request');

    // Map Pull Request Status.
    $this->addFieldMapping('field_pull_request_status', '_pull_request_status');

    // Map PR Branch Name.
    $this->addFieldMapping('field_pr_branch_name', '_pr_branch_name');
  }
}
