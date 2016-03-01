<?php

/**
 * @file
 * Contains ShoovJsLmBuildsResource.
 */

class ShoovJsLmBuildsResource extends \ShoovEntityBaseNode {

  /**
   * Overrides \RestfulEntityBaseNode::publicFieldsInfo().
   */
  public function publicFieldsInfo() {
    $public_fields = parent::publicFieldsInfo();

    $public_fields['url'] = array(
      'property' => 'field_js_lm_url',
    );

    $public_fields['token'] = array(
      'property' => 'field_js_lm_build_token',
    );

    return $public_fields;
  }

  /**
   * {@inheritdoc}
   */
  protected function checkEntityAccess($op, $entity_type, $entity) {
    $account = $this->getAccount();
    $access = node_access($op, $entity, $account);
    return $access;
  }
}
