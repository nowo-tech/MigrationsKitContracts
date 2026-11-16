# PHP-FIG PSR evaluation (REQ-CS-007)

Package: `nowo-tech/migrations-kit-contracts` (`library`, contracts only)

This document records which [PHP-FIG PSRs](https://www.php-fig.org/psr/) apply to this package.

## Table of contents

- [Baseline (always)](#baseline-always)
- [Interface / contract PSRs](#interface--contract-psrs)
- [Summary](#summary)

## Baseline (always)

| PSR | Decision | How |
| --- | -------- | --- |
| PSR-12 (coding style) | **Adopted** | `@PSR12` in `.php-cs-fixer.dist.php` (REQ-CS-001). |
| PSR-4 (autoloading) | **Adopted** | `composer.json` PSR-4 map for `src/` and `tests/`. |

## Interface / contract PSRs

| PSR | Decision | Notes |
| --- | -------- | ----- |
| PSR-3 Logger | **N/A** | Nothing is logged; value objects only. |
| PSR-6 / PSR-16 Cache | **N/A** | No cache. |
| PSR-7 / PSR-17 / PSR-18 HTTP | **N/A** | No HTTP. |
| PSR-11 Container | **N/A** | No container; MigrationsKitBundle discovers implementations by autoconfiguration. |
| PSR-14 Event dispatcher | **N/A** | No events. |
| PSR-15 Middleware | **N/A** | No HTTP pipeline. |
| PSR-20 Clock | **N/A** | No time-dependent logic. |

## Summary

- **Adopted beyond baseline:** none. The package must keep `require` to PHP only.
- Re-evaluate if the contracts ever gain logging, caching or event concerns (they should not).

---

_REQ-CS-007 evaluation date: 2026-11-16._
