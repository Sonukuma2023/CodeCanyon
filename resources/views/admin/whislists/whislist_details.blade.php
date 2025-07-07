@extends('admin.layouts.master')
@section('title', 'Wishlist Details')

@section('content')
<div class="container mt-4">
    <h4 class="mb-4">Wishlist #{{ $wishlist->id }}</h4>

    <div class="card mb-4">
        <div class="card-header fw-bold">User Info</div>
        <div class="card-body">
            <p><strong>Name:</strong> {{ $wishlist->user->name ?? 'N/A' }}</p>
            <p><strong>Email:</strong> {{ $wishlist->user->email ?? 'N/A' }}</p>
            <p><strong>Created At:</strong> {{ $wishlist->created_at->format('d M Y, h:i A') }}</p>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header fw-bold">Wishlist Product Details</div>
        <div class="card-body">
            <p><strong>Product Name:</strong> {{ $wishlist->product->name ?? 'N/A' }}</p>
            <p><strong>Price:</strong> ₹{{ number_format($wishlist->product->regular_license_price ?? 0, 2) }}</p>
            <p><strong>Category:</strong> {{ $wishlist->product->category->name ?? 'N/A' }}</p>
            <p><strong>Description:</strong> {{ $wishlist->product->description ?? 'N/A' }}</p>
            @if($wishlist->product->thumbnail)
                <p><strong>Thumbnail:</strong></p>
                <img src="{{ asset('storage/uploads/thumbnails/' . $wishlist->product->thumbnail) }}" alt="Product Image" style="max-width: 200px;">
            @endif
        </div>
    </div>

    <a href="{{ route('admin.whislistPage') }}" class="btn btn-secondary">Back to Wishlist</a>
</div>
@endsection
