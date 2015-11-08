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

    $repo_node = $this->getRepositoryNode();

    $build_node = $this->getBuildNode($repo_node);

    $hash = shoov_screenshot_create_hash($files, $build_node->nid);

    if ($nid = shoov_screenshot_regression_exists($hash)) {
      return node_load($nid);
    }

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

    $wrapper->field_screenshot_hash->set($hash);

    $vocabulary_id = shoov_repository_get_vocabulary_by_repo('screenshots_tags', $repo_node->nid);

    if ($request['tags']) {
      $tags = explode(',', $request['tags']);
      $tids = array();
      foreach($tags as $tag) {
        $tid = shoov_screenshot_add_tag_to_vocabulary($tag, $vocabulary_id);
        $tids[] = $tid;
      }
      $wrapper->{OG_VOCAB_FIELD}->set($tids);
    }

    $wrapper->field_build->set($build_node);

    // Set the repo node.
    $wrapper->og_repo->set($repo_node);

    $wrapper->save();
    return $wrapper->value();
  }

  /**
   * Get or create a new Build node.
   *
   * @param \stdClass $repo_node
   *   The repository node object.
   *
   * @return \stdClass
   *   An existing or newly saved Build node object.
   *
   * @throws \RestfulBadRequestException
   */
  protected function getBuildNode($repo_node) {
    $request = $this->getRequest();

    // Find the build node.
    $query = new EntityFieldQuery();
    $result = $query
      ->entityCondition('entity_type', 'node')
      ->entityCondition('bundle', 'ui_build')
      ->fieldCondition('field_git_commit', 'value', $request['git_commit'])
      ->fieldCondition('og_repo', 'target_id', $repo_node->nid)
      ->range(0, 1)
      ->execute();

    if (!empty($result['node'])) {
      $id = key($result['node']);
      $build_node = node_load($id);
    }
    else {
      $params = array(
        '@subject' => substr($request['git_subject'], 0, 60),
        '@hash' => substr($request['git_commit'], 0, 7),
      );

      // Create a new node.
      $values = array(
        'type' => 'ui_build',
        'uid' => $this->getAccount()->uid,
        'title' => format_string('@subject (@hash)', $params),
      );

      $build_node = entity_create('node', $values);
      $wrapper = entity_metadata_wrapper('node', $build_node);

      $vcs_field_names = array(
        'directory_prefix',
        'git_commit',
        'git_branch',
      );

      foreach ($vcs_field_names as $vcs_field_name) {
        if (!isset($request[$vcs_field_name])) {
          $params = array('@name' => $vcs_field_name);
          throw new \RestfulBadRequestException(format_string('Property @name is missing form the request.', $params));
        }

        $wrapper->{'field_' . $vcs_field_name}->set($request[$vcs_field_name]);
      }

      // Set group to be private.
      $wrapper->og_repo->set($repo_node);
      $wrapper->save();
    }

    return $build_node;
  }

  /**
   * Get or create a new Repository node.
   *
   * @return \stdClass
   *   An existing or newly saved Repository node object.
   *
   * @throws \RestfulBadRequestException
   */
  protected function getRepositoryNode() {
    $request = $this->getRequest();

    if (empty($request['repository'])) {
      throw new \RestfulBadRequestException('"repository" is a required value');
    }

    $repo_name = trim($request['repository']);

    // Find the repository node.
    $query = new EntityFieldQuery();
    $result = $query
      ->entityCondition('entity_type', 'node')
      ->entityCondition('bundle', 'repository')
      ->propertyCondition('title', $repo_name)
      ->range(0, 1)
      ->execute();

    if (!empty($result['node'])) {
      $id = key($result['node']);
      $repo_node = node_load($id);

      if (!node_access('view', $repo_node, $this->getAccount())) {
        throw new \RestfulBadRequestException('"repository" name is wrong or you may not have access to it.');
      }
    }
    else {
      // Create a new node.
      $values = array(
        'type' => 'repository',
        'uid' => $this->getAccount()->uid,
        'title' => $repo_name,
      );

      $repo_node = entity_create('node', $values);
      $wrapper = entity_metadata_wrapper('node', $repo_node);

      // Set group to be private.
      $wrapper->{OG_ACCESS_FIELD}->set(TRUE);
      $wrapper->save();
    }

    return $repo_node;
  }
}
