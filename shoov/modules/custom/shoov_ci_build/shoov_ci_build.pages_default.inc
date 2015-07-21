<?php
/**
 * @file
 * shoov_ci_build.pages_default.inc
 */

/**
 * Implements hook_default_page_manager_handlers().
 */
function shoov_ci_build_default_page_manager_handlers() {
  $export = array();

  $handler = new stdClass();
  $handler->disabled = FALSE; /* Edit this to true to make a default handler disabled initially */
  $handler->api_version = 1;
  $handler->name = 'node_view__ci_builds';
  $handler->task = 'node_view';
  $handler->subtask = '';
  $handler->handler = 'panel_context';
  $handler->weight = 0;
  $handler->conf = array(
    'title' => 'ci_builds',
    'no_blocks' => 0,
    'pipeline' => 'standard',
    'body_classes_to_remove' => '',
    'body_classes_to_add' => '',
    'css_id' => '',
    'css' => '',
    'contexts' => array(),
    'relationships' => array(),
    'name' => 'ci_builds',
    'access' => array(
      'plugins' => array(
        0 => array(
          'name' => 'node_type',
          'settings' => array(
            'type' => array(
              'ci_build' => 'ci_build',
            ),
          ),
          'context' => 'argument_entity_id:node_1',
          'not' => FALSE,
        ),
      ),
      'logic' => 'and',
    ),
  );
  $display = new panels_display();
  $display->layout = 'twocol';
  $display->layout_settings = array();
  $display->panel_settings = array(
    'style_settings' => array(
      'default' => NULL,
      'left' => NULL,
      'right' => NULL,
    ),
  );
  $display->cache = array();
  $display->title = '';
  $display->uuid = '191e7772-1357-401b-abff-66373a352fb6';
  $display->content = array();
  $display->panels = array();
    $pane = new stdClass();
    $pane->pid = 'new-cd485b3b-e880-4032-a579-658fb4170efa';
    $pane->panel = 'left';
    $pane->type = 'entity_view';
    $pane->subtype = 'node';
    $pane->shown = TRUE;
    $pane->access = array();
    $pane->configuration = array(
      'view_mode' => 'full',
      'context' => 'argument_entity_id:node_1',
      'override_title' => 0,
      'override_title_text' => '',
      'override_title_heading' => 'h2',
    );
    $pane->cache = array();
    $pane->style = array(
      'settings' => NULL,
    );
    $pane->css = array();
    $pane->extras = array();
    $pane->position = 0;
    $pane->locks = array();
    $pane->uuid = 'cd485b3b-e880-4032-a579-658fb4170efa';
    $display->content['new-cd485b3b-e880-4032-a579-658fb4170efa'] = $pane;
    $display->panels['left'][0] = 'new-cd485b3b-e880-4032-a579-658fb4170efa';
    $pane = new stdClass();
    $pane->pid = 'new-5951327f-f898-4d8c-bd9d-ba702599dc3d';
    $pane->panel = 'right';
    $pane->type = 'views_panes';
    $pane->subtype = 'ci_items-panel_pane_1';
    $pane->shown = TRUE;
    $pane->access = array();
    $pane->configuration = array();
    $pane->cache = array();
    $pane->style = array(
      'settings' => NULL,
    );
    $pane->css = array();
    $pane->extras = array();
    $pane->position = 0;
    $pane->locks = array();
    $pane->uuid = '5951327f-f898-4d8c-bd9d-ba702599dc3d';
    $display->content['new-5951327f-f898-4d8c-bd9d-ba702599dc3d'] = $pane;
    $display->panels['right'][0] = 'new-5951327f-f898-4d8c-bd9d-ba702599dc3d';
  $display->hide_title = PANELS_TITLE_FIXED;
  $display->title_pane = 'new-cd485b3b-e880-4032-a579-658fb4170efa';
  $handler->conf['display'] = $display;
  $export['node_view__ci_builds'] = $handler;

  return $export;
}
