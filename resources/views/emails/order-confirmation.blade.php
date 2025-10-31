<div style="font-family: Arial, Helvetica, sans-serif; line-height: 1.5; color: #222;">
    <h2 style="margin: 0 0 12px;">Order Confirmation - {{ $order->order_number }}</h2>

    <p style="margin: 0 0 8px;">Hi {{ $order->customer->first_name }} {{ $order->customer->last_name }},</p>
    <p style="margin: 0 0 12px;">Thank you for your order at {{ config('app.name') }}. We’re preparing your items and will notify you when they ship.</p>

    <h3 style="margin: 16px 0 8px;">Order Summary</h3>
    <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-collapse: collapse; margin-bottom: 12px;">
        <tr>
            <td style="padding: 6px 0;">Subtotal</td>
            <td style="padding: 6px 0; text-align: right;">₹{{ number_format($order->subtotal, 2) }}</td>
        </tr>
        @if($order->discount_amount > 0)
        <tr>
            <td style="padding: 6px 0;">Discount</td>
            <td style="padding: 6px 0; text-align: right;">-₹{{ number_format($order->discount_amount, 2) }}</td>
        </tr>
        @endif
        <tr>
            <td style="padding: 6px 0;">Shipping</td>
            <td style="padding: 6px 0; text-align: right;">₹{{ number_format($order->shipping_amount, 2) }}</td>
        </tr>
        @if($order->tax_amount > 0)
        <tr>
            <td style="padding: 6px 0;">Tax</td>
            <td style="padding: 6px 0; text-align: right;">₹{{ number_format($order->tax_amount, 2) }}</td>
        </tr>
        @endif
        <tr>
            <td style="padding: 8px 0; font-weight: bold; border-top: 1px solid #eee;">Total</td>
            <td style="padding: 8px 0; font-weight: bold; text-align: right; border-top: 1px solid #eee;">₹{{ number_format($order->total_amount, 2) }}</td>
        </tr>
    </table>

    <h3 style="margin: 16px 0 8px;">Items</h3>
    <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-collapse: collapse;">
        @foreach($order->items as $item)
        <tr>
            <td style="padding: 6px 0;">{{ $item->product_name }} (x{{ $item->quantity }})</td>
            <td style="padding: 6px 0; text-align: right;">₹{{ number_format($item->total_price, 2) }}</td>
        </tr>
        @endforeach
    </table>

    <p style="margin: 16px 0 0;">You can view your order status here:
        <a href="{{ route('orders.confirmation', $order->id) }}" style="color: #0d6efd; text-decoration: none;">Order {{ $order->order_number }}</a>
    </p>

    <p style="margin: 16px 0 0;">Regards,<br>{{ config('app.name') }} Team</p>
</div>


