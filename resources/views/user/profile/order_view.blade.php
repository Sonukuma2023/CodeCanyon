@extends('user.layouts.master')
@section('title', 'Order #' . $order->id)

@section('content')
<div class="container py-5">
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between">
            <h5 class="mb-0">Order Details</h5>
            <span>Order #{{ $order->id }}</span>
        </div>
        <div class="card-body">
            <p><strong>Date:</strong> {{ $order->created_at->format('d M Y') }}</p>
            <p><strong>Status:</strong> <span class="badge bg-info">{{ ucfirst($order->status) }}</span></p>
            <p><strong>Total:</strong> <span class="text-success fw-bold">${{ number_format($order->total, 2) }}</span></p>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-light">
            <h5 class="mb-0">Products in this Order</h5>
        </div>
        <div class="card-body">
            @forelse($order->items as $item)
                @php
                    $product = $item->product;
                @endphp
                <div class="d-flex mb-4 align-items-center border-bottom pb-3">
                    <img src="{{ asset('storage/uploads/thumbnails/' . $product->thumbnail) }}" alt="{{ $product->name }}" style="width: 80px; height: 80px; object-fit: cover;" class="me-3 rounded">
                    <div class="flex-grow-1">
                        <h6 class="mb-1">
                            <a href="{{ route('user.singleproduct', $product->id) }}" class="text-decoration-none text-dark fw-bold">
                                {{ $product->name }}
                            </a>
                        </h6>
                        <p class="mb-0 text-muted">{{ Str::limit($product->description, 80) }}</p>
                    </div>
                    <div class="text-end">
                        <div class="text-primary fw-bold">${{ number_format($item->price, 2) }}</div>
                        <div class="text-muted small">Qty: {{ $item->quantity }}</div>
                    </div>
                </div>
            @empty
                <p class="text-center text-muted">No items found in this order.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
