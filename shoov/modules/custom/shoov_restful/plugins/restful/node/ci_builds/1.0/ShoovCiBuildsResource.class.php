<?php

/**
 * @file
 * Contains ShoovCiBuildsResource.
 */

class ShoovCiBuildsResource extends \ShoovEntityBaseNode {


  /**
   * Overrides \RestfulEntityBaseNode::publicFieldsInfo().
   */
  public function publicFieldsInfo() {
    $public_fields = parent::publicFieldsInfo();

    $public_fields['enabled'] = array(
      'property' => 'field_ci_build_enabled',
    );

    $public_fields['git_branch'] = array(
      'property' => 'field_git_branch',
    );

    $public_fields['repository'] = array(
      'property' => 'og_repo',
      'resource' => array(
        'repository' => array(
          'name' => 'repositories',
          'full_view' => FALSE,
        ),
      ),
    );

    $public_fields['interval'] = array(
      'property' => 'field_ci_build_interval',
    );

    $public_fields['private_key'] = array(
      'property' => 'field_private_key',
    );

    $public_fields['notification'] = array(
      'callback' => array($this, 'getNotification'),
      'create_or_update_passthrough' => TRUE,
    );

    return $public_fields;
  }

  /**
   * Gets the value of the membership entity's field_receive_notifications.
   */
  protected function getNotification($wrapper) {
    $account = $this->getAccount();

    $membership = og_get_membership('node', $wrapper->og_repo->getIdentifier(), 'user', $account->uid);
    $wrapper = entity_metadata_wrapper('og_membership', $membership);
    return $wrapper->field_receive_notifications->value();
  }

  protected function setPropertyValues(EntityMetadataWrapper $wrapper, $null_missing_fields = FALSE) {
    try {
      parent::setPropertyValues($wrapper, $null_missing_fields);
    }
    catch (\RestfulBadRequestException $exception){
      $request = $this->getRequest();
      if (!isset($request['notification'])) {
        // Don't throw the bad request exception if we're updating the notification status.
        throw $exception;
      }
    }
  }
  /**
   * {@inheritdoc}
   */
  protected function updateEntity($id, $null_missing_fields = FALSE) {

    $request = $this->getRequest();

    if (isset($request['notification'])) {
      // Check entity is valid, to make sure we can safely save the OG membership.
      $entity_id = $this->getEntityIdByFieldId($id);
      $this->isValidEntity('update', $entity_id);

      $wrapper = entity_metadata_wrapper($this->getEntityType(), $entity_id);
      $account = $this->getAccount();
      $request = $this->getRequest();

      $membership = og_get_membership('node', $wrapper->og_repo->getIdentifier(), 'user', $account->uid);
      $wrapper = entity_metadata_wrapper('og_membership', $membership);
      $wrapper->field_receive_notifications->set($request['notification']);
      $wrapper->save();
    }

    return parent::updateEntity($id, $null_missing_fields);
  }
}
