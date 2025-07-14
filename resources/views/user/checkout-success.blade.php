@extends('user.layouts.master')

@section('content')


<div class="container mt-5" >
    @if($order->payment_status === 'paid' ||  $order->payment_status === 'COMPLETED')
    <div class="card shadow-sm p-4 border-success">
        <!-- Animated Success Checkmark -->
        <div class="text-center mb-4">
            <div class="success-animation">
                <!--<svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                    <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/>
                    <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                </svg>-->
            </div>
        </div>

        <h2 class="mb-3 text-center text-success">Payment Successful!</h2>
        <p class="lead text-center">Your order has been placed successfully.</p>

        <div class="order-summary mt-4">
            <h4 class="text-center mb-3">Order Details</h4>
            <ul class="list-group mb-3">
                <li class="list-group-item d-flex justify-content-between">
                    <span><strong>Order ID:</strong></span>
                    <span>{{ $order->id }}</span>
                </li>
                @if($order->total)
                <li class="list-group-item d-flex justify-content-between">
                    <span><strong>Total Amount:</strong></span>
                    <span>${{ number_format($order->total, 2) }}</span>
                </li>
                @endif
                <li class="list-group-item d-flex justify-content-between">
                    <span><strong>Payment Status:</strong></span>
                    <span class="badge bg-success">Paid</span>
                </li>
                @if($order->transaction_id)
                <li class="list-group-item d-flex justify-content-between">
                    <span><strong>Transaction ID:</strong></span>
                    <span>{{ $order->transaction_id }}</span>
                </li>
                @endif
            </ul>
        </div>

        <div class="d-flex justify-content-center gap-3 mt-4">
            <a href="{{ route('dashboard') }}" class="btn btn-primary px-4">Go to Dashboard</a>
            <a href="{{ route('orders') }}" class="btn btn-primary px-4">View My Orders</a>
        </div>
    </div>
    @else
    <div class="card shadow-sm p-4 border-danger">
        <!-- Failure Icon with Animation -->
        <div class="text-center mb-4">
            <div class="failure-animation">
                <svg class="crossmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                    <circle class="crossmark__circle" cx="26" cy="26" r="25" fill="none"/>
                    <path class="crossmark__cross" fill="none" d="M16 16 36 36 M36 16 16 36"/>
                </svg>
            </div>
        </div>

        <h2 class="mb-3 text-center text-danger">Order Processing Failed</h2>
        <p class="lead text-center">We encountered an issue while processing your order.</p>

        @if($order->error_message)
        <div class="alert alert-danger mt-3">
            <p class="mb-0"><strong>Error:</strong> {{ $order->error_message }}</p>
        </div>
        @endif

        <div class="order-summary mt-4">
            <h4 class="text-center mb-3">Order Details</h4>
            <ul class="list-group mb-3">
                <li class="list-group-item d-flex justify-content-between">
                    <span><strong>Order Reference:</strong></span>
                    <span>{{ $order->id }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span><strong>Status:</strong></span>
                    <span class="badge bg-danger">Failed</span>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span><strong>Payment Status:</strong></span>
                    <span class="badge bg-danger">Failed</span>
                </li>
            </ul>
        </div>
    </div>
    @endif
</div>
@if($order->payment_status === 'paid' || $order->payment_status === 'COMPLETED')
<!-- Review & Rating Modal -->
{{-- <div class="modal fade" id="reviewModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
    <div class="modal-dialog">
       <form id="reviewForm" method="POST" action="{{ route('submit.review') }}">
            @csrf
            <input type="hidden" name="order_id" value="{{ $order->id }}">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Rate Your Order</h5>
                </div>
                <div class="modal-body">
                    <div class="mb-3 text-center">
                        <label class="form-label d-block">Your Rating</label>
                        <div class="star-rating">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="star bi bi-star-fill" data-value="{{ $i }}"></i>
                            @endfor
                            <input type="hidden" name="rating" id="ratingValue" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="review" class="form-label">Write a Review</label>
                        <textarea name="review" class="form-control" rows="3" placeholder="Share your experience..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="submit" class="btn btn-success">Submit Review</button>
                </div>
            </div>
        </form>
    </div>
</div> --}}
@endif



@if($order->payment_status === 'paid')
<style>
    /* Success Animation Styles */
    .success-animation {
        margin: 0 auto;
        width: 80px;
        height: 80px;
    }

    .checkmark {
        /* removed margin-top for better vertical alignment */
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: block;
        stroke-width: 4;
        stroke: #4bb71b;
        stroke-miterlimit: 10;
        box-shadow: 0 0 0 rgba(75, 183, 27, 0.4);
        animation: fill 0.4s ease-in-out 0.4s forwards, scale 0.3s ease-in-out 0.9s both;
    }

    .checkmark__circle {
        stroke-dasharray: 166;
        stroke-dashoffset: 166;
        stroke-width: 4;
        stroke-miterlimit: 10;
        stroke: #4bb71b;
        fill: none;
        animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
    }

    .checkmark__check {
        transform-origin: 50% 50%;
        stroke-dasharray: 48;
        stroke-dashoffset: 48;
        animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards;
    }

    @keyframes stroke {
        100% {
            stroke-dashoffset: 0;
        }
    }

    @keyframes scale {
        0%, 100% {
            transform: none;
        }
        50% {
            transform: scale3d(1.1, 1.1, 1);
        }
    }

    @keyframes fill {
        100% {
            box-shadow: inset 0 0 0 100px rgba(75, 183, 27, 0);
        }
    }
</style>
@else
<style>
    /* Failure Animation Styles */
    .failure-animation {
        margin: 0 auto;
        width: 80px;
        height: 80px;
    }

    .crossmark {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: block;
        stroke-width: 4;
        stroke: #ff4444;
        stroke-miterlimit: 10;
        animation: crossmark-rotate 0.7s linear;
    }

    .crossmark__circle {
        stroke-dasharray: 166;
        stroke-dashoffset: 166;
        stroke-width: 4;
        stroke-miterlimit: 10;
        stroke: #ff4444;
        fill: none;
        animation: crossmark-stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
    }

    .crossmark__cross {
        transform-origin: 50% 50%;
        stroke-dasharray: 48;
        stroke-dashoffset: 48;
        animation: crossmark-stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards;
    }

    @keyframes crossmark-stroke {
        100% {
            stroke-dashoffset: 0;
        }
    }

    @keyframes crossmark-rotate {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(45deg);
        }
    }
</style>
<style>
    .star-rating {
        font-size: 2.2rem;
        color: #ccc;
        cursor: pointer;
    }

    .star-rating .star.selected {
        color: gold;
    }

    .star-rating .star {
        transition: color 0.2s;
    }
</style>





@endif








@endsection
