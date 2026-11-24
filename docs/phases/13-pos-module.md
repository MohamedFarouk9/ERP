# Phase 13: Wave 2 — Point of Sale (POS) Module (`modules/Pos/`)

## Summary
Phase 13 completes **Wave 2** by introducing a full **Point of Sale (`modules/Pos/`)** module for retail register operations, shift cash management, instant ticket checkout, printable receipts, and live POS analytics.

## Key Components
- **Service Provider**: `Modules\Pos\Providers\PosServiceProvider` explicitly registered in `bootstrap/providers.php`.
- **Database Tables (1 migration = 1 table, ULIDs, `bigint` cents)**:
  - `pos_shifts` (`2024_01_01_000039_create_pos_shifts_table.php`)
  - `pos_sales` (`2024_01_01_000040_create_pos_sales_table.php`)
  - `pos_sale_items` (`2024_01_01_000041_create_pos_sale_items_table.php`)
- **Cross-Module Integration**:
  - `Modules\Pos\Events\PosSaleCompleted` dispatched inside `CompletePosSaleAction`.
  - `Modules\Inventory\Listeners\DeductStockOnPosSaleCompleted` registered in `InventoryServiceProvider` to automatically deduct stock from the active shift's warehouse.
- **Actions & Form Requests**:
  - `OpenPosShiftAction` / `OpenPosShiftRequest`
  - `ClosePosShiftAction` / `ClosePosShiftRequest`
  - `CompletePosSaleAction` / `CompletePosSaleRequest`
- **Controllers & Routes (`admin/pos/*`)**:
  - `PosDashboardController`: `admin.pos.dashboard` (`/admin/pos/dashboard`)
  - `PosTerminalController`: `admin.pos.terminal`, `admin.pos.checkout`, `admin.pos.receipt`
  - `PosShiftController`: `admin.pos.shifts`, `admin.pos.shifts.data`, `admin.pos.sales.data`, `admin.pos.shifts.open`, `admin.pos.shifts.close`
- **Feature Flag & Permissions**:
  - Feature key: `pos` (`Point of Sale (POS)`), enabled by default in `CoreDatabaseSeeder`.
  - Permissions: `pos.terminal.view`, `pos.sales.create`, `pos.shifts.view`, `pos.shifts.manage`.
- **Rukada UI Views (`resources/views/admin/pos/`)**:
  - `dashboard.blade.php` (KPI cards + ApexCharts daily sales & payment mix + top items & recent receipts)
  - `terminal.blade.php` (live product search/category filter, interactive cart, tax/discount/tendered calculation, shift modal)
  - `receipt.blade.php` (printable thermal-style receipt)
  - `shifts.blade.php` (Server-Side DataTables for register shifts & sales history + open/close shift modals)
