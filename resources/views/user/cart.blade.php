@extends('user.layouts.master')

@section('content')
<style>
    .cart-item-img {
        width: 100px;
        height: 80px;
        object-fit: cover;
    }
    .quantity-input {
        width: 100px;
        text-align: center;
        padding: 0px;
    }
    .summary-card {
        position: sticky;
        top: 20px;
    }
</style>

<div class="container">
    <h1 class="section-title mb-4">Your Shopping Cart</h1>
    <div class="cart-content-wrapper">
        @if(count($cartItems) > 0)
        <div class="row g-4">
            <!-- Cart Items -->
            <div class="col-lg-8 cart-items-section">
                <div class="card shadow-sm">
                    <div class="card-body">
                        @foreach($cartItems as $id => $item)
                        <div class="row g-3 align-items-center py-3 border-bottom">
                            <div class="col-md-2">
                                <img src="{{ asset('storage/uploads/thumbnails/' . $item->product->thumbnail) }}" alt="{{ $item['name'] }}" class="img-fluid rounded cart-item-img">
                            </div>
                            <div class="col-md-6">
                                <h5 class="mb-1">{{ $item->product->name }}</h5>
                                <p class="h5 text-primary mb-2">${{ number_format($item['price'], 2) }}</p>

                                <button type="button" class="btn btn-sm btn-link text-danger p-0 remove-from-cart"
                                    data-id="{{ $item->id }}">
                                    <i class="fas fa-trash-alt me-1"></i> Remove
                                </button>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex align-items-center">
                                    <button type="button" class="btn btn-outline-secondary px-3 py-1 quantity-btn decrease-btn" data-id="{{ $item->id }}">-</button>
                                    
                                    <input type="number" value="{{ $item['quantity'] }}" min="1"
                                        class="form-control quantity-input mx-2" readonly>

                                    <button type="button" class="btn btn-outline-secondary px-3 py-1 quantity-btn increase-btn" data-id="{{ $item->id }}">+</button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-lg-4 cart-summary-section">
                <div class="card shadow-sm summary-card">
                    <div class="card-body">
                        <h2 class="h4 mb-4">
                            <i class="fas fa-receipt text-primary me-2"></i>Order Summary
                        </h2>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal ({{ $totalItems }} items)</span>
                            <span>${{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Discount</span>
                            <span class="text-danger">-${{ number_format($discount, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Shipping</span>
                            <span>Free</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                            <span>Tax</span>
                            <span>${{ number_format($tax, 2) }}</span>
                        </div>

                        <div class="d-flex justify-content-between mb-4">
                            <span class="h5">Total</span>
                            <span class="h5">${{ number_format($total, 2) }}</span>
                        </div>

                        <form action="{{ route('coupon.apply') }}" method="POST" class="input-group mb-4">
                            @csrf
                            <input type="text" name="coupon_code" class="form-control" placeholder="Coupon code">
                            <button type="submit" class="btn btn-primary">Apply</button>
                        </form>

                        <a href="{{ route('checkout') }}" class="btn btn-primary btn-lg w-100 mb-3">
                            <i class="fas fa-lock me-2"></i> Proceed to Checkout
                        </a>

                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-shopping-cart display-4 text-secondary mb-4"></i>
            <h2 class="h3 mb-3">Your cart is empty</h2>
            <p class="text-secondary mb-4">Looks like you haven't added any items to your cart yet.</p>
            <a href="{{ route('user.dashboard') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-1"></i> Continue Shopping
            </a>
        </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on('click', '.remove-from-cart', function (e) {
        e.preventDefault();

        const button = $(this);
        const itemId = button.data('id');
        const url = "{{ route('cart.remove', ':id') }}".replace(':id', itemId);

        Swal.fire({
            title: 'Are you sure?',
            text: "Do you really want to remove this item from the cart?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, remove it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    success: function (response) {
                        if (response.success) {
                            button.closest('.row').remove();
                            $('.cart-count').text(response.cartCount);
                            updateCartSummary(response.summary);
                            if (response.cartCount == 0) {
                                $('.cart-content-wrapper').html(`
                                    <div class="text-center py-5">
                                        <i class="fas fa-shopping-cart display-4 text-secondary mb-4"></i>
                                        <h2 class="h3 mb-3">Your cart is empty</h2>
                                        <p class="text-secondary mb-4">Looks like you haven't added any items to your cart yet.</p>
                                        <a href="{{ route('user.dashboard') }}" class="btn btn-outline-primary">
                                            <i class="fas fa-arrow-left me-1"></i> Continue Shopping
                                        </a>
                                    </div>
                                `);
                            }

                            Swal.fire('Removed!', response.message, 'success');
                        }
                    },
                    error: function (xhr) {
                        console.error(xhr.responseText);
                        Swal.fire('Error!', 'Something went wrong.', 'error');
                    }
                });
            }
        });
    });

       $(document).on('click', '.increase-btn', function () {
        const button = $(this);
        const itemId = button.data('id');

        $.ajax({
            url: "{{ route('cart.increase', ':id') }}".replace(':id', itemId),
            type: 'POST',
            success: function (response) {
                if (response.success) {
                    $('.cart-count').text(response.summary.totalItems);
                    button.closest('.row').find('.quantity-input').val(response.quantity);
                    updateCartSummary(response.summary);
                }
            },
            error: function (xhr) {
                console.error(xhr.responseText);
            }
        });
    });

    // DECREASE Quantity
    $(document).on('click', '.decrease-btn', function () {
        const button = $(this);
        const itemId = button.data('id');

        $.ajax({
            url: "{{ route('cart.decrease', ':id') }}".replace(':id', itemId),
            type: 'POST',
            success: function (response) {
                if (response.success) {
                    if (response.quantity > 0) {
                         $('.cart-count').text(response.quantity);
                        button.closest('.row').find('.quantity-input').val(response.quantity);
                        updateCartSummary(response.summary);
                    } else {
                        button.closest('.row').remove();
                        if (response.cartCount == 0) {
                            $('.cart-count').text(response.quantity);
                            $('.cart-items-section').remove();
                            $('.cart-summary-section').remove();
                            $('.cart-content-wrapper').html(`
                                <div class="text-center py-5">
                                    <i class="fas fa-shopping-cart display-4 text-secondary mb-4"></i>
                                    <h2 class="h3 mb-3">Your cart is empty</h2>
                                    <p class="text-secondary mb-4">Looks like you haven't added any items to your cart yet.</p>
                                    <a href="{{ route('user.dashboard') }}" class="btn btn-outline-primary">
                                        <i class="fas fa-arrow-left me-1"></i> Continue Shopping
                                    </a>
                                </div>
                            `);
                        }
                    }
                }
            },
            error: function (xhr) {
                console.error(xhr.responseText);
            }
        });
    });

    // Helper function to update order summary
    function updateCartSummary(summary) {
        $('.cart-summary-section').html(`
            <div class="card shadow-sm summary-card">
                <div class="card-body">
                    <h2 class="h4 mb-4">
                        <i class="fas fa-receipt text-primary me-2"></i>Order Summary
                    </h2>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal (${summary.totalItems} items)</span>
                        <span>$${summary.subtotal}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Discount</span>
                        <span class="text-danger">-$${summary.discount}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Shipping</span>
                        <span>Free</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                        <span>Tax</span>
                        <span>$${summary.tax}</span>
                    </div>

                    <div class="d-flex justify-content-between mb-4">
                        <span class="h5">Total</span>
                        <span class="h5">$${summary.total}</span>
                    </div>

                    <form action="{{ route('coupon.apply') }}" method="POST" class="input-group mb-4">
                        @csrf
                        <input type="text" name="coupon_code" class="form-control" placeholder="Coupon code">
                        <button type="submit" class="btn btn-primary">Apply</button>
                    </form>

                    <a href="{{ route('checkout') }}" class="btn btn-primary btn-lg w-100 mb-3">
                        <i class="fas fa-lock me-2"></i> Proceed to Checkout
                    </a>

                </div>
            </div>
        `);
    }
</script>
@endsection
