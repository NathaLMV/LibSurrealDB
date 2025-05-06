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

namespace natha\surrealdb\query;

use natha\surrealdb\utils\PromiseHandler;
use natha\surrealdb\provider\SurrealProvider;

class QueryManager {
  
  public function __construct(private SurrealProvider $provider) {}
  
  public function createTable(string $tableName,string $callbackClass,string $callbackMethod,string $schema = "SCHEMALESS",array $extraData = []): void {
    $this->provider->createTable($tableName, $schema, $callbackClass, $callbackMethod, $extraData);
  }

  public function insertData(string $tableName,array $data,string $callbackClass,string $callbackMethod,array $extraData = []): void {
    $this->provider->insertData($tableName, $data, $callbackClass, $callbackMethod, $extraData);
  }

  public function selectData(string $tableName,string $callbackClass,string $callbackMethod,string $conditions = "",array $extraData = []): void {
    $this->provider->selectData($tableName, $conditions, $callbackClass, $callbackMethod, $extraData);
  }
  
  public function dropTable(string $table, string $callbackClass, string $callbackMethod, array $extraData = []): void {
    $this->provider->dropTable($table, $callbackClass, $callbackMethod, $extraData);
  }
  
  public function alterTable(string $table, string $schema, string $callbackClass, string $callbackMethod, array $extraData = []): void {
    $this->provider->alterTable($table, $schema, $callbackClass, $callbackMethod, $extraData);
  }
    
  public function update(string $table, array $data, string $conditions, string $callbackClass, string $callbackMethod, array $extraData = []): void {
    $this->provider->updateData($table, $data, $conditions, $callbackClass, $callbackMethod, $extraData);
  }
  
  public function patch(string $table, array $patch, string $conditions, string $callbackClass, string $callbackMethod, array $extraData = []): void {
    $this->provider->patchData($table, $patch, $conditions, $callbackClass, $callbackMethod, $extraData);
  }
  
  public function delete(string $table, string $conditions, string $callbackClass, string $callbackMethod, array $extraData = []): void {
    $this->provider->deleteData($table, $conditions, $callbackClass, $callbackMethod, $extraData);
  }
  
  public function count(string $table, string $conditions, string $callbackClass, string $callbackMethod, array $extraData = []): void {
    $this->provider->count($table, $conditions, $callbackClass, $callbackMethod, $extraData);
  }
  
  public function raw(string $sql, string $callbackClass, string $callbackMethod, array $extraData = []): void {
    $this->provider->rawQuery($sql, $callbackClass, $callbackMethod, $extraData);
  }
  
  public function info(string $callbackClass, string $callbackMethod, array $extraData = []): void {
    $this->provider->info($callbackClass, $callbackMethod, $extraData);
  }
  public function health(string $callbackClass, string $callbackMethod, array $extraData = []): void {
    $this->provider->healthCheck($callbackClass, $callbackMethod, $extraData);
  }
  
  public function defineIndex(string $table, string $field, string $indexName, string $callbackClass, string $callbackMethod, array $extraData = []): void {
    $this->provider->defineIndex($table, $field, $indexName, $callbackClass, $callbackMethod, $extraData);
  }

  public function removeIndex(string $indexName, string $callbackClass, string $callbackMethod, array $extraData = []): void {
    $this->provider->removeIndex($indexName, $callbackClass, $callbackMethod, $extraData);
  }

  public function defineFunction(string $name, string $parameters, string $body, string $callbackClass, string $callbackMethod, array $extraData = []): void {
    $this->provider->defineFunction($name, $parameters, $body, $callbackClass, $callbackMethod, $extraData);
  }

  public function runTransaction(array $queries, string $callbackClass, string $callbackMethod, array $extraData = []): void {
    $this->provider->runTransaction($queries, $callbackClass, $callbackMethod, $extraData);
  }

  public function rawQuery(string $query, string $callbackClass, string $callbackMethod, array $extraData = []): void {
    $this->provider->rawQuery($query, $callbackClass, $callbackMethod, $extraData);
  }
}
