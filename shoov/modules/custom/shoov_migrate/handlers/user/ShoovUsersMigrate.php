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
    '_username'
  );

  public $entityType = 'user';

  public function __construct() {
    parent::__construct();
    $this->description = t('Import users from a CSV file.');

    $this->addFieldMapping('name', '_username');

    // Set default password '1234' for each imported user.
    $this
      ->addFieldMapping('pass')
      ->defaultValue('1234');

    $this->addFieldMapping('mail', '_email');

    $this
      ->addFieldMapping('roles')
      ->defaultValue(DRUPAL_AUTHENTICATED_RID);

    $this
      ->addFieldMapping('status')
      ->defaultValue(TRUE);

    // Set random Github access token because this field is required.
    $this->addFieldMapping('field_github_access_token', '_github_token');

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
   * Add default email and randomly generated Github token.
   */
  public function prepareRow($row) {
    $row->_email = strtolower($row->_username) . '@example.com';
    $row->_github_token = sha1(srand());
  }
}
