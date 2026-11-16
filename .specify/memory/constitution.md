# Migrations Kit Contracts Constitution

## Core Principles

### I. Documented integrator contract
Product behavior lives in `specs/001-baseline/spec.md`, `docs/SPEC-DRIVEN-DEVELOPMENT.md`, and integrator docs (`USAGE.md`, `CONFIGURATION.md`).

### II. Spec-first, test-proven
PHPUnit and PHPStan are the mechanical proof. Behavioral changes require tests; CI requires 100% line and method coverage.

### III. 100% code inventory traceability
Every production file under `src/` must appear in `specs/001-baseline/code-inventory.md`. New files require spec updates in the same PR.

### IV. Cursor + Spec Kit
GitHub Spec Kit is initialized with **Cursor Agent** (`cursor-agent`). Skills live in `.cursor/skills/speckit-*`.

### V. Plain PHP library
Follow the declared PHP range in `composer.json` (`>=8.2 <8.6`). No Symfony dependency; FrankenPHP worker mode with kernel reset off must keep working (no static or cross-request state).

## Governance
Amendments update this file, the baseline spec when principles affect behavior, and `CHANGELOG.md` when consumer-visible.

**Version**: 1.0.0 | **Ratified**: 2026-11-16 | **Last Amended**: 2026-11-16
