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
        \RestfulInterface::GET => 'viewEntity',
      ),
    );
  }

  /**
   * Overrides \RestfulEntityBase::publicFieldsInfo().
   */
  public function publicFieldsInfo() {
    $public_fields = array();

    $public_fields['build_status'] = array(
      'callback' => array($this, 'statusHtml'),
    );

    return $public_fields;
  }

  protected function statusHtml(\EntityMetadataWrapper $wrapper) {
    $ci_build_status = $wrapper->field_ci_build_incident_status->value();
    switch($ci_build_status) {
      case 'unconfirmed_error':
        $file = 'unconfirmed_error';
        break;
      case 'error':
        $file = 'error';
        break;
      default:
        $file = 'passing';
        break;
    }

    return theme('image', array('path' => drupal_get_path('module', 'shoov_ci_build') . '/images/statuses/' . $file . '.png'));
  }

  protected function isValidEntity($op, $entity_id) {
    dpm($op);
    dpm($entity_id);

    $request = $this->getRequest();


    return TRUE;
  }
}
