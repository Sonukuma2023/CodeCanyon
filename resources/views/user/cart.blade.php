@extends('user.layouts.master')
@section('content')

    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
        }

        .text-primary {
            color: var(--primary) !important;
        }

        .text-secondary {
            color: var(--text-secondary) !important;
        }

        .text-danger {
            color: var(--danger) !important;
        }

        .section-title {
            position: relative;
            padding-bottom: 15px;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 4px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 2px;
        }

        .cart-item-img {
            width: 100px;
            height: 80px;
            object-fit: cover;
        }

        .quantity-input {
            width: 100px;
            text-align: center;
            padding: 0px
        }

        .summary-card {
            position: sticky;
            top: 20px;
        }

        .tech-tags {
            display: flex;
            gap: 15px;
        }

        .tech-tag {
            background-color: var(--light);
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--text-secondary);
        }

        .search-bar {
            flex: 1;
            max-width: 500px;
            min-width: 200px;
            position: relative;
        }

        .search-bar input {
            width: 100%;
            padding: 10px 15px 10px 40px;
            border: 1px solid var(--gray);
            border-radius: 30px;
            font-size: 0.95rem;
            background-color: var(--light);
        }

        .search-bar i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
        }

        .auth-buttons {
            display: flex;
            gap: 10px;
        }

        @media (max-width: 1024px) {
            .header-content {
                flex-direction: column;
                align-items: stretch;
            }

            .tech-tags {
                order: 3;
                justify-content: center;
                flex-wrap: wrap;
            }

            .search-bar {
                order: 2;
                max-width: 100%;
            }

            .auth-buttons {
                order: 1;
                justify-content: flex-end;
            }
        }

        @media (max-width: 576px) {
            .auth-buttons {
                justify-content: center;
            }

            .tech-tag {
                padding: 6px 12px;
                font-size: 0.8rem;
            }
        }
    </style>

    <body class="bg-light">

        <div class="container">

            <h1 class="section-title mb-4">Your Shopping Cart</h1>

            @if(count($cartItems) > 0)
                <div class="row g-4">
                    <!-- Cart Items -->
                    <div class="col-lg-8">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                @foreach($cartItems as $id => $item)
                                    <!-- Cart Item -->
                                    <div class="row g-3 align-items-center py-3 border-bottom">
                                        <div class="col-md-2">
                                            <img src="{{ asset('frontend/images/6.jpg') }}" alt="{{ $item['name'] }}"
                                                class="img-fluid rounded cart-item-img">
                                        </div>
                                        <div class="col-md-6">
                                            <h5 class="mb-1">{{ $item['name'] }}</h5>
                                            <p class="h5 text-primary mb-2">${{ number_format($item['price'], 2) }}</p>

                                            <form action="{{ route('cart.remove', $id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-link text-danger p-0">
                                                    <i class="fas fa-trash-alt me-1"></i> Remove
                                                </button>
                                            </form>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="d-flex align-items-center">
                                                <form action="{{ route('cart.decrease', $id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit"
                                                        class="btn btn-outline-secondary px-3 py-1 quantity-btn">-</button>
                                                </form>
                                                <input type="number" value="{{ $item['quantity'] }}" min="1"
                                                    class="form-control quantity-input mx-2" readonly>
                                                <form action="{{ route('cart.increase', $id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit"
                                                        class="btn btn-outline-secondary px-3 py-1 quantity-btn">+</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach



                            </div>
                        </div>
                    </div>

                    <!-- Cart Summary -->
                    <div class="col-lg-4">
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

                                <a href="{{ route('checkout') }}" class="btn bg-gradient-primary text-white w-100 py-2 mb-3">
                                    <i class="fas fa-lock me-2"></i> Proceed to Checkout
                                </a>

                                <div class="text-center">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Empty Cart State -->
                <div class="text-center py-5">
                    <i class="fas fa-shopping-cart display-4 text-secondary mb-4"></i>
                    <h2 class="h3 mb-3">Your cart is empty</h2>
                    <p class="text-secondary mb-4">Looks like you haven't added any items to your cart yet.</p>

                </div>
            @endif
        </div>
@endsection