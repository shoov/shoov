<?php

/**
 * @file
 * Contains ShoovConfigResource.
 */

class ShoovConfigResource extends \RestfulEntityBaseUser {

  /**
   * Overrides \RestfulEntityBase::controllers.
   */
  protected $controllers = array(
    '' => array(
      \RestfulInterface::GET => 'viewEntity',
    ),
  );

  protected $slack_config = $this->getSlackConfig();

  /**
   * Overrides \RestfulEntityBaseUser::publicFieldsInfo().
   */
  public function publicFieldsInfo() {
    $public_fields = parent::publicFieldsInfo();

    $public_fields['access_token'] = array(
      'callback' => array($this, 'getAccessToken'),
    );

    $public_fields['browserstack_username'] = array(
      'property' => 'field_browserstack_username',
    );

    $public_fields['browserstack_key'] = array(
      'property' => 'field_browserstack_key',
    );

    $public_fields['sauce_username'] = array(
      'property' => 'field_saucelabs_username',
    );

    $public_fields['sauce_access_key'] = array(
      'property' => 'field_saucelabs_key',
    );

    $public_fields['slack_webhook_url'] = array(
      'callback' => array($this, 'getSlackWebHookUrl'),
    );

    $public_fields['slack_channel'] = array(
      'callback' => array($this, 'getSlackChannel'),
    );

    $public_fields['slack_username'] = array(
      'callback' => array($this, 'getSlackUsername'),
    );

    unset($public_fields['id']);
    unset($public_fields['self']);
    unset($public_fields['label']);
    unset($public_fields['url']);
    unset($public_fields['mail']);

    return $public_fields;
  }

  /**
   * Overrides \RestfulEntityBase::viewEntity().
   *
   * Always return the current user.
   */
  public function viewEntity($entity_id) {
    $account = $this->getAccount();
    return parent::viewEntity($account->uid);
  }

  protected function getAccessToken() {
    $account = $this->getAccount();
    return shoov_restful_get_user_token($account);
  }

  protected function getSlackConfig() {
    $account = $this->getAccount();
    return shoov_restful_get_slack_config($account);
  }

  private function getSlackWebHookUrl() {
    if ($this->slack_config) {
      return $this->slack_config['webhook_url'];
    }
  }

  private function getSlackChannel() {
    if ($this->slack_config) {
      return $this->slack_config['channel'];
    }
  }

  private function getSlackUsername() {
    if ($this->slack_config) {
      return $this->slack_config['username'];
    }
  }
}
