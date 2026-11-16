## AI contribution guidelines (Nowo PHP library)

Use this when suggesting code, tests, documentation, or CI changes for this repository.

### Scope

- This is a **plain PHP library** (`nowo-tech/migrations-kit-contracts`, contracts only) published on Packagist. It has **no Symfony dependency**.
- Respect the **PHP** and runtime dependency ranges declared in `composer.json`.
- Do not introduce `doctrine/annotations`; use PHP 8 attributes where metadata is needed.

### Code

- Follow **PSR-12** and project conventions in `.php-cs-fixer.dist.php`.
- Use **strict comparison** (`===`).
- No static or cross-request state: the library must work in FrankenPHP worker mode with kernel reset off.
- Keep changes **minimal**; align with `composer cs-check`, `composer phpstan`, `composer igor` and `composer test`.

### Documentation

- User-facing documentation is **English** under `docs/`. Only `README.md` (and `CODE_OF_CONDUCT.md`) at the root.
- Update `docs/CHANGELOG.md` (and `docs/UPGRADING.md` when integrators must act) in the same pull request.

### Tests

- Add or update tests for new behaviour; CI requires 100% line and method coverage.

## Commits

- Never add AI co-author trailers: no `Co-authored-by: Cursor` / `cursoragent@cursor.com`, no `Co-Authored-By: Claude …` / `noreply@anthropic.com` (REQ-GIT-001).
