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
    <div class="container">
        <h2 class="mb-4">{{ $category->name }} Scripts</h2>

        <div class="row g-4">
            @forelse($products as $product)
                @php
                    $mainFiles = json_decode($product->main_files, true) ?? [];
                    $previewFiles = json_decode($product->preview, true) ?? [];
                    $livePreviewFiles = json_decode($product->live_preview, true) ?? [];


                    $thumbnail = $product->thumbnail ? asset('storage/uploads/thumbnails/' . $product->thumbnail) : asset('default-thumbnail.jpg');
                    $inlinePreview = $product->inline_preview ? asset('storage/uploads/inline_previews/' . $product->inline_preview) : '#';


                    $salesCount = $product->sales ?? rand(10, 200);
                    $rating = $product->rating ?? 4.5;
                @endphp

                <div class="col-md-3">
                    <div class="card h-100 shadow-sm border-0 position-relative">
					<div class="position-absolute top-0 end-0 m-2">
						<button type="button" class="btn btn-light btn-sm p-1 rounded-circle shadow-sm add-to-wishlist" data-id="{{ $product->id }}">
							<i class="bi bi-heart{{ $product->is_wishlisted ? '-fill text-danger' : '' }}"></i>
						</button>
					</div>


                    <a href="{{ route('user.singleDetailsCategory', $product->id) }}" class="text-decoration-none text-dark">

						<img src="{{ $thumbnail }}" class="card-img-top" style="height: 160px; object-fit: cover;" alt="{{ $product->name }} thumbnail">

                        <div class="card-body d-flex flex-column">
                            <h6 class="fw-bold mb-1">{{ Str::limit($product->name, 40) }}</h6>
                            <p class="text-muted small mb-2">{{ Str::limit($product->description, 60) }}</p>

                            <div class="mb-2 d-flex align-items-center small">
                                <div class="text-warning me-2">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="bi {{ $i <= floor($rating) ? 'bi-star-fill' : ($i - $rating < 1 ? 'bi-star-half' : 'bi-star') }}"></i>
                                    @endfor
                                </div>
                                <span class="text-muted">({{ number_format($salesCount) }} Sales)</span>
                            </div>

                            </a>

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

                            <div class="mt-auto pt-2 border-top d-flex justify-content-between align-items-center">
                                <span class="fw-bold text-primary">${{ $product->regular_license_price }}</span>

                                <div class="d-flex gap-1">
                                    @if($product->inline_preview)
                                        <a href="{{ $inlinePreview }}" target="_blank" class="btn btn-outline-secondary btn-sm">
                                            Live Preview
                                        </a>
                                    @endif
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
                    <div class="alert alert-warning text-center">
                        No scripts available in this category.
                    </div>
                </div>
            @endforelse
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
<<<<<<< HEAD
            e.preventDefault();
=======

        e.preventDefault();
>>>>>>> d1b4e82eb8e499ba3c1312275d315cdff5ec9993

            const button = $(this);
            const productId = button.data('id');
            const quantity = 1;
            const price = button.data('price');

<<<<<<< HEAD
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
                    }
                },
                error: function (xhr) {
                    console.log(xhr);
                    button.prop('disabled', false);
=======
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
            
>>>>>>> d1b4e82eb8e499ba3c1312275d315cdff5ec9993
                }
            });
        });


    });
</script>
@endsection

