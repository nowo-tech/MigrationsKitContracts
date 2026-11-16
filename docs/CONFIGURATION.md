# Configuration

This package has **no configuration**: no config tree, no environment variables, no services.

What *is* configurable lives in the declaring package and in the checker:

- **Table names** — a declaring package builds each `ExactKeyColumn` from the same configuration its schema is built
  from (table prefix, host-chosen table name). See [Usage](USAGE.md#resolve-table-names-at-runtime).
- **Optional columns** — a column that only exists in an opt-in mode is declared with `$condition` (the setting, as
  text) and `$active` (whether that setting is on in this configuration).
- **The check itself** (connection, ignored columns, output) — configured in
  [`nowo-tech/migrations-kit-bundle`](https://github.com/nowo-tech/MigrationsKitBundle).
