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
      // The context hasn't been sent.
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
    // Add library to have the link that is using AJAX.
    drupal_add_library('system', 'drupal.ajax');
    // Get the flag
    $flag = flag_get_flag('subscribe_ci_builds');
    foreach ($result['node'] as $ci_build) {
      // Show link to un/subscribe the user to the CI Build.
      if ($flag->is_flagged($ci_build->nid, $values->uid)) {
        return l(t('Unsubscribe'), 'ci_build/' . $ci_build->nid . '/' . $values->uid . '/unsubscribe/nojs/' . md5($ci_build->nid . '+' . $values->uid),
          array('attributes' => array('class' => 'use-ajax', 'id' => 'unsubscribe-' . $values->uid))
        );
      }
      else {
        return l(t('Subscribe'), 'ci_build/' . $ci_build->nid . '/' . $values->uid . '/subscribe/nojs/' . md5($ci_build->nid . '+' . $values->uid),
          array('attributes' => array('class' => 'use-ajax', 'id' => 'subscribe-' . $values->uid))
        );
      }
    }
  }
}
