# Phase 3: RBAC, Organization Hierarchy & Audit Trail

## Module
- `modules/Users/`

## Key Components
- **RBAC**: `Permission` (`module.resource.action`), `Role` (tenant-scoped), `HasPermissions` trait (direct custom grants/denies override roles, per-request cache), `Gate::before` bridge in `UsersServiceProvider`.
- **Organization**: `Department` and hierarchical `Team` with multi-team memberships (`team_members`).
- **Audit Trail**: `AuditLog` (immutable — throws `RuntimeException` on update/delete) + `Auditable` trait hooking `created`, `updated`, `deleted`.
- **Security**: All RBAC, Department, Team, and Audit Log endpoints enforce `can:users.*` middleware.
- **Seeder**: `Modules\Users\Database\Seeders\UsersDatabaseSeeder` (seeds all system permissions, `Super Admin`, `Accountant`, `Sales Manager` roles, demo users, departments, and teams).
