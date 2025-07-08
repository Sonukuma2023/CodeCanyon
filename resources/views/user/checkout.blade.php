@extends('user.layouts.master')
@section('content')

<div class="py-5">
    <div class="container">
        @if(empty($cartItems))
            <div class="alert alert-warning text-center">
                Your cart is empty. Please <a href="{{ route('dashboard') }}">add some products</a> before checking out.
            </div>
        @else
        <form id="payment-form" method="POST" action="{{ route('checkout.process') }}">
        @csrf
        <div class="row g-4">
            <!-- Left: Shipping + Payment -->
            <div class="col-lg-7">
                <div class="card p-4 shadow-sm border-0 mb-4">
                    <h2 class="mb-4">Shipping Information</h2>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="first_name" class="form-label">First Name</label>
                                    <input type="text" class="form-control" name="first_name" id="first_name" value="{{ old('first_name') }}" required>
                                    @error('first_name')<div class="text-danger">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="last_name" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" name="last_name" id="last_name" value="{{ old('last_name') }}" required>
                                    @error('last_name')<div class="text-danger">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" name="email" id="email" value="{{ old('email') }}" required>
                            @error('email')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control" name="address" id="address" value="{{ old('address') }}" placeholder="Street address" required>
                            @error('address')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="city" class="form-label">City</label>
                                    <input type="text" class="form-control" name="city" id="city" value="{{ old('city') }}" required>
                                    @error('city')<div class="text-danger">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="zip" class="form-label">ZIP Code</label>
                                    <input type="text" class="form-control" name="zip" id="zip" value="{{ old('zip') }}" required>
                                    @error('zip')<div class="text-danger">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="country" class="form-label">Country</label>
                            <select name="country" id="country" class="form-select" required>
                                <option value="">Select Country</option>
                                @foreach(['US' => 'United States', 'UK' => 'United Kingdom', 'CA' => 'Canada', 'AU' => 'Australia'] as $code => $name)
                                    <option value="{{ $code }}" @if(old('country') == $code) selected @endif>{{ $name }}</option>
                                @endforeach
                            </select>
                            @error('country')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>

                        <h2 class="mb-4">Payment Method</h2>
                        <div class="payment-methods mb-3">
                            @php
                                $paymentMethods = [
                                    ['id' => 'card', 'icon' => 'fab fa-cc-stripe', 'title' => 'Credit/Debit Card', 'desc' => 'Pay with Visa, Mastercard, or others'],
                                ];
                                $selectedPayment = old('payment_method', 'card');
                            @endphp

                            @foreach($paymentMethods as $method)
                                <label class="payment-method {{ $selectedPayment === $method['id'] ? 'active' : '' }}">
                                    <input type="radio" name="payment_method" value="{{ $method['id'] }}" style="display:none" {{ $selectedPayment === $method['id'] ? 'checked' : '' }}>
                                    <i class="{{ $method['icon'] }}"></i>
                                    <div class="payment-method-details">
                                        <div class="payment-method-title">{{ $method['title'] }}</div>
                                        <div class="payment-method-desc">{{ $method['desc'] }}</div>
                                    </div>
                                    @if($selectedPayment === $method['id'])
                                        <i class="fas fa-check-circle text-success"></i>
                                    @endif
                                </label>
                            @endforeach
                            @error('payment_method')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>

                        <div id="stripe-card-element" class="{{ $selectedPayment !== 'card' ? 'd-none' : '' }}">
                            <div class="mb-3">
                                <label class="form-label">Card Details</label>
                                <div id="card-element" class="form-control"></div>
                                <div id="card-errors" class="text-danger mt-1"></div>
                            </div>
                        </div>

                        <input type="hidden" name="stripe_token" id="stripe_token">
                </div>
            </div>

            <!-- Right: Order Summary -->
            <div class="col-lg-5">
                <div class="card p-4 shadow-sm border-0">
                    <h2 class="mb-4">Order Summary</h2>

                    @foreach($cartItems as $item)
                        <div class="d-flex align-items-center mb-3">
                            @if(!empty($item['image']))
                                <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="me-3 rounded" style="width:60px; height:60px; object-fit:cover;">
                            @endif
                            <div class="flex-grow-1">
                                <div class="fw-semibold">{{ $item['name'] }}</div>
                                <small class="text-muted">Qty: {{ $item['quantity'] }}</small>
                            </div>
                            <div class="text-end fw-semibold">${{ number_format($item['price'] * $item['quantity'], 2) }}</div>
                        </div>
                    @endforeach

                    <hr>

                    <div class="d-flex justify-content-between py-2">
                        <span>Subtotal</span>
                        <span>${{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2">
                        <span>Discount</span>
                        <span class="text-danger">- ${{ number_format($discount, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2">
                        <span>Shipping</span>
                        <span>Free</span>
                    </div>
                    <div class="d-flex justify-content-between py-2">
                        <span>Tax</span>
                        <span>${{ number_format($tax, 2) }}</span>
                    </div>

                    <div class="d-flex justify-content-between py-3 fs-5 fw-bold border-top mt-3">
                        <span>Total</span>
                        <span>${{ number_format($total, 2) }}</span>
                    </div>

                    <!-- Hidden fields -->
                    <input type="hidden" name="final_total" id="final_total" value="{{ $total }}">
                    <input type="hidden" name="applied_coupon_code" id="applied_coupon_code" value="">

                    <div class="input-group mb-4">
                        <input type="text" name="coupon_code" id="coupon_code_input" class="form-control" placeholder="Coupon code (optional)">
                        <button type="button" class="btn btn-primary" onclick="applyCoupon()">Apply</button>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mt-3">Complete Order</button>

                    <div class="text-center mt-3 text-muted small">
                        <i class="fas fa-lock me-1 text-success"></i> Secure checkout
                    </div>
                </div>
            </div>
        </div>
        </form>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
function applyCoupon() {
    const couponCode = document.getElementById('coupon_code_input').value;

    if (!couponCode.trim()) {
        Swal.fire('Error', 'Please enter a coupon code.', 'warning');
        return;
    }

    $.ajax({
        url: '{{ route('user.applyCoupon') }}',
        method: 'POST',
        data: {
            coupon_code: couponCode,
            _token: '{{ csrf_token() }}'
        },
        success: function (response) {
            if (response.success) {
                Swal.fire('Success', response.message, 'success');
                updateCartSummary(response.summary);
                const code = document.getElementById('coupon_code_input').value;
                $('#applied_coupon_code').val(code);
            } else {
                Swal.fire('Invalid', response.message, 'warning');
            }
        },
        error: function (xhr) {
            console.error(xhr.responseText);
            Swal.fire('Error', 'Something went wrong. Please try again.', 'error');
        }
    });
}

function updateCartSummary(summary) {
    $('span:contains("Subtotal")').next().text(`$${summary.subtotal}`);
    $('span:contains("Discount")').next().text(`- $${summary.discount}`);
    $('span:contains("Tax")').next().text(`$${summary.tax}`);
    $('span:contains("Total")').next().text(`$${summary.total}`);

    $('#final_total').val(summary.total);
}
</script>
@endsection
