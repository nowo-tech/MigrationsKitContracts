# Contributing

Thanks for helping. This package is deliberately tiny: **interfaces and immutable value objects only**, with no
dependencies beyond PHP.

## Table of contents

- [What belongs here](#what-belongs-here)
- [Getting started](#getting-started)
- [Quality gates](#quality-gates)
- [Backward compatibility](#backward-compatibility)
- [Commits and pull requests](#commits-and-pull-requests)

## What belongs here

Only what a **declaring** package needs in order to say "these columns of mine are identity keys". Anything that
reads a database, talks to Doctrine or Symfony, or performs the check itself belongs in
[`nowo-tech/migrations-kit-bundle`](https://github.com/nowo-tech/MigrationsKitBundle) instead.

If a change would add a `require` entry, it is almost certainly in the wrong repository.

## Getting started

```bash
composer install
composer qa          # cs-check + phpstan + test
```

## Quality gates

- `composer cs-check` / `composer cs-fix` — PHP-CS-Fixer.
- `composer phpstan` — level 8, empty `ignoreErrors`.
- `composer test` — PHPUnit.
- `composer rector-dry` — Rector, no pending changes.
- `composer validate --strict`.

CI runs the tests on PHP 8.2–8.5 plus PHPStan and the style check; all must pass.

## Backward compatibility

Every class here is public API for packages that may be released independently of this one, so treat it as frozen:

- Adding a method to `ExactKeyColumnsInterface` is a **major** change — every implementer breaks.
- Adding a **required** constructor argument to `ExactKeyColumn` is a major change; adding an optional one at the
  end is a minor.
- Adding a case to `ExactKeyStatus` is a minor change, but a consumer that `match`es exhaustively will throw, so
  say so in `UPGRADING` and in the changelog.
- Removing or renaming anything is a major change.

## Commits and pull requests

- Conventional commit subjects (`feat:`, `fix:`, `docs:`, `refactor:`, `test:`, `chore:`).
- No AI co-author trailers of any kind (REQ-GIT-001).
- One topic per pull request, with the changelog entry in the same pull request.
- `main` is protected: pull request plus green CI, no force-push, no deletion (REQ-GIT-002).
