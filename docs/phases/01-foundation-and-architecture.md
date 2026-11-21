# Phase 1: Foundation & Static Modular Architecture

## Architecture Decision
- **Static Modular Monolith** (no dynamic `module.json` discovery, no runtime module loading).
- All modules live under `modules/<Domain>/` with PSR-4 autoloading `"Modules\\": "modules/"` in `composer.json`.
- Every module Service Provider is explicitly registered in `bootstrap/providers.php`.
- Primary Keys: **ULIDs** everywhere (`HasUlids`).
- Money: Stored as **bigint minor units** (e.g., cents).
- Strict Types: `declare(strict_types=1);` in every PHP file.
- Code Quality: Laravel Pint (`pint.json`) & Larastan Level 8 (`phpstan.neon`).
