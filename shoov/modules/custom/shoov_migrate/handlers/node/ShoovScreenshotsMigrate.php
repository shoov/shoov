<?php

/**
 * @file
 * Contains \ShoovScreenshotsMigrate.
 */

class ShoovScreenshotsMigrate extends \ShoovMigrateNode {

  public $entityType = 'node';
  public $bundle = 'screenshot';

  public $fields = array(
    '_repository',
    '_baseline_name',
    '_baseline_image',
    '_regression_image',
    '_diff_image',
    '_repository',
    '_ui_build',
    '_tags',
  );

  public $dependencies = array(
    'ShoovUsersMigrate',
    'ShoovRepositoriesMigrate',
    'ShoovUiBuildsMigrate'
  );

  public function __construct($arguments = array()) {
    parent::__construct($arguments);

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
      ->sourceMigration('ShoovUiBuildsMigrate');

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
   * Implements MigrateDestination::complete().
   *
   * Assign this screenshot with Ui Build that its have. Because they depend
   * from each other.
   * Set the hash field value.
   */
  public function complete($entity, $row) {
    $ui_build_id = $entity->field_build[LANGUAGE_NONE][0]['target_id'];
    $wrapper = entity_metadata_wrapper('node', $ui_build_id);
    $wrapper->field_pr_screenshot_ids->set($entity->nid);
    $wrapper->save();

    // Create hash for the screenshot and set the field value.
    $wrapper = entity_metadata_wrapper('node', $entity);
    $files = array();
    $files[]['id'] = $wrapper->field_baseline_image->value()['fid'];
    $files[]['id'] = $wrapper->field_regression_image->value()['fid'];
    $files[]['id'] = $wrapper->field_diff_image->value()['fid'];

    $hash = shoov_screenshot_create_hash($files, $ui_build_id);
    $wrapper->field_screenshot_hash->set($hash);
    $wrapper->save();
  }

  /**
   * Implements MigrateDestination::prepare().
   *
   * Handle for tags.
   */
  function prepare($entity, $row) {
    $wrapper = entity_metadata_wrapper('node', $entity);

    // Get a ID of "Screenshot tags" vocabulary.
    $vocabulary_id = shoov_repository_get_vocabulary_by_repo('Screenshots tags', $wrapper->og_repo->value()->nid);

    // If a screenshot have tags handle it.
    if ($row->_tags) {
      $tags = explode(',', $row->_tags);
      $tids = array();
      foreach($tags as $tag) {
        $tid = shoov_screenshot_add_tag_to_vocabulary($tag, $vocabulary_id);
        $tids[] = $tid;
      }
      $wrapper->{OG_VOCAB_FIELD}->set($tids);
    }
  }
}
