<?php

/**
 * @file
 * Contains ShoovGitHubReposResource.
 */

class ShoovGitHubOrgsResource extends \ShoovDataProviderGitHub {

  /**
   * {@inheritdoc}
   */
  public function publicFieldsInfo() {
    $public_fields['login'] = array(
      'property' => 'login',
    );

    $public_fields['id'] = array(
      'property' => 'id',
    );

    return $public_fields;
  }
}
