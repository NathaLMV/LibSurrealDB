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

namespace natha\surrealdb;

use natha\surrealdb\provider\SurrealProvider;
use natha\surrealdb\query\QueryManager;

class SurrealAPI {
  
  private static ?SurrealProvider $provider = null;
  private static ?QueryManager $queryManager = null;
  
  public static function init(SurrealProvider $provider): void {
    self::$provider = $provider;
    self::$queryManager = new QueryManager(self::$provider);
  }
  
  public static function provider(): SurrealProvider {
    if (self::$provider === null) {
      throw new \RuntimeException("SurrealDB not initialized. Call SurrealDB::init() first.");
    }
    return self::$provider;
  }

  public static function queries(): QueryManager {
    if (self::$queryManager === null) {
      throw new \RuntimeException("QueryManager not initialized. Call SurrealDB::init() first.");
    }
    return self::$queryManager;
  }
}
