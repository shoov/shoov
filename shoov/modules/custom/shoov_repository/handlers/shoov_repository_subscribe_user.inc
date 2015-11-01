<?php

/**
 * @file
 * Contains shoov_repository_subscribe_user.
 */

class shoov_repository_subscribe_user extends views_handler_field_entity {

  /**
   * {@inheritdoc}
   */
  function render($values) {

    // Get group is being viewed ID.
    $argument = $this->view->argument;
    if (empty($argument['og_user_node_target_id'])) {
      return;
    }
    $gid = $argument['og_user_node_target_id']->value[0];

    // Get CI Build item.
    $query = new EntityFieldQuery();
    $result = $query
      ->entityCondition('entity_type', 'node')
      ->entityCondition('bundle', 'ci_build')
      ->fieldCondition('og_repo', 'target_id', $gid)
      ->execute();

    if (empty($result['node'])) {
      return;
    }
    drupal_add_library('system', 'drupal.ajax');
    foreach ($result['node'] as $build) {
      $build_id = $build->nid;

      $flag = flag_get_flag('subscribe_ci_builds');
      if ($flag->is_flagged($build_id, $values->uid)) {
        return l(t('Unsubscribe'), 'ci_build/' . $build_id . '/' . $values->uid . '/unsubscribe/nojs',
          array('attributes' => array('class' => 'use-ajax', 'id' => 'unsubscribe-' . $values->uid))
        );
      }
      else {
        return l(t('Subscribe'), 'ci_build/' . $build_id . '/' . $values->uid . '/subscribe/nojs',
          array('attributes' => array('class' => 'use-ajax', 'id' => 'subscribe-' . $values->uid))
        );
      }
    }
  }
}
