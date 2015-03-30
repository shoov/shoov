<?php

/**
 * @file
 * Contains ShoovBuildsResource.
 */

class ShoovBuildsResource extends \ShoovEntityBaseNode {


  /**
   * Overrides \RestfulEntityBaseNode::publicFieldsInfo().
   */
  public function publicFieldsInfo() {
    $public_fields = parent::publicFieldsInfo();

    $public_fields['git_commit'] = array(
      'property' => 'field_git_commit',
    );

    $public_fields['git_branch'] = array(
      'property' => 'field_git_branch',
    );

    $public_fields['directory_prefix'] = array(
      'property' => 'field_directory_prefix',
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

    $public_fields['pull_request'] = array(
      'property' => 'field_pull_request',
    );


    return $public_fields;
  }
}
