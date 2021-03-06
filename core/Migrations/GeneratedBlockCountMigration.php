<?php

namespace Core\Migrations;

use \Db\Connection;

/**
 * Allows migrations to be generated at runtime.
 * This means we don't need to create separate migrations for each new currency discovered,
 * because each currency will have a constant table structure.
 */
class GeneratedBlockCountMigration extends \Db\Migration {

  function __construct($currency) {
    $this->currency = $currency;
  }

  function getTable() {
    return "blockcount_" . $this->currency;
  }

  /**
   * Override the default function to check that a table exists.
   */
  function isApplied(Connection $db) {
    return $this->tableExists($db, $this->getTable());
  }

  /**
   * Apply only the current migration.
   * @return true on success or false on failure
   */
  function apply(Connection $db) {
    $q = $db->prepare("CREATE TABLE " . $this->getTable() . " (
      id int not null auto_increment primary key,
      created_at timestamp not null default current_timestamp,

      blockcount int not null,
      is_recent tinyint not null default 0,

      INDEX(is_recent)
    );");
    return $q->execute();
  }

  function getName() {
    return parent::getName() . "_" . $this->currency;
  }

}
