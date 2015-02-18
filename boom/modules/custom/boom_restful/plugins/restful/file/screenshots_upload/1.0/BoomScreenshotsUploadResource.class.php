<?php

/**
 * @file
 * Contains BoomScreenshotsUploadResource.
 */

class BoomScreenshotsUploadResource extends RestfulFilesUpload {

  /**
   * Create and save files.
   *
   * @return array
   *   Array with a list of file IDs that were created and saved.
   *
   * @throws \Exception
   */
  public function createEntity() {
    $return = parent::createEntity();
    return $return;
  }

  /**
   * Create a screenshot node from an uploaded file.
   */
  protected function createScreenshotNode($file) {
    $values = array(
      'type' => 'screenshot',
      'uid' => $file->uid,
      'title' => $file->filename,
    );

    $node = entity_create('node', $values);
    $wrapper = entity_metadata_wrapper('node', $node);

    $wrapper->field_image->set(array('fid' => $file->fid, 'display' => TRUE));
    $wrapper->save();
  }
}
