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

    unset($public_fields['id']);
    unset($public_fields['self']);
    unset($public_fields['label']);
    unset($public_fields['url']);
    unset($public_fields['mail']);
    unset($public_fields['browserstack_username']);
    unset($public_fields['browserstack_key']);

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
