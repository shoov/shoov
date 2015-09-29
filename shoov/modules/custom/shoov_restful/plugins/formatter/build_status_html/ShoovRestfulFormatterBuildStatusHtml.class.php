<?php

/**
 * @file
 * Contains ShoovRestfulFormatterSimple.
 */

class ShoovRestfulFormatterBuildStatusHtml extends \RestfulFormatterBase implements \RestfulFormatterInterface {

  /**
   * Content Type
   *
   * @var string
   */
  protected $contentType = 'text/html; charset=utf-8';

  /**
   * {@inheritdoc}
   */
  public function prepare(array $data) {
    // If we're returning an error then set the content type to
    // 'application/problem+json; charset=utf-8'.
    if (!empty($data['status']) && floor($data['status'] / 100) != 2) {
      $this->contentType = 'application/problem+json; charset=utf-8';
      return $data;
    }

    $this->contentType = 'image/png';

    return $data;
  }

  /**
   * {@inheritdoc}
   */
  public function render(array $structured_data) {
    if (empty($structured_data['build_status'])) {
      // Response is an error, so send as JSON.
      return drupal_json_encode($structured_data);
    }

    return $structured_data['build_status'];
  }

  /**
   * {@inheritdoc}
   */
  public function getContentTypeHeader() {
    return $this->contentType;
  }
}

