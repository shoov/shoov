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

    // Add label.
    $request = $this->getRequest();

    $wrapper->title->set($request['build'] . ' ' . time());
  }


  public function propertyValuesPreprocess($property_name, $value, $public_field_name) {
    if ($public_field_name == 'image') {

      if (strpos($value, 'data:image/png;base64,') !== 0) {
        throw new \RestfulBadRequestException('Image data is not provided as a valid base64.');
      }

      $base64 = str_replace('data:image/png;base64,', '', $value);
      $base64 = str_replace(' ', '+', $base64);


      if (!$file = file_save_data(base64_decode($base64), 'piped://image64.png')) {
        throw new \RestfulBadRequestException('Image file could not have been saved');
      }
      $value = $file->fid;
    }

    return parent::propertyValuesPreprocess($property_name, $value, $public_field_name);
  }


}
