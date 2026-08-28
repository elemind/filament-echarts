# Laravel 11 is supported but no longer tested

`filament/filament: ^4.0` still accepts Laravel 11, so the package continues to
*support* it, but the CI matrix starts at Laravel 12. Laravel 11 has reached end
of life and every remaining `11.x` release carries an unresolved security
advisory, so Composer 2.9 (which blocks advised packages by default) can no
longer resolve the row at all — neither `prefer-lowest` nor `prefer-stable`.

## Considered Options

- **Disable the advisory block for the Laravel 11 row** (`composer config policy.advisories.block false`).
  Rejected: it would turn the row green by silencing the only signal it still
  carries, and it would make CI exercise a configuration no user should run.
- **Narrow the Composer constraint so Laravel 11 is neither supported nor tested.**
  Rejected: Filament 4 genuinely works on Laravel 11, and dropping the claim
  would break installs that are fine today.

## Consequences

Supported and tested versions are deliberately not the same set. Do not "fix"
the matrix by adding Laravel 11 back, and do not narrow the Composer constraint
to match the matrix — the gap is the decision.

The same reasoning is why `laravel: 13.* x stability: prefer-lowest` is excluded
from the matrix: the declared floor `^4.0` predates Filament's Laravel 13
support, which lands in Filament 4.9.3 and 5.4.3. Encoding that floor in
`composer.json` would understate the support for Laravel 11 and 12.
