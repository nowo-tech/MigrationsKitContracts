# Migrations Kit Contracts

[![CI](https://github.com/nowo-tech/MigrationsKitContracts/actions/workflows/ci.yml/badge.svg)](https://github.com/nowo-tech/MigrationsKitContracts/actions/workflows/ci.yml) [![Packagist Version](https://img.shields.io/packagist/v/nowo-tech/migrations-kit-contracts.svg?style=flat)](https://packagist.org/packages/nowo-tech/migrations-kit-contracts) [![Packagist Downloads](https://img.shields.io/packagist/dt/nowo-tech/migrations-kit-contracts.svg)](https://packagist.org/packages/nowo-tech/migrations-kit-contracts) [![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE) [![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?logo=php)](https://php.net) [![GitHub stars](https://img.shields.io/github/stars/nowo-tech/MigrationsKitContracts.svg?style=social&label=Star)](https://github.com/nowo-tech/MigrationsKitContracts) [![Coverage](https://img.shields.io/badge/Coverage-100%25-brightgreen)](#tests-and-coverage)

> ⭐ **Found this useful?** Install from [Packagist](https://packagist.org/packages/nowo-tech/migrations-kit-contracts) and give the repo a [star on GitHub](https://github.com/nowo-tech/MigrationsKitContracts) if it helps you.

Contracts a package implements to **declare its identity and access-key columns**, so that a host can assert they
are in a binary **NO PAD** collation (REQ-SEC-006). Interfaces and value objects only — **no dependencies beyond
PHP**, no Doctrine, no Symfony.

The check that consumes these declarations ships in
[`nowo-tech/migrations-kit-bundle`](https://github.com/nowo-tech/MigrationsKitBundle) as
`nowo:migrations:check-key-collations`. A declaring bundle requires **this** package; only the host (or the
bundle's own test suite) needs the bundle.

![FrankenPHP Friendly Worker Mode](docs/images/frankenphp-friendly.png)

This bundle is **FrankenPHP worker mode friendly**.

## Installation

```bash
composer require nowo-tech/migrations-kit-contracts
```

PHP 8.2 to 8.5, no other dependency. Details: [Installation](docs/INSTALLATION.md).

## Why a positive list

An identity or access key — a user id, a tenant key, a token, a share slug, an API key hash — is only ever compared
with `=`. It must live in a binary NO PAD collation:

| Server | Correct collation |
|--------|-------------------|
| MySQL 8.0.17+ and 9.x | `utf8mb4_0900_bin` |
| MariaDB (10.2+) | `utf8mb4_nopad_bin` |
| MySQL 5.7, MySQL 8.0.0–8.0.16 | `utf8mb4_bin` — the documented fallback; binary but **PAD SPACE**, so `'abc' = 'abc   '` |

The server default a column with no `COLLATE` inherits (`utf8mb4_0900_ai_ci` on MySQL 8+) folds case, accents **and**
trailing spaces, so two different users can own the same identity key.

Scanning for "bad" collations only finds columns somebody already wrote a collation for. **A column nobody ever
thought about produces no hit at all** — which is exactly the dangerous case. So the list is positive: a column is
checked once a package declares it, and declaring it is what forces the collation decision.

## Declaring columns

```php
use Nowo\MigrationsKitContracts\Collation\ExactKeyColumn;
use Nowo\MigrationsKitContracts\Collation\ExactKeyColumnsInterface;

final readonly class WorkflowExactKeyColumns implements ExactKeyColumnsInterface
{
    public function __construct(private TableNamePrefixer $prefixer, private bool $ownerTracking) {}

    public function exactKeyColumns(): array
    {
        return [
            // Resolve the table name the way the schema does: never a literal, or a host with a prefix
            // or a renamed table gets a false failure.
            new ExactKeyColumn($this->prefixer->applyPrefix('workflow_definition'), 'tenant_key',
                'nowo-tech/workflow-bundle', 'tenant key, compared with ='),

            // A column that only exists in an opt-in mode: declared inactive, reported as skipped, never failed.
            new ExactKeyColumn($this->prefixer->applyPrefix('workflow_owner_audit'), 'owner_id',
                'nowo-tech/workflow-bundle', 'host user id',
                'nowo_workflow.owner_tracking: true', $this->ownerTracking),
        ];
    }
}
```

In a Symfony application, MigrationsKitBundle tags every service implementing `ExactKeyColumnsInterface` by
autoconfiguration, so a host registers nothing and one command asserts every installed package's declaration at
once.

Three rules that matter:

- **Resolve the table name at runtime.** Build the declaration from the same configuration the schema is built from
  (prefix, host-chosen table name). A literal breaks on exactly the hosts that rename things.
- **Declare, do not omit, a column of a mode that is off.** Pass the setting as `$condition` and `false` as
  `$active`: the check then reports it as skipped rather than failing it — while a declared **active** column whose
  table is absent *is* a failure, because that is a rename or a drop.
- `exactKeyColumns()` must not touch the database and must be safe to call repeatedly (FrankenPHP worker mode).

## What is here

| Class | Role |
|-------|------|
| `Collation\ExactKeyColumnsInterface` | The port: `exactKeyColumns(): list<ExactKeyColumn>` |
| `Collation\ExactKeyColumn` | One declared column: table, column, owner, purpose, condition, active |
| `Collation\ExactKeyColumns` | A fixed list, for a package whose table names do not depend on configuration |
| `Collation\ExactKeyStatus` | The outcome of checking one column: `ok`, `fallback`, `fail`, `missing`, `skipped`, `ignored` |

## Documentation

- [Installation](docs/INSTALLATION.md)
- [Configuration](docs/CONFIGURATION.md)
- [Usage](docs/USAGE.md)
- [Contributing](docs/CONTRIBUTING.md)
- [Code of Conduct](CODE_OF_CONDUCT.md)
- [Changelog](docs/CHANGELOG.md)
- [Upgrading](docs/UPGRADING.md)
- [Release](docs/RELEASE.md)
- [Security](docs/SECURITY.md)
- [Engram](docs/ENGRAM.md)
- [Spec-driven development](docs/SPEC-DRIVEN-DEVELOPMENT.md)
- [GitHub Spec Kit](docs/SPEC-KIT.md)

### Additional documentation

- [PSR evaluation (REQ-CS-007)](docs/PSR.md)

## Tests and coverage

- **Tests:** PHPUnit (`tests/Unit`); no integration tests (the package has no I/O).
- **PHP (lines):** **100%** of `src/` (CI gate: 100% lines and methods on PHP 8.2; locally `make test-coverage`
  prints **Global PHP coverage (Lines)**).
- **TS/JS:** N/A
- **Python:** N/A

## License

MIT. See [LICENSE](LICENSE).
