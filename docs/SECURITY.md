# Security

Security posture of `nowo-tech/migrations-kit-contracts`.

## Table of contents

- [Scope](#scope)
- [Reporting a vulnerability](#reporting-a-vulnerability)
- [Attack surface](#attack-surface)
- [What the contracts are for](#what-the-contracts-are-for)
- [Dependencies and update policy](#dependencies-and-update-policy)

## Scope

This package contains **interfaces and immutable value objects only**: `ExactKeyColumnsInterface`,
`ExactKeyColumn`, `ExactKeyColumns` and `ExactKeyStatus`. It has no services, no I/O, no database access, no file
access, no network access, no configuration and no runtime dependencies beyond PHP itself.

## Reporting a vulnerability

Report privately to <hectorfranco@nowo.tech>. Do not open a public issue for an unfixed vulnerability.

## Attack surface

Effectively none. The only executable code is a constructor that rejects empty `table`, `column` and `owner`
arguments, a `strtolower()` key accessor and string concatenation for display. Nothing here parses untrusted input,
builds SQL, or touches a resource.

A consumer of these contracts does build SQL from the declared names; that responsibility, and the identifier
quoting that goes with it, belongs to the consumer (`nowo-tech/migrations-kit-bundle`), not here. Declared table and
column names come from the declaring package's own configuration and mapping, not from end-user input.

## What the contracts are for

They exist to make a security property **checkable**: that every column an application compares with `=` as an
identity or access key (a user id, a tenant key, a token, an API key hash) lives in a binary **NO PAD** collation
(REQ-SEC-006).

Under a PAD SPACE collation `'abc' = 'abc   '`, and under the MySQL 8+ server default `utf8mb4_0900_ai_ci` case and
accents fold too, so two different principals can own the same key. The practical consequences are cross-tenant
reads, one person's data in another person's subject access export, and an erasure request deleting the wrong rows.

The declaration is a **positive list** on purpose: a column nobody ever gave a `COLLATE` to produces no grep hit and
silently inherits the server default, so only a declaration can catch it. A package that declares nothing is not
protected by this mechanism — declaring a column is what forces the collation decision.

## Dependencies and update policy

`require` is `php: >=8.2 <8.6` and nothing else, so there is no third-party supply chain to track. Development
tooling (PHPUnit, PHPStan, PHP-CS-Fixer, Rector) is `require-dev` and never ships: `.gitattributes` marks
`tests/`, `.github/` and every tooling config `export-ignore`, so the distributed archive contains only `src/`,
`LICENSE`, `README.md`, `composer.json` and `docs/CHANGELOG.md`.
