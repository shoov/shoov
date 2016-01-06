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
      'required' => TRUE,
    );

    $public_fields['errors'] = array(
      'property' => 'field_js_lm_errors',
      'required' => TRUE,
    );

    $public_fields['url'] = array(
      'property' => 'field_js_lm_url',
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
   * Determine JS LM Build Token has been checked and validated.
   */
  protected $tokenValid = NULL;

  /**
   * Check token that has been sent is valid.
   */
  protected function checkToken() {
    if (isset($this->tokenValid)) {
      // Token already has been checked.
      return $this->tokenValid;
    }

    $request = $this->getRequest();
    // Check the build token.
    if (empty($_GET['token'])) {
      $this->tokenValid = FALSE;
      return FALSE;
    }
    $token = $_GET['token'];

    if (!$build = node_load($request['build'])) {
      $this->tokenValid = FALSE;
      return FALSE;
    }

    $wrapper = entity_metadata_wrapper('node', $build);
    $build_token = $wrapper->field_js_lm_build_token->value();
    $this->tokenValid = $token == $build_token;
    return $this->tokenValid;
  }

  /**
   * Overrides \ShoovEntityBaseNode::checkEntityAccess().
   */
  protected function checkEntityAccess($op, $entity_type, $entity) {
    return $this->checkToken();
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

    $account = $this->getAccount();
    $build = node_load($request['build']);
    if (!$account->uid) {
      // Set the user to JS-LM is user is anonymous.
      $this->setAccount(user_load($build->uid));
    }

    if (isset($request['image'])) {
      // Get the  file contents from the Data URL.
      list($meta, $content) = explode(',', $request['image']);
      // Replace spaces with "+" since javascript puts spaces in the encoded data.
      $content = base64_decode(str_replace(' ', '+', $content));

      // Save the image.
      $filename = md5('JSLM-' . $request['build'] . '-incident-' . time());
      $file = file_save_data($content, 'public://' . $filename . '.png');

      // Replace the Data URL with the file ID in the request.
      $request['image'] = $file->fid;
      // Re-set the updated request to create entity.
      $this->setRequest($request);
    }

    parent::createEntity();
  }
}
