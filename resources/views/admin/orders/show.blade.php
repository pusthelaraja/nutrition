@extends('layouts.admin')

@section('title', 'Order Details')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Order #{{ $order->order_number }}</h1>
            <p class="text-muted">Order placed on {{ $order->created_at->format('M d, Y H:i') }}</p>
        </div>
        <div>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Orders
            </a>
            <a href="{{ route('admin.orders.edit', $order) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Edit Order
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Order Details -->
        <div class="col-lg-8">
            <!-- Order Status -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Order Status</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Order Status:</strong>
                            <span class="badge bg-{{ $order->status == 'pending' ? 'warning' : ($order->status == 'processing' ? 'info' : ($order->status == 'shipped' ? 'primary' : ($order->status == 'delivered' ? 'success' : 'danger'))) }} ms-2">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                        <div class="col-md-6">
                            <strong>Payment Status:</strong>
                            <span class="badge bg-{{ $order->payment_status == 'paid' ? 'success' : 'danger' }} ms-2">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </div>
                    </div>
                    @if($order->shipped_at)
                        <div class="mt-2">
                            <strong>Shipped:</strong> {{ $order->shipped_at->format('M d, Y H:i') }}
                        </div>
                    @endif
                    @if($order->delivered_at)
                        <div class="mt-2">
                            <strong>Delivered:</strong> {{ $order->delivered_at->format('M d, Y H:i') }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Order Items -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Order Items</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>SKU</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderItems as $item)
                                    <tr>
                                        <td>
                                            <div>
                                                <strong>{{ $item->product_name }}</strong>
                                                @if($item->product_attributes)
                                                    <br><small class="text-muted">{{ json_encode($item->product_attributes) }}</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>{{ $item->product_sku }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>₹{{ number_format($item->unit_price, 2) }}</td>
                                        <td>₹{{ number_format($item->total_price, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4">Subtotal:</th>
                                    <th>₹{{ number_format($order->subtotal, 2) }}</th>
                                </tr>
                                @if($order->tax_amount > 0)
                                    <tr>
                                        <th colspan="4">Tax:</th>
                                        <th>₹{{ number_format($order->tax_amount, 2) }}</th>
                                    </tr>
                                @endif
                                @if($order->shipping_amount > 0)
                                    <tr>
                                        <th colspan="4">Shipping:</th>
                                        <th>₹{{ number_format($order->shipping_amount, 2) }}</th>
                                    </tr>
                                @endif
                                <tr class="table-active">
                                    <th colspan="4">Total:</th>
                                    <th>₹{{ number_format($order->total_amount, 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Razorpay Details -->
            @if($order->razorpay_order_id)
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Payment Details</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Razorpay Order ID:</strong><br>
                                <code>{{ $order->razorpay_order_id }}</code>
                            </div>
                            @if($order->razorpay_payment_id)
                                <div class="col-md-6">
                                    <strong>Payment ID:</strong><br>
                                    <code>{{ $order->razorpay_payment_id }}</code>
                                </div>
                            @endif
                        </div>
                        @if($order->razorpay_status)
                            <div class="mt-2">
                                <strong>Payment Status:</strong>
                                <span class="badge bg-{{ $order->razorpay_status == 'captured' ? 'success' : 'warning' }} ms-2">
                                    {{ ucfirst($order->razorpay_status) }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- DTDC Tracking -->
            @if($order->dtdc_awb_number)
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Shipping Details</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>AWB Number:</strong><br>
                                <code>{{ $order->dtdc_awb_number }}</code>
                            </div>
                            @if($order->dtdc_consignment_number)
                                <div class="col-md-6">
                                    <strong>Consignment:</strong><br>
                                    <code>{{ $order->dtdc_consignment_number }}</code>
                                </div>
                            @endif
                        </div>
                        @if($order->dtdc_status)
                            <div class="mt-2">
                                <strong>Shipping Status:</strong>
                                <span class="badge bg-info ms-2">{{ ucfirst($order->dtdc_status) }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Customer & Address Info -->
        <div class="col-lg-4">
            <!-- Customer Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Customer Information</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Name:</strong><br>
                        {{ $order->user->name }}
                    </div>
                    <div class="mb-3">
                        <strong>Email:</strong><br>
                        <a href="mailto:{{ $order->user->email }}">{{ $order->user->email }}</a>
                    </div>
                    <div class="mb-3">
                        <strong>Phone:</strong><br>
                        {{ $order->user->phone ?? 'Not provided' }}
                    </div>
                </div>
            </div>

            <!-- Shipping Address -->
            @if($order->shipping_address)
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Shipping Address</h6>
                    </div>
                    <div class="card-body">
                        @if(is_array($order->shipping_address))
                            <address>
                                {{ $order->shipping_address['name'] ?? '' }}<br>
                                {{ $order->shipping_address['address'] ?? '' }}<br>
                                {{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['state'] ?? '' }}<br>
                                {{ $order->shipping_address['pincode'] ?? '' }}<br>
                                {{ $order->shipping_address['country'] ?? '' }}
                            </address>
                        @else
                            {{ $order->shipping_address }}
                        @endif
                    </div>
                </div>
            @endif

            <!-- Order Notes -->
            @if($order->notes)
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Order Notes</h6>
                    </div>
                    <div class="card-body">
                        {{ $order->notes }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
