@extends('user.layouts.master')
@section('title', $product->name)

@section('content')
<style>
.addtocart {
    display: block;
    padding: 0.5em 1em;
    border-radius: 50px;
    border: none;
    font-size: 1rem;
    position: relative;
    background: #0652DD;
    color: #fff;
    cursor: pointer;
    height: 2.5em;
    width: 100%; /* Make it fill the parent container */
    max-width: 220px; /* Limit max width */
    overflow: hidden;
    transition: transform 0.1s;
    z-index: 1;
    margin: 0 auto; /* Center horizontally */
}

.addtocart:hover {
    transform: scale(1.05);
}

.pretext {
    color: #fff;
    background: #0652DD;
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    font-family: 'Quicksand', sans-serif;
}

.done {
    background: #be2edd;
    transform: translate(-110%) skew(-40deg);
    transition: transform 0.3s ease;
}

.posttext {
    background: #be2edd;
    display: flex;
    align-items: center;
    font-size: 0.9rem;
}

.fa-cart-plus, .fa-check {
    margin-right: 6px;
    font-size: 0.9rem;
}
</style>
<div class="container py-5">
    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-8">
            <h2 class="mb-2">{{ $product->name }}</h2>
            <p class="text-success fw-bold mb-2">Recently Updated</p>

            <ul class="nav nav-tabs mb-3">
                <li class="nav-item"><a class="nav-link active" href="#">Item Details</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Comments</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Support</a></li>
            </ul>

            <!-- Product Preview Image -->
            <div class="mb-4 text-center">
                <!-- <img src="{{ asset('storage/uploads/thumbnails/' . $product->thumbnail) }}" class="img-fluid rounded shadow" alt="{{ $product->name }}"> -->
                 <img src="{{ $product->thumbnail && file_exists(public_path('storage/uploads/thumbnails/' . $product->thumbnail)) 
                        ? asset('storage/uploads/thumbnails/' . $product->thumbnail) 
                        : asset('storage/uploads/thumbnails/default-image.png') }}" 
                        class="img-fluid rounded shadow"
                        alt="{{ $product->title }}">
            </div>

            <div class="mb-4">
                <h5>Description:</h5>
                <p>{{ $product->description }}</p>
            </div>

            <div class="mb-4 text-center">
                <img src="{{ $product->inline_preview && file_exists(public_path('storage/uploads/inline_preview/' . $product->inline_preview)) ? asset('storage/uploads/inline_preview/' . $product->inline_preview) : asset('storage/uploads/thumbnails/default-image.png') }}" class="img-fluid rounded shadow" alt="{{ $product->name }}">
            </div>

            <div class="mb-4">
                <h5>Product Features:</h5>
                <ul>
                    <li>Well documented</li>
                    <li>Easy integration</li>
                    <li>Optimized code</li>
                </ul>
            </div>

            @php
                $livePreview = json_decode($product->live_preview, true);
                $preview = json_decode($product->preview, true);
            @endphp

            @if(!empty($livePreview) && isset($livePreview[0]))
                <a href="{{ asset($livePreview[0]) }}" target="_blank" class="btn btn-primary mb-3">Live Preview</a>
            @endif

            @if(!empty($preview) && isset($preview[0]))
                <a href="{{ asset($preview[0]) }}" class="btn btn-secondary mb-3" download>Download Preview</a>
            @endif
        </div>

        <!-- Right Column: Pricing -->
        <div class="col-lg-4">
            <div class="card p-4 shadow-sm">
                <h4 class="mb-3">Regular License</h4>
                <h2 class="text-success mb-3">${{ number_format($product->regular_license_price, 2) }}</h2>

                <ul class="list-unstyled mb-3">
                    <li><i class="bi bi-check-circle-fill text-success me-2"></i> Quality checked</li>
                    <li><i class="bi bi-check-circle-fill text-success me-2"></i> Future updates</li>
                    <li><i class="bi bi-check-circle-fill text-success me-2"></i> 6-month support</li>
                </ul>

                <button class="addtocart" data-id="{{ $product->id }}" data-price="{{ $product->regular_license_price }}">
                    <div class="pretext">
                        <i class="fas fa-cart-plus"></i> ADD TO CART
                    </div>

                    <div class="pretext done">
                        <div class="posttext"><i class="fas fa-check"></i> ADDED</div>
                    </div>
                </button>



                <p class="text-muted small text-center mt-2">
                    Price in USD. Tax & handling excluded.
                </p>

                <div class="text-center mt-4">
                    <i class="bi bi-person-circle fs-1 text-muted"></i>
                    <p class="mt-2 mb-0">By <strong>{{ $product->user->name ?? 'Admin' }}</strong></p>
                    <span class="text-muted small">Elite Author</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        $('.addtocart').each(function () {
            const button = $(this);
            const done = button.find('.done');
            let added = false;

            button.off('click').on('click', function (e) {
                e.preventDefault();

                if (added) return;

                const productId = button.data('id');
                const price = button.data('price');

                $.ajax({
                    url: "{{ route('user.saveCart', ':id') }}".replace(':id', productId),
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        quantity: 1,
                        price: price
                    },
                    success: function (response) {
                        if (response.success) {
                            $('.cart-count').text(response.cartCount);

                            done.css('transform', 'translate(0px)');
                            added = true;

                            setTimeout(() => {
                                done.css('transform', 'translate(-110%) skew(-40deg)');
                                added = false;
                            }, 2000);
                        }
                    },
                    error: function (xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });
        });
    });
</script>
@endsection

