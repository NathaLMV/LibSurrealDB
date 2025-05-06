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
use pocketmine\plugin\PluginBase;

class Loader extends PluginBase {
  
  protected function onLoad(): void {
    $this->saveDefaultConfig();
    $config = $this->getConfig();
    SurrealAPI::init(new SurrealProvider(
      $config->get("endpoint"),
      $config->get("namespace"),
      $config->get("database"),
      $config->get("username"),
      $config->get("password")
    ));
  }
  
  protected function onEnable(): void {
    $this->getLogger()->info("SurrealAPI has been successfully enabled.");
  }
  
  protected function onDisable(): void {
    $this->getLogger()->info("SurrealAPI has been disabled.");
  }
}
