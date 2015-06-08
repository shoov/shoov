<?php

/**
 * @file
 * Contains \ShoovMigrateNode.
 */

abstract class ShoovMigrate extends Migration {

  public function __construct() {
    parent::__construct();

    $this->description = t('Import @type - @bundle from SQL table', array('@type' => $this->entityType, '@bundle' => $this->bundle));

    $this->fields = !empty($this->fields) ? $this->fields : array();
    $sql_fields[] = '_unique_id';

    $sql_fields = array_merge($sql_fields, $this->addDefaultSqlFields());

    // Rebuild the csv columns array.
    $this->fields = array_merge($sql_fields, $this->fields);

    // Create a map object for tracking the relationships between source rows
    $key = array(
      '_unique_id' => array(
        'type' => 'varchar',
        'length' => 255,
        'not null' => TRUE,
      ),
    );

    $destination_handler = new MigrateDestinationEntityAPI($this->entityType, $this->bundle, array('text_format' => 'filtered_html'));
    $this->map = new MigrateSQLMap($this->machineName, $key, $destination_handler->getKeySchema($this->entityType));

    // Create a MigrateSource object.
    $sql_prefix = $this->getSqlTablePrefix();
    $sql_table = isset($this->sqlTable) ? $sql_prefix . '_' . $this->sqlTable : $sql_prefix . '_' . $this->bundle;

    $query = db_select($sql_table, 't')
      ->fields('t')
      ->orderBy('__id');
    $this->source = new MigrateSourceSQL($query, $this->fields);

    $this->destination = $destination_handler;
  }

  protected function addDefaultSqlFields() {
    return array();
  }

  protected function getSqlTablePrefix() {
    return '_raw';
  }

  /**
   * Return the migrate directory.
   *
   * @return string
   *   The migrate directory.
   */
  protected function getMigrateDirectory() {
    return variable_get('shoov_migrate_directory', FALSE) ? variable_get('shoov_migrate_directory') : drupal_get_path('module', 'shoov_migrate');
  }


}
