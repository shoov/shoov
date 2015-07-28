<?php
/**
 * @file
 * shoov_ui_build.pages_default.inc
 */

/**
 * Implements hook_default_page_manager_handlers().
 */
function shoov_ui_build_default_page_manager_handlers() {
  $export = array();

  $handler = new stdClass();
  $handler->disabled = FALSE; /* Edit this to true to make a default handler disabled initially */
  $handler->api_version = 1;
  $handler->name = 'node_view__ui_builds';
  $handler->task = 'node_view';
  $handler->subtask = '';
  $handler->handler = 'panel_context';
  $handler->weight = 1;
  $handler->conf = array(
    'title' => 'ui_builds',
    'no_blocks' => 0,
    'pipeline' => 'standard',
    'body_classes_to_remove' => '',
    'body_classes_to_add' => '',
    'css_id' => '',
    'css' => '',
    'contexts' => array(),
    'relationships' => array(),
    'name' => 'ui_builds',
    'access' => array(
      'plugins' => array(
        0 => array(
          'name' => 'entity_bundle:node',
          'settings' => array(
            'type' => array(
              'ui_build' => 'ui_build',
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
  $display->uuid = '307b2d03-5633-489b-bf0e-5aa8b45ce84c';
  $display->content = array();
  $display->panels = array();
    $pane = new stdClass();
    $pane->pid = 'new-5bad4fb8-678c-46ee-b639-f8f2b2ee1f17';
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
    $pane->uuid = '5bad4fb8-678c-46ee-b639-f8f2b2ee1f17';
    $display->content['new-5bad4fb8-678c-46ee-b639-f8f2b2ee1f17'] = $pane;
    $display->panels['left'][0] = 'new-5bad4fb8-678c-46ee-b639-f8f2b2ee1f17';
    $pane = new stdClass();
    $pane->pid = 'new-7da074cc-2a7c-4135-9e96-480f02e79359';
    $pane->panel = 'right';
    $pane->type = 'views';
    $pane->subtype = 'screenshots';
    $pane->shown = TRUE;
    $pane->access = array();
    $pane->configuration = array(
      'override_pager_settings' => 0,
      'use_pager' => 1,
      'nodes_per_page' => '0',
      'pager_id' => '0',
      'offset' => '0',
      'more_link' => 0,
      'feed_icons' => 0,
      'panel_args' => 0,
      'link_to_view' => 0,
      'args' => '',
      'url' => '',
      'display' => 'default',
      'context' => array(
        0 => 'argument_entity_id:node_1.nid',
      ),
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
    $pane->uuid = '7da074cc-2a7c-4135-9e96-480f02e79359';
    $display->content['new-7da074cc-2a7c-4135-9e96-480f02e79359'] = $pane;
    $display->panels['right'][0] = 'new-7da074cc-2a7c-4135-9e96-480f02e79359';
  $display->hide_title = PANELS_TITLE_FIXED;
  $display->title_pane = 'new-5bad4fb8-678c-46ee-b639-f8f2b2ee1f17';
  $handler->conf['display'] = $display;
  $export['node_view__ui_builds'] = $handler;

  return $export;
}
