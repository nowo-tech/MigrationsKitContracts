# Usage

## Table of contents

- [Declare the columns of a package](#declare-the-columns-of-a-package)
- [Resolve table names at runtime](#resolve-table-names-at-runtime)
- [Columns of an optional mode](#columns-of-an-optional-mode)
- [A fixed list](#a-fixed-list)
- [Statuses](#statuses)
- [Standard Symfony host and DDD / hexagonal host](#standard-symfony-host-and-ddd--hexagonal-host)
- [FrankenPHP worker mode](#frankenphp-worker-mode)

## Declare the columns of a package

Implement `Collation\ExactKeyColumnsInterface` on a small service of the package that owns the tables:

```php
use Nowo\MigrationsKitContracts\Collation\ExactKeyColumn;
use Nowo\MigrationsKitContracts\Collation\ExactKeyColumnsInterface;

final readonly class WorkflowExactKeyColumns implements ExactKeyColumnsInterface
{
    public function __construct(private TableNamePrefixer $prefixer, private bool $ownerTracking) {}

    public function exactKeyColumns(): array
    {
        return [
            new ExactKeyColumn($this->prefixer->applyPrefix('workflow_definition'), 'tenant_key',
                'nowo-tech/workflow-bundle', 'tenant key, compared with ='),
            new ExactKeyColumn($this->prefixer->applyPrefix('workflow_owner_audit'), 'owner_id',
                'nowo-tech/workflow-bundle', 'host user id',
                'nowo_workflow.owner_tracking: true', $this->ownerTracking),
        ];
    }
}
```

`ExactKeyColumn` rejects an empty (or whitespace-only) `table`, `column` or `owner` with an
`InvalidArgumentException`. `key()` returns the lower-cased `table.column` the checker matches `information_schema`
rows on; `describe()` returns it as declared.

## Resolve table names at runtime

Never declare a literal table name when the host can rename or prefix the table: build it from the same configuration
the schema uses. A literal makes the check report a false failure on exactly the hosts that rename things.

## Columns of an optional mode

Declare, do not omit, a column of a mode that is off: pass the setting as `$condition` and `false` as `$active`. The
checker reports it as `skipped`. A declared **active** column whose table or column is absent is `missing`, which
fails: that is a rename or a drop, not a disabled feature.

## A fixed list

A package whose table names never depend on configuration can register `Collation\ExactKeyColumns`:

```php
new ExactKeyColumns(
    new ExactKeyColumn('acme_api_key', 'key_hash', 'acme/api-bundle', 'API key hash'),
);
```

## Statuses

`Collation\ExactKeyStatus` is what the checker reports per column: `ok`, `fallback` (binary but PAD SPACE, on servers
without a NO PAD collation), `fail`, `missing`, `skipped`, `ignored`. `isFailure()` is true for `fail` and `missing`
only.

## Standard Symfony host and DDD / hexagonal host

- **Standard Symfony host:** install MigrationsKitBundle; every service implementing the interface is tagged by
  autoconfiguration and `nowo:migrations:check-key-collations` checks all installed declarations at once.
- **DDD / hexagonal host:** the contracts are plain PHP (interface, readonly value objects, enum) with no Doctrine or
  Symfony types, so a domain or infrastructure layer can implement or consume them without a framework dependency.

## FrankenPHP worker mode

`exactKeyColumns()` must not query the database and must be safe to call more than once per process. The value
objects are immutable and the package keeps no static state, so nothing leaks between requests when the kernel is
not reset.
