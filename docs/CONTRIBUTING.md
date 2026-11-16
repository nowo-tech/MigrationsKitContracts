# Contributing

Thanks for helping. This package is deliberately tiny: **interfaces and immutable value objects only**, with no
dependencies beyond PHP.

## Table of contents

- [What belongs here](#what-belongs-here)
- [Getting started](#getting-started)
- [Quality gates](#quality-gates)
- [Backward compatibility](#backward-compatibility)
- [Commits and pull requests](#commits-and-pull-requests)
- [Cursor Agent](#cursor-agent)

## What belongs here

Only what a **declaring** package needs in order to say "these columns of mine are identity keys". Anything that
reads a database, talks to Doctrine or Symfony, or performs the check itself belongs in
[`nowo-tech/migrations-kit-bundle`](https://github.com/nowo-tech/MigrationsKitBundle) instead.

If a change would add a `require` entry, it is almost certainly in the wrong repository.

## Getting started

With Docker (recommended, same image for everyone):

```bash
make up              # build the dev container and install dependencies
make setup-hooks     # git hooks: style + PHPStan before commit, strip AI co-author trailers (REQ-MAKE-006)
make qa              # cs-check + phpstan + test
make release-check   # full pre-release chain (REQ-MAKE-002)
make down
```

Without Docker (PHP 8.2+ and Composer on the host):

```bash
composer install
composer qa          # cs-check + phpstan + test
```

## Quality gates

- `composer cs-check` / `composer cs-fix` — PHP-CS-Fixer.
- `composer phpstan` — level 8, empty `ignoreErrors`, FrankenPHP classic + worker rulesets
  (`nowo-tech/phpstan-frankenphp`).
- `composer igor` — Igor worker-state audit.
- `composer test` / `composer test-coverage` — PHPUnit; CI requires 100% lines and methods on PHP 8.2.
- `composer rector-dry` — Rector, no pending changes.
- `composer validate --strict`.

CI runs the tests on PHP 8.2–8.5 (coverage gate on 8.2), PHPStan + Rector + Igor + `composer audit`, the style check
and the git-history check; all must pass. `make release-check` runs the same chain locally.

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

## Cursor Agent

Contributors using Cursor Agent on this repository should enable these user-level settings (REQ-IDE-006):

1. **Submit with Ctrl + Enter** (macOS: **⌘ + Enter**) — Cursor Settings → Agents / Chat → Submit with Ctrl + Enter.
   Enter inserts a newline; Ctrl/⌘+Enter submits the prompt.
2. **Queue follow-ups while the agent is running** — in your user `settings.json` (Linux
   `~/.config/Cursor/User/settings.json`, Windows `%APPDATA%\Cursor\User\settings.json`, macOS
   `~/Library/Application Support/Cursor/User/settings.json`):

```json
{
  "cursor.composer.queueMessageDefaultBehavior": "queue"
}
```

Do not use `"steer"` as the default: it interrupts the running agent turn as soon as the follow-up is sent.

Repository rules for assistants live in `.cursor/rules/`; GitHub Spec Kit skills in `.cursor/skills/speckit-*`
(see [SPEC-KIT.md](SPEC-KIT.md)).
