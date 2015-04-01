<?php

/**
 * Public piped (piped://) stream wrapper class.
 */
class ShoovPipedStreamWrapper extends DrupalLocalStreamWrapper {
  /**
   * Implements abstract public function getDirectoryPath()
   */
  public function getDirectoryPath() {
    return variable_get('file_public_path', conf_path() . '/files');
  }

  /**
   * Overrides getExternalUrl().
   *
   * Return the HTML URI of a private file.
   */
  function getExternalUrl() {
    $path = str_replace('\\', '/', $this->getTarget());
    return url('shoov/images/' . $path, array('absolute' => TRUE));
  }
}