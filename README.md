# ERP Application

A modular Laravel application with multi-tenancy, RBAC, MFA, and audit logging.

## Overview

This project implements a comprehensive Enterprise Resource Planning (ERP) system built on Laravel 11 with a modular architecture. The application is organized into separate modules that can be developed and maintained independently.

## Current Features

### ✅ Core Infrastructure (Completed)

- **Multi-tenancy support** - Tenant resolution middleware and configuration
- **User authentication** - Core authentication system with session management
- **Docker environment** - Containerized development environment
- **CI/CD workflow** - GitHub Actions for testing and deployment

### ✅ Core Module (Completed)

- Tenant model and database migration
- Tenant resolution middleware (`EnsureTenantIsActive`, `ResolveTenantMiddleware`)
- Tenant context service
- BelongsToTenant trait for Eloquent models
- Core routes and service provider

### ✅ Users Module (Completed)

- **User authentication** - Login, logout, registration
- **Multi-factor authentication (MFA)** - TOTP-based setup and verification
- **RBAC (Role-Based Access Control)** - Roles, permissions, and assignments
- **Profile management** - User profile updates
- **Department and team management** - Organizational structure
- **Audit logging** - Complete audit trail of user actions
- **Password and login history** - Tracking and history features

#### Auth Actions:
- `AuthenticateUserAction` - User login
- `ChangePasswordAction` - Password changes
- `ConfirmMfaSetupAction` - MFA setup completion
- `EnableMfaAction` - MFA enablement
- `RevokeSessionAction` - Session revocation
- `VerifyMfaTotpAction` - TOTP verification
- `VerifyMfaRequest` - MFA verification request
- `LoginRequest` - Login validation
- `RegisterRequest` - Registration validation

#### Controllers:
- `AuthController` - Authentication endpoints
- `MfaController` - MFA management
- `ProfileController` - Profile updates
- `RoleController` - Role management
- `OrganizationController` - Organization management
- `SessionController` - Session management
- `AuditLogController` - Audit log viewing

#### Models:
- `User` - Extended with tenant and profile capabilities
- `Role` - RBAC role management
- `Permission` - Permission granularity
- `Team` - Team grouping
- `Department` - Department structure
- `AuditLog` - Audit trail entries
- `LoginHistory` - Login tracking
- `PasswordHistory` - Password change history
- `UserMfaSecret` - TOTP secret storage
- `UserActiveSession` - Active session tracking

#### Database Migrations:
- Users table with tenant and profile fields
- RBAC tables (roles, permissions, model_has_roles, model_has_permissions, role_has_permissions)
- MFA tables (mfa_secrets, login_histories, password_histories, user_sessions)
- Organizations tables
- Audit logs table
- Login and password history tables
- User sessions table

#### Services:
- `TotpService` - TOTP generation and verification

#### Routes:
- `modules/Users/routes/api.php` - API routes
- `modules/Users/routes/web.php` - Web routes

#### Service Provider:
- `UsersServiceProvider` - Module registration

#### Traits:
- `HasPermissions` - Permission checking trait
- `Auditable` - Audit logging trait

#### HTTP Controllers and Requests:
- Full set of controllers for authentication, MFA, profile, roles, organizations, sessions, and audit logs
- Form requests for validation

### 🔄 Next Steps

1. **Complete remaining module implementations**
   - Additional feature modules (Finance, HR, Inventory, etc.)

2. **Enhance existing features**
   - Advanced RBAC permissions
   - MFA backup codes
   - Remember me functionality enhancements
   - Profile picture/upload support

3. **Testing and QA**
   - Write comprehensive test coverage
   - Implement feature tests for authentication flows
   - Add integration tests for multi-tenancy

4. **Documentation**
   - API documentation
   - Installation and setup guide
   - Deployment instructions

## Getting Started

```bash
# Install dependencies
composer install
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Run migrations
php artisan migrate

# Start development server
php artisan serve
```

## Architecture

The application uses a **module-based architecture** where features are organized into separate modules under the `modules/` directory. The `Core` module provides foundational tenancy and authentication features, while the `Users` module extends these with full user management capabilities.

Each module follows Laravel conventions and can be independently developed, tested, and deployed.