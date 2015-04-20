<?php

/**
 * @file
 * Contains ShoovGitHubReposResource.
 */

class ShoovGitHubReposResource extends \ShoovDataProviderGitHub {


  /**
   * Overrides \RestfulEntityBaseNode::publicFieldsInfo().
   */
  public function publicFieldsInfo() {
    $public_fields = parent::publicFieldsInfo();

    unset($public_fields['updated']);

    return $public_fields;
  }
}
