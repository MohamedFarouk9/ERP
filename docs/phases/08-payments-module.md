# Phase 8: Payments Module

## Module
- `modules/Payments/`

## Database Tables (1 migration = 1 table)
- `payment_methods`
- `payments`
- `refunds`

## Domain Events & Security
- Cross-module communication via Domain Events: `PaymentCompleted`, `RefundCompleted`.
- Concurrency-safe refunds: `CreateRefundAction` locks the `Payment` row (`lockForUpdate()`) inside `DB::transaction` and prevents refunding more than the remaining payment balance.
- Dedicated Form Requests: `StorePaymentMethodRequest`, `StorePaymentRequest`, `StoreRefundRequest`.
- RBAC permissions: `payments.methods.*`, `payments.transactions.*`, `payments.refunds.*` + `feature:payments` middleware.
- **Seeder**: `Modules\Payments\Database\Seeders\PaymentsDatabaseSeeder`.
