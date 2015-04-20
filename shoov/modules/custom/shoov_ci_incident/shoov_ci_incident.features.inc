<?php
/**
 * @file
 * shoov_ci_incident.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function shoov_ci_incident_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "strongarm" && $api == "strongarm") {
    return array("version" => "1");
  }
}

/**
 * Implements hook_default_message_type().
 */
function shoov_ci_incident_default_message_type() {
  $items = array();
  $items['ci_incident_error'] = entity_import('message_type', '{
    "name" : "ci_incident_error",
    "description" : "CI Incident error",
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
          "value" : "@repo branch @branch has failed",
          "format" : "plain_text",
          "safe_value" : "\\u003Cp\\u003E@repo branch @branch has failed\\u003C\\/p\\u003E\\n"
        },
        {
          "value" : "See \\u003Ca href=\\u0022@url\\u0022\\u003Elogs\\u003C\\/a\\u003E",
          "format" : "filtered_html",
          "safe_value" : "\\u003Cp\\u003ESee \\u003Ca href=\\u0022@url\\u0022\\u003Elogs\\u003C\\/a\\u003E\\u003C\\/p\\u003E\\n"
        }
      ]
    }
  }');
  $items['ci_incident_fixed'] = entity_import('message_type', '{
    "name" : "ci_incident_fixed",
    "description" : "CI Incident fixed",
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
          "value" : "@repo branch @branch is fixed",
          "format" : "plain_text",
          "safe_value" : "\\u003Cp\\u003E@repo branch @branch is fixed\\u003C\\/p\\u003E\\n"
        },
        {
          "value" : "Site was down for about @downtime",
          "format" : "filtered_html",
          "safe_value" : "\\u003Cp\\u003ESite was down for about @downtime\\u003C\\/p\\u003E\\n"
        }
      ]
    }
  }');
  return $items;
}

/**
 * Implements hook_node_info().
 */
function shoov_ci_incident_node_info() {
  $items = array(
    'ci_incident' => array(
      'name' => t('CI Incident'),
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
