<?php

/**
 * @file
 * Contains ShoovGitHubReposResource.
 */

class ShoovGitHubReposResource extends \ShoovDataProviderGitHub {

  /**
   * {@inheritdoc}
   */
  public function publicFieldsInfo() {
    $public_fields['label'] = array(
      'property' => 'full_name',
    );

    $public_fields['id'] = array(
      'property' => 'id',
    );

    $public_fields['shoov_id'] = array(
      'property' => 'shoov_id',
    );

    $public_fields['build'] = array(
      'property' => 'shoov_build',
    );

    $public_fields['organization'] = array(
      'property' => 'owner',
      'process_callbacks' => array(
        array($this, 'organizationProcess'),
      ),
    );

    return $public_fields;
  }
}
