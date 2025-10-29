@extends('layouts.admin')

@section('title', 'Edit Order')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Edit Order #{{ $order->order_number }}</h1>
            <p class="text-muted">Update order status and details</p>
        </div>
        <div>
            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Order
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Order Details</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.orders.update', $order) }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Order Status</label>
                                <select name="status" id="status" class="form-select" required>
                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                    <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                                @error('status')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="payment_status" class="form-label">Payment Status</label>
                                <select name="payment_status" id="payment_status" class="form-select" required>
                                    <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                                    <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>Failed</option>
                                    <option value="refunded" {{ $order->payment_status == 'refunded' ? 'selected' : '' }}>Refunded</option>
                                </select>
                                @error('payment_status')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Order Notes</label>
                            <textarea name="notes" id="notes" class="form-control" rows="4" placeholder="Add any notes about this order...">{{ old('notes', $order->notes) }}</textarea>
                            @error('notes')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Order
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Order Summary -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Order Summary</h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <strong>Order #:</strong> {{ $order->order_number }}
                    </div>
                    <div class="mb-2">
                        <strong>Customer:</strong> {{ $order->user->name }}
                    </div>
                    <div class="mb-2">
                        <strong>Date:</strong> {{ $order->created_at->format('M d, Y H:i') }}
                    </div>
                    <div class="mb-2">
                        <strong>Items:</strong> {{ $order->orderItems->count() }}
                    </div>
                    <div class="mb-2">
                        <strong>Total:</strong> ₹{{ number_format($order->total_amount, 2) }}
                    </div>
                </div>
            </div>

            <!-- Current Status -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Current Status</h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <strong>Order Status:</strong>
                        <span class="badge bg-{{ $order->status == 'pending' ? 'warning' : ($order->status == 'processing' ? 'info' : ($order->status == 'shipped' ? 'primary' : ($order->status == 'delivered' ? 'success' : 'danger'))) }} ms-2">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                    <div class="mb-2">
                        <strong>Payment Status:</strong>
                        <span class="badge bg-{{ $order->payment_status == 'paid' ? 'success' : 'danger' }} ms-2">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>
                    @if($order->shipped_at)
                        <div class="mb-2">
                            <strong>Shipped:</strong> {{ $order->shipped_at->format('M d, Y H:i') }}
                        </div>
                    @endif
                    @if($order->delivered_at)
                        <div class="mb-2">
                            <strong>Delivered:</strong> {{ $order->delivered_at->format('M d, Y H:i') }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Order Items -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Order Items</h6>
                </div>
                <div class="card-body">
                    @foreach($order->orderItems as $item)
                        <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                            <div>
                                <strong>{{ $item->product_name }}</strong>
                                <br><small class="text-muted">Qty: {{ $item->quantity }}</small>
                            </div>
                            <div class="text-end">
                                ₹{{ number_format($item->total_price, 2) }}
                            </div>
                        </div>
                    @endforeach
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <strong>Total:</strong>
                        <strong>₹{{ number_format($order->total_amount, 2) }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
