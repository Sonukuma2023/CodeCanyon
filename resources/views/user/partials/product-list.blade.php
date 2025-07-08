<div class="row">
    @forelse ($products as $product)
        <div class="col-md-3 mb-4">
            <div class="card h-100 product-card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="position-relative">
                    <img src="{{ $product->thumbnail && file_exists(public_path('storage/uploads/thumbnails/' . $product->thumbnail)) 
                        ? asset('storage/uploads/thumbnails/' . $product->thumbnail) 
                        : asset('storage/uploads/thumbnails/default-image.png') }}" 
                        class="card-img-top product-image" 
                        alt="{{ $product->title }}">
                </div>
                <div class="card-body d-flex flex-column p-3">
                    <h5 class="fw-semibold text-dark mb-1">{{ $product->name }}</h5>
                    <p class="text-muted small mb-1"><i class="bi bi-tag"></i> {{ $product->category->name ?? 'Uncategorized' }}</p>
                    <p class="text-secondary small mb-2">{{ Str::limit($product->description, 80) }}</p>
                    <div class="mt-auto d-flex justify-content-between align-items-center">
                        <span class="badge bg-primary fs-6 py-2 px-3">₹{{ $product->regular_license_price }}</span>
                        <a href="{{ route('user.singleDetailsCategory', $product->id) }}" class="btn btn-sm btn-outline-dark rounded-pill">View</a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center">
            <p class="text-muted">No products found.</p>
        </div>
    @endforelse
</div>

<style>
    .product-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08);
    }

    .product-image {
        height: 200px;
        object-fit: cover;
        border-bottom: 1px solid #eee;
    }

    .card-body h5 {
        font-size: 1.1rem;
    }

    .btn-outline-dark:hover {
        background-color: #212529;
        color: #fff;
        border-color: #212529;
    }

    .badge {
        font-weight: 500;
    }
</style>
