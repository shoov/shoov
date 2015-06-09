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
    $public_fields['encrypt'] = array(
      'callback' => array($this, 'getEncrypt'),
    );

    return $public_fields;
  }

  /**
   * Function it is a simple proxy between client request to encode a params and
   * nodejs server who have encrypting tool to do that.
   *
   * @param $value
   *   CI Build object in wrapper.
   * @return string
   *   Encrypted string.
   */
  protected function getEncrypt($value) {
    $private_key = $value->field_private_key->value();

    $request = $this->getRequest();

    if (empty($request['key'])) {
      throw new \RestfulBadRequestException("Request should contain a key.");
    }

    if (empty($request['value'])) {
      throw new \RestfulBadRequestException("Request should contain a value.");
    }

    $node_js_url = variable_get('shoov_ui_build_pr_server', 'http://localhost:3000');
    $url = $node_js_url . '/encrypt';

    $data = array(
      'privateKey' => $private_key,
      'keyToConvert' => $request['key'],
      'valueToConvert' => $request['value'],
    );

    $options = array(
      'method' => 'POST',
      'data' => http_build_query($data),
      'headers' => array('Content-Type' => 'application/x-www-form-urlencoded'),
    );

    $response = drupal_http_request($url, $options);

    if ($response->code != 200) {
      throw new \RestfulServerConfigurationException("Can't connect to NodeJS server.");
    }

    $json = json_decode($response->data);

    return $json->encrypt;
  }

}
