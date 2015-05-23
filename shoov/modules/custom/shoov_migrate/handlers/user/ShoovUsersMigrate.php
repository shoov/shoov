<?php

/**
 * @file
 * Contains \ShoovUsersMigrate.
 */

class ShoovUsersMigrate extends Migration {

  /**
   * Map the field and properties to the CSV header.
   */
  public $fields = array(
    '_unique_id',
    '_username',
  );

  public $entityType = 'user';

  public function __construct() {
    parent::__construct();
    $this->description = t('Import users from a CSV file.');


    $this->addFieldMapping('name', '_username');

    $this
      ->addFieldMapping('pass')
      ->defaultValue('1234');

    $this->addFieldMapping('mail');

    $this
      ->addFieldMapping('roles')
      ->defaultValue(DRUPAL_AUTHENTICATED_RID);

    $this
      ->addFieldMapping('status')
      ->defaultValue(TRUE);

    // Create a map object for tracking the relationships between source rows
    $key = array(
      '_unique_id' => array(
        'type' => 'varchar',
        'length' => 255,
        'not null' => TRUE,
      ),
    );
    $destination_handler = new MigrateDestinationUser();
    $this->map = new MigrateSQLMap($this->machineName, $key, $destination_handler->getKeySchema());

    $query = db_select('_raw_user', 't')
      ->fields('t')
      ->orderBy('__id');

    $this->source = new MigrateSourceSQL($query, $this->fields);

    // Create a MigrateSource object.
    $this->destination = new MigrateDestinationUser();
  }

  /**
   * Overrides Migration::prepareRow().
   *
   * Add default email.
   */
  public function prepareRow($row) {
    $row->mail = $row->name . '@example.com';
  }
}
