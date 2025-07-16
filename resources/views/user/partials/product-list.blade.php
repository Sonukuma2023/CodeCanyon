<div class="row">
@forelse ($products as $product)
    @php
        $mainFiles = json_decode($product->main_files, true) ?? [];
        $previewFiles = json_decode($product->preview, true) ?? [];
        $livePreviewFiles = json_decode($product->live_preview, true) ?? [];
    @endphp

    <div class="col-md-4 mb-4">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="position-relative">
                <img src="{{ $product->thumbnail && file_exists(public_path('storage/uploads/thumbnails/' . $product->thumbnail)) 
                    ? asset('storage/uploads/thumbnails/' . $product->thumbnail) 
                    : asset('storage/uploads/thumbnails/default-image.png') }}" 
                    alt="{{ $product->name }} thumbnail"
                    class="card-img-top rounded-top" 
                    style="height: 200px; object-fit: cover;">
                <button class="position-absolute top-0 end-0 m-2 btn btn-sm bg-white rounded-circle shadow-sm add-to-wishlist" data-id="{{ $product->id }}">
                    <i class="wishlist-icon bi {{ $product->is_wishlisted ? 'bi-heart-fill text-danger' : 'bi-heart' }}"></i>
                </button>

                <button class="position-absolute bottom-0 start-0 m-2 btn btn-sm bg-white rounded-circle shadow-sm add-to-collection" 
                        data-id="{{ $product->id }}" 
                        title="Add to Collection" 
                        id="collection-btn-{{ $product->id }}">
                    <i class="bi bi-collection"></i>
                </button>

            </div>

            <div class="card-body d-flex flex-column">
                <h6 class="fw-semibold text-dark mb-1">{{ $product->name }}</h6>
                <p class="text-muted small mb-2">{{ Str::limit($product->description, 60) }}</p>

                <div class="mb-2">
                    <span class="text-warning">
                        @for ($i = 0; $i < 5; $i++)
                            <i class="bi bi-star{{ $i < 4 ? '-fill' : '' }}"></i>
                        @endfor
                    </span>
                    <span class="text-muted small">({{ rand(30, 150) }} Sales)</span>
                </div>

                <div class="mb-2">
                    @if(count($mainFiles))
                        <span class="badge bg-success me-1">Main File</span>
                    @endif
                    @if(count($previewFiles))
                        <span class="badge bg-info me-1">Preview File</span>
                    @endif
                    @if(count($livePreviewFiles))
                        <span class="badge bg-warning text-dark me-1">Live Preview File</span>
                    @endif
                </div>

                <!-- Price & Buttons -->
                <div class="d-flex justify-content-between align-items-center mt-auto border-top pt-3">
                    <span class="fw-bold text-primary">${{ number_format($product->regular_license_price, 2) }}</span>
                    <div>
                        <a href="{{ route('user.singleDetailsCategory', $product->id) }}" class="btn btn-sm btn-outline-secondary">Live Preview</a>
                        <button type="button" class="btn btn-outline-dark btn-sm add-to-cart" data-id="{{ $product->id }}" data-price="{{ $product->regular_license_price }}">
                            <span class="cart-icon"><i class="bi bi-cart-plus"></i></span>
                            <span class="cart-added d-none"><i class="bi bi-check-circle-fill text-success"></i></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@empty
<div class="col-12">
    <div class="alert alert-light border shadow-sm text-center py-5">
        <div class="mb-3">
            <i class="bi bi-emoji-frown display-4 text-warning"></i>
        </div>
        <h4 class="fw-semibold text-dark">No Scripts Found</h4>
        <p class="text-muted mb-0">We're sorry, there are no scripts available in this category right now.</p>
    </div>
</div>
@endforelse
</div>

<!-- Modal -->
    <div class="modal fade" id="collectionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="createCollectionForm" class="modal-content">
                @csrf
                <input type="hidden" name="product_id" id="collectionProductId">

                <div class="modal-header">
                    <h5 class="modal-title">Create New Collection</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div id="collectionError" class="text-danger mb-2"></div>
                    <div class="mb-3">
                        <label class="form-label">Collection Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Create & Save</button>
                </div>
            </form>
        </div>
    </div>
    
<style>
.card {
    transition: transform 0.2s ease;
}
.card:hover {
    transform: translateY(-3px);
}
.badge {
    font-size: 0.75rem;
    padding: 0.35em 0.6em;
}
@keyframes collectionPulse {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.3);
        background-color: #d1e7dd;
    }
    100% {
        transform: scale(1);
    }
}

.collection-animate {
    animation: collectionPulse 0.5s ease-in-out;
}
</style>
