<?php
/**
 * @file
 * shoov_repository.views_default.inc
 */

/**
 * Implements hook_views_default_views().
 */
function shoov_repository_views_default_views() {
  $export = array();

  $view = new view();
  $view->name = 'group_members_subscribtion';
  $view->description = '';
  $view->tag = 'default';
  $view->base_table = 'users';
  $view->human_name = 'Group members subscribtion';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['title'] = 'Group members subscribtion';
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['access']['type'] = 'perm';
  $handler->display->display_options['access']['perm'] = 'access user profiles';
  $handler->display->display_options['cache']['type'] = 'none';
  $handler->display->display_options['query']['type'] = 'views_query';
  $handler->display->display_options['exposed_form']['type'] = 'basic';
  $handler->display->display_options['pager']['type'] = 'full';
  $handler->display->display_options['pager']['options']['items_per_page'] = '10';
  $handler->display->display_options['style_plugin'] = 'table';
  $handler->display->display_options['style_options']['columns'] = array(
    'name' => 'name',
  );
  $handler->display->display_options['style_options']['default'] = '-1';
  $handler->display->display_options['style_options']['info'] = array(
    'name' => array(
      'sortable' => 0,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
  );
  /* Field: User: Name */
  $handler->display->display_options['fields']['name']['id'] = 'name';
  $handler->display->display_options['fields']['name']['table'] = 'users';
  $handler->display->display_options['fields']['name']['field'] = 'name';
  $handler->display->display_options['fields']['name']['label'] = 'User Name';
  $handler->display->display_options['fields']['name']['alter']['word_boundary'] = FALSE;
  $handler->display->display_options['fields']['name']['alter']['ellipsis'] = FALSE;
  /* Field: User: Subscribe user to the CI Build */
  $handler->display->display_options['fields']['subscribe']['id'] = 'subscribe';
  $handler->display->display_options['fields']['subscribe']['table'] = 'users';
  $handler->display->display_options['fields']['subscribe']['field'] = 'subscribe';
  $handler->display->display_options['fields']['subscribe']['label'] = '';
  $handler->display->display_options['fields']['subscribe']['exclude'] = TRUE;
  $handler->display->display_options['fields']['subscribe']['element_label_colon'] = FALSE;
  /* Field: Global: Custom text */
  $handler->display->display_options['fields']['nothing']['id'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['table'] = 'views';
  $handler->display->display_options['fields']['nothing']['field'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['label'] = 'Subscribe/Unsubscribe';
  $handler->display->display_options['fields']['nothing']['alter']['text'] = '[subscribe]';
  /* Sort criterion: User: Created date */
  $handler->display->display_options['sorts']['created']['id'] = 'created';
  $handler->display->display_options['sorts']['created']['table'] = 'users';
  $handler->display->display_options['sorts']['created']['field'] = 'created';
  $handler->display->display_options['sorts']['created']['order'] = 'DESC';
  /* Contextual filter: User: Group membership (og_user_node) */
  $handler->display->display_options['arguments']['og_user_node_target_id']['id'] = 'og_user_node_target_id';
  $handler->display->display_options['arguments']['og_user_node_target_id']['table'] = 'og_membership';
  $handler->display->display_options['arguments']['og_user_node_target_id']['field'] = 'og_user_node_target_id';
  $handler->display->display_options['arguments']['og_user_node_target_id']['default_action'] = 'default';
  $handler->display->display_options['arguments']['og_user_node_target_id']['default_argument_type'] = 'node';
  $handler->display->display_options['arguments']['og_user_node_target_id']['summary']['number_of_records'] = '0';
  $handler->display->display_options['arguments']['og_user_node_target_id']['summary']['format'] = 'default_summary';
  $handler->display->display_options['arguments']['og_user_node_target_id']['summary_options']['items_per_page'] = '25';
  $handler->display->display_options['arguments']['og_user_node_target_id']['specify_validation'] = TRUE;
  $handler->display->display_options['arguments']['og_user_node_target_id']['validate']['type'] = 'node';
  $handler->display->display_options['arguments']['og_user_node_target_id']['validate_options']['types'] = array(
    'repository' => 'repository',
  );
  /* Contextual filter: Content: Repository (og_repo) */
  $handler->display->display_options['arguments']['og_repo_target_id']['id'] = 'og_repo_target_id';
  $handler->display->display_options['arguments']['og_repo_target_id']['table'] = 'og_membership';
  $handler->display->display_options['arguments']['og_repo_target_id']['field'] = 'og_repo_target_id';
  $handler->display->display_options['arguments']['og_repo_target_id']['default_action'] = 'default';
  $handler->display->display_options['arguments']['og_repo_target_id']['default_argument_type'] = 'node';
  $handler->display->display_options['arguments']['og_repo_target_id']['summary']['number_of_records'] = '0';
  $handler->display->display_options['arguments']['og_repo_target_id']['summary']['format'] = 'default_summary';
  $handler->display->display_options['arguments']['og_repo_target_id']['summary_options']['items_per_page'] = '25';
  /* Filter criterion: User: Active */
  $handler->display->display_options['filters']['status']['id'] = 'status';
  $handler->display->display_options['filters']['status']['table'] = 'users';
  $handler->display->display_options['filters']['status']['field'] = 'status';
  $handler->display->display_options['filters']['status']['value'] = '1';
  $handler->display->display_options['filters']['status']['group'] = 1;
  $handler->display->display_options['filters']['status']['expose']['operator'] = FALSE;

  /* Display: Content pane */
  $handler = $view->new_display('panel_pane', 'Content pane', 'panel_pane_1');
  $handler->display->display_options['argument_input'] = array(
    'og_user_node_target_id' => array(
      'type' => 'context',
      'context' => 'entity:node.nid',
      'context_optional' => 0,
      'panel' => '0',
      'fixed' => '',
      'label' => 'User: Group membership (og_user_node)',
    ),
  );
  $export['group_members_subscribtion'] = $view;

  return $export;
}
