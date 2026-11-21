# Phase 6: Inventory Module

## Module
- `modules/Inventory/`

## Database Tables (1 migration = 1 table)
- `categories`
- `warehouses`
- `products`
- `stock_levels`
- `stock_movements` (immutable ledger)

## Domain Invariants & Security
- `StockMovement` throws `RuntimeException` on update/delete.
- `RecordStockMovementAction` runs inside `DB::transaction` with `lockForUpdate()` and blocks negative stock.
- Dedicated Form Requests: `StoreCategoryRequest`, `StoreWarehouseRequest`, `StoreProductRequest`, `RecordStockMovementRequest`.
- RBAC permissions: `inventory.categories.*`, `inventory.warehouses.*`, `inventory.products.*`, `inventory.stock-movements.*` + `feature:inventory` middleware.
- **Seeder**: `Modules\Inventory\Database\Seeders\InventoryDatabaseSeeder`.
