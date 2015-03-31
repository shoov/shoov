<?php

/**
 * @file
 * Contains ShoovMeResource.
 */

class ShoovMeResource extends \RestfulEntityBaseUser {

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

    unset($public_fields['self']);


    $public_fields['github_access_token'] = array(
      'property' => 'field_github_access_token',
      'access_callbacks' => array(
        array($this, 'accessGithubAccessToken'),
      ),
    );

    return $public_fields;
  }

  /**
   * Overrides \RestfulEntityBase::viewEntity().
   *
   * Always return the current user.
   */
  public function viewEntity($entity_id) {
    $account = $this->getAccount();
    return array(parent::viewEntity($account->uid));
  }

  /**
   * @todo: Allow access to the SSH private key only with a crypted key.
   */
  protected function accessGithubAccessToken($op, $public_field_name, \EntityMetadataWrapper $property_wrapper, \EntityMetadataWrapper $wrapper) {
    $request = $this->getRequest();

    return !empty($request['github_access_token']) ? \RestfulInterface::ACCESS_ALLOW : \RestfulInterface::ACCESS_DENY;
  }
}
