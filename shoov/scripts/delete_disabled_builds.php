<?php
/**
 * @file delete_disabled_builds.php
 * Script to find and delete all ci_build messages with a queued status and
 * relevant ci_build node mode is set to disabled.
 */

_shoov_ci_build_delete_disabled_build_messages();

/**
 * Helper function; deletes queue ci_build messages
 * that belong to disabled ci_build nodes.
 */
function _shoov_ci_build_delete_disabled_build_messages() {
  $not_found_text = "Script to delete disabled build message ran, but nothing was found";

  // Find all the disabled ci_build nodes.
  $query = new EntityFieldQuery();
  $result = $query
    ->entityCondition('entity_type', 'node')
    ->entityCondition('bundle', 'ci_build')
    ->fieldCondition('field_ci_build_enabled', 'value', FALSE)
    ->execute();

  if (empty($result['node'])) {
    // All of the existing ci_build nodes are enabled.
    watchdog(WATCHDOG_INFO, $not_found_text);
    return;
  }

  // Get all of the messages to delete.
  $nids = array_keys($result['node']);

  $query = new EntityFieldQuery();
  $result = $query
    ->entityCondition('entity_type', 'message')
    ->entityCondition('bundle', 'ci_build')
    ->fieldCondition('field_ci_build', 'target_id', $nids, 'IN')
    ->fieldCondition('field_ci_build_status', 'value', 'queue')
    ->execute();

  if (empty($result['message'])) {
    // No messages to delete.
    watchdog(WATCHDOG_INFO, $not_found_text);
    return;
  }

  // Delete messages that were found.
  $mids = array_keys($result['message']);
  message_delete_multiple($mids);
  $parameters = array('@count' => count($mids));
  watchdog(WATCHDOG_INFO, "Script to delete disabled build message ran, deleted @count ci_build messages.", $parameters);
}
