# 🚀 LibSurrealDB

[![License](https://img.shields.io/badge/License-GPL--3.0-blue.svg)](LICENSE)
[![PHP](https://img.shields.io/badge/PHP-8.0%2B-purple.svg)](https://www.php.net/)
[![PocketMine-MP](https://img.shields.io/badge/PocketMine--MP-5.0%2B-orange.svg)](https://pmmp.io/)

## 📋 Description

LibSurrealDB is a powerful library for integrating [SurrealDB](https://surrealdb.com/) into your PocketMine-MP plugins. It provides a simple and asynchronous interface to interact with SurrealDB databases, allowing you to perform CRUD operations and advanced queries without blocking the server's main thread.

## ✨ Features

- 🔄 Fully asynchronous operations
- 🛠️ Intuitive and easy-to-use API
- 📊 Support for all CRUD operations
- 🔍 Native SQL queries
- 📝 Transactions
- 🧩 Definable indexes and functions
- 🔒 Schema management

## 📥 Installation

1. Download the latest version of the plugin
2. Place it in the `plugins` folder of your PocketMine-MP server
3. Restart the server
4. Configure the connection details in `config.yml`

## ⚙️ Configuration

```yaml
# LibSurrealDB Configuration

# SurrealDB server URL
endpoint: "http://localhost:8000/sql"

# SurrealDB namespace
namespace: "test"

# SurrealDB database
database: "test"

# Access credentials
username: "root"
password: "root"
```

## 🚀 Basic Usage

### Initialization

The plugin initializes automatically when loaded. To use LibSurrealDB in your plugin:

```php
use natha\surrealdb\SurrealAPI;

// You don't need to manually initialize, the plugin does it for you
$queryManager = SurrealAPI::queries();
```

### Usage Examples

#### Creating a table

```php
SurrealAPI::queries()->createTable(
    "users", 
    "YourPlugin", 
    "onComplete",
    "SCHEMALESS"
);

// With callback
public static function onComplete(array $result): void {
    if (empty($result["error"])) {
        echo "Table created successfully!";
    } else {
        echo "Error: " . $result["error"];
    }
}
```

#### Inserting data

```php
SurrealAPI::queries()->insertData(
    "users", 
    [
        "id" => "user:123",
        "name" => "John",
        "age" => 25,
        "roles" => ["admin", "moderator"]
    ],
    "YourPlugin", 
    "onInsert"
);

// Callback
public static function onInsert(array $result): void {
    $rows = $result["response"][0]["result"] ?? [];
    if (!empty($rows)) {
        echo "User inserted with ID: " . $rows[0]["id"];
    }
}
```

#### Querying data

```php
SurrealAPI::queries()->selectData(
    "users", 
    "YourPlugin", 
    "onSelect",
    "WHERE age > 18"
);

// Callback
public static function onSelect(array $result): void {
    $users = $result["response"][0]["result"] ?? [];
    foreach ($users as $user) {
        echo "Name: " . $user["name"] . "\n";
    }
}
```

#### Updating data

```php
SurrealAPI::queries()->update(
    "users", 
    ["age" => 26], 
    "WHERE id = 'user:123'",
    "YourPlugin", 
    "onUpdate"
);
```

#### Deleting data

```php
SurrealAPI::queries()->delete(
    "users", 
    "WHERE id = 'user:123'",
    "YourPlugin", 
    "onDelete"
);
```

## 🔄 Promise Management

LibSurrealDB includes a `PromiseHandler` class to facilitate response handling:

```php
use natha\surrealdb\utils\PromiseHandler;

public static function handleQueryResponse(array $result): void {
    PromiseHandler::handle(
        $result,
        function(array $rows, array $extra) {
            // Success
            echo "Data received: " . count($rows);
        },
        function(string $error, array $extra) {
            // Error
            echo "Error: " . $error;
        }
    );
}
```

## 🛠️ Advanced Functions

### Defining indexes

```php
SurrealAPI::queries()->defineIndex(
    "users", 
    "email", 
    "idx_email", 
    "YourPlugin", 
    "onDefineIndex"
);
```

### Running transactions

```php
SurrealAPI::queries()->runTransaction(
    [
        "UPDATE users SET credits = credits - 100 WHERE id = 'user:123';",
        "UPDATE store SET stock = stock - 1 WHERE id = 'item:456';"
    ],
    "YourPlugin", 
    "onTransaction"
);
```

### Native SQL queries

```php
SurrealAPI::queries()->raw(
    "SELECT * FROM users WHERE age > 18 ORDER BY name LIMIT 10",
    "YourPlugin", 
    "onRawQuery"
);
```

## 📝 API Documentation

### SurrealAPI Class
- `init(SurrealProvider $provider): void` - Initializes the API
- `provider(): SurrealProvider` - Gets the SurrealDB provider
- `queries(): QueryManager` - Gets the query manager

### QueryManager Class
- `createTable(string $tableName, string $callbackClass, string $callbackMethod, string $schema = "SCHEMALESS", array $extraData = []): void`
- `insertData(string $tableName, array $data, string $callbackClass, string $callbackMethod, array $extraData = []): void`
- `selectData(string $tableName, string $callbackClass, string $callbackMethod, string $conditions = "", array $extraData = []): void`
- `dropTable(string $table, string $callbackClass, string $callbackMethod, array $extraData = []): void`
- `alterTable(string $table, string $schema, string $callbackClass, string $callbackMethod, array $extraData = []): void`
- `update(string $table, array $data, string $conditions, string $callbackClass, string $callbackMethod, array $extraData = []): void`
- `patch(string $table, array $patch, string $conditions, string $callbackClass, string $callbackMethod, array $extraData = []): void`
- `delete(string $table, string $conditions, string $callbackClass, string $callbackMethod, array $extraData = []): void`
- `count(string $table, string $conditions, string $callbackClass, string $callbackMethod, array $extraData = []): void`
- `raw(string $sql, string $callbackClass, string $callbackMethod, array $extraData = []): void`
- `info(string $callbackClass, string $callbackMethod, array $extraData = []): void`
- `health(string $callbackClass, string $callbackMethod, array $extraData = []): void`
- `defineIndex(string $table, string $field, string $indexName, string $callbackClass, string $callbackMethod, array $extraData = []): void`
- `removeIndex(string $indexName, string $callbackClass, string $callbackMethod, array $extraData = []): void`
- `defineFunction(string $name, string $parameters, string $body, string $callbackClass, string $callbackMethod, array $extraData = []): void`
- `runTransaction(array $queries, string $callbackClass, string $callbackMethod, array $extraData = []): void`

## 🤝 Contributing

Contributions are welcome! If you'd like to contribute to LibSurrealDB, please visit the [GitHub repository](https://github.com/NathaLMV/LibSurrealDB).

Feel free to:
- Report bugs
- Suggest new features
- Submit pull requests
- Improve documentation

Before contributing, please check the existing issues and PRs to avoid duplicates.

## 📄 License

This project is licensed under the [GNU General Public License v3.0](LICENSE) - see the LICENSE file for details.

## 📞 Contact

For questions or support, please open an issue in the [GitHub repository](https://github.com/NathaLMV/LibSurrealDB).

---

## 🧾 Changelog

### v1.0.0 - 2025-05-06

-Full rewrite of the asynchronous task system using `AsyncTask` to avoid `asyncWorker` crashes.

- Implementation of `PromiseHandler` for structured management of SurrealDB responses.

- Incorporation of `SurrealQueryTask` to execute HTTP queries without blocking the main thread.

- Improvements in `QueryManager` to delegate operations in a modular way.

- New class `SurrealProvider` to manage configuration and connection with the server.

- Support for multiple advanced operations (transactions, indexes, functions, native SQL queries).

- Removal of the direct use of `file_get_contents()` or cURL from the main thread.

⭐️ Don't forget to star the [repository](https://github.com/NathaLMV/LibSurrealDB) if you found it useful! ⭐️
