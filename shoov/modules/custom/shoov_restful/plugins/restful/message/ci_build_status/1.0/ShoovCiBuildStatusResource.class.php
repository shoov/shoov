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
        $variables['text'] = 'Unconfirmed Error';
        $variables['color'] = '#af8c38';
        break;
      case 'error':
        $variables['text'] = 'Error';
        $variables['color'] = '#f1353d';
        break;
      default:
        $variables['text'] = 'Passing';
        $variables['color'] = '#3fa75f';
        break;
    }
    return theme('build_status', $variables);
  }
}
