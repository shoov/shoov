<?php
/**
 * @file
 * shoov_user.pages_default.inc
 */

/**
 * Implements hook_default_page_manager_handlers().
 */
function shoov_user_default_page_manager_handlers() {
  $export = array();

  $handler = new stdClass();
  $handler->disabled = FALSE; /* Edit this to true to make a default handler disabled initially */
  $handler->api_version = 1;
  $handler->name = 'user_view__user';
  $handler->task = 'user_view';
  $handler->subtask = '';
  $handler->handler = 'panel_context';
  $handler->weight = 0;
  $handler->conf = array(
    'title' => 'User',
    'no_blocks' => 0,
    'pipeline' => 'standard',
    'body_classes_to_remove' => '',
    'body_classes_to_add' => '',
    'css_id' => '',
    'css' => '',
    'contexts' => array(),
    'relationships' => array(),
    'name' => 'user',
  );
  $display = new panels_display();
  $display->layout = 'twocol';
  $display->layout_settings = array();
  $display->panel_settings = array(
    'style_settings' => array(
      'default' => NULL,
      'center' => NULL,
      'left' => NULL,
      'right' => NULL,
    ),
  );
  $display->cache = array();
  $display->title = '';
  $display->uuid = '0ffef2d1-cd02-48f1-997f-8714f2929a87';
  $display->content = array();
  $display->panels = array();
    $pane = new stdClass();
    $pane->pid = 'new-61c9a02d-c2c0-4c5f-8f82-d646a6b33d1f';
    $pane->panel = 'left';
    $pane->type = 'entity_view';
    $pane->subtype = 'user';
    $pane->shown = TRUE;
    $pane->access = array();
    $pane->configuration = array(
      'view_mode' => 'full',
      'context' => 'argument_entity_id:user_1',
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
    $pane->uuid = '61c9a02d-c2c0-4c5f-8f82-d646a6b33d1f';
    $display->content['new-61c9a02d-c2c0-4c5f-8f82-d646a6b33d1f'] = $pane;
    $display->panels['left'][0] = 'new-61c9a02d-c2c0-4c5f-8f82-d646a6b33d1f';
    $pane = new stdClass();
    $pane->pid = 'new-92380d3d-2c45-425f-a615-f0259dee8a9e';
    $pane->panel = 'right';
    $pane->type = 'views';
    $pane->subtype = 'user_content';
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
        0 => 'argument_entity_id:user_1.uid',
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
    $pane->uuid = '92380d3d-2c45-425f-a615-f0259dee8a9e';
    $display->content['new-92380d3d-2c45-425f-a615-f0259dee8a9e'] = $pane;
    $display->panels['right'][0] = 'new-92380d3d-2c45-425f-a615-f0259dee8a9e';
  $display->hide_title = PANELS_TITLE_FIXED;
  $display->title_pane = '0';
  $handler->conf['display'] = $display;
  $export['user_view__user'] = $handler;

  return $export;
}
