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
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });


        $(document).on('click', '.add-to-wishlist', function (e) {
			e.preventDefault();

			const button = $(this);
			const productId = button.data('id');
			const icon = button.find('i');

			$.ajax({
				url: "{{ route('user.addWhislist') }}",
				method: "POST",
				data: {
					product_id: productId,
					_token: "{{ csrf_token() }}"
				},
				success: function (response) {
					if (response.status === 'added') {
						icon.removeClass('bi-heart').addClass('bi-heart-fill text-danger');
					} else if (response.status === 'removed') {
						icon.removeClass('bi-heart-fill text-danger').addClass('bi-heart');
					}
				},
				error: function (xhr) {
					console.log(xhr);
				}
			});
		});

        $(document).on('click', '.add-to-cart', function (e) {
            e.preventDefault();

            const button = $(this);
            const productId = button.data('id');
            const quantity = 1;
            const price = button.data('price');

            $.ajax({
                url: "{{ route('user.saveCart', ':id') }}".replace(':id', productId),
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    quantity: quantity,
                    price: price
                },
                beforeSend: function () {
                    button.prop('disabled', true);
                },
                success: function (response) {
                    if (response.success) {
                        $('.cart-count').text(response.cartCount);

                        // Animate feedback
                        button.addClass('cart-animate');
                        button.find('.cart-icon').addClass('d-none');
                        button.find('.cart-added').removeClass('d-none');

                        setTimeout(() => {
                            button.removeClass('cart-animate');
                            button.find('.cart-added').addClass('d-none');
                            button.find('.cart-icon').removeClass('d-none');
                            button.prop('disabled', false);
                        }, 1500);
                    } else {
                        button.prop('disabled', false);
                        alert(response.message || "Something went wrong.");
                    }
                },
                error: function (xhr) {
                    console.log(xhr);
                    button.prop('disabled', false);
                    alert("Failed to add to cart.");
                }
            });
        });
    });

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

