# Phase 7: Sales Module

## Module
- `modules/Sales/`

## Database Tables (1 migration = 1 table)
- `customers`
- `sales_orders`
- `sales_order_items`
- `invoices`
- `invoice_items`

## Domain Events & Security
- Cross-module communication via Domain Events: `SalesOrderConfirmed`, `InvoiceIssued`.
- Monetary totals (`subtotal`, `tax_amount`, `discount_amount`, `total_amount`) calculated server-side in integer minor units.
- Dedicated Form Requests: `StoreCustomerRequest`, `StoreSalesOrderRequest`, `StoreInvoiceRequest`.
- RBAC permissions: `sales.customers.*`, `sales.orders.*`, `sales.invoices.*` + `feature:sales` middleware.
- **Seeder**: `Modules\Sales\Database\Seeders\SalesDatabaseSeeder`.
