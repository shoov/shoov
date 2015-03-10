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

    $node = $this->createScreenshotNode($return);

    $handler = restful_get_restful_handler('screenshots');
    return $handler->get($node->nid);
  }

  /**
   * Create a screenshot node from uploaded files.
   */
  protected function createScreenshotNode(array $files) {
    $request = $this->getRequest();

    $values = array(
      'type' => 'screenshot',
      'uid' => $this->getAccount()->uid,
      'title' => $request['baseline_name'],
    );

    $node = entity_create('node', $values);
    $wrapper = entity_metadata_wrapper('node', $node);

    $field_names = array(
      'field_baseline_image',
      'field_regression_image',
      'field_diff_image',
    );

    foreach ($files as $delta => $file) {
      $field_name = $field_names[$delta];

      $wrapper->{$field_name}->set(array('fid' => $file['id'], 'display' => TRUE));
    }

    $wrapper->field_baseline_name->set($request['baseline_name']);

    if (!empty($request['git_commit'])) {
      $wrapper->field_git_commit->set($request['git_commit']);
    }

    if (!empty($request['git_branch'])) {
      $wrapper->field_git_branch->set($request['git_branch']);
    }


    $wrapper->save();
    return $wrapper->value();
  }
}
