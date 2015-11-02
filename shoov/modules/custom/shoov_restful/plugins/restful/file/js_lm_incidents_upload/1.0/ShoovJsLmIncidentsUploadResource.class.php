<?php

/**
 * @file
 * Contains ShoovJsLmIncidentsUploadResource.
 */

class ShoovJsLmIncidentsUploadResource extends RestfulFilesUpload {

  /**
   * Create and save files.
   *
   * @return array
   *   Array with a list of file IDs that were created and saved.
   *
   * @throws \Exception
   */
  public function createEntity() {
    $request = $this->getRequest();

    $files = array();

    if (!empty($request['image'])) {
      $this->base64ImageToFile($request['image']);
      $files = parent::createEntity();
    }


    return $this->createJsLmIncidentNode($files);
  }

  /**
   * Create a screenshot node from uploaded files.
   */
  protected function createJsLmIncidentNode(array $files = array()) {
    $request = $this->getRequest();

    if ($files) {
      $file = key($files);
      $request['image'] = $file['id'];
    }


    $handler = restful_get_restful_handler('js_lm_incidents');
    return $handler->post('', $request);
  }

  protected function base64ImageToFile($base64) {
    $base64 = str_replace('data:image/png;base64,', '', $base64);
    if (!$path = file_unmanaged_save_data(base64_decode($base64), 'temporary://')) {
      throw new \RestfulBadRequestException('Image could not be saved');
    }


    // Update $_FILES, in the format it is expected by
    // \RestfulFilesUpload::createEntity().

    $name = md5(time());

    $_FILES['files'][$name] = array(
      'name'     =>  $name . '.png',
      'type'     =>  'image/png',
      'tmp_name' =>  $path,
      'error'    =>  0,
      'size'     =>  filesize($path),
    );

  }

  /**
   * Overrides RestfulEntityBase::access().
   *
   * If "File entity" module exists, determine access by its provided permissions
   * otherwise, check if variable is set to allow anonymous users to upload.
   * Defaults to authenticated user.
   */
  public function access() {
    return TRUE;
  }


}
