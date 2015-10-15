<?php
/**
 * @file
 * shoov_user.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function shoov_user_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "page_manager" && $api == "pages_default") {
    return array("version" => "1");
  }
  if ($module == "strongarm" && $api == "strongarm") {
    return array("version" => "1");
  }
}

/**
 * Implements hook_views_api().
 */
function shoov_user_views_api($module = NULL, $api = NULL) {
  return array("api" => "3.0");
}

/**
 * Implements hook_default_message_type().
 */
function shoov_user_default_message_type() {
  $items = array();
  $items['user_added_demo_url'] = entity_import('message_type', '{
    "name" : "user_added_demo_url",
    "description" : "User added demo url",
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
          "value" : "User has added demo request URL.\\r\\n",
          "format" : "filtered_html",
          "safe_value" : "\\u003Cp\\u003EUser has added demo request URL.\\u003C\\/p\\u003E\\n"
        },
        {
          "value" : "Hi, admin!\\r\\n\\r\\nUser @account has added the demo request URL. Please, contact user.\\r\\n\\r\\nBest regards,\\r\\nShoov support team.",
          "format" : "filtered_html",
          "safe_value" : "\\u003Cp\\u003EHi, admin!\\u003C\\/p\\u003E\\n\\u003Cp\\u003EUser @account has added the demo request URL. Please, contact user.\\u003C\\/p\\u003E\\n\\u003Cp\\u003EBest regards,\\u003Cbr \\/\\u003E\\nShoov support team.\\u003C\\/p\\u003E\\n"
        }
      ]
    }
  }');
  return $items;
}
