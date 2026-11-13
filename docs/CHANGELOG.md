# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## Table of contents

- [[Unreleased]](#unreleased)
- [[1.0.0] - 2026-11-13](#100---2026-11-13)

## [Unreleased]

## [1.0.0] - 2026-11-13

### Added

- **Identity / access-key column declaration (REQ-SEC-006).** Extracted from
  `nowo-tech/migrations-kit-bundle` so that a declaring package can require contracts with no dependencies beyond
  PHP, instead of implementing an interface from a package it does not depend on.
  - `Collation\ExactKeyColumnsInterface` — `exactKeyColumns(): list<ExactKeyColumn>`.
  - `Collation\ExactKeyColumn` — one declared column: the table **resolved at runtime** (prefixes, host-chosen
    names), the column, the owning package, a one-line purpose, and an optional `$condition` / `$active` for a
    column that only exists in an opt-in mode.
  - `Collation\ExactKeyColumns` — a fixed list, for a package whose table names do not depend on configuration.
  - `Collation\ExactKeyStatus` — `ok`, `fallback`, `fail`, `missing`, `skipped`, `ignored`; `isFailure()` is true for
    `fail` and `missing` only.

  The check that consumes the declarations is `nowo:migrations:check-key-collations` in
  `nowo-tech/migrations-kit-bundle` 2.5.0.
