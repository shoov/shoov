<?php

/**
 * @file
 * Contains \ShoovMigrateMessage.
 */

abstract class ShoovMigrateMessage extends ShoovMigrate {

  protected function getSqlTablePrefix() {
    return '_raw_msg';
  }
}
