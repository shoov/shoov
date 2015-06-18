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

    return parent::viewEntity($entity_id);
  }

  /**
   * Get the pusher auth.
   */
  protected function getPusherAuth($op, $public_field_name, \EntityMetadataWrapper $property_wrapper, \EntityMetadataWrapper $wrapper) {
    $request = $this->getRequest();
  }
}
