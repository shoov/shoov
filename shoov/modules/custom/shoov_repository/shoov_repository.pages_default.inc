<?php
/**
 * @file
 * shoov_repository.pages_default.inc
 */

/**
 * Implements hook_default_page_manager_handlers().
 */
function shoov_repository_default_page_manager_handlers() {
  $export = array();

  $handler = new stdClass();
  $handler->disabled = FALSE; /* Edit this to true to make a default handler disabled initially */
  $handler->api_version = 1;
  $handler->name = 'node_view__repositories';
  $handler->task = 'node_view';
  $handler->subtask = '';
  $handler->handler = 'panel_context';
  $handler->weight = 2;
  $handler->conf = array(
    'title' => 'repositories',
    'no_blocks' => 0,
    'pipeline' => 'standard',
    'body_classes_to_remove' => '',
    'body_classes_to_add' => '',
    'css_id' => '',
    'css' => '',
    'contexts' => array(),
    'relationships' => array(),
    'name' => 'repositories',
    'access' => array(
      'plugins' => array(
        0 => array(
          'name' => 'node_type',
          'settings' => array(
            'type' => array(
              'repository' => 'repository',
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
      'center' => NULL,
      'left' => NULL,
      'right' => NULL,
    ),
  );
  $display->cache = array();
  $display->title = '';
  $display->uuid = '5b308fb4-2406-4d7b-8f89-f7fcf2cf7554';
  $display->content = array();
  $display->panels = array();
    $pane = new stdClass();
    $pane->pid = 'new-f6bd3f2a-b387-4359-8f4f-7acc0feb7443';
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
    $pane->uuid = 'f6bd3f2a-b387-4359-8f4f-7acc0feb7443';
    $display->content['new-f6bd3f2a-b387-4359-8f4f-7acc0feb7443'] = $pane;
    $display->panels['left'][0] = 'new-f6bd3f2a-b387-4359-8f4f-7acc0feb7443';
    $pane = new stdClass();
    $pane->pid = 'new-93ec5592-5bb5-4d9a-b292-837862383c93';
    $pane->panel = 'right';
    $pane->type = 'views_panes';
    $pane->subtype = 'ci_items-panel_pane_2';
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
    $pane->uuid = '93ec5592-5bb5-4d9a-b292-837862383c93';
    $display->content['new-93ec5592-5bb5-4d9a-b292-837862383c93'] = $pane;
    $display->panels['right'][0] = 'new-93ec5592-5bb5-4d9a-b292-837862383c93';
    $pane = new stdClass();
    $pane->pid = 'new-edecb08f-2ba8-4203-a4bb-80a93b0c58cc';
    $pane->panel = 'right';
    $pane->type = 'views_panes';
    $pane->subtype = 'group_members_subscribtion-panel_pane_1';
    $pane->shown = TRUE;
    $pane->access = array();
    $pane->configuration = array(
      'context' => array(
        0 => 'argument_entity_id:node_1',
      ),
    );
    $pane->cache = array();
    $pane->style = array(
      'settings' => NULL,
    );
    $pane->css = array();
    $pane->extras = array();
    $pane->position = 1;
    $pane->locks = array();
    $pane->uuid = 'edecb08f-2ba8-4203-a4bb-80a93b0c58cc';
    $display->content['new-edecb08f-2ba8-4203-a4bb-80a93b0c58cc'] = $pane;
    $display->panels['right'][1] = 'new-edecb08f-2ba8-4203-a4bb-80a93b0c58cc';
  $display->hide_title = PANELS_TITLE_FIXED;
  $display->title_pane = '0';
  $handler->conf['display'] = $display;
  $export['node_view__repositories'] = $handler;

  return $export;
}
