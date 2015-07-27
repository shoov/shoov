<?php

/**
 * @file
 * Contains ShoovCiBuildStatusResource.
 */

class ShoovCiBuildStatusResource extends \RestfulEntityBase {

  /**
   * Overrides \RestfulDataProviderEFQ::controllersInfo().
   *
   * Accept only GET request with an id.
   */
  public static function controllersInfo() {
    return array(
      '^.*$' => array(
        \RestfulInterface::GET => 'viewEntities',
      ),
    );
  }

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
    switch($ci_build_status) {
      case 'unconfirmed_error':
        $file = 'unconfirmed_error.png';
        break;
      case 'error':
        $file = 'error.png';
        break;
      default:
        $file = 'passing.png';
        break;
    }

    return theme('image', array('path' => drupal_get_path('module', 'shoov_ci_build') . '/images/statuses/' . $file));
  }
}
