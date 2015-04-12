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
          "value" : "Build for repo",
          "format" : "filtered_html",
          "safe_value" : "\\u003Cp\\u003EBuild for repo\\u003C\\/p\\u003E\\n"
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
