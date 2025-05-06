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
    $response = $result['response'] ?? null;
    $error = $result['error'] ?? '';
    $extra = $result['extraData'] ?? [];
    if ($response === null) {
      $onError('Empty response received.', $extra);
      return;
    }
    if (!empty($error)) {
      $onError($error, $extra);
      return;
    }
    $decoded = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
      $onError("Error decoding JSON: " . json_last_error_msg(), $extra);
      return;
    }
    $rows = $decoded[0]['result'] ?? [];
    $onSuccess($rows, $extra);
  }
}
