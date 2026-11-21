# Phase 9: Admin Dashboard Blade Integration

## Overview
Integrated the Rukada Bootstrap 5 admin dashboard (`resources/views/admin/`) with live multi-tenant ERP data, RBAC `@can` permissions, and `FeatureManager` module flags.

## Controllers (`app/Http/Controllers/Admin/`)
- `AuthController` (`showLogin`, `login`, `logout`) + `AdminLoginRequest`
- `DashboardController` (live tenant KPIs: orders, revenue, payments, products, low-stock alerts, accounts, transactions)
- `FeatureFlagController` (view & toggle module flags per tenant)
- `AccountingWebController` (accounts, transactions)
- `InventoryWebController` (products, categories, warehouses)
- `SalesWebController` (customers, sales orders, invoices)
- `PaymentWebController` (payments, payment methods, refunds)
- `AccessControlWebController` (roles, permissions, immutable audit logs)

## Web Routes & Security (`routes/web.php`)
- All admin routes require `['auth', 'tenant', 'tenant.active']`.
- Module routes enforce `feature:<module>` middleware and `can:<module>.<resource>.<action>` RBAC permissions.
- Sidebar (`resources/views/admin/layouts/sidebar.blade.php`) dynamically hides/shows modules based on both `FeatureManager::isEnabled()` and `@can(...)`.
