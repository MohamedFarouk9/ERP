@extends('admin.layouts.app')
@section('title', 'Create Order')

@section('content')
<div class="page-wrapper">
    <div class="page-content">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Create New Order</h4>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Back to Orders</a>
        </div>

        @include('admin.components.flash-messages')

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">New Order</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.orders.store') }}" method="POST">
                            @csrf

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Customer <span class="text-danger">*</span></label>
                                    <select name="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
                                        <option value="">{{ __('Select Customer') }}</option>
                                        @foreach(\App\Models\User::where('role', 'user')->get() as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Order Number <span class="text-danger">*</span></label>
                                    <input type="text" name="order_number" class="form-control @error('order_number') is-invalid @enderror" value="{{ old('order_number') }}" placeholder="ORD-001" required>
                                    @error('order_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Order Status <span class="text-danger">*</span></label>
                                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                        <option value="">{{ __('Select Status') }}</option>
                                        <option value="pending" {{ old('status') === 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                                        <option value="processing" {{ old('status') === 'processing' ? 'selected' : '' }}>{{ __('Processing') }}</option>
                                        <option value="shipped" {{ old('status') === 'shipped' ? 'selected' : '' }}>{{ __('Shipped') }}</option>
                                        <option value="delivered" {{ old('status') === 'delivered' ? 'selected' : '' }}>{{ __('Delivered') }}</option>
                                        <option value="cancelled" {{ old('status') === 'cancelled' ? 'selected' : '' }}>{{ __('Cancelled') }}</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Total Amount <span class="text-danger">*</span></label>
                                    <input type="number" name="total_amount" class="form-control @error('total_amount') is-invalid @enderror" value="{{ old('total_amount') }}" step="0.01" placeholder="0.00" required>
                                    @error('total_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                                    <select name="payment_method" class="form-select @error('payment_method') is-invalid @enderror" required>
                                        <option value="">{{ __('Select Payment Method') }}</option>
                                        <option value="credit_card" {{ old('payment_method') === 'credit_card' ? 'selected' : '' }}>{{ __('Credit Card') }}</option>
                                        <option value="paypal" {{ old('payment_method') === 'paypal' ? 'selected' : '' }}>{{ __('PayPal') }}</option>
                                        <option value="bank_transfer" {{ old('payment_method') === 'bank_transfer' ? 'selected' : '' }}>{{ __('Bank Transfer') }}</option>
                                        <option value="cash" {{ old('payment_method') === 'cash' ? 'selected' : '' }}>{{ __('Cash') }}</option>
                                    </select>
                                    @error('payment_method')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Payment Status <span class="text-danger">*</span></label>
                                    <select name="payment_status" class="form-select @error('payment_status') is-invalid @enderror" required>
                                        <option value="">{{ __('Select Payment Status') }}</option>
                                        <option value="pending" {{ old('payment_status') === 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                                        <option value="paid" {{ old('payment_status') === 'paid' ? 'selected' : '' }}>{{ __('Paid') }}</option>
                                        <option value="failed" {{ old('payment_status') === 'failed' ? 'selected' : '' }}>{{ __('Failed') }}</option>
                                        <option value="refunded" {{ old('payment_status') === 'refunded' ? 'selected' : '' }}>{{ __('Refunded') }}</option>
                                    </select>
                                    @error('payment_status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Shipping Address <span class="text-danger">*</span></label>
                                <textarea name="shipping_address" class="form-control @error('shipping_address') is-invalid @enderror" rows="3" placeholder="Enter shipping address" required>{{ old('shipping_address') }}</textarea>
                                @error('shipping_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Billing Address <span class="text-danger">*</span></label>
                                <textarea name="billing_address" class="form-control @error('billing_address') is-invalid @enderror" rows="3" placeholder="Enter billing address" required>{{ old('billing_address') }}</textarea>
                                @error('billing_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">{{ __('Notes') }}</label>
                                <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3" placeholder="Add any notes for this order">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">{{ __('Create Order') }}</button>
                                <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
