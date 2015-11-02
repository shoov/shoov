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
    // Update $_FILES, as it's expected by \RestfulFilesUpload::createEntity().
    if (!$path = file_unmanaged_save_data(base64_decode($base64), 'temporary://')) {
      throw new \RestfulBadRequestException('Image could not be saved');
    }

    $name = basename($path);
    $_FILES[$name] = $path;
  }


}
