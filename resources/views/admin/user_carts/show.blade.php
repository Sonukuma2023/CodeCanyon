@extends('admin.layouts.master')
@section('title', 'View Cart')

@section('content')
<div class="container py-4">
    <div class="card shadow-lg rounded-3">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="mdi mdi-cart-outline me-2"></i> Cart Details</h5>
            <a href="{{ route('admin.usersCartPage') }}" class="btn btn-light btn-sm">
                <i class="mdi mdi-arrow-left"></i> Back to Cart List
            </a>
        </div>
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-6">
                    <h6 class="text-muted">Cart ID</h6>
                    <p class="fw-semibold">{{ $cart->id }}</p>
                </div>

                <div class="col-md-6">
                    <h6 class="text-muted">User</h6>
                    <p class="fw-semibold">{{ $cart->user->name ?? 'Guest' }}</p>
                </div>

                <div class="col-md-6">
                    <h6 class="text-muted">Product</h6>
                    <p class="fw-semibold">{{ $cart->product->name ?? 'N/A' }}</p>
                </div>

                <div class="col-md-6">
                    <h6 class="text-muted">Quantity</h6>
                    <p class="fw-semibold">{{ $cart->quantity }}</p>
                </div>

                <div class="col-md-6">
                    <h6 class="text-muted">Price</h6>
                    <p class="fw-semibold text-success">$ {{ number_format($cart->price, 2) }}</p>
                </div>


                <div class="col-md-6">
                    <h6 class="text-muted">Created At</h6>
                    <p class="fw-semibold">{{ $cart->created_at->format('d M Y, h:i A') }}</p>
                </div>

                
                <div class="col-md-6">
                    <h6 class="text-muted">Total Price</h6>
                    <p class="fw-semibold text-success">$ {{ number_format($cart->quantity * $cart->price, 2) }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
