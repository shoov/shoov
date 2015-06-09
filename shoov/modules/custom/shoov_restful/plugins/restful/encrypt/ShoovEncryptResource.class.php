<?php

/**
 * @file
 * Contains ShoovEncryptResource.
 */

class ShoovEncryptResource extends \RestfulEntityBase {

  /**
   * Overrides \RestfulBase::controllers.
   */
  protected $controllers = array(
    '^.*$' => array(
      \RestfulInterface::POST => 'viewEntities',
    ),
  );


  /**
   * Overrides \RestfulBase::publicFieldsInfo().
   */
  public function publicFieldsInfo() {
    $public_fields = parent::publicFieldsInfo();

    $public_fields['encrypt'] = array(
      'callback' => array($this, 'getEncrypt'),
    );

    return $public_fields;
  }

  /**
   * @param $value
   * @return string
   */
  protected function getEncrypt($value) {
    $request = $this->getRequest();

    $server_url = variable_get('shoov_ui_build_pr_server', 'http://localhost:3000');

    return 'some value!';
  }

}
