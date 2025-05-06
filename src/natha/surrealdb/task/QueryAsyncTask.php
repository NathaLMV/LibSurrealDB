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

namespace natha\surrealdb\task;

use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;

class QueryAsyncTask extends AsyncTask {
  
  public function __construct(private string $endpoint,private string $namespace,private string $database,private string $username,private string $password,private string $query,private string $ownerClass,private string $ownerMethod,private string $extraDataJson = "[]") {}

  public function onRun(): void {
    try {
      if (empty($this->query)) {
        throw new \RuntimeException("Query cannot be empty.");
      }
      $ch = curl_init($this->endpoint);
      curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $this->query,
        CURLOPT_HTTPHEADER => [
          "Accept: application/json",
          "Content-Type: text/plain",
          "NS: {$this->namespace}",
          "DB: {$this->database}",
          "Authorization: Basic " . base64_encode("{$this->username}:{$this->password}")
        ]
      ]);
      $response = curl_exec($ch);
      $error = curl_error($ch);
      curl_close($ch);
      $this->setResult([
        "response" => $response ?: null,
        "error" => $error ?: '',
        "ownerClass" => $this->ownerClass,
        "ownerMethod" => $this->ownerMethod,
        "extraData" => $this->extraDataJson
      ]);
    } catch (\Throwable $e) {
      $this->setResult([
        "response" => null,
        "error" => "Excepción en onRun: " . $e->getMessage(),
        "ownerClass" => $this->ownerClass,
        "ownerMethod" => $this->ownerMethod,
        "extraData" => $this->extraDataJson
      ]);
    }
  }
  
  public function onCompletion(): void {
    $result = $this->getResult();
    $ownerClass = $result["ownerClass"] ?? null;
    $ownerMethod = $result["ownerMethod"] ?? null;
    $extraData = json_decode($result["extraData"] ?? "[]", true);
    if (!$ownerClass || !$ownerMethod) {
      Server::getInstance()->getLogger()->error("SurrealDB: Callback no definido correctamente.");
      return;
    }
    if (!class_exists($ownerClass)) {
      Server::getInstance()->getLogger()->error("SurrealDB: La clase callback '{$ownerClass}' no existe.");
      return;
    }
    if (!method_exists($ownerClass, $ownerMethod)) {
      Server::getInstance()->getLogger()->error("SurrealDB: El método '{$ownerMethod}' no existe en la clase '{$ownerClass}'.");
      return;
    }
    try {
      call_user_func([$ownerClass, $ownerMethod],[
        "response" => $result["response"],
        "error" => $result["error"],
        "extraData" => $extraData
      ]);
    } catch (\Throwable $e) {
      Server::getInstance()->getLogger()->error("SurrealDB: Error ejecutando callback '{$ownerId}::{$ownerMethod}': " . $e->getMessage());
    }
  }
}
