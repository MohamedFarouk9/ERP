<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->invoice_number }} - {{ $tenant->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print { .no-print { display: none !important; } body { background: #fff !important; } }
    </style>
</head>
<body class="bg-light py-4">
<div class="container bg-white p-5 rounded shadow-sm" style="max-width: 900px;">
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h2 class="fw-bold mb-1">{{ $tenant->name }}</h2>
            <p class="text-muted mb-0">Official Commercial Invoice</p>
        </div>
        <div class="text-end">
            <h4 class="mb-1">{{ $invoice->invoice_number }}</h4>
            <span class="badge bg-dark text-uppercase">{{ $invoice->status }}</span>
            <div class="mt-2 no-print">
                <button onclick="window.print()" class="btn btn-sm btn-primary">Print / Save PDF</button>
                <button onclick="window.close()" class="btn btn-sm btn-outline-secondary">Close</button>
            </div>
        </div>
    </div>

    <hr>

    <div class="row mb-4">
        <div class="col-6">
            <h6 class="text-muted text-uppercase small">Billed To</h6>
            <strong>{{ $invoice->customer?->name }}</strong><br>
            <span>{{ $invoice->customer?->billing_address }}</span><br>
            <span>{{ $invoice->customer?->email }} {{ $invoice->customer?->phone }}</span>
        </div>
        <div class="col-6 text-end">
            <div><strong>Invoice Date:</strong> {{ $invoice->invoice_date?->format('Y-m-d') }}</div>
            <div><strong>Due Date:</strong> {{ $invoice->due_date?->format('Y-m-d') }}</div>
            @if($invoice->salesOrder)
                <div><strong>Sales Order:</strong> {{ $invoice->salesOrder->order_number }}</div>
            @endif
        </div>
    </div>

    <table class="table table-bordered align-middle mb-4">
        <thead class="table-light">
            <tr>
                <th>{{ __('Description') }}</th>
                <th class="text-end">{{ __('Qty') }}</th>
                <th class="text-end">{{ __('Unit Price') }}</th>
                <th class="text-end">{{ __('Tax') }}</th>
                <th class="text-end">{{ __('Line Total') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
                <tr>
                    <td>{{ $item->description }}</td>
                    <td class="text-end">{{ $item->quantity }}</td>
                    <td class="text-end">${{ number_format($item->unit_price / 100, 2) }}</td>
                    <td class="text-end">${{ number_format($item->tax_amount / 100, 2) }}</td>
                    <td class="text-end">${{ number_format($item->line_total / 100, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="row justify-content-end">
        <div class="col-md-5">
            <table class="table table-sm">
                <tr><td>Subtotal</td><td class="text-end">${{ number_format($invoice->subtotal / 100, 2) }}</td></tr>
                <tr><td>Tax</td><td class="text-end">${{ number_format($invoice->tax_amount / 100, 2) }}</td></tr>
                <tr class="fw-bold fs-5"><td>Total ({{ $invoice->currency_code }})</td><td class="text-end">${{ number_format($invoice->total_amount / 100, 2) }}</td></tr>
                <tr class="text-success"><td>Amount Paid</td><td class="text-end">-${{ number_format($invoice->amount_paid / 100, 2) }}</td></tr>
                <tr class="fw-bold table-light"><td>Balance Due</td><td class="text-end">${{ number_format(max(0, $invoice->total_amount - $invoice->amount_paid) / 100, 2) }}</td></tr>
            </table>
        </div>
    </div>

    @if($invoice->notes)
        <div class="mt-4 text-muted small"><strong>Notes:</strong> {{ $invoice->notes }}</div>
    @endif
</div>
</body>
</html>
