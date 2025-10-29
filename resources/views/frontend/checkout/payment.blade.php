@extends('layouts.frontend')

@section('title', 'Complete Payment')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">Pay with Razorpay</h5>
                    <p class="mb-1"><strong>Order:</strong> {{ $order->order_number }}</p>
                    <p class="mb-3"><strong>Amount:</strong> {{ number_format($order->total_amount, 2) }} {{ $currency }}</p>

                    <button id="rzp-button" class="btn btn-primary w-100">
                        <i class="fas fa-credit-card me-2"></i>Pay Now
                    </button>

                    <form id="rzp-verify-form" method="POST" action="{{ route('payment.razorpay.verify') }}" class="d-none">
                        @csrf
                        <input type="hidden" name="order_id" value="{{ $order->id }}">
                        <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
                        <input type="hidden" name="razorpay_order_id" id="razorpay_order_id">
                        <input type="hidden" name="razorpay_signature" id="razorpay_signature">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    document.getElementById('rzp-button').addEventListener('click', function () {
        const options = {
            key: @json($razorpayKey),
            amount: @json($amountPaise),
            currency: @json($currency),
            name: 'Nutrition Store',
            description: 'Order ' + @json($order->order_number),
            order_id: @json($razorpayOrderId),
            prefill: {
                name: @json(optional($order->customer)->first_name . ' ' . optional($order->customer)->last_name),
                email: @json(optional($order->customer)->email),
                contact: ''
            },
            notes: {
                local_order_id: String(@json($order->id))
            },
            theme: { color: '#0d6efd' },
            handler: function (response) {
                document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
                document.getElementById('razorpay_order_id').value = response.razorpay_order_id;
                document.getElementById('razorpay_signature').value = response.razorpay_signature;
                document.getElementById('rzp-verify-form').submit();
            },
            modal: {
                ondismiss: function () {
                    // Optionally notify user
                }
            }
        };

        const rzp = new Razorpay(options);
        rzp.open();
    });
</script>
@endpush
@endsection


