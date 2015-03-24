<?php

/**
 * @file
 * Contains ShoovRepositoriesResource.
 */

class ShoovRepositoriesResource extends \ShoovEntityBaseNode {


  /**
   * Overrides \RestfulEntityBaseNode::publicFieldsInfo().
   */
  public function publicFieldsInfo() {
    $public_fields = parent::publicFieldsInfo();

    // @todo: Add encryption
    $public_fields['ssh_private_key'] = array(
      'property' => 'field_ssh_private_key',
      'access_callbacks' => array(
        array($this, 'accessSshPrivateKey'),
      ),
    );

    unset($public_fields['updated']);

    return $public_fields;
  }

  /**
   * @fixme: This doesn't work.
   */
  protected function accessSshPrivateKey($op, $public_field_name, \EntityMetadataWrapper $property_wrapper, \EntityMetadataWrapper $wrapper) {
    return FALSE;
  }
}
