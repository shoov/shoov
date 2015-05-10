<?php

/**
 * @file
 * Contains ShoovCiBuildItemsResource.
 */

class ShoovCiBuildItemsResource extends \RestfulEntityBase {

  /**
   * Overrides \RestfulEntityBase::publicFieldsInfo().
   */
  public function publicFieldsInfo() {
    $public_fields = parent::publicFieldsInfo();

    unset($public_fields['label']);

    $public_fields['start_timestamp'] = array(
      'property' => 'field_ci_build_timestamp',
    );

    $public_fields['status'] = array(
      'property' => 'field_ci_build_status',
    );

    $public_fields['log'] = array(
      'property' => 'field_ci_build_log',
      'sub_property' => 'value',
      'process_callbacks' => array(
        array($this, 'processLog'),
      ),
    );

    $public_fields['build'] = array(
      'property' => 'field_ci_build',
      'resource' => array(
        'ci_build' => array(
          'name' => 'ci_builds',
          'full_view' => FALSE,
        ),
      ),
    );

    return $public_fields;
  }

  /**
   * @param $value
   * @return string
   */
  protected function processLog($value) {
    if (strpos($value, "ENOENT, open '/home/shoov/build/.shoov.yml'") == TRUE) {
      return '.shoov.yml file is missing. Make sure to add one in the root of your repository.';
    }

    return $value;
  }

  /**
   * {@inheritdoc}
   *
   * Set the Log field to be full html.
   * @todo: be more careful here...
   */
  protected function propertyValuesPreprocessText($property_name, $value, $field_info) {
    $output = parent::propertyValuesPreprocessText($property_name, $value, $field_info);
    if ($property_name == 'log') {
      $output['format'] = 'full_html';
    }

    return $output;
  }
}
