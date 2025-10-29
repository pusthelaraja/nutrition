@extends('layouts.frontend')

@section('title', 'Invoice - ' . $order->order_number)

@section('content')
<!-- Invoice Section -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Invoice Header -->
                <div class="invoice-header bg-white rounded-4 shadow-sm p-5 mb-4">
                    <div class="row">
                        <div class="col-md-6">
                            <h1 class="fw-bold text-primary mb-2">INVOICE</h1>
                            <div class="invoice-details">
                                <p class="mb-1"><strong>Invoice #:</strong> {{ $order->order_number }}</p>
                                <p class="mb-1"><strong>Date:</strong> {{ $order->created_at->format('M d, Y') }}</p>
                                <p class="mb-0"><strong>Due Date:</strong> {{ $order->created_at->addDays(30)->format('M d, Y') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6 text-end">
                            <div class="company-info">
                                <h3 class="fw-bold text-dark mb-2">{{ config('app.name') }}</h3>
                                <address class="text-muted mb-0">
                                    123 Nutrition Street<br>
                                    Health City, HC 12345<br>
                                    Phone: +91 9876543210<br>
                                    Email: info@nutritionstore.com
                                </address>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bill To Section -->
                <div class="bill-to-section bg-white rounded-4 shadow-sm p-4 mb-4">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="fw-bold text-dark mb-3">Bill To:</h5>
                            @php
                                $shippingAddress = json_decode($order->shipping_address, true);
                            @endphp
                            <address class="text-muted mb-0">
                                <strong>{{ $shippingAddress['first_name'] }} {{ $shippingAddress['last_name'] }}</strong><br>
                                {{ $shippingAddress['address_line_1'] }}<br>
                                @if($shippingAddress['address_line_2'])
                                    {{ $shippingAddress['address_line_2'] }}<br>
                                @endif
                                {{ $shippingAddress['city'] }}, {{ $shippingAddress['state'] }} {{ $shippingAddress['postal_code'] }}<br>
                                {{ $shippingAddress['country'] }}<br>
                                <strong>Phone:</strong> {{ $shippingAddress['phone'] }}
                            </address>
                        </div>
                        <div class="col-md-6">
                            <h5 class="fw-bold text-dark mb-3">Order Information:</h5>
                            <div class="order-info">
                                <p class="mb-1"><strong>Order Status:</strong>
                                    <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : 'info') }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </p>
                                <p class="mb-1"><strong>Payment Method:</strong> {{ ucfirst($order->payment_method) }}</p>
                                <p class="mb-1"><strong>Payment Status:</strong>
                                    <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }}">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                </p>
                                @if($order->coupon_code)
                                    <p class="mb-0"><strong>Coupon Used:</strong> {{ $order->coupon_code }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Invoice Items -->
                <div class="invoice-items bg-white rounded-4 shadow-sm p-4 mb-4">
                    <h5 class="fw-bold text-dark mb-4">Items Ordered:</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Product</th>
                                    <th>SKU</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $item->product->featured_image ?? 'https://via.placeholder.com/40x40/10b981/ffffff?text=Product' }}"
                                                     alt="{{ $item->product->name }}"
                                                     class="img-fluid rounded me-2"
                                                     style="width: 40px; height: 40px; object-fit: cover;">
                                                <div>
                                                    <h6 class="mb-0 text-dark">{{ $item->product->name }}</h6>
                                                    <small class="text-muted">{{ Str::limit($item->product->short_description, 30) }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $item->product->sku }}</td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-end">₹{{ number_format($item->price, 2) }}</td>
                                        <td class="text-end fw-bold">₹{{ number_format($item->total_price, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Invoice Totals -->
                <div class="invoice-totals bg-white rounded-4 shadow-sm p-4 mb-4">
                    <div class="row">
                        <div class="col-md-8">
                            @if($order->notes)
                                <h5 class="fw-bold text-dark mb-3">Order Notes:</h5>
                                <p class="text-muted">{{ $order->notes }}</p>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <div class="totals-breakdown">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Subtotal:</span>
                                    <span>₹{{ number_format($order->subtotal, 2) }}</span>
                                </div>
                                @if($order->discount_amount > 0)
                                    <div class="d-flex justify-content-between mb-2 text-success">
                                        <span>Discount ({{ $order->coupon_code }}):</span>
                                        <span>- ₹{{ number_format($order->discount_amount, 2) }}</span>
                                    </div>
                                @endif
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Shipping:</span>
                                    <span>₹{{ number_format($order->shipping_amount, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Tax (18% GST):</span>
                                    <span>₹{{ number_format($order->tax_amount, 2) }}</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between mb-2 fw-bold fs-4">
                                    <span>Total Amount:</span>
                                    <span class="text-primary">₹{{ number_format($order->total_amount, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Invoice Footer -->
                <div class="invoice-footer bg-white rounded-4 shadow-sm p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-bold text-dark mb-3">Payment Terms:</h6>
                            <ul class="list-unstyled text-muted">
                                <li>• Payment due within 30 days of invoice date</li>
                                <li>• Late payments may incur additional charges</li>
                                <li>• For payment inquiries, contact our billing department</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold text-dark mb-3">Thank You!</h6>
                            <p class="text-muted mb-0">
                                Thank you for choosing {{ config('app.name') }}. We appreciate your business and look forward to serving you again.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="invoice-actions text-center mt-4">
                    <button onclick="window.print()" class="btn btn-primary me-3">
                        <i class="fas fa-print me-2"></i>Print Invoice
                    </button>
                    <a href="{{ route('customer.orders') }}" class="btn btn-outline-primary me-3">
                        <i class="fas fa-list me-2"></i>Back to Orders
                    </a>
                    <a href="{{ route('orders.details', $order->id) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-eye me-2"></i>View Order Details
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('styles')
<style>
@media print {
    .invoice-actions {
        display: none !important;
    }

    .invoice-header,
    .bill-to-section,
    .invoice-items,
    .invoice-totals,
    .invoice-footer {
        box-shadow: none !important;
        border: 1px solid #dee2e6 !important;
    }

    body {
        background: white !important;
    }
}

.invoice-header,
.bill-to-section,
.invoice-items,
.invoice-totals,
.invoice-footer {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.invoice-header:hover,
.bill-to-section:hover,
.invoice-items:hover,
.invoice-totals:hover,
.invoice-footer:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
    background-color: #f8f9fa;
}

.table td {
    vertical-align: middle;
}

.btn {
    border-radius: 25px;
    padding: 8px 20px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

.btn-primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #0056b3 0%, #004085 100%);
}

.badge {
    font-size: 0.875rem;
    padding: 0.5rem 1rem;
}
</style>
@endsection
