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
    $privateKey = $value->field_private_key->value();

    if (empty($privateKey)) {
      throw new Exception('CI Build should contain valid Private Key');
    }

    $request = $this->getRequest();
    $keyToConvert = array_key_exists('key', $request) ? $request['key'] : NULL;
    $valueToConvert = array_key_exists('value', $request) ? $request['value'] : NULL;

    if (empty($keyToConvert) || empty($valueToConvert)) {
      throw new Exception('Request should contain key and value keys.');
    }

    $nodeServerUrl = variable_get('shoov_ui_build_pr_server', 'http://localhost:3000');
    $url = $nodeServerUrl . '/encrypt';
    $options = [
      'method' => 'POST',
      'data' => "privateKey={$privateKey}&keyToConvert={$keyToConvert}&valueToConvert={$valueToConvert}",
      'headers' => array('Content-Type' => 'application/x-www-form-urlencoded'),
    ];

    $response = drupal_http_request($url, $options);
    $json = json_decode($response->data);

    return $json->encrypt;
  }

}
