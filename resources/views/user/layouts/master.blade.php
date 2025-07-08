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
</head>
<body>

    @include('user.layouts.navbar')

    <div class="page-body-wrapper">

        <div class="main-panel">
            <div class="content-wrapper">
                @include('layouts.loader');
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