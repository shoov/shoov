<?php
/**
 * @file
 * shoov_ci_build.views_default.inc
 */

/**
 * Implements hook_views_default_views().
 */
function shoov_ci_build_views_default_views() {
  $export = array();

  $view = new view();
  $view->name = 'ci_items';
  $view->description = 'Show all existing messages.';
  $view->tag = 'Message';
  $view->base_table = 'message';
  $view->human_name = 'CI build items';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Defaults */
  $handler = $view->new_display('default', 'Defaults', 'default');
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['access']['type'] = 'perm';
  $handler->display->display_options['access']['perm'] = 'administer message types';
  $handler->display->display_options['cache']['type'] = 'none';
  $handler->display->display_options['query']['type'] = 'views_query';
  $handler->display->display_options['query']['options']['query_comment'] = FALSE;
  $handler->display->display_options['exposed_form']['type'] = 'basic';
  $handler->display->display_options['pager']['type'] = 'full';
  $handler->display->display_options['pager']['options']['items_per_page'] = '30';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['pager']['options']['id'] = '0';
  $handler->display->display_options['pager']['options']['quantity'] = '9';
  $handler->display->display_options['style_plugin'] = 'table';
  $handler->display->display_options['style_options']['columns'] = array(
    'timestamp' => 'timestamp',
    'message_render' => 'message_render',
    'name' => 'name',
  );
  $handler->display->display_options['style_options']['default'] = '-1';
  $handler->display->display_options['style_options']['info'] = array(
    'timestamp' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
    ),
    'message_render' => array(
      'align' => '',
      'separator' => '',
    ),
    'name' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
    ),
  );
  $handler->display->display_options['style_options']['sticky'] = TRUE;
  /* No results behavior: Global: Text area */
  $handler->display->display_options['empty']['area']['id'] = 'area';
  $handler->display->display_options['empty']['area']['table'] = 'views';
  $handler->display->display_options['empty']['area']['field'] = 'area';
  $handler->display->display_options['empty']['area']['content'] = 'No messages created yet.';
  $handler->display->display_options['empty']['area']['format'] = 'filtered_html';
  /* Relationship: Message: User uid */
  $handler->display->display_options['relationships']['user']['id'] = 'user';
  $handler->display->display_options['relationships']['user']['table'] = 'message';
  $handler->display->display_options['relationships']['user']['field'] = 'user';
  /* Field: Message: Message ID */
  $handler->display->display_options['fields']['mid']['id'] = 'mid';
  $handler->display->display_options['fields']['mid']['table'] = 'message';
  $handler->display->display_options['fields']['mid']['field'] = 'mid';
  $handler->display->display_options['fields']['mid']['exclude'] = TRUE;
  /* Field: Message: Timestamp */
  $handler->display->display_options['fields']['timestamp']['id'] = 'timestamp';
  $handler->display->display_options['fields']['timestamp']['table'] = 'message';
  $handler->display->display_options['fields']['timestamp']['field'] = 'timestamp';
  $handler->display->display_options['fields']['timestamp']['date_format'] = 'short';
  /* Field: Message: Render message (Get text) */
  $handler->display->display_options['fields']['message_render']['id'] = 'message_render';
  $handler->display->display_options['fields']['message_render']['table'] = 'message';
  $handler->display->display_options['fields']['message_render']['field'] = 'message_render';
  $handler->display->display_options['fields']['message_render']['label'] = 'Text';
  $handler->display->display_options['fields']['message_render']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['message_render']['hide_alter_empty'] = FALSE;
  /* Field: Message: Start time */
  $handler->display->display_options['fields']['field_ci_build_timestamp']['id'] = 'field_ci_build_timestamp';
  $handler->display->display_options['fields']['field_ci_build_timestamp']['table'] = 'field_data_field_ci_build_timestamp';
  $handler->display->display_options['fields']['field_ci_build_timestamp']['field'] = 'field_ci_build_timestamp';
  $handler->display->display_options['fields']['field_ci_build_timestamp']['settings'] = array(
    'format_type' => 'short',
    'fromto' => 'both',
    'multiple_number' => '',
    'multiple_from' => '',
    'multiple_to' => '',
  );
  /* Field: User: Name */
  $handler->display->display_options['fields']['name']['id'] = 'name';
  $handler->display->display_options['fields']['name']['table'] = 'users';
  $handler->display->display_options['fields']['name']['field'] = 'name';
  $handler->display->display_options['fields']['name']['relationship'] = 'user';
  $handler->display->display_options['fields']['name']['label'] = 'User';
  /* Field: Message: Edit message */
  $handler->display->display_options['fields']['edit']['id'] = 'edit';
  $handler->display->display_options['fields']['edit']['table'] = 'message';
  $handler->display->display_options['fields']['edit']['field'] = 'edit';
  $handler->display->display_options['fields']['edit']['label'] = '';
  $handler->display->display_options['fields']['edit']['exclude'] = TRUE;
  $handler->display->display_options['fields']['edit']['element_label_colon'] = FALSE;
  /* Field: Message: Delete message */
  $handler->display->display_options['fields']['delete']['id'] = 'delete';
  $handler->display->display_options['fields']['delete']['table'] = 'message';
  $handler->display->display_options['fields']['delete']['field'] = 'delete';
  $handler->display->display_options['fields']['delete']['label'] = '';
  $handler->display->display_options['fields']['delete']['exclude'] = TRUE;
  $handler->display->display_options['fields']['delete']['element_label_colon'] = FALSE;
  /* Field: Message: View message */
  $handler->display->display_options['fields']['view']['id'] = 'view';
  $handler->display->display_options['fields']['view']['table'] = 'message';
  $handler->display->display_options['fields']['view']['field'] = 'view';
  $handler->display->display_options['fields']['view']['label'] = '';
  $handler->display->display_options['fields']['view']['exclude'] = TRUE;
  $handler->display->display_options['fields']['view']['element_label_colon'] = FALSE;
  /* Field: Global: Custom text */
  $handler->display->display_options['fields']['nothing']['id'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['table'] = 'views';
  $handler->display->display_options['fields']['nothing']['field'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['label'] = 'Operation';
  $handler->display->display_options['fields']['nothing']['alter']['text'] = '[view] [edit] [delete]';
  /* Sort criterion: Message: Timestamp */
  $handler->display->display_options['sorts']['timestamp']['id'] = 'timestamp';
  $handler->display->display_options['sorts']['timestamp']['table'] = 'message';
  $handler->display->display_options['sorts']['timestamp']['field'] = 'timestamp';
  $handler->display->display_options['sorts']['timestamp']['order'] = 'DESC';
  /* Contextual filter: Field: CI build (field_ci_build) */
  $handler->display->display_options['arguments']['field_ci_build_target_id']['id'] = 'field_ci_build_target_id';
  $handler->display->display_options['arguments']['field_ci_build_target_id']['table'] = 'field_data_field_ci_build';
  $handler->display->display_options['arguments']['field_ci_build_target_id']['field'] = 'field_ci_build_target_id';
  $handler->display->display_options['arguments']['field_ci_build_target_id']['default_action'] = 'default';
  $handler->display->display_options['arguments']['field_ci_build_target_id']['default_argument_type'] = 'node';
  $handler->display->display_options['arguments']['field_ci_build_target_id']['summary']['number_of_records'] = '0';
  $handler->display->display_options['arguments']['field_ci_build_target_id']['summary']['format'] = 'default_summary';
  $handler->display->display_options['arguments']['field_ci_build_target_id']['summary_options']['items_per_page'] = '25';
  /* Filter criterion: Message: Type */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'message';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['value'] = array(
    'ci_build' => 'ci_build',
  );
  $handler->display->display_options['filters']['type']['expose']['operator_id'] = 'type_op';
  $handler->display->display_options['filters']['type']['expose']['label'] = 'Message type';
  $handler->display->display_options['filters']['type']['expose']['operator'] = 'type_op';
  $handler->display->display_options['filters']['type']['expose']['identifier'] = 'type';
  $handler->display->display_options['filters']['type']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
  );
  /* Filter criterion: Message: Status (field_ci_build_status) */
  $handler->display->display_options['filters']['field_ci_build_status_value']['id'] = 'field_ci_build_status_value';
  $handler->display->display_options['filters']['field_ci_build_status_value']['table'] = 'field_data_field_ci_build_status';
  $handler->display->display_options['filters']['field_ci_build_status_value']['field'] = 'field_ci_build_status_value';
  $handler->display->display_options['filters']['field_ci_build_status_value']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_ci_build_status_value']['expose']['operator_id'] = 'field_ci_build_status_value_op';
  $handler->display->display_options['filters']['field_ci_build_status_value']['expose']['label'] = 'Status';
  $handler->display->display_options['filters']['field_ci_build_status_value']['expose']['operator'] = 'field_ci_build_status_value_op';
  $handler->display->display_options['filters']['field_ci_build_status_value']['expose']['identifier'] = 'field_ci_build_status_value';
  $handler->display->display_options['filters']['field_ci_build_status_value']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
  );

  /* Display: Page */
  $handler = $view->new_display('page', 'Page', 'page_1');
  $handler->display->display_options['defaults']['arguments'] = FALSE;
  $handler->display->display_options['path'] = 'admin/content/ci-build-items';
  $handler->display->display_options['menu']['type'] = 'tab';
  $handler->display->display_options['menu']['title'] = 'CI build items';
  $handler->display->display_options['menu']['description'] = 'A list of all CI build items';
  $handler->display->display_options['menu']['weight'] = '0';
  $handler->display->display_options['menu']['name'] = 'management';
  $handler->display->display_options['menu']['context'] = 0;
  $handler->display->display_options['menu']['context_only_inline'] = 0;

  /* Display: Content pane */
  $handler = $view->new_display('panel_pane', 'Content pane', 'panel_pane_1');
  $handler->display->display_options['defaults']['access'] = FALSE;
  $handler->display->display_options['access']['type'] = 'none';
  $handler->display->display_options['defaults']['pager'] = FALSE;
  $handler->display->display_options['pager']['type'] = 'full';
  $handler->display->display_options['pager']['options']['items_per_page'] = '25';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['pager']['options']['id'] = '0';
  $handler->display->display_options['pager']['options']['quantity'] = '9';
  $handler->display->display_options['defaults']['fields'] = FALSE;
  /* Field: Message: Message ID */
  $handler->display->display_options['fields']['mid']['id'] = 'mid';
  $handler->display->display_options['fields']['mid']['table'] = 'message';
  $handler->display->display_options['fields']['mid']['field'] = 'mid';
  $handler->display->display_options['fields']['mid']['exclude'] = TRUE;
  /* Field: Message: Timestamp */
  $handler->display->display_options['fields']['timestamp']['id'] = 'timestamp';
  $handler->display->display_options['fields']['timestamp']['table'] = 'message';
  $handler->display->display_options['fields']['timestamp']['field'] = 'timestamp';
  $handler->display->display_options['fields']['timestamp']['date_format'] = 'short';
  /* Field: Message: Render message (Get text) */
  $handler->display->display_options['fields']['message_render']['id'] = 'message_render';
  $handler->display->display_options['fields']['message_render']['table'] = 'message';
  $handler->display->display_options['fields']['message_render']['field'] = 'message_render';
  $handler->display->display_options['fields']['message_render']['label'] = 'Text';
  $handler->display->display_options['fields']['message_render']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['message_render']['hide_alter_empty'] = FALSE;
  /* Field: Message: Start time */
  $handler->display->display_options['fields']['field_ci_build_timestamp']['id'] = 'field_ci_build_timestamp';
  $handler->display->display_options['fields']['field_ci_build_timestamp']['table'] = 'field_data_field_ci_build_timestamp';
  $handler->display->display_options['fields']['field_ci_build_timestamp']['field'] = 'field_ci_build_timestamp';
  $handler->display->display_options['fields']['field_ci_build_timestamp']['settings'] = array(
    'format_type' => 'short',
    'fromto' => 'both',
    'multiple_number' => '',
    'multiple_from' => '',
    'multiple_to' => '',
  );
  /* Field: User: Name */
  $handler->display->display_options['fields']['name']['id'] = 'name';
  $handler->display->display_options['fields']['name']['table'] = 'users';
  $handler->display->display_options['fields']['name']['field'] = 'name';
  $handler->display->display_options['fields']['name']['relationship'] = 'user';
  $handler->display->display_options['fields']['name']['label'] = 'User';
  /* Field: Message: Edit message */
  $handler->display->display_options['fields']['edit']['id'] = 'edit';
  $handler->display->display_options['fields']['edit']['table'] = 'message';
  $handler->display->display_options['fields']['edit']['field'] = 'edit';
  $handler->display->display_options['fields']['edit']['label'] = '';
  $handler->display->display_options['fields']['edit']['exclude'] = TRUE;
  $handler->display->display_options['fields']['edit']['element_label_colon'] = FALSE;
  /* Field: Message: Delete message */
  $handler->display->display_options['fields']['delete']['id'] = 'delete';
  $handler->display->display_options['fields']['delete']['table'] = 'message';
  $handler->display->display_options['fields']['delete']['field'] = 'delete';
  $handler->display->display_options['fields']['delete']['label'] = '';
  $handler->display->display_options['fields']['delete']['exclude'] = TRUE;
  $handler->display->display_options['fields']['delete']['element_label_colon'] = FALSE;
  /* Field: Message: View message */
  $handler->display->display_options['fields']['view']['id'] = 'view';
  $handler->display->display_options['fields']['view']['table'] = 'message';
  $handler->display->display_options['fields']['view']['field'] = 'view';
  $handler->display->display_options['fields']['view']['label'] = '';
  $handler->display->display_options['fields']['view']['exclude'] = TRUE;
  $handler->display->display_options['fields']['view']['element_label_colon'] = FALSE;
  /* Field: Message: Trigger CI Build message */
  $handler->display->display_options['fields']['trigger']['id'] = 'trigger';
  $handler->display->display_options['fields']['trigger']['table'] = 'message';
  $handler->display->display_options['fields']['trigger']['field'] = 'trigger';
  $handler->display->display_options['fields']['trigger']['label'] = '';
  $handler->display->display_options['fields']['trigger']['exclude'] = TRUE;
  $handler->display->display_options['fields']['trigger']['element_label_colon'] = FALSE;
  /* Field: Global: Custom text */
  $handler->display->display_options['fields']['nothing']['id'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['table'] = 'views';
  $handler->display->display_options['fields']['nothing']['field'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['label'] = 'Operation';
  $handler->display->display_options['fields']['nothing']['alter']['text'] = '[view] [edit] [delete] [trigger]';
  $handler->display->display_options['inherit_panels_path'] = '1';

  /* Display: Content pane for repo */
  $handler = $view->new_display('panel_pane', 'Content pane for repo', 'panel_pane_2');
  $handler->display->display_options['defaults']['access'] = FALSE;
  $handler->display->display_options['access']['type'] = 'none';
  $handler->display->display_options['defaults']['pager'] = FALSE;
  $handler->display->display_options['pager']['type'] = 'full';
  $handler->display->display_options['pager']['options']['items_per_page'] = '25';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['pager']['options']['id'] = '0';
  $handler->display->display_options['pager']['options']['quantity'] = '9';
  $handler->display->display_options['defaults']['relationships'] = FALSE;
  /* Relationship: Message: User uid */
  $handler->display->display_options['relationships']['user']['id'] = 'user';
  $handler->display->display_options['relationships']['user']['table'] = 'message';
  $handler->display->display_options['relationships']['user']['field'] = 'user';
  /* Relationship: Entity Reference: Referenced Entity */
  $handler->display->display_options['relationships']['field_ci_build_target_id']['id'] = 'field_ci_build_target_id';
  $handler->display->display_options['relationships']['field_ci_build_target_id']['table'] = 'field_data_field_ci_build';
  $handler->display->display_options['relationships']['field_ci_build_target_id']['field'] = 'field_ci_build_target_id';
  $handler->display->display_options['defaults']['fields'] = FALSE;
  /* Field: Message: Message ID */
  $handler->display->display_options['fields']['mid']['id'] = 'mid';
  $handler->display->display_options['fields']['mid']['table'] = 'message';
  $handler->display->display_options['fields']['mid']['field'] = 'mid';
  $handler->display->display_options['fields']['mid']['exclude'] = TRUE;
  /* Field: Message: Timestamp */
  $handler->display->display_options['fields']['timestamp']['id'] = 'timestamp';
  $handler->display->display_options['fields']['timestamp']['table'] = 'message';
  $handler->display->display_options['fields']['timestamp']['field'] = 'timestamp';
  $handler->display->display_options['fields']['timestamp']['date_format'] = 'short';
  /* Field: Message: Render message (Get text) */
  $handler->display->display_options['fields']['message_render']['id'] = 'message_render';
  $handler->display->display_options['fields']['message_render']['table'] = 'message';
  $handler->display->display_options['fields']['message_render']['field'] = 'message_render';
  $handler->display->display_options['fields']['message_render']['label'] = 'Text';
  $handler->display->display_options['fields']['message_render']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['message_render']['hide_alter_empty'] = FALSE;
  /* Field: Message: Start time */
  $handler->display->display_options['fields']['field_ci_build_timestamp']['id'] = 'field_ci_build_timestamp';
  $handler->display->display_options['fields']['field_ci_build_timestamp']['table'] = 'field_data_field_ci_build_timestamp';
  $handler->display->display_options['fields']['field_ci_build_timestamp']['field'] = 'field_ci_build_timestamp';
  $handler->display->display_options['fields']['field_ci_build_timestamp']['settings'] = array(
    'format_type' => 'short',
    'fromto' => 'both',
    'multiple_number' => '',
    'multiple_from' => '',
    'multiple_to' => '',
  );
  /* Field: User: Name */
  $handler->display->display_options['fields']['name']['id'] = 'name';
  $handler->display->display_options['fields']['name']['table'] = 'users';
  $handler->display->display_options['fields']['name']['field'] = 'name';
  $handler->display->display_options['fields']['name']['relationship'] = 'user';
  $handler->display->display_options['fields']['name']['label'] = 'User';
  /* Field: Message: Edit message */
  $handler->display->display_options['fields']['edit']['id'] = 'edit';
  $handler->display->display_options['fields']['edit']['table'] = 'message';
  $handler->display->display_options['fields']['edit']['field'] = 'edit';
  $handler->display->display_options['fields']['edit']['label'] = '';
  $handler->display->display_options['fields']['edit']['exclude'] = TRUE;
  $handler->display->display_options['fields']['edit']['element_label_colon'] = FALSE;
  /* Field: Message: Delete message */
  $handler->display->display_options['fields']['delete']['id'] = 'delete';
  $handler->display->display_options['fields']['delete']['table'] = 'message';
  $handler->display->display_options['fields']['delete']['field'] = 'delete';
  $handler->display->display_options['fields']['delete']['label'] = '';
  $handler->display->display_options['fields']['delete']['exclude'] = TRUE;
  $handler->display->display_options['fields']['delete']['element_label_colon'] = FALSE;
  /* Field: Message: View message */
  $handler->display->display_options['fields']['view']['id'] = 'view';
  $handler->display->display_options['fields']['view']['table'] = 'message';
  $handler->display->display_options['fields']['view']['field'] = 'view';
  $handler->display->display_options['fields']['view']['label'] = '';
  $handler->display->display_options['fields']['view']['exclude'] = TRUE;
  $handler->display->display_options['fields']['view']['element_label_colon'] = FALSE;
  /* Field: Message: Trigger CI Build message */
  $handler->display->display_options['fields']['trigger']['id'] = 'trigger';
  $handler->display->display_options['fields']['trigger']['table'] = 'message';
  $handler->display->display_options['fields']['trigger']['field'] = 'trigger';
  $handler->display->display_options['fields']['trigger']['label'] = '';
  $handler->display->display_options['fields']['trigger']['exclude'] = TRUE;
  $handler->display->display_options['fields']['trigger']['element_label_colon'] = FALSE;
  /* Field: Global: Custom text */
  $handler->display->display_options['fields']['nothing']['id'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['table'] = 'views';
  $handler->display->display_options['fields']['nothing']['field'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['label'] = 'Operation';
  $handler->display->display_options['fields']['nothing']['alter']['text'] = '[view] [edit] [delete] [trigger]';
  $handler->display->display_options['defaults']['arguments'] = FALSE;
  /* Contextual filter: Content: Repository (og_repo) */
  $handler->display->display_options['arguments']['og_repo_target_id']['id'] = 'og_repo_target_id';
  $handler->display->display_options['arguments']['og_repo_target_id']['table'] = 'og_membership';
  $handler->display->display_options['arguments']['og_repo_target_id']['field'] = 'og_repo_target_id';
  $handler->display->display_options['arguments']['og_repo_target_id']['relationship'] = 'field_ci_build_target_id';
  $handler->display->display_options['arguments']['og_repo_target_id']['default_action'] = 'default';
  $handler->display->display_options['arguments']['og_repo_target_id']['default_argument_type'] = 'node';
  $handler->display->display_options['arguments']['og_repo_target_id']['summary']['number_of_records'] = '0';
  $handler->display->display_options['arguments']['og_repo_target_id']['summary']['format'] = 'default_summary';
  $handler->display->display_options['arguments']['og_repo_target_id']['summary_options']['items_per_page'] = '25';
  $handler->display->display_options['inherit_panels_path'] = '1';
  $export['ci_items'] = $view;

  $view = new view();
  $view->name = 're_queued_items';
  $view->description = '';
  $view->tag = 'default';
  $view->base_table = 'message';
  $view->human_name = 'Re-queued items';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['title'] = 'Re-queued items';
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['access']['type'] = 'perm';
  $handler->display->display_options['access']['perm'] = 'administer nodes';
  $handler->display->display_options['cache']['type'] = 'none';
  $handler->display->display_options['query']['type'] = 'views_query';
  $handler->display->display_options['exposed_form']['type'] = 'basic';
  $handler->display->display_options['pager']['type'] = 'full';
  $handler->display->display_options['pager']['options']['items_per_page'] = '50';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['pager']['options']['id'] = '0';
  $handler->display->display_options['pager']['options']['quantity'] = '9';
  $handler->display->display_options['style_plugin'] = 'table';
  $handler->display->display_options['style_options']['columns'] = array(
    'title_1' => 'title_1',
    'title' => 'title',
    'field_requeue_count' => 'field_requeue_count',
  );
  $handler->display->display_options['style_options']['default'] = '-1';
  $handler->display->display_options['style_options']['info'] = array(
    'title_1' => array(
      'sortable' => 0,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'title' => array(
      'sortable' => 0,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_requeue_count' => array(
      'sortable' => 1,
      'default_sort_order' => 'desc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
  );
  /* Relationship: Entity Reference: Referenced Entity */
  $handler->display->display_options['relationships']['field_ci_build_target_id']['id'] = 'field_ci_build_target_id';
  $handler->display->display_options['relationships']['field_ci_build_target_id']['table'] = 'field_data_field_ci_build';
  $handler->display->display_options['relationships']['field_ci_build_target_id']['field'] = 'field_ci_build_target_id';
  /* Relationship: Entity Reference: Referenced Entity */
  $handler->display->display_options['relationships']['og_repo_target_id']['id'] = 'og_repo_target_id';
  $handler->display->display_options['relationships']['og_repo_target_id']['table'] = 'og_membership';
  $handler->display->display_options['relationships']['og_repo_target_id']['field'] = 'og_repo_target_id';
  $handler->display->display_options['relationships']['og_repo_target_id']['relationship'] = 'field_ci_build_target_id';
  /* Field: Content: Title */
  $handler->display->display_options['fields']['title_1']['id'] = 'title_1';
  $handler->display->display_options['fields']['title_1']['table'] = 'node';
  $handler->display->display_options['fields']['title_1']['field'] = 'title';
  $handler->display->display_options['fields']['title_1']['relationship'] = 'og_repo_target_id';
  $handler->display->display_options['fields']['title_1']['label'] = 'Repository';
  /* Field: Content: Title */
  $handler->display->display_options['fields']['title']['id'] = 'title';
  $handler->display->display_options['fields']['title']['table'] = 'node';
  $handler->display->display_options['fields']['title']['field'] = 'title';
  $handler->display->display_options['fields']['title']['relationship'] = 'field_ci_build_target_id';
  $handler->display->display_options['fields']['title']['label'] = 'CI Build';
  /* Field: Message: Requeue Count */
  $handler->display->display_options['fields']['field_requeue_count']['id'] = 'field_requeue_count';
  $handler->display->display_options['fields']['field_requeue_count']['table'] = 'field_data_field_requeue_count';
  $handler->display->display_options['fields']['field_requeue_count']['field'] = 'field_requeue_count';
  $handler->display->display_options['fields']['field_requeue_count']['empty'] = '0';
  $handler->display->display_options['fields']['field_requeue_count']['settings'] = array(
    'thousand_separator' => '',
    'prefix_suffix' => 1,
  );
  /* Field: Message: Message ID */
  $handler->display->display_options['fields']['mid']['id'] = 'mid';
  $handler->display->display_options['fields']['mid']['table'] = 'message';
  $handler->display->display_options['fields']['mid']['field'] = 'mid';
  $handler->display->display_options['fields']['mid']['separator'] = '';
  /* Field: Message: User uid */
  $handler->display->display_options['fields']['user']['id'] = 'user';
  $handler->display->display_options['fields']['user']['table'] = 'message';
  $handler->display->display_options['fields']['user']['field'] = 'user';
  $handler->display->display_options['fields']['user']['separator'] = '';
  /* Sort criterion: Message: Requeue Count (field_requeue_count) */
  $handler->display->display_options['sorts']['field_requeue_count_value']['id'] = 'field_requeue_count_value';
  $handler->display->display_options['sorts']['field_requeue_count_value']['table'] = 'field_data_field_requeue_count';
  $handler->display->display_options['sorts']['field_requeue_count_value']['field'] = 'field_requeue_count_value';
  $handler->display->display_options['sorts']['field_requeue_count_value']['order'] = 'DESC';

  /* Display: Page */
  $handler = $view->new_display('page', 'Page', 'page');
  $handler->display->display_options['path'] = 'admin/content/re-queued-items';
  $handler->display->display_options['menu']['type'] = 'tab';
  $handler->display->display_options['menu']['title'] = 'Re-queues Items';
  $handler->display->display_options['menu']['description'] = 'List of messages with the re-queue count';
  $handler->display->display_options['menu']['weight'] = '0';
  $handler->display->display_options['menu']['name'] = 'management';
  $handler->display->display_options['menu']['context'] = 0;
  $handler->display->display_options['menu']['context_only_inline'] = 0;
  $export['re_queued_items'] = $view;

  return $export;
}
