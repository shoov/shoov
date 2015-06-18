<?php

/**
 * @file
 * Contains ShoovPusherAuthResource.
 */

class ShoovPusherAuthResource extends \RestfulEntityBaseUser {

  /**
   * Overrides \RestfulEntityBase::controllers.
   */
  protected $controllers = array(
    '' => array(
      \RestfulInterface::POST => 'viewEntity',
    ),
  );

  /**
   * Overrides \RestfulEntityBaseUser::publicFieldsInfo().
   */
  public function publicFieldsInfo() {
    $public_fields = array();

    $public_fields['auth'] = array(
      'callback' => array($this, 'getPusherAuth'),
    );

    return $public_fields;
  }

  /**
   * Overrides \RestfulEntityBase::viewEntity().
   *
   * Always return the current user.
   */
  public function viewEntity($entity_id) {
    $request = $this->getRequest();

    if (empty($request['channel_name'])) {
      throw new \RestfulBadRequestException('"channel_name" property is missing');
    }

    if (empty($request['socket_id'])) {
      throw new \RestfulBadRequestException('"socket_id" property is missing');
    }

    $account = $this->getAccount();
    return parent::viewEntity($account->uid);
  }

  /**
   * Get the pusher auth.
   */
  protected function getPusherAuth() {
    $request = $this->getRequest();

    $app_key = variable_get('shoov_pusher_app_key');
    $app_secret = variable_get('shoov_pusher_app_secret');
    $app_id = variable_get('shoov_pusher_app_id');

    if (empty($app_key) || empty($app_secret) || empty($app_id)) {
      throw new \RestfulServerConfigurationException('Pusher app is not configured properly.');
    }

    $pusher = new Pusher($app_key, $app_secret, $app_id);
    $result = $pusher->socket_auth($request['channel_name'], $request['socket_id']);
    $data = drupal_json_decode($result);

    return $data['auth'];
  }
}
