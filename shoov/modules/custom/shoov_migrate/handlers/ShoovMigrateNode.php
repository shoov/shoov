<?php

/**
 * @file
 * Contains \ShoovMigrateNode.
 */

abstract class ShoovMigrateNode extends Migration {

  public function __construct() {
    parent::__construct();

    // Make sure we can use it for node and term only.
    if (!in_array($this->entityType, array('node'))) {
      throw new Exception('\ShoovMigrateBase supports only nodes and terms.');
    }

    $this->description = t('Import @type - @bundle from SQL table', array('@type' => $this->entityType, '@bundle' => $this->bundle));

    $this->fields = !empty($this->fields) ? $this->fields : array();
    $sql_fields[] = '_unique_id';

    $sql_fields[] = '_title';
    $this->addFieldMapping('title', '_title');

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

    $destination_handler = new MigrateDestinationEntityAPI($this->entityType, $this->bundle);
    $this->map = new MigrateSQLMap($this->machineName, $key, $destination_handler->getKeySchema($this->entityType));

    // Create a MigrateSource object.
    $sql_table = (isset($this->sqlTable)) ? '_raw_' . $this->sqlTable : '_raw_' . $this->bundle;

    $query = db_select($sql_table, 't')
      ->fields('t')
      ->orderBy('__id');
    $this->source = new MigrateSourceSQL($query, $this->fields);

    $this->destination = new MigrateDestinationNode($this->bundle, array('text_format' => 'filtered_html'));
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

  /**
   * Implements Callback function.
   *
   * Return the author ID of the specific repository.
   *
   * @param $repo_id
   *  Node ID of repository.
   * @return mixed
   *  Owner ID of repository.
   */
  protected function getUidFromRepo($repo_id) {
    $repo_node = node_load($repo_id['destid1']);
    return $repo_node->uid;
  }
}
