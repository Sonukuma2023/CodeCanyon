@extends('user.layouts.master')
@section('title','Edit Coupon')
@section('content')
<div class="container">
    <h2>All Products</h2>

    <!-- Filter Form -->
    <div class="row mb-4">
        <div class="col-md-3">
            <select id="categoryFilter" class="form-control">
                <option value="">All Categories</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <input type="number" id="minPrice" class="form-control" placeholder="Min Price">
        </div>
        <div class="col-md-3">
            <input type="number" id="maxPrice" class="form-control" placeholder="Max Price">
        </div>
        <div class="col-md-3">
            <button id="applyFilter" class="btn btn-primary w-100">Apply Filter</button>
        </div>
    </div>

    <!-- Products List -->
    <div id="productList">
        @include('user.partials.product-list', ['products' => $products])
    </div>
</div>
@endsection

@section('scripts')
<script>
    function fetchProducts() {
        const category = $('#categoryFilter').val();
        const minPrice = $('#minPrice').val();
        const maxPrice = $('#maxPrice').val();

        $.ajax({
            url: "{{ route('user.allProductFilter') }}",
            data: {
                category_id: category,
                min_price: minPrice,
                max_price: maxPrice,
            },
            success: function (response) {
                $('#productList').html(response);
            }
        });
    }

    $(document).ready(function () {
        fetchProducts(); 

        $('#applyFilter').on('click', function () {
            fetchProducts();
        });
    });
</script>
@endsection
