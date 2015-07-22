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
    $public_fields = parent::publicFieldsInfo();

    unset($public_fields['label']);

    $public_fields['status'] = array(
      'property' => 'nid',
      'process_callbacks' => array(
        array($this, 'statusImage'),
      ),
    );

    return $public_fields;
  }

  protected function statusImage($value = NULL) {
    return url("ci-build/$value/status", array('absolute' => TRUE));
  }
}
