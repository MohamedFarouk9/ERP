@extends('admin.layouts.app')
@section('title', __('Print Settings'))

@section('content')
<div class="page-wrapper">
    <div class="page-content">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h4 class="mb-0"><i class="bx bx-printer me-1 text-primary"></i> {{ __('Print Settings') }}</h4>
        </div>

        <div class="row g-3">
            <div class="col-12 col-lg-7">
                <div class="card radius-10">
                    <div class="card-body">
                        <form class="ajax-form" method="POST" action="{{ route('admin.pos.print-settings.update') }}">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">{{ __('Printer Type') }} *</label>
                                    <select name="printer_type" class="form-select" required>
                                        <option value="thermal_80mm" @selected($settings->printer_type === 'thermal_80mm')>Thermal 80mm</option>
                                        <option value="thermal_58mm" @selected($settings->printer_type === 'thermal_58mm')>Thermal 58mm</option>
                                        <option value="a4" @selected($settings->printer_type === 'a4')>A4 Standard</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">{{ __('Barcode Type') }} *</label>
                                    <select name="barcode_type" class="form-select" required>
                                        <option value="CODE128" @selected($settings->barcode_type === 'CODE128')>Code 128</option>
                                        <option value="CODE39" @selected($settings->barcode_type === 'CODE39')>Code 39</option>
                                        <option value="EAN13" @selected($settings->barcode_type === 'EAN13')>EAN-13</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">{{ __('Paper Size') }} *</label>
                                    <select name="label_paper_size" class="form-select" required>
                                        <option value="A4" @selected($settings->label_paper_size === 'A4')>A4</option>
                                        <option value="thermal_80mm" @selected($settings->label_paper_size === 'thermal_80mm')>Thermal 80mm</option>
                                        <option value="thermal_58mm" @selected($settings->label_paper_size === 'thermal_58mm')>Thermal 58mm</option>
                                        <option value="roll_50x30" @selected($settings->label_paper_size === 'roll_50x30')>Label Roll 50x30mm</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">{{ __('Store Name') }}</label>
                                    <input type="text" name="store_name" class="form-control" value="{{ $settings->store_name ?? $tenant->name }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">{{ __('Phone') }}</label>
                                    <input type="text" name="store_phone" class="form-control" value="{{ $settings->store_phone }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">{{ __('Tax Number') }}</label>
                                    <input type="text" name="tax_number" class="form-control" value="{{ $settings->tax_number }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">{{ __('Address') }}</label>
                                    <input type="text" name="store_address" class="form-control" value="{{ $settings->store_address }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">{{ __('Footer Message') }}</label>
                                    <input type="text" name="footer_message" class="form-control" value="{{ $settings->footer_message }}">
                                </div>

                                <div class="col-12"><hr class="my-1"></div>

                                <div class="col-md-6">
                                    <div class="form-check form-switch">
                                        <input type="hidden" name="auto_print_receipt" value="0">
                                        <input class="form-check-input" type="checkbox" name="auto_print_receipt" value="1" id="ps_auto" @checked($settings->auto_print_receipt)>
                                        <label class="form-check-label" for="ps_auto">{{ __('Auto-Print Receipt') }}</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-switch">
                                        <input type="hidden" name="show_barcode_on_receipt" value="0">
                                        <input class="form-check-input" type="checkbox" name="show_barcode_on_receipt" value="1" id="ps_bc" @checked($settings->show_barcode_on_receipt)>
                                        <label class="form-check-label" for="ps_bc">{{ __('Show Barcode on Receipt') }}</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-switch">
                                        <input type="hidden" name="show_cashier" value="0">
                                        <input class="form-check-input" type="checkbox" name="show_cashier" value="1" id="ps_cashier" @checked($settings->show_cashier)>
                                        <label class="form-check-label" for="ps_cashier">{{ __('Show Cashier') }}</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-switch">
                                        <input type="hidden" name="show_customer" value="0">
                                        <input class="form-check-input" type="checkbox" name="show_customer" value="1" id="ps_cust" @checked($settings->show_customer)>
                                        <label class="form-check-label" for="ps_cust">{{ __('Show Customer') }}</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-switch">
                                        <input type="hidden" name="show_tax_breakdown" value="0">
                                        <input class="form-check-input" type="checkbox" name="show_tax_breakdown" value="1" id="ps_tax" @checked($settings->show_tax_breakdown)>
                                        <label class="form-check-label" for="ps_tax">{{ __('Show Tax Breakdown') }}</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-switch">
                                        <input type="hidden" name="show_logo" value="0">
                                        <input class="form-check-input" type="checkbox" name="show_logo" value="1" id="ps_logo" @checked($settings->show_logo)>
                                        <label class="form-check-label" for="ps_logo">{{ __('Show Logo') }}</label>
                                    </div>
                                </div>

                                <div class="col-12 mt-3">
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="bx bx-save me-1"></i> {{ __('Save Changes') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-5">
                <div class="card radius-10">
                    <div class="card-header"><h6 class="mb-0">{{ __('Receipt Preview') }}</h6></div>
                    <div class="card-body">
                        <div class="border rounded p-3 mx-auto" style="max-width: 320px;">
                            <div class="text-center border-bottom pb-2 mb-2">
                                <h6 class="fw-bold mb-0">{{ $settings->store_name ?? $tenant->name }}</h6>
                                @if($settings->store_address)<div class="small text-muted">{{ $settings->store_address }}</div>@endif
                                @if($settings->store_phone)<div class="small text-muted">{{ $settings->store_phone }}</div>@endif
                                @if($settings->tax_number)<div class="small text-muted">{{ __('Tax Number') }}: {{ $settings->tax_number }}</div>@endif
                            </div>
                            <div class="small d-flex justify-content-between"><span>{{ __('Receipt #') }}</span><strong>RCP-SAMPLE-01</strong></div>
                            @if($settings->show_cashier)<div class="small d-flex justify-content-between"><span>{{ __('Cashier') }}</span><span>Admin</span></div>@endif
                            @if($settings->show_customer)<div class="small d-flex justify-content-between"><span>{{ __('Customer') }}</span><span>{{ __('Walk-in Customer') }}</span></div>@endif
                            <hr class="my-2">
                            <div class="small d-flex justify-content-between"><span>Sample Item x1</span><span>$100.00</span></div>
                            @if($settings->show_tax_breakdown)<div class="small d-flex justify-content-between text-muted"><span>{{ __('Tax') }}</span><span>$10.00</span></div>@endif
                            <div class="fw-bold d-flex justify-content-between border-top pt-2 mt-2"><span>{{ __('Total') }}</span><span>$110.00</span></div>
                            <div class="text-center small text-muted border-top pt-2 mt-3">{{ $settings->footer_message }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
