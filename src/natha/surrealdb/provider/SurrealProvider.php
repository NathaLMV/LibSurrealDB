<?php

/**
 * This file is part of SurrealAPI.
 *
 * SurrealAPI is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License.
 *
 * SurrealAPI is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with SurrealAPI.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace natha\surrealdb\provider;

use pocketmine\Server;

use natha\surrealdb\task\QueryAsyncTask;

class SurrealProvider {
  
  public function __construct(private string $endpoint,private string $namespace,private string $database,private string $username,private string $password) {}

  public function executeQuery(string $sql, string $callbackClass, string $callbackMethod, array $extraData = []): void {
    if (empty($sql) || !is_string($callbackClass) || !is_string($callbackMethod)) {
      throw new \InvalidArgumentException("SQL, callback class, and callback method cannot be empty.");
    }
    $task = new QueryAsyncTask(
      $this->endpoint,
      $this->namespace,
      $this->database,
      $this->username,
      $this->password,
      $sql,
      $callbackClass,
      $callbackMethod,
      json_encode($extraData)
    );
    Server::getInstance()->getAsyncPool()->submitTask($task);
  }
  
  public function createTable(string $table,string $callbackClass,string $callbackMethod,string $schema = "SCHEMALESS",array $extraData = []): void {
    $sql = "DEFINE TABLE IF NOT EXISTS {$table} TYPE ANY {$schema} PERMISSIONS FOR select, create, update, delete WHERE true;";
    $this->executeQuery($sql, $callbackClass, $callbackMethod, $extraData);
  }

  public function dropTable(string $table,string $callbackClass,string $callbackMethod,array $extraData = []): void {
    $sql = "REMOVE TABLE IF EXISTS {$table};";
    $this->executeQuery($sql, $callbackClass, $callbackMethod, $extraData);
  }

  public function alterTable(string $table,string $schema,string $callbackClass,string $callbackMethod,array $extraData = []): void {
    $sql = "ALTER TABLE {$table} TYPE ANY {$schema};";
    $this->executeQuery($sql, $callbackClass, $callbackMethod, $extraData);
  }

  public function insertData(string $table,array $data,string $callbackClass,string $callbackMethod,array $extraData = []): void {
    $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    if ($json === false) {
      Server::getInstance()->getLogger()->error("SurrealDB: JSON encode error: " . json_last_error_msg());
      return;
    }
    if (strlen($json) > 500_000) {
      Server::getInstance()->getLogger()->error("SurrealDB: Payload too large for insertion.");
      return;
    }
    $sql = "INSERT INTO {$table} {$json};";
    $this->executeQuery($sql, $callbackClass, $callbackMethod, $extraData);
  }

  public function selectData(string $data,string $table,string $conditions,string $callbackClass,string $callbackMethod,array $extraData = []): void {
    $sql = "SELECT {$data} FROM {$table} {$conditions};";
    $this->executeQuery($sql, $callbackClass, $callbackMethod, $extraData);
  }

  public function updateData(string $table,array $data,string $conditions,string $callbackClass,string $callbackMethod,array $extraData = []): void {
    $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    if ($json === false) {
      Server::getInstance()->getLogger()->error("SurrealDB: JSON encode error: " . json_last_error_msg());
      return;
    }
    $sql = "UPDATE {$table} CONTENT {$json} {$conditions};";
    $this->executeQuery($sql, $callbackClass, $callbackMethod, $extraData);
  }

  public function patchData(string $table,array $patch,string $conditions,string $callbackClass,string $callbackMethod,array $extraData = []): void {
    $json = json_encode($patch, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    if ($json === false) {
      Server::getInstance()->getLogger()->error("SurrealDB: JSON encode error: " . json_last_error_msg());
      return;
    }
    $sql = "UPDATE {$table} MERGE {$json} {$conditions};";
    $this->executeQuery($sql, $callbackClass, $callbackMethod, $extraData);
  }

  public function deleteData(string $table,string $conditions,string $callbackClass,string $callbackMethod,array $extraData = []): void {
    $sql = "DELETE FROM {$table} {$conditions};";
    $this->executeQuery($sql, $callbackClass, $callbackMethod, $extraData);
  }

  public function count(string $table,string $conditions,string $callbackClass,string $callbackMethod,array $extraData = []): void {
    $sql = "SELECT count() AS total FROM {$table} {$conditions};";
    $this->executeQuery($sql, $callbackClass, $callbackMethod, $extraData);
  }
  
  public function rawQuery(string $sql,string $callbackClass,string $callbackMethod,array $extraData = []): void {
    $this->executeQuery(rtrim($sql, ";") . ";", $callbackClass, $callbackMethod, $extraData);
  }

  public function info(string $callbackClass,string $callbackMethod,array $extraData = []): void {
    $this->executeQuery("INFO FOR DB;", $callbackClass, $callbackMethod, $extraData);
  }

  public function healthCheck(string $callbackClass,string $callbackMethod,array $extraData = []): void {
    $this->executeQuery("SELECT * FROM server:status;", $callbackClass, $callbackMethod, $extraData);
  }

  public function defineIndex(string $table,string $field,string $indexName,string $callbackClass,string $callbackMethod,array $extraData = []): void {
    $sql = "DEFINE INDEX IF NOT EXISTS {$indexName} ON TABLE {$table} COLUMNS {$field};";
    $this->executeQuery($sql, $callbackClass, $callbackMethod, $extraData);
  }

  public function removeIndex(string $table,string $indexName,string $callbackClass,string $callbackMethod,array $extraData = []): void {
    $sql = "REMOVE INDEX {$indexName} ON TABLE {$table};";
    $this->executeQuery($sql, $callbackClass, $callbackMethod, $extraData);
  }

  public function defineFunction(string $name,string $parameters,string $body,string $callbackClass,string $callbackMethod,array $extraData = []): void {
    $sql = "DEFINE FUNCTION IF NOT EXISTS fn::{$name}({$parameters}) {$body};";
    $this->executeQuery($sql, $callbackClass, $callbackMethod, $extraData);
  }

  public function runTransaction(array $queries,string $callbackClass,string $callbackMethod,array $extraData = []): void {
    $sql = "BEGIN TRANSACTION;" . PHP_EOL . implode(PHP_EOL, $queries) . PHP_EOL . "COMMIT;";
    $this->executeQuery($sql, $callbackClass, $callbackMethod, $extraData);
  }
}
