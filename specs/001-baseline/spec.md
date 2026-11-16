# Feature Specification: Migrations Kit Contracts baseline (100% code coverage)

**Feature Branch**: `001-baseline`  
**Status**: Active  

**Package**: `nowo-tech/migrations-kit-contracts`  
**Code inventory**: [`code-inventory.md`](code-inventory.md)

---

## Summary

Contracts-only library: a package **declares** the identity and access-key columns of its own tables so that a host
(through `nowo-tech/migrations-kit-bundle`, `nowo:migrations:check-key-collations`) can assert they live in a binary
NO PAD collation (REQ-SEC-006). Interface, readonly value objects and an enum; no dependencies beyond PHP.

---

## User Scenarios

### US-01 — Declare key columns (P1)

**Given** a package that owns tables with identity keys, **When** it implements `ExactKeyColumnsInterface`, **Then**
`exactKeyColumns()` returns one `ExactKeyColumn` per column, with table names resolved from the package configuration.

### US-02 — Optional modes (P1)

**Given** a column that only exists when a setting is on, **When** it is declared with `$condition` and
`$active = false`, **Then** the checker reports it as skipped, never as a failure.

### US-03 — Fixed list (P2)

**Given** a package whose table names never change, **When** it registers `ExactKeyColumns(...)`, **Then** the list
is returned unchanged.

### US-04 — Outcome semantics (P1)

**Given** a checked column, **When** the checker reports an `ExactKeyStatus`, **Then** only `fail` and `missing`
count as failures.

---

## Requirements

### Collation contracts

- **FR-COL-001**: `ExactKeyColumnsInterface::exactKeyColumns(): list<ExactKeyColumn>` — must not query the database
  and must be repeatable (FrankenPHP worker mode).
- **FR-COL-002**: `ExactKeyColumn` — readonly table, column, owner, purpose, condition, active; rejects an empty or
  whitespace-only table, column or owner (`InvalidArgumentException`); `key()` is the lower-cased `table.column`,
  `describe()` the declared form.
- **FR-COL-003**: `ExactKeyColumns` — immutable fixed list implementing the interface (re-indexed `list`).
- **FR-COL-004**: `ExactKeyStatus` — `ok`, `fallback`, `fail`, `missing`, `skipped`, `ignored`; `isFailure()` true
  for `fail` and `missing` only.

---

## Success Criteria

- **SC-001**: **4/4** `src/` files mapped.
- **SC-002**: PHPUnit covers 100% of lines and methods (CI gate).

---

## Explicit non-goals

- Reading the database or performing the collation check (MigrationsKitBundle does).
- Any Doctrine or Symfony dependency.

---

## Validation

PHPUnit (coverage gate), PHPStan level 8 with FrankenPHP rulesets, Igor, inventory audit.
