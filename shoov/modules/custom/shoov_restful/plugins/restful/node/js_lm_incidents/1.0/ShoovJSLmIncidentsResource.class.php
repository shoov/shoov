<?php

/**
 * @file
 * Contains ShoovJsLmIncidentsResource.
 */

class ShoovJsLmIncidentsResource extends \ShoovEntityBaseNode {

  /**
   * Overrides \RestfulEntityBaseNode::publicFieldsInfo().
   */
  public function publicFieldsInfo() {
    $public_fields = parent::publicFieldsInfo();

    $public_fields['build'] = array(
      'property' => 'field_js_lm_build',
    );

    $public_fields['user_id'] = array(
      'property' => 'field_user_id',
    );

    $public_fields['errors'] = array(
      'property' => 'field_js_lm_errors',
    );

    $public_fields['image'] = array(
      'property' => 'field_js_lm_image',
    );

    return $public_fields;
  }

  /**
   * Overrides \ShoovEntityBaseNode::checkEntityAccess().
   *
   * Always grant access to create.
   *
   * @todo: Reconsider.
   */
  protected function checkEntityAccess($op, $entity_type, $entity) {
    return TRUE;
  }


  public function entityPreSave(\EntityMetadataWrapper $wrapper) {
    parent::entityPreSave($wrapper);

    $request = $this->getRequest();

    // Add label.
    $wrapper->title->set($request['build'] . ' ' . time());


  }

}
