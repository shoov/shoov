<?php

/**
 * @file
 * Contains ShoovCiBuildsResource.
 */

class ShoovCiBuildsResource extends \ShoovEntityBaseNode {


  /**
   * Overrides \RestfulEntityBaseNode::publicFieldsInfo().
   */
  public function publicFieldsInfo() {
    $public_fields = parent::publicFieldsInfo();

    $public_fields['enabled'] = array(
      'property' => 'field_ci_build_enabled',
    );

    $public_fields['git_branch'] = array(
      'property' => 'field_git_branch',
    );

    $public_fields['repository'] = array(
      'property' => 'og_repo',
      'resource' => array(
        'repository' => array(
          'name' => 'repositories',
          'full_view' => FALSE,
        ),
      ),
    );

    $public_fields['interval'] = array(
      'property' => 'field_ci_build_interval',
    );

    $public_fields['private_key'] = array(
      'property' => 'field_private_key',
    );

    $public_fields['status_token'] = array(
      'property' => 'field_status_token',
    );

    return $public_fields;
  }

  /**
   * {@inheritdoc}
   *
   * Catch '.shoov.yml is missing' exception and through
   * restful exception instead.
   */
  public function createEntity() {
    try {
      $entity = parent::createEntity();
    }
    catch (Exception $e) {
      if ($e->getMessage() == '.shoov.yml is missing in the root of the repository.') {
        throw new \RestfulBadRequestException(".shoov.yml is missing in the root of the repository.");
      }
      throw $e;
    }
    return $entity;
  }

  /**
   * {@inheritdoc}
   *
   * Catch '.shoov.yml is missing' exception and through
   * restful exception instead.
   */
  public function patchEntity($entity_id) {
    try {
      $entity = parent::patchEntity($entity_id);
    }
    catch (Exception $e) {
      if ($e->getMessage() == '.shoov.yml is missing in the root of the repository.') {
        throw new \RestfulBadRequestException(".shoov.yml is missing in the root of the repository.");
      }
      throw $e;
    }
    return $entity;
  }

  /**
   * {@inheritdoc}
   */
  protected function checkEntityAccess($op, $entity_type, $entity) {
    $account = $this->getAccount();

    $wrapper = entity_metadata_wrapper('node', $entity);
    $repo_name = $wrapper->og_repo->label();

    if (og_is_member('node', $wrapper->og_repo->getIdentifier(), 'user', $account)) {
      // User is member of the repository in shoov and has access to the CI
      // build even if doesn't have it in GitHub.
      return TRUE;
    }

    // Check user is member of the repository in GitHub.
    $user_wrapper = entity_metadata_wrapper('user', $account);
    $user_name = $user_wrapper->label();
    $access_token = $user_wrapper->field_github_access_token->value();

    $options = array(
      'method' => 'GET',
      'headers' => array(
        'Authorization' => 'token ' . $access_token,
      ),
    );
    $url = 'repos/' . $repo_name . '/collaborators/' . $user_name;
    $response = shoov_github_http_request($url, $options);

    if ($response['meta']['status'] == 204) {
      // User is a member of the repository. Subscribe them to the repository.
      $params = array(
        'entity_type' => 'user',
        'entity' => $account,
        'field_name' => 'og_user_node'
      );

      og_group('node', $wrapper->og_repo->getIdentifier(), $params);
      return TRUE;
    }

    return FALSE;
  }
}
