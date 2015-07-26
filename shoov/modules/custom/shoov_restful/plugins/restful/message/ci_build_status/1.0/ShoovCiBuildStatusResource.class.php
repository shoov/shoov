<?php

/**
 * @file
 * Contains ShoovCiBuildStatusResource.
 */

class ShoovCiBuildStatusResource extends \RestfulEntityBase {

  /**
   * Overrides \RestfulEntityBase::publicFieldsInfo().
   */
  public function publicFieldsInfo() {
    $public_fields = array();

    $public_fields['status'] = array(
      'callback' => array($this, 'statusHtml'),
    );

    return $public_fields;
  }

  protected function statusHtml(\EntityMetadataWrapper $wrapper) {
    $ci_build_status = $wrapper->field_ci_build_incident_status->value();
    $variables = array();
    switch($ci_build_status) {
      case 'unconfirmed_error':
        $variables['file'] = 'unconfirmed_error.png';
        break;
      case 'error':
        $variables['file'] = 'error.png';
        break;
      default:
        $variables['file'] = 'passing.png';
        break;
    }

    return theme('image', array('path' => drupal_get_path('module', 'shoov_ci_build') . '/images/statuses/' . $variables['file']));
  }
}
