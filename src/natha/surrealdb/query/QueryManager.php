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
    $this->provider->createTable($tableName, $callbackClass, $callbackMethod, $schema, $extraData);
  }

  public function insertData(string $tableName,array $data,string $callbackClass,string $callbackMethod,array $extraData = []): void {
    $this->provider->insertData($tableName, $data, $callbackClass, $callbackMethod, $extraData);
  }

  public function selectData(string $tableName,string $callbackClass,string $callbackMethod,string $conditions = "",array $extraData = []): void {
    $this->provider->selectData($tableName, $conditions, $callbackClass, $callbackMethod, $extraData);
  }

  public function dropTable(string $tableName,string $callbackClass,string $callbackMethod,array $extraData = []): void {
    $this->provider->dropTable($tableName, $callbackClass, $callbackMethod, $extraData);
  }

  public function alterTable(string $tableName,string $schema,string $callbackClass,string $callbackMethod,array $extraData = []): void {
    $this->provider->alterTable($tableName, $schema, $callbackClass, $callbackMethod, $extraData);
  }

  public function update(string $tableName,array $data,string $conditions,string $callbackClass,string $callbackMethod,array $extraData = []): void {
    $this->provider->updateData($tableName, $data, $conditions, $callbackClass, $callbackMethod, $extraData);
  }

  public function patch(string $tableName,array $patch,string $conditions,string $callbackClass,string $callbackMethod,array $extraData = []): void {
    $this->provider->patchData($tableName, $patch, $conditions, $callbackClass, $callbackMethod, $extraData);
  }

  public function delete(string $tableName,string $conditions,string $callbackClass,string $callbackMethod,array $extraData = []): void {
    $this->provider->deleteData($tableName, $conditions, $callbackClass, $callbackMethod, $extraData);
  }

  public function count(string $tableName,string $conditions,string $callbackClass,string $callbackMethod,array $extraData = []): void {
    $this->provider->count($tableName, $conditions, $callbackClass, $callbackMethod, $extraData);
  }

  public function rawQuery(string $sql,string $callbackClass,string $callbackMethod,array $extraData = []): void {
    $this->provider->rawQuery($sql, $callbackClass, $callbackMethod, $extraData);
  }

  public function info(string $callbackClass,string $callbackMethod,array $extraData = []): void {
    $this->provider->info($callbackClass, $callbackMethod, $extraData);
  }

  public function healthCheck(string $callbackClass,string $callbackMethod,array $extraData = []): void {
    $this->provider->healthCheck($callbackClass, $callbackMethod, $extraData);
  }

  public function defineIndex(string $tableName,string $field,string $indexName,string $callbackClass,string $callbackMethod,array $extraData = []): void {
    $this->provider->defineIndex($tableName, $field, $indexName, $callbackClass, $callbackMethod, $extraData);
  }

  public function removeIndex(string $tableName,string $indexName,string $callbackClass,string $callbackMethod,array $extraData = []): void {
    $this->provider->removeIndex($tableName, $indexName, $callbackClass, $callbackMethod, $extraData);
  }

  public function defineFunction(string $name,string $parameters,string $body,string $callbackClass,string $callbackMethod,array $extraData = []): void {
    $this->provider->defineFunction($name, $parameters, $body, $callbackClass, $callbackMethod, $extraData);
  }

  public function runTransaction(array $queries,string $callbackClass,string $callbackMethod,array $extraData = []): void {
    $this->provider->runTransaction($queries, $callbackClass, $callbackMethod, $extraData);
  }
}
