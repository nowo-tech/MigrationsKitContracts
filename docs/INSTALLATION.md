# Installation

## Table of contents

- [Requirements](#requirements)
- [Install](#install)
- [Who installs what](#who-installs-what)

## Requirements

- PHP **8.2** to **8.5** (`>=8.2 <8.6`).
- No other runtime dependency: no Doctrine, no Symfony.

## Install

```bash
composer require nowo-tech/migrations-kit-contracts
```

## Who installs what

| Package | Requires |
|---------|----------|
| A bundle or library that **declares** its identity / access-key columns | `nowo-tech/migrations-kit-contracts` (this package) |
| The **host** application, or the declaring bundle's own test suite, that **checks** the declarations | [`nowo-tech/migrations-kit-bundle`](https://github.com/nowo-tech/MigrationsKitBundle) (`nowo:migrations:check-key-collations`) |

A declaring package never needs the bundle at runtime. Nothing has to be registered: in a Symfony application,
MigrationsKitBundle autoconfigures every service implementing `ExactKeyColumnsInterface`.

Next: [Usage](USAGE.md).
