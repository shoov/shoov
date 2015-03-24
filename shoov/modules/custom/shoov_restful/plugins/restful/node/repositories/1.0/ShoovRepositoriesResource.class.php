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
    );

    return $public_fields;
  }
}
