<?php

/**
 * @file
 * Contains ShoovCompaniesResource.
 */

class ShoovScreenshotsResource extends \ShoovEntityBaseNode {


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

    $public_fields['build'] = array(
      'property' => 'field_build',
      'resource' => array(
        'ui_build' => array(
          'name' => 'builds',
          'full_view' => FALSE,
        ),
      ),
    );

    $public_fields['repository'] = array(
      'property' => 'og_repo',
      'resource' => array(
        'repository' => array(
          'name' => 'repositories',
          'full_view' => TRUE,
        ),
      ),
    );

    return $public_fields;
  }
}
