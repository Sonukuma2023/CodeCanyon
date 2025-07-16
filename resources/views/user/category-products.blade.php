@extends('user.layouts.master')
@section('title', $category->name . ' Script Page')
@section('content')
<style>
    .add-to-cart:disabled {
        pointer-events: none;
        opacity: 0.7;
    }

    .cart-added {
        transition: all 0.3s ease;
    }

    .btn.cart-animate {
        animation: cartPulse 0.5s ease-in-out;
    }

    @keyframes cartPulse {
        0%   { transform: scale(1); }
        50%  { transform: scale(1.2); }
        100% { transform: scale(1); }
    }
</style>
<section class="scripts-section py-5">
    <div class="container mb-4">
        <div class="row justify-content-center">
            <div class="col-auto">
                <form id="filter-form" class="d-flex flex-wrap gap-3 justify-content-center">
                    <select name="min_price" class="form-select" style="width: 160px;">
                        <option value="">Min Price</option>
                        <option value="0">0</option>
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="30">30</option>
                    </select>

                    <select name="max_price" class="form-select" style="width: 160px;">
                        <option value="">Max Price</option>
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="30">30</option>
                        <option value="40">40</option>
                        <option value="50">50</option>
                    </select>

                    <select name="rating" class="form-select" style="width: 160px;">
                        <option value="">All Ratings</option>
                        <option value="4">4+ Stars</option>
                        <option value="3">3+ Stars</option>
                    </select>

                    <button type="submit" class="btn btn-primary px-4">Apply Filters</button>
                </form>
            </div>
        </div>
    </div>

    <div class="container">
        <h2 class="mb-4">{{ $category->name }} Scripts</h2>

<<<<<<< HEAD
        <div class="row g-4">
            @forelse($products as $product)
                @php
                    $mainFiles = json_decode($product->main_files, true) ?? [];
                    $previewFiles = json_decode($product->preview, true) ?? [];
                    $livePreviewFiles = json_decode($product->live_preview, true) ?? [];

                    $thumbnail = $product->thumbnail
    ? asset('storage/' . $product->thumbnail)
    : asset('default-thumbnail.jpg');

// echo $thumbnail;
// // echo "<br>";
// // echo "http://127.0.0.1:8000/storage/uploads/thumbnails/5MJ7c8yMcChpcTpYqS2Ngw7qiKowM1nULjHSs9bl.jpg";

                    $inlinePreview = $product->inline_preview ? asset('storage/' . $product->inline_preview) : '#';
                @endphp

                <div class="col-md-4">
                    <div class="card h-100 shadow-sm border-0">
                        <img src="{{ asset('storage/' . $product->thumbnail) }}" class="card-img-top" style="height: 200px; object-fit: cover;" alt="{{ $product->name }} thumbnail">
						<img src="http://127.0.0.1:8000/storage/uploads/thumbnails/JAo79hsRhR3qlWAnjfRS1zjpxNqVYJaTL6MMDQit.jpg" />


                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text text-muted">{{ Str::limit($product->description, 100) }}</p>

                            @if($product->inline_preview)
                                <a href="{{ $inlinePreview }}" target="_blank" class="btn btn-outline-secondary btn-sm mb-2">
                                    Inline Preview
                                </a>
                            @endif

                            @if(!empty($mainFiles))
                                <div class="mb-2">
                                    @foreach($mainFiles as $file)
                                        <a href="{{ asset($file) }}" class="btn btn-sm btn-success me-1 mb-1" download>
                                            Main File
                                        </a>
                                    @endforeach
                                </div>
                            @endif

                            @if(!empty($previewFiles))
                                <div class="mb-2">
                                    @foreach($previewFiles as $file)
                                        <a href="{{ asset($file) }}" class="btn btn-sm btn-info me-1 mb-1" download>
                                            Preview File
                                        </a>
                                    @endforeach
                                </div>
                            @endif

                            @if(!empty($livePreviewFiles))
                                <div class="mb-2">
                                    @foreach($livePreviewFiles as $file)
                                        <a href="{{ asset($file) }}" class="btn btn-sm btn-warning me-1 mb-1" download>
                                            Live Preview File
                                        </a>
                                    @endforeach
                                </div>
                            @endif

                            <a href="#" class="btn btn-primary btn-sm mt-auto">View Script</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-warning text-center">
                        No scripts available in this category.
                    </div>
                </div>
            @endforelse
=======
        <div class="row g-4" id="product-container">
            @include('user.partials.product-cards', ['products' => $products])
>>>>>>> 4d4c50f16f534fd26830383688802d84c84ee185
        </div>

    </div>
</section>
@endsection

@section('scripts')
<script>
$(document).ready(function () {
    $('#filter-form').on('submit', function (e) {
        e.preventDefault();
        const formData = $(this).serialize();
        const url = window.location.href;

        $.ajax({
            url: url,
            type: 'GET',
            data: formData,
            success: function (res) {
                $('#product-container').html(res.html);
            },
            error: function () {
                alert('Failed to load products.');
            }
        });
    });
});
</script>
@endsection

