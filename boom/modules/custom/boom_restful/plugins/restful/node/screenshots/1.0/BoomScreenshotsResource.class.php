<?php

/**
 * @file
 * Contains BoomCompaniesResource.
 */

class BoomScreenshotsResource extends \BoomEntityBaseNode {


  /**
   * Overrides \RestfulEntityBaseNode::publicFieldsInfo().
   */
  public function publicFieldsInfo() {
    $public_fields = parent::publicFieldsInfo();

    $public_fields['baseline_name'] = array(
      'property' => 'field_baseline_name',
    );

    $public_fields['baseline'] = array(
      'property' => 'field_baseline_image',
      // This will add 3 image variants in the output.
      'image_styles' => array('thumbnail', 'medium', 'large'),
      'process_callbacks' => array(
        array($this, 'imageProcess'),
      ),
    );

    $public_fields['regression'] = array(
      'property' => 'field_regression_image',
      // This will add 3 image variants in the output.
      'image_styles' => array('thumbnail', 'medium', 'large'),
      'process_callbacks' => array(
        array($this, 'imageProcess'),
      ),
    );

    $public_fields['diff'] = array(
      'property' => 'field_diff_image',
      // This will add 3 image variants in the output.
      'image_styles' => array('thumbnail', 'medium', 'large'),
      'process_callbacks' => array(
        array($this, 'imageProcess'),
      ),
    );

    $public_fields['git_commit'] = array(
      'property' => 'field_git_commit',
    );

    $public_fields['git_branch'] = array(
      'property' => 'field_git_branch',
    );

    $public_fields['directory_prefix'] = array(
      'property' => 'field_directory_prefix',
    );

    return $public_fields;
  }
}