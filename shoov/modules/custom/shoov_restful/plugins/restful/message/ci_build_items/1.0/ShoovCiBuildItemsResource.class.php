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
}
