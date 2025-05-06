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

namespace natha\surrealdb\utils;

class PromiseHandler {
  
  public static function handle(array $result, callable $onSuccess, callable $onError): void {
    $resp  = $result['response']   ?? null;
    $extra = $result['extraData']  ?? [];
    if (!is_array($resp) || !isset($resp[0]['status'])) {
      $onError("Invalid response format", $extra);
      return;
    }
    $status = $resp[0]['status'];
    if ($status !== "OK") {
      $message = is_scalar($resp[0]['result']) ? $resp[0]['result'] : json_encode($resp[0]['result']);
      $onError($message, $extra);
      return;
    }
    $rows = $resp[0]['result'] ?? [];
    $onSuccess($rows, $extra);
  }
}
