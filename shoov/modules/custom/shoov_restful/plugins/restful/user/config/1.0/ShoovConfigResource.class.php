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
}
