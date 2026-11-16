# Upgrading

## Table of contents

- [Unreleased](#unreleased)
- [1.0.0](#100)

## Unreleased

Nothing to do: tooling, documentation and test layout only (no change under `src/`).

## 1.0.0

First release. The contracts were extracted from `nowo-tech/migrations-kit-bundle` 2.5.0: a declaring package now
requires `nowo-tech/migrations-kit-contracts` and implements `Nowo\MigrationsKitContracts\Collation\ExactKeyColumnsInterface`.

Backward-compatibility rules for this package are listed in [CONTRIBUTING](CONTRIBUTING.md#backward-compatibility).
