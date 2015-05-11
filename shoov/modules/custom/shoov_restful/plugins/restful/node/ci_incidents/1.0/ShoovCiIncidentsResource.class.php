<?php

/**
 * @file
 * Contains ShoovCiIncidentsResource.
 */

class ShoovCiIncidentsResource extends \ShoovEntityBaseNode {


  /**
   * Overrides \RestfulEntityBaseNode::publicFieldsInfo().
   */
  public function publicFieldsInfo() {
    $public_fields = parent::publicFieldsInfo();

    $public_fields['failing_build'] = array(
      'property' => 'field_failing_build',
      'resource' => array(
        'ci_build' => array(
          'name' => 'ci-build-items',
          'full_view' => TRUE,
        ),
      ),
    );

    $public_fields['fixed_build'] = array(
      'property' => 'field_fixed_build',
      'resource' => array(
        'ci_build' => array(
          'name' => 'ci-build-items',
          'full_view' => TRUE,
        ),
      ),
    );

    $public_fields['error'] = array(
      'property' => 'field_ci_build_error',
    );

    $public_fields['ci_build'] = array(
      'property' => 'field_ci_build',
      'resource' => array(
        'ci_build' => array(
          'name' => 'ci-builds',
          'full_view' => FALSE,
        ),
      ),
    );

    $public_fields['repository'] = array(
      'property' => 'og_repo',
      'resource' => array(
        'repository' => array(
          'name' => 'repositories',
          'full_view' => FALSE,
        ),
      ),
    );

    return $public_fields;
  }
}
