# Phase 4: Tenant Feature Flags System

## Module
- `modules/Core/`

## Key Components
- **Table**: `tenant_features` (`tenant_id`, `feature_slug`, `is_enabled`, `metadata`).
- **Model**: `TenantFeature` (`HasUlids`, `BelongsToTenant`).
- **Service**: `FeatureManager` singleton with per-request caching (`isEnabled`, `enable`, `disable`, `all`, `flushCache`).
- **Middleware**: `EnsureFeatureEnabled` aliased as `'feature'` in `bootstrap/app.php`.
- **Security**: Feature administration routes (`/api/v1/core/features/*`) require `can:core.features.view` and `can:core.features.manage`.
