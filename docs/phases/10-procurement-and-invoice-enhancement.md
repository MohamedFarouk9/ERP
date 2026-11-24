# Phase 10: Wave 2 — Procurement Module & Complete Invoice Service

## 1. Procurement Module (`modules/Procurement/`)
- **Tables (1 migration per file)**: `suppliers`, `purchase_orders`, `purchase_order_items`, `vendor_bills`.
- **Cross-Module Automation**: `PurchaseOrderReceived` domain event &rarr; `AddStockOnPurchaseOrderReceived` listener in Inventory auto-records Stock IN movements.
- **Dashboards & Full CRUD**:
  - `ProcurementDashboardController` (`/admin/procurement/dashboard`)
  - `SupplierController` (Server-Side DataTables + Create/Update/Delete via `CreateSupplierAction` & `UpdateSupplierAction`)
  - `PurchaseOrderController` (Server-Side DataTables + Multi-Item PO Builder + Receive Goods)
  - `VendorBillController` (Server-Side DataTables + Record, Approve & Mark Paid)
- **Feature Flag & RBAC**: Gated by `feature:procurement` and `procurement.*` permissions; seeded via `ProcurementDatabaseSeeder`.

## 2. Complete Invoice Service Enhancements (`modules/Sales/`)
- **Status Filter Tabs**: All / Draft / Issued / Paid / Overdue / Cancelled on Server-Side DataTables.
- **Generate Invoice from Sales Order**: 1-click conversion from Confirmed Sales Orders.
- **Print / Save PDF View**: Dedicated print-ready layout (`admin/sales/invoice-print.blade.php`).
- **Quick Payment Collection**: Record payments directly from Issued/Overdue invoices with pre-filled balance due.
- **Cancel Unpaid Invoice** & **Balance Due** column.
