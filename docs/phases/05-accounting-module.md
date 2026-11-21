# Phase 5: Accounting Module

## Module
- `modules/Accounting/`

## Database Tables (1 migration = 1 table)
- `currencies`
- `financial_years`
- `financial_periods`
- `accounts`
- `accounting_transactions`
- `accounting_transaction_lines`

## Domain Invariants & Security
- Double-entry balance invariant: `Total Debits === Total Credits` (`> 0`).
- Cannot post to closed `FinancialPeriod` or non-draft transactions.
- Cross-tenant IDOR protection: `CreateAccountingTransactionAction` validates all `account_id`s belong to the active tenant.
- Dedicated Form Requests: `StoreAccountRequest`, `StoreAccountingTransactionRequest`.
- RBAC permissions: `accounting.accounts.*`, `accounting.transactions.*` + `feature:accounting` middleware.
- **Seeder**: `Modules\Accounting\Database\Seeders\AccountingDatabaseSeeder`.
