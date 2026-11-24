# Phase 8: Payments Collection & Concurrency-Safe Refunds (`modules/Payments/`)

## 1. How This Benefits Your Business (Owner's Perspective)

Issuing an invoice is only half the battle — cash in the bank keeps the business alive. The Payments module tracks how money comes in and protects you from refund errors or fraud:

1. **Payment Methods (`payment_methods`)**:
   Configurable tender channels (`Cash`, `Bank Transfer`, `Credit Card`, etc.), optionally mapped directly to a specific `Account` (`account_id`) in Accounting so bank deposits hit the right GL account automatically.
2. **Invoice Payments (`payments`)**:
   Staff record partial or full payments (`RecordPaymentAction` / `CompletePaymentAction`) linked to an invoice and customer. Completing a payment fires `PaymentCompleted`, triggering **two automatic cross-module actions**:
   - **Sales**: `UpdateInvoiceOnPaymentCompleted` adds the payment to `invoice.amount_paid` and marks the invoice `paid` once the balance hits zero.
   - **Accounting**: `RecordPaymentLedgerEntry` debits the Payment Method's Cash/Bank account (`account_id` or `1010`) and credits Accounts Receivable (`1200`).
3. **Double-Refund Prevention (`refunds`)**:
   What happens if two support agents try to refund the same `$500` payment at the exact same second? `CreateRefundAction` locks the `Payment` row (`lockForUpdate()`) inside `DB::transaction`, sums prior completed refunds, and rejects any refund exceeding the remaining refundable balance.

---

## 2. Payments & Refunds Diagrams

### Entity-Relationship Diagram
```mermaid
erDiagram
    PAYMENT_METHODS ||--o{ PAYMENTS : processes
    INVOICES ||--o{ PAYMENTS : paid_by
    CUSTOMERS ||--o{ PAYMENTS : pays
    PAYMENTS ||--o{ REFUNDS : reversed_by
```

### Payment Completion & Concurrency-Safe Refund Sequences
```mermaid
sequenceDiagram
    actor Cashier as "Finance Staff"
    participant PayAct as "CompletePaymentAction / RecordPaymentAction"
    participant SalesL as "Sales: UpdateInvoiceOnPaymentCompleted"
    participant AccL as "Accounting: RecordPaymentLedgerEntry"

    Cashier->>PayAct: "Record Payment on Invoice"
    PayAct->>PayAct: "Save Payment (status = completed) & Dispatch PaymentCompleted"
    par "Cross-Module Automation"
        PayAct->>SalesL: "Lock Invoice -> amount_paid += amount -> status = paid if settled"
    and
        PayAct->>AccL: "Create & Post Journal Entry (Dr Cash/Bank / Cr 1200 AR)"
    end
```

```mermaid
sequenceDiagram
    actor Staff as "Refund Clerk"
    participant RefAct as "CreateRefundAction"
    participant Pay as "Payment Row"
    participant Ref as "Refund Row"

    Staff->>RefAct: "POST Refund (payment_id, amount)"
    RefAct->>Pay: "DB::transaction -> SELECT ... FOR UPDATE (lockForUpdate)"
    RefAct->>Ref: "Sum existing completed refunds on payment"
    alt "alreadyRefunded + refundAmount > payment.amount"
        RefAct-->>Staff: "Throw InvalidArgumentException (Exceeds refundable balance)"
    else "Within Balance"
        RefAct->>Ref: "Create Refund (status = completed)"
        RefAct->>Pay: "If fully refunded -> status = refunded"
        RefAct-->>Staff: "Dispatch RefundCompleted Event"
    end
```

---

## 3. How the Code Is Connected End-to-End

| Layer | Exact Files | Responsibility |
| :--- | :--- | :--- |
| Provider | `modules/Payments/Providers/PaymentsServiceProvider.php` | Loads `api.php` (`/api/v1/payments/*`), `web.php`, and migrations |
| Migrations | `2024_01_01_000027` (`payment_methods`), `000028` (`payments`), `000029` (`refunds`) | 3 single-table migrations |
| Models | `PaymentMethod`, `Payment`, `Refund` | Include `amount`, status constants (`completed`, `refunded`, etc.), `BelongsToTenant`, `Auditable` |
| Events | `Events/PaymentCompleted`, `Events/RefundCompleted` | Consumed by `Sales` (updates invoice balance) and `Accounting` (posts Cash/AR ledger) |
| Actions | `CreatePaymentMethodAction`, `UpdatePaymentMethodAction`, `RecordPaymentAction`, `CompletePaymentAction`, `CreateRefundAction` | Enforces unique payment/refund numbers per tenant, row-level locking on refunds |
| Form Requests | `StorePaymentMethodRequest`, `UpdatePaymentMethodRequest`, `StorePaymentRequest`, `StoreRefundRequest` | Validates positive minor-unit amounts, dates, and payment ownership |
| Controllers | `PaymentMethodController`, `PaymentController`, `RefundController` | DataTables JSON endpoints, create/complete/refund actions |
| Blade UI | `resources/views/admin/payments/{index,refunds}.blade.php` | Payments & Payment Methods screen, Refunds tracking table |
| Seeder | `modules/Payments/Database/Seeders/PaymentsDatabaseSeeder.php` | Seeds Cash, Bank Transfer, and Card methods plus initial payments |
