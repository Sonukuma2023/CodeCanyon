<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All In OneScript | One-Stop Shop for Ready-Made Scripts</title>
    <meta name="description" content="Marketplace for premium scripts, plugins, and templates">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
	<!-- Toastr CSS -->
	<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">  

    <!-- Bootstrap CSS (latest stable) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<style>
    .addtocart {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.5em 1.2em;
    border-radius: 25px;
    border: none;
    font-size: 0.9rem;
    background: #0652DD;
    color: #fff;
    cursor: pointer;
    position: relative;
    overflow: hidden;
    transition: transform 0.2s;
    min-width: auto;
    width: auto;
    max-width: 100%;
    margin: 0 auto;
}

.addtocart:hover {
    transform: scale(1.05);
}

.addtocart .pretext {
    position: relative;
    z-index: 2;
    width: 100%;
    height: 100%;
    background: transparent;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: opacity 0.3s ease;
    font-family: 'Quicksand', sans-serif;
}

.addtocart.added .pretext {
    opacity: 0;
}

.addtocart .done {
    position: absolute;
    inset: 0;
    background: #38c172;
    transform: translateX(-100%);
    transition: transform 0.4s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1;
    color: #fff;
}

.addtocart.added .done {
    transform: translateX(0);
}

.addtocart .posttext {
    display: flex;
    align-items: center;
    font-size: 0.9rem;
}

.fa-cart-plus, .fa-check {
    margin-right: 6px;
    font-size: 0.9rem;
}
.addtocart:hover, .addtocart:active, .addtocart:focus-visible, .addtocart:focus-within {
    background: #0652DD !important;
    color: #fff !important;
    transform: unset !important;
}
.addtocart .done {
    background:#38c172 !important;
}
</style>
</head>
<body>

    @include('user.layouts.navbar')

    <div class="page-body-wrapper">

        <div class="main-panel">
            <div class="content-wrapper">
                @include('layouts.loader')
                @yield('content')

            </div>
        </div>
    </div>

    {{-- @include('user.layouts.footer') --}}
    
    <!-- Toastr JS -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://js.stripe.com/v3/"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Bootstrap Bundle JS (includes Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        @if(session('success'))
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "timeOut": "5000"
            };
            toastr.success('{{ session('success') }}');
        @endif

        @if(session('error'))
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "timeOut": "5000"
            };
            toastr.error('{{ session('error') }}');
        @endif

        @if ($errors->any())
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "timeOut": "8000"
            };
            toastr.error("{!! implode('<br>', $errors->all()) !!}");
        @endif

    function setupCheckoutPage() {
        // Toggle between payment methods
        const paymentMethods = document.querySelectorAll('.payment-method input[type="radio"]');
        paymentMethods.forEach(method => {
            method.addEventListener('change', function () {
                const stripeElement = document.getElementById('stripe-card-element');
                const fallbackForm = document.getElementById('fallback-card-form');

                if (this.value === 'card') {
                    stripeElement.classList.remove('d-none');
                    fallbackForm.classList.remove('d-none');
                    initializeStripe();
                } else {
                    stripeElement.classList.add('d-none');
                    fallbackForm.classList.add('d-none');
                }
            });
        });

        // Initialize Stripe if card is selected by default
        const selectedMethod = document.querySelector('.payment-method input[type="radio"]:checked');
        if (selectedMethod && selectedMethod.value === 'card') {
            initializeStripe();
        }
    }

    function initializeStripe() {
        if (typeof Stripe === 'undefined') {
            const script = document.createElement('script');
            script.src = 'https://js.stripe.com/v3/';
            script.onload = () => {
                if (!window.stripeInitialized) {
                    window.stripeInitialized = true;
                    setupStripeElements();
                }
            };
            document.head.appendChild(script);
        } else {
            if (!window.stripeInitialized) {
                window.stripeInitialized = true;
                setupStripeElements();
            }
        }
    }

    function setupStripeElements() {
        const stripe = Stripe('{{ config('services.stripe.key') }}');
        const elements = stripe.elements();

        const style = {
            base: {
                color: '#32325d',
                fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                fontSmoothing: 'antialiased',
                fontSize: '16px',
                '::placeholder': {
                    color: '#aab7c4'
                }
            },
            invalid: {
                color: '#fa755a',
                iconColor: '#fa755a'
            }
        };

        const card = elements.create('card', { style: style });
        card.mount('#card-element');

        card.addEventListener('change', function (event) {
            const displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });

        const form = document.getElementById('payment-form');
        if (form) {
            form.addEventListener('submit', function (event) {
                event.preventDefault();

                const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
                if (paymentMethod !== 'card') {
                    // form.submit();
                    submitViaAjax();
                    return;
                }

                const submitButton = form.querySelector('button[type="submit"]');
                submitButton.disabled = true;

                stripe.createToken(card).then(function (result) {
                    if (result.error) {
                        const displayError = document.getElementById('card-errors');
                        displayError.textContent = result.error.message;
                        submitButton.disabled = false;
                    } else {
                        const hiddenInput = document.createElement('input');
                        hiddenInput.setAttribute('type', 'hidden');
                        hiddenInput.setAttribute('name', 'stripe_token');
                        hiddenInput.setAttribute('value', result.token.id);
                        form.appendChild(hiddenInput);
                        // form.submit();
                        submitViaAjax();
                    }
                });
            });
        }
    }

    // Run setup on DOM load
    document.addEventListener('DOMContentLoaded', setupCheckoutPage);
    </script>

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

        $(document).on('click', '.add-to-cart, .addtocart', function (e) {
            e.preventDefault();

            const button = $(this);
            const productId = button.data('id');
            const price = button.data('price');
            const quantity = 1;

            if (button.hasClass('processing') || button.hasClass('added') || button.prop('disabled')) return;

            button.addClass('processing').prop('disabled', true);

            $.ajax({
                url: "{{ route('user.saveCart', ':id') }}".replace(':id', productId),
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    quantity: quantity,
                    price: price
                },
                success: function (response) {
                    if (response.success) {
                        $('.cart-count').text(response.cartCount);

                        if (button.hasClass('add-to-cart')) {
                            button.addClass('cart-animate');
                            button.find('.cart-icon').addClass('d-none');
                            button.find('.cart-added').removeClass('d-none');

                            setTimeout(() => {
                                button.removeClass('cart-animate processing');
                                button.find('.cart-added').addClass('d-none');
                                button.find('.cart-icon').removeClass('d-none');
                                button.prop('disabled', false);
                            }, 1500);
                        }

                        else if (button.hasClass('addtocart')) {
                            button.addClass('added');

                            setTimeout(() => {
                                button.removeClass('added processing');
                                button.prop('disabled', false);
                            }, 2000);
                        }
                    } else {
                        button.removeClass('processing').prop('disabled', false);
                        // alert(response.message || "Something went wrong.");
                    }
                },
                error: function (xhr) {
                    console.log(xhr);
                    button.removeClass('processing').prop('disabled', false);
                    // alert("Failed to add to cart.");
                }
            });
        });
    });
    </script>
	
	<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
	<script>
		Pusher.logToConsole = true;
		var pusher = new Pusher('d6a1f321f4efde2a2722', {
			cluster: 'ap2'
		});
	</script>
	
	@yield('scripts')
</body>
</html>