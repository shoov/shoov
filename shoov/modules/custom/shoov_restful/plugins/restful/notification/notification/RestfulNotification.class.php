<?php

class RestfulNotification extends \RestfulBase implements RestfulDataProviderInterface {

  /**
   * Overrides \RestfulEntityBase::controllers.
   */
  protected $controllers = array(
    '^.*$' => array(
      \RestfulInterface::PATCH => 'toggleNotification',
    ),
  );

  /**
   * Return the properties that should be public.
   *
   * @throws \RestfulEntityViewMode
   *
   * @return array
   */
  public function publicFieldsInfo() {
    return array(
      'passed' => array(),
    );
  }

  /**
   * Toggle notification.
   */
  public function toggleNotification() {
    $account = $this->getAccount();
    $gid = $this->path;

    // Find the og_membership entity.
    $membership = og_get_membership('node', $gid, 'user', $account->uid);
    if (empty($membership)) {
      return array('changed' => 0);
    }

    // Toggle the value.
    $wrapper = entity_metadata_wrapper('og_membership', $membership);
    $new_value = !$wrapper->field_receive_notifications->value();
    $wrapper->field_receive_notifications->set($new_value);
    $wrapper->save();

    $response = array(
      'changed' => 1,
      'value' => $new_value,
    );

    return $response;
  }
}
