<?php

/**
 * @file
 * Contains ShoovScreenshotsUploadResource.
 */

class ShoovScreenshotsUploadResource extends RestfulFilesUpload {

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
    // @todo: Move to restful.
    $handler->setAccount($this->getAccount());
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
      'title' => $request['label'],
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

    $vcs_field_names = array(
      'directory_prefix',
      'git_commit',
      'git_branch',
    );

    foreach ($vcs_field_names as $vcs_field_name) {
      if (isset($request[$vcs_field_name])) {
        $wrapper->{'field_' . $vcs_field_name}->set($request[$vcs_field_name]);
      }
    }

    $wrapper->save();
    return $wrapper->value();
  }
}
