# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/)  
and this project adheres to [Semantic Versioning](https://semver.org/).

## [Unreleased]

## [1.0.0] – 2025‑05‑07
### Added
- Initial release of SurrealAPI for PocketMine-MP.
- Async support using `QueryAsyncTask` to prevent main thread blocking.
- Secure HTTP connection handling with cURL and Basic Auth.
- Query delegation system using callback class/method with `extraData`.
- Full SurrealDB query coverage:
  - `selectData`, `insertData`, `updateData`, `patchData`, `deleteData`, `rawQuery`.
  - Table operations: `createTable`, `dropTable`, `alterTable`.
  - Index operations: `defineIndex`, `removeIndex`.
  - Function definition: `defineFunction`.
  - Transaction handling: `runTransaction`.
  - Database status: `info`, `healthCheck`.
- JSON encoding validation with size and error handling.
- Basic error reporting and logger integration with PocketMine.
- License headers for GPL-3.0 compliance.

### Fixed
- Prevented async thread blocking by handling cURL hangs and exceptions.
- Improved handling of missing or invalid callback targets.
- Fixed silent failure on empty query or malformed data.

### Security
- Added validation for all sensitive credentials passed to `QueryAsyncTask`.
