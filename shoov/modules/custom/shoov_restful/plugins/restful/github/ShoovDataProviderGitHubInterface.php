<?php

/**
 * @file
 * Contains \RestfulDataProviderCToolsPluginsInterface
 */

interface ShoovDataProviderGitHubInterface extends RestfulDataProviderInterface {


  /**
   * Get the total count of entities that match certain request.
   *
   * @return int
   *   The total number of results without including pagination.
   */
  public function getTotalCount();

  /**
   * Add navigation links (next, prev, first, last).
   *
   * @return mixed
   *  The links with title and href if they exist.
   */
  public function additionalHateoas();
}
