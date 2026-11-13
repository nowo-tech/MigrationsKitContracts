# Migrations Kit Contracts

[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)
[![PHP](https://img.shields.io/badge/php-%3E%3D8.2-777bb3.svg)](https://www.php.net/)

Contracts a package implements to **declare its identity and access-key columns**, so that a host can assert they
are in a binary **NO PAD** collation (REQ-SEC-006). Interfaces and value objects only — **no dependencies beyond
PHP**, no Doctrine, no Symfony.

The check that consumes these declarations ships in
[`nowo-tech/migrations-kit-bundle`](https://github.com/nowo-tech/MigrationsKitBundle) as
`nowo:migrations:check-key-collations`. A declaring bundle requires **this** package; only the host (or the
bundle's own test suite) needs the bundle.

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

## Installation

```bash
composer require nowo-tech/migrations-kit-contracts
```

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

- [Changelog](docs/CHANGELOG.md)
- [Contributing](docs/CONTRIBUTING.md)
- [Security](docs/SECURITY.md)
- [Code of Conduct](CODE_OF_CONDUCT.md)

## License

MIT. See [LICENSE](LICENSE).
