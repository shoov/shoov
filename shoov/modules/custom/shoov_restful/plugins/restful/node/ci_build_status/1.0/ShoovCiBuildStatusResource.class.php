<?php

/**
 * @file
 * Contains ShoovCiBuildStatusResource.
 */

class ShoovCiBuildStatusResource extends \RestfulEntityBase {

  /**
   * Overrides \RestfulDataProviderEFQ::controllersInfo().
   *
   * Accept only GET request with an id.
   */
  public static function controllersInfo() {
    return array(
      '^.*$' => array(
        \RestfulInterface::GET => 'viewEntity',
      ),
    );
  }

  /**
   * Overrides \RestfulEntityBase::publicFieldsInfo().
   */
  public function publicFieldsInfo() {
    $public_fields = array();

    $public_fields['build_status'] = array(
      'callback' => array($this, 'statusHtml'),
    );

    return $public_fields;
  }

  protected function statusHtml(\EntityMetadataWrapper $wrapper) {
    $ci_build_status = $wrapper->field_ci_build_incident_status->value();
    switch($ci_build_status) {
      case 'unconfirmed_error':
        $file = 'unconfirmed_error';
        break;
      case 'error':
        $file = 'error';
        break;
      default:
        $file = 'passing';
        break;
    }

    return theme('image', array('path' => drupal_get_path('module', 'shoov_ci_build') . '/images/statuses/' . $file . '.png'));
  }

  protected function isValidEntity($op, $entity_id) {
    $params = array(
      '@id' => $entity_id,
    );

    $request = $this->getRequest();

    if (empty($request['status_token'])) {
      throw new RestfulForbiddenException(format_string('Access denied. Check the status token was sent.'));
    }

    if ($this->getEntityType() != 'node') {
      throw new RestfulUnprocessableEntityException(format_string('The entity ID @id is not a Node.', $params));
    }

    $node = node_load($entity_id);
    if ($node->type != 'ci_build') {
      throw new RestfulUnprocessableEntityException(format_string('The entity ID @id is not a valid CI Build.', $params));
    }
    $wrapper = entity_metadata_wrapper('node', $node);
    $status_token = $wrapper->field_status_token->value();

    if ($status_token != $request['status_token']) {
      throw new RestfulForbiddenException(format_string('You do not have access to CI Build ID @id status. Check the status token.', $params));
    }

    return TRUE;
  }
}
