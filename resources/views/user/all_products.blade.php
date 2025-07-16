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

    // Add to Collection button click
    $(document).on('click', '.add-to-collection', function () {
        const productId = $(this).data('id');

        $.ajax({
            url: "{{ route('user.addToCollection') }}",
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                product_id: productId
            },
            success: function (response) {
                if (response.hasCollections === false) {
                    $('#collectionProductId').val(productId);
                    $('#collectionModal').modal('show');
                } else if (response.status === 'exists') {
                    animateCollectionButton(productId);
                    Swal.fire({
                        icon: 'info',
                        title: 'Already in Collection',
                        text: response.message,
                        toast: true,
                        position: 'top-end',
                        timer: 3000,
                        showConfirmButton: false
                    });
                } else if (response.status === 'success') {
                    animateCollectionButton(productId);
                    Swal.fire({
                        icon: 'success',
                        title: 'Added to Collection',
                        text: response.message,
                        toast: true,
                        position: 'top-end',
                        timer: 3000,
                        showConfirmButton: false
                    });
                }
            },
            error: function (xhr, status, error) {
                console.error('Error adding product to collection:', error);
            }
        });
    });

    // Create Collection form submit
    $(document).on('submit', '#createCollectionForm', function (e) {
        e.preventDefault();

        const formData = $(this).serialize();

        $.ajax({
            url: "{{ route('user.createCollection') }}",
            method: 'POST',
            data: formData,
            success: function (response) {
                $('#collectionModal').modal('hide');
                $('#createCollectionForm')[0].reset();
                $('#collectionError').text('');

                animateCollectionButton($('#collectionProductId').val());

                Swal.fire({
                    icon: response.status === 'exists' ? 'info' : 'success',
                    title: response.message || 'Collection created!',
                    toast: true,
                    position: 'top-end',
                    timer: 3000,
                    showConfirmButton: false
                });
            },
            error: function (xhr) {
                let errorMsg = 'Something went wrong!';
                if (xhr.responseJSON?.errors) {
                    const firstError = Object.values(xhr.responseJSON.errors)[0][0];
                    errorMsg = firstError;
                }
                $('#collectionError').text(errorMsg);
            }
        });
    });

    function animateCollectionButton(productId) 
    {
        const btn = $(`#collection-btn-${productId}`);
        const icon = btn.find('i');

        icon.removeClass('bi-collection').addClass('bi-check-circle-fill text-success');

        btn.addClass('collection-animate');

        setTimeout(() => {
            btn.removeClass('collection-animate');
            icon.removeClass('bi-check-circle-fill text-success').addClass('bi-collection');
        }, 1000);
    }
});
</script>
@endsection
