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

        <div class="row g-4" id="product-container">
            @include('user.product-cards', ['products' => $products])
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

