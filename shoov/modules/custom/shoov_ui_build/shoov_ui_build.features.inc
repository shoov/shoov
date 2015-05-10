<?php
/**
 * @file
 * shoov_ui_build.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function shoov_ui_build_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "strongarm" && $api == "strongarm") {
    return array("version" => "1");
  }
}
