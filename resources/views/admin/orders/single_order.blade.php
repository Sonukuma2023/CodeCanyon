@extends('admin.layouts.master')
@section('title', 'Order Details')

@section('content')
<div class="container mt-4">
    <h4 class="mb-4">Order #{{ $order->id }}</h4>

    <div class="card mb-4">
        <div class="card-header fw-bold">Customer Info</div>
        <div class="card-body">
            <p><strong>Name:</strong> {{ $order->first_name }} {{ $order->last_name }}</p>
            <p><strong>Email:</strong> {{ $order->email }}</p>
            <p><strongAddress:</strong> {{ $order->address }}, {{ $order->city }}, {{ $order->zip }}, {{ $order->country }}</p>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header fw-bold">Order Items</div>
        <div class="card-body table-responsive">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price (Each)</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>{{ $item->product->name ?? 'N/A' }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>₹{{ number_format($item->price, 2) }}</td>
                            <td>₹{{ number_format($item->price * $item->quantity, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header fw-bold">Payment Summary</div>
        <div class="card-body">
            <p><strong>Subtotal:</strong> ₹{{ number_format($order->subtotal, 2) }}</p>
            <p><strong>Discount:</strong> ₹{{ number_format($order->discount, 2) }}</p>
            <p><strong>Tax:</strong> ₹{{ number_format($order->tax, 2) }}</p>
            <hr>
            <p><strong>Total:</strong> ₹{{ number_format($order->total, 2) }}</p>
            <p><strong>Payment Method:</strong> {{ ucfirst($order->payment_method) }}</p>
            <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
            <p><strong>Payment Status:</strong> {{ ucfirst($order->payment_status) }}</p>
        </div>
    </div>

    <a href="{{ route('admin.ordersPage') }}" class="btn btn-secondary">Back to Orders</a>
</div>
@endsection
