@extends('admin.layouts.master')
@section('title', 'User Collections')

@section('content')
<div class="container py-4">
    <h4 class="mb-4 fw-bold">All User Collection Products</h4>

    @forelse ($collections as $item)
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-primary text-white fw-semibold">
                {{ $item->product->name ?? 'Unnamed Product' }}
            </div>
            <div class="card-body">
                {{-- Collection Info --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Collection Name:</strong> {{ $item->collection->name ?? 'N/A' }}</p>
                        <p class="mb-1"><strong>Created:</strong> {{ $item->created_at->diffForHumans() }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1"><strong>User:</strong> {{ $item->collection->user->name ?? 'N/A' }}</p>
                        <p class="mb-1"><strong>Email:</strong> {{ $item->collection->user->email ?? 'N/A' }}</p>
                    </div>
                </div>

                {{-- Price Info --}}
                <div class="row mb-0">
                    <div class="col-md-6">
                        <p><strong>Product Price:</strong> ₹{{ number_format($item->product->regular_license_price ?? 0, 2) }}</p>
                    </div>

                </div>
            </div>
        </div>
    @empty
        <div class="text-center text-muted">No collection products found.</div>
    @endforelse
</div>
@endsection
