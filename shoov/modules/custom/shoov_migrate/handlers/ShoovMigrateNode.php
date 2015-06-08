<?php

/**
 * @file
 * Contains \ShoovMigrateNode.
 */

abstract class ShoovMigrateNode extends ShoovMigrate {

  protected function addDefaultSqlFields() {
    $this->addFieldMapping('title', '_title');
    return array('_title');
  }
}
