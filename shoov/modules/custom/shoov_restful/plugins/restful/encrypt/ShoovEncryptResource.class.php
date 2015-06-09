<?php

/**
 * @file
 * Contains ShoovEncryptResource.
 */

class ShoovEncryptResource extends \RestfulBase  implements \RestfulDataProviderInterface {

  /**
   * Overrides \RestfulEntityBase::controllers.
   */
  protected $controllers = array(
    '' => array(
      \RestfulInterface::GET => 'getEncrypt',
    ),
  );


  /**
   * Overrides \RestfulEntityBase::publicFieldsInfo().
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
  }

}
