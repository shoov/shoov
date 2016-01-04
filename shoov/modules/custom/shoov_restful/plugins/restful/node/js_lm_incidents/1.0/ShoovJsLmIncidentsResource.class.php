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
      'process_callbacks' => array(
        array($this, 'imageProcess'),
      ),
    );

    return $public_fields;
  }

  /**
   * Determine if JS LM Build Token has been checked it matches the field value.
   */
  protected $token_valid = FALSE;

  /**
   * Overrides \ShoovEntityBaseNode::checkEntityAccess().
   */
  protected function checkEntityAccess($op, $entity_type, $entity) {
    return $this->token_valid;
  }

  /**
   * Overrides \ShoovEntityBaseNode::checkPropertyAccess().
   */
  protected function checkPropertyAccess($op, $public_field_name, EntityMetadataWrapper $property_wrapper, EntityMetadataWrapper $wrapper) {
    return $this->token_valid;
  }

  public function entityPreSave(\EntityMetadataWrapper $wrapper) {
    parent::entityPreSave($wrapper);

    $request = $this->getRequest();

    // Add label.
    $wrapper->title->set($request['build'] . ' ' . time());

    // Add group reference
    $node_wrapper = entity_metadata_wrapper('node', $request['build']);
    $wrapper->js_lm->set($node_wrapper->js_lm->value(array('identifier' => TRUE)));
  }

  /**
   * Overrides \ShoovEntityBaseNode::createEntity().
   *
   * Create file from Data URL before creating entity.
   */
  public function createEntity() {
    $request = $this->getRequest();

    // Get the  file contents from the Data URL.
    list($meta, $content) = explode(',', $request['image']);
    // Replace spaces with "+" since javascript puts spaces in the encoded data.
    $content = base64_decode(str_replace(' ', '+', $content));

    // Save the image.
    $filename = md5('JSLM-' . $request['build'] . '-incident-' . time());
    $file = file_save_data($content, 'public://' . $filename . '.png');

    // Replace the Data URL with the file ID in the request.
    $request['image'] = $file->fid;

    // Check the build token and remove it from the request.
    $token = $request['token'];
    $build = node_load($request['build']);
    $wrapper = entity_metadata_wrapper('node', $build);
    $build_token = $wrapper->field_js_lm_build_token->value();
    $this->token_valid = $token == $build_token;
    unset($request['token']);

    // Re-set the updated request to create entity.
    $this->setRequest($request);

    parent::createEntity();
  }
}
