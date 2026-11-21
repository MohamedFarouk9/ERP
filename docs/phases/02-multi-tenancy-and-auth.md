# Phase 2: Multi-Tenancy, Auth, MFA & Sessions

## Modules
- `modules/Core/`
- `modules/Users/`

## Key Components
- **Tenancy**: `Tenant` model, `TenantContext` singleton, `BelongsToTenant` global scope trait, `ResolveTenantMiddleware` (`tenant`), `EnsureTenantIsActive` (`tenant.active`).
- **Authentication**: `RegisterUserAction`, `AuthenticateUserAction` (5-attempt / 15-min lockout), `ChangePasswordAction` (5-password reuse prevention).
- **MFA**: `TotpService` (RFC 6238), `EnableMfaAction`, `ConfirmMfaSetupAction`, `VerifyMfaTotpAction`, recovery codes.
- **Sessions**: `UserActiveSession`, `SessionController`, `RevokeSessionAction`.
- **Security**: Public auth endpoints (`/register`, `/login`, `/login/mfa`) protected by rate limiting (`throttle:10,1`).
- **Seeder**: `Modules\Core\Database\Seeders\CoreDatabaseSeeder`.
