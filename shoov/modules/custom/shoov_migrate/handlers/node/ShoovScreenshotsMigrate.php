<?php

/**
 * @file
 * Contains \ShoovScreenshotsMigrate.
 */

class ShoovScreenshotsMigrate extends \ShoovMigrateBase {

  public $entityType = 'node';
  public $bundle = 'screenshot';

  public $fields = array(
    '_repository',
    '_baseline_name',
    '_baseline_image',
    '_regression_image',
    '_diff_image',
    '_repository',
    '_ui_build'
  );

  public $dependencies = array(
    'ShoovUsersMigrate',
    'ShoovRepositoriesMigrate',
    'ShoovUiBuildMigrate'
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

    // Map UI Build.
    $this
      ->addFieldMapping('field_build', '_ui_build')
      ->sourceMigration('ShoovUiBuildMigrate');

    // Map Baseline name.
    $this->addFieldMapping('field_baseline_name', '_baseline_name');

    // Map Baseline Image.
    $this->addFieldMapping('field_baseline_image', '_baseline_image');
    $this->addFieldMapping('field_baseline_image:file_replace')
      ->defaultValue(FILE_EXISTS_REPLACE);
    $this->addFieldMapping('field_baseline_image:source_dir')
      ->defaultValue(drupal_get_path('module', 'shoov_migrate') . '/images');
    $this->addFieldMapping('field_baseline_image:destination_dir', 'destination');

    // Map Regression Image.
    $this->addFieldMapping('field_regression_image', '_regression_image');
    $this->addFieldMapping('field_regression_image:file_replace')
      ->defaultValue(FILE_EXISTS_REPLACE);
    $this->addFieldMapping('field_regression_image:source_dir')
      ->defaultValue(drupal_get_path('module', 'shoov_migrate') . '/images');
    $this->addFieldMapping('field_regression_image:destination_dir', 'destination');

    // Map Diff Image.
    $this->addFieldMapping('field_diff_image', '_diff_image');
    $this->addFieldMapping('field_diff_image:file_replace')
      ->defaultValue(FILE_EXISTS_REPLACE);
    $this->addFieldMapping('field_diff_image:source_dir')
      ->defaultValue(drupal_get_path('module', 'shoov_migrate') . '/images');
    $this->addFieldMapping('field_diff_image:destination_dir', 'destination');

  }

  /**
   * Assign this screenshot with Ui Build.
   *
   * @param $entity
   * @param $row
   */
  public function complete($entity, $row) {
    $ui_build = node_load($entity->field_build[LANGUAGE_NONE][0]['target_id']);
    $wrapper = entity_metadata_wrapper('node', $ui_build);
    $wrapper->field_pr_screenshot_ids->set($entity->nid);
    $wrapper->save();
  }
}
