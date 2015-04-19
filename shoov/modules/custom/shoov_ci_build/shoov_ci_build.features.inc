<?php
/**
 * @file
 * shoov_ci_build.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function shoov_ci_build_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "strongarm" && $api == "strongarm") {
    return array("version" => "1");
  }
}

/**
 * Implements hook_flag_default_flags().
 */
function shoov_ci_build_flag_default_flags() {
  $flags = array();
  // Exported flag: "Subscribe CI builds".
  $flags['subscribe_ci_builds'] = array(
    'entity_type' => 'node',
    'title' => 'Subscribe CI builds',
    'global' => 0,
    'types' => array(
      0 => 'ci_build',
    ),
    'flag_short' => 'Flag this item',
    'flag_long' => '',
    'flag_message' => '',
    'unflag_short' => 'Unflag this item',
    'unflag_long' => '',
    'unflag_message' => '',
    'unflag_denied_text' => '',
    'link_type' => 'toggle',
    'weight' => 0,
    'show_in_links' => array(
      'full' => 'full',
      'teaser' => 'teaser',
      'rss' => 0,
      'token' => 0,
    ),
    'show_as_field' => 0,
    'show_on_form' => 0,
    'access_author' => '',
    'show_contextual_link' => 0,
    'i18n' => 0,
    'api_version' => 3,
    'module' => 'shoov_ci_build',
    'locked' => array(
      0 => 'name',
    ),
  );
  return $flags;

}

/**
 * Implements hook_default_message_type().
 */
function shoov_ci_build_default_message_type() {
  $items = array();
  $items['ci_build'] = entity_import('message_type', '{
    "name" : "ci_build",
    "description" : "CI build",
    "argument_keys" : [],
    "argument" : [],
    "category" : "message_type",
    "data" : {
      "token options" : { "clear" : 0 },
      "purge" : { "override" : 0, "enabled" : 0, "quota" : "", "days" : "" }
    },
    "language" : "",
    "arguments" : null,
    "message_text" : { "und" : [
        {
          "value" : "@{repo} branch @{branch} @{status}",
          "format" : "plain_text",
          "safe_value" : "\\u003Cp\\u003E@{repo} branch @{branch} @{status}\\u003C\\/p\\u003E\\n"
        },
        {
          "value" : "\\u003Ca href=\\u0022@{url}\\u0022\\u003ESee logs\\u003C\\/a\\u003E",
          "format" : "filtered_html",
          "safe_value" : "\\u003Cp\\u003E\\u003Ca href=\\u0022@{url}\\u0022\\u003ESee logs\\u003C\\/a\\u003E\\u003C\\/p\\u003E\\n"
        },
        {
          "value" : "Site was down for about @{downtime}",
          "format" : "filtered_html",
          "safe_value" : "\\u003Cp\\u003ESite was down for about @{downtime}\\u003C\\/p\\u003E\\n"
        }
      ]
    }
  }');
  return $items;
}

/**
 * Implements hook_node_info().
 */
function shoov_ci_build_node_info() {
  $items = array(
    'ci_build' => array(
      'name' => t('CI Build'),
      'base' => 'node_content',
      'description' => '',
      'has_title' => '1',
      'title_label' => t('Title'),
      'help' => '',
    ),
  );
  drupal_alter('node_info', $items);
  return $items;
}
