# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## Table of contents

- [[Unreleased]](#unreleased)
- [[1.0.0] - 2026-11-13](#100---2026-11-13)

## [Unreleased]

### Changed

- No change under `src/`: repository tooling and documentation aligned with the nowo-tech package requirements
  (REQ compliance sweep 2026-11-16).
- Development: Docker image + `docker-compose.yml`, root `Makefile` (`release-check`, `setup-hooks`, `igor`, …),
  git hooks; PHPStan now includes the `nowo-tech/phpstan-frankenphp` classic and worker rulesets; Igor worker-state
  audit; CI adds a 100% line / method coverage gate on PHP 8.2, Rector, Igor, `composer audit` and a git-history
  check; unit tests moved to `tests/Unit/`.
- `composer.json`: `archive.exclude`, `support.docs`.

### Documentation

- `docs/INSTALLATION.md`, `CONFIGURATION.md`, `USAGE.md`, `UPGRADING.md`, `RELEASE.md`, `ENGRAM.md`, `PSR.md`,
  `SPEC-DRIVEN-DEVELOPMENT.md`, `SPEC-KIT.md`; GitHub Spec Kit baseline (`specs/001-baseline/`, 4/4 files mapped);
  release security checklist (12.4.1) in `SECURITY.md`; canonical README (badges, documentation list, tests and
  coverage).
- `.github/`: issue / pull request templates, `CODEOWNERS`, `SECURITY.md`, Copilot instructions, Dependabot,
  release, release-sync, PR-title lint and stale workflows. `.cursor/` rules and Engram MCP config.

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

### Documentation

- `README`, `docs/CONTRIBUTING.md`, `docs/SECURITY.md` and `CODE_OF_CONDUCT.md` (REQ-DOCS-003). CONTRIBUTING states
  the backward-compatibility rules that matter for a contracts package: adding a method to the interface, or a
  required constructor argument to the value object, is a major change.
