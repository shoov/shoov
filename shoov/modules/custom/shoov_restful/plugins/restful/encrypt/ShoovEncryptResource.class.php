<?php

/**
 * @file
 * Contains ShoovEncryptResource.
 */

class ShoovEncryptResource extends \RestfulBase {

  /**
   * Overrides \RestfulBase::controllers.
   */
  protected $controllers = array(
    '' => array(
      \RestfulInterface::POST => 'getEncrypt',
    ),
  );


  /**
   * Overrides \RestfulBase::publicFieldsInfo().
   */
  public function publicFieldsInfo() {
    $public_fields = parent::publicFieldsInfo();

    $public_fields['encrypt'] = array(
      'collback' => array($this, 'getEncrypt'),
    );

    return $public_fields;
  }

  /**
   * @param $value
   * @return string
   */
  protected function getEncrypt($value) {

    print_r($value);
    die;

    $request = $this->getRequest();

    $server_url = variable_get('shoov_ui_build_pr_server', 'http://localhost:3000');

//    drupal_http_request();

  }

}
