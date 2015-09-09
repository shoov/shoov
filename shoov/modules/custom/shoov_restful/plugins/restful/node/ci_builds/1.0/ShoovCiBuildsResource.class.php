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
      'callback' => array($this, 'notificationProcess'),
    );

    return $public_fields;
  }

  /**
   * Gets the value of the membership entity's field_receive_notifications.
   */
  public function notificationProcess($wrapper) {
    $account = $this->getAccount();

    $membership = og_get_membership('node', $wrapper->og_repo->getIdentifier(), 'user', $account->uid);
    $wrapper = entity_metadata_wrapper('og_membership', $membership);
    return $wrapper->field_receive_notifications->value();
  }

}
