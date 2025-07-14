@extends('user.layouts.master')

@section('content')
    <style>
        .chat-box {
            max-height: 400px;
            min-height: 300px;
            overflow-y: auto;
            padding: 15px;
            background: #f9f9f9;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .message {
            max-width: 70%;
            padding: 10px 15px;
            border-radius: 15px;
            margin-bottom: 15px;
            position: relative;
            word-wrap: break-word;
            display: inline-block;
            clear: both;
        }

        .message.you {
            background-color: #dcf8c6;
            color: #333;
            float: right;
            text-align: right;
        }

        .message.other {
            background-color: #fff;
            border: 1px solid #ddd;
            color: #333;
            float: left;
            text-align: left;
        }

        .timestamp {
            display: block;
            font-size: 11px;
            color: #999;
            margin-top: 5px;
        }

        .chat-input textarea {
            resize: none;
        }

        @media (max-width: 576px) {
            #supportChatModal {
                right: 10px;
                left: 10px;
                width: auto;
            }

        }

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

        .fa-cart-plus,
        .fa-check {
            margin-right: 6px;
            font-size: 0.9rem;
        }
    </style>

    <section class="hero">
        <div class="container">
            <div class="col-md-6">
                <h1 class="hero-title">Build Amazing Projects Faster</h1>
                <p class="hero-subtitle">Premium code, templates, and plugins for developers and creators</p>
                <div class="hero-cta">
                    <a href="#" class="btn btn-primary btn-lg">Browse Products</a>
                    <a href="#" class="btn btn-primary btn-lg">Become an Author</a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="hero-image">
                    <img src="{{ asset('frontend/images/5.jpg') }}" alt="Thumbnail"
                        style="width: 80%;mix-blend-mode: multiply;">
                </div>
            </div>

        </div>
    </section>


    <!-- Custom Tools Section -->
    <section class="tools-section bg-white">
        <div class="container">
            <div class="row g-4">

                <!-- Community Card -->
                <div class="col-md-3">
                    <a href="{{ route('user.communityList') }}" class="category-card text-center">
                        <div class="category-icon">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <h3>Community</h3>
                        <p class="item-count">Discuss & Share</p>
                    </a>
                </div>

                <!-- Script Runner Card -->
                <div class="col-md-3">
                    <a href="{{ route('user.scriptRunnerPage') }}" class="category-card text-center">
                        <div class="category-icon">
                            <i class="bi bi-terminal-fill"></i>
                        </div>
                        <h3>Script Runner</h3>
                        <p class="item-count">Try & Execute Code</p>
                    </a>
                </div>

                <!-- All Products Card -->
                <div class="col-md-3">
                    <a href="{{ route('user.allProducts') }}" class="category-card text-center">
                        <div class="category-icon">
                            <i class="bi bi-box-seam"></i> <!-- Box icon for products -->
                        </div>
                        <h3>All Products</h3>
                        <p class="item-count">Browse All Scripts</p>
                    </a>
                </div>

            </div>
        </div>
    </section>



    <!-- Categories Section -->
    <section class="categories-section">
        <div class="container">
            <h2 class="section-title">Popular Categories</h2>
            <div class="categories-grid">
                @foreach ($categories as $category)
                    <a href="{{ route('user.showCategoryProducts', ['slug' => strtolower($category->name)]) }}"
                        class="category-card">
                        <div class="category-icon">
                            @if (strtolower($category->name) == 'html5')
                                <i class="fab fa-html5"></i>
                            @elseif(strtolower($category->name) == 'css')
                                <i class="fab fa-css3-alt"></i>
                            @elseif(strtolower($category->name) == 'javascript')
                                <i class="fab fa-js-square"></i>
                            @elseif(strtolower($category->name) == 'php')
                                <i class="fab fa-php"></i>
                            @elseif(strtolower($category->name) == 'java')
                                <i class="fab fa-java"></i>
                            @elseif(strtolower($category->name) == 'mysql')
                                <i class="fas fa-database"></i>
                            @elseif(strtolower($category->name) == 'wordpress')
                                <i class="fab fa-wordpress"></i>
                            @elseif(strtolower($category->name) == 'laravel')
                                <i class="fab fa-laravel"></i>
                            @elseif(strtolower($category->name) == 'react')
                                <i class="fab fa-react"></i>
                            @elseif(strtolower($category->name) == 'vue')
                                <i class="fab fa-vuejs"></i>
                            @elseif(strtolower($category->name) == 'nodejs')
                                <i class="fab fa-node-js"></i>
                            @elseif(strtolower($category->name) == 'angular')
                                <i class="fab fa-angular"></i>
                            @elseif(strtolower($category->name) == 'bootstrap5')
                                <i class="fab fa-bootstrap"></i>
                            @elseif(strtolower($category->name) == 'github')
                                <i class="fab fa-github"></i>
                            @elseif(strtolower($category->name) == 'aws')
                                <i class="fab fa-aws"></i>
                            @else
                                <i class="fas fa-folder"></i>
                            @endif
                        </div>
                        <h3>{{ $category->name }}</h3>
                        <p class="item-count">420+ Templates</p>
                    </a>
                @endforeach
            </div>
        </div>
    </section>



    <!-- Featured Products -->
    <section class="featured-products">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">2025's Best Selling Scripts</h2>
                <a href="#" class="view-all">View All <i class="fas fa-arrow-right"></i></a>
            </div>

            <div class="products-grid">
                @foreach ($products as $product)
                    @if ($product->status != 'pending')
                        <div class="product-card">
                            <div class="product-image">
                                <img src="{{ asset('storage/uploads/thumbnails/' . $product->thumbnail) }}"
                                    alt="{{ $product->name }}" loading="lazy">
                                <a href="{{ route('user.singleproduct', $product->id) }}" class="quick-view"
                                    data-product-id="{{ $product->id }}">Quick View</a>
                            </div>

                            <div class="product-details">
                                <h3 class="product-title">{{ $product->name }}</h3>

                                <div class="product-author">by <a href="#">{{ $product->name }}</a></div>

                                <div class="product-meta">
                                    @php
                                        $userReviews = $reviewsByProduct[$product->id] ?? collect();
                                        $rating = $userReviews->first()->rating ?? 0;
                                    @endphp
                                    @if ($rating > 0)
                                        @for ($i = 1; $i <= $rating; $i++)
                                            <i class="fas fa-star text-warning"></i>
                                        @endfor
                                        @for ($j = $rating + 1; $j <= 5; $j++)
                                            <i class="far fa-star text-muted"></i> {{-- empty star --}}
                                        @endfor
                                    @else
                                        <span>No reviews</span>
                                    @endif

                                    <div class="check Check_product">
                                        <button class="btn btn-success addToRatingBtn" data-id="{{ $product->id }}">Add
                                            to Rating</button>
                                    </div>



                                </div>

                                <div class="product-footer">
                                    <!-- Product price -->
                                    <div class="price">${{ number_format($product->regular_license_price, 2) }}</div>
                                    <button class="addtocart" data-id="{{ $product->id }}"
                                        data-price="{{ $product->regular_license_price }}">
                                        <div class="pretext">
                                            <i class="fas fa-cart-plus"></i> ADD TO CART
                                        </div>

                                        <div class="done">
                                            <div class="posttext"><i class="fas fa-check"></i> ADDED</div>
                                        </div>

                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

        </div>
    </section>

    <div class="modal fade" id="reviewModal" tabindex="-1"
        aria-labelledby="reviewModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="reviewForm" method="POST" action="{{ route('submit.review') }}">
                @csrf
                <input type="hidden" name="order_id" id="order_id" value="">
                <input type="hidden" name="product_id" id="product_id" value="">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Rate Your Order</h5>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3 text-center">
                            <label class="form-label d-block">Your Rating</label>
                            <div class="star-rating">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="star bi bi-star-fill" data-value="{{ $i }}"
                                        style="cursor: pointer; font-size: 24px;"></i>
                                @endfor
                                <input type="hidden" name="rating" id="ratingValue" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="review" class="form-label">Write a Review</label>
                            <textarea name="review" class="form-control" rows="3" id="review" placeholder="Share your experience..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="submit" class="btn btn-success">Submit Review</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @auth
        <!-- Floating Chat Button -->
        <div id="chatToggleBtn" style="position: fixed; bottom: 30px; right: 30px; z-index: 9999;">
            <button class="btn btn-primary rounded-circle" style="width: 60px; height: 60px;">
                <i class="fas fa-comment-alt" style="font-size: 20px;"></i>
                <span id="unreadCountBadge"
                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                    style="font-size: 12px; display: none;">
                    0
                </span>
            </button>
        </div>

        <!-- Custom Floating Chat Modal -->
        <div id="supportChatModal"
            style="position: fixed;bottom: 100px; right: 30px; width: 320px; background: #fff;border-radius: 10px; box-shadow: 0 5px 25px rgba(0, 0, 0, 0.2); z-index: 9999; display: none; overflow: hidden;">
            <div class="modal-header bg-primary text-white d-flex justify-content-between align-items-center px-3 py-2">
                <h5 class="modal-title mb-0">Support Chat</h5>
                <button type="button" class="btn-close btn-close-white" onclick="$('#supportChatModal').hide()"></button>
            </div>

            <div class="modal-body p-3" style="flex: 1;">
                <div class="chat-box" id="chatBox">
                    <div id="messages-container"></div>
                </div>
                <form id="chatForm" class="mt-2">
                    @csrf
                    <div class="chat-input">
                        <textarea class="form-control mb-2" rows="2" id="message_content" name="message_content"
                            placeholder="Type your message..."></textarea>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary w-100">Send</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    @endauth

    <!-- Call to Action -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content">
                <h2>Ready to Start Selling?</h2>
                <p>Join our community of 50,000+ authors and reach millions of customers worldwide.</p>
                <a href="#" class="btn btn-light btn-lg">Become an Author</a>
            </div>
        </div>
    </section>

@endsection
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>




    <!-- ***************************************new code *********************************** -->
    <script>
        $('.star').on('click', function() {
            const rating = $(this).data('value');
            $('#ratingValue').val(rating);

            $('.star').each(function() {
                const value = $(this).data('value');
                if (value <= rating) {
                    $(this).addClass('text-warning');
                } else {
                    $(this).removeClass('text-warning');
                }
            });
        });


        document.addEventListener("DOMContentLoaded", function() {
            const modalElement = document.getElementById('reviewModal');
            const modal = new bootstrap.Modal(modalElement, {
                backdrop: 'static',
                keyboard: true
            });

            // Show modal when button is clicked
            // document.getElementById('addToRatingBtn').addEventListener('click', function() {
            //     modal.show();
            // });

            // Handle star selection
            const stars = document.querySelectorAll('.star-rating .star');
            const ratingInput = document.getElementById('ratingValue');

            stars.forEach((star, index) => {
                star.addEventListener('click', function() {
                    const rating = this.getAttribute('data-value');
                    ratingInput.value = rating;

                    stars.forEach(s => s.classList.remove('text-warning'));
                    for (let i = 0; i < rating; i++) {
                        stars[i].classList.add('text-warning');
                    }
                });
            });
              modalElement.addEventListener('click', function (e) {
                modal.hide();
            });
             modalElement.querySelector('.modal-content').addEventListener('click', function (e) {
                e.stopPropagation();
            });

        });
    </script>


    <script>
        $(document).ready(function() {
            $('#reviewForm').on('submit', function(e) {
                e.preventDefault();

                const form = $(this);
                const actionUrl = form.attr('action');
                const formData = form.serialize();

                $.ajax({
                    url: actionUrl,
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.redirect_url) {
                            window.location.href = response.redirect_url;
                        }
                    },
                    error: function(xhr) {
                        const response = xhr.responseJSON;

                        if (xhr.status === 409 && response?.error && response?.redirect_url) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Already Reviewed',
                                text: response.error,
                                confirmButtonText: 'Go to Order'
                            }).then(() => {
                                window.location.href = response.redirect_url;
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response?.error || 'Something went wrong.',
                                confirmButtonText: 'OK'
                            });
                        }
                    }
                });
            });
        });
    </script>



    <!-- **********************************new _code******************************************* -->
    <script>
        $(document).ready(function() {



            // new change ******************************
            $('.addToRatingBtn').on('click', function(e) {
                e.preventDefault();
                const productId = $(this).data('id');
                $.ajax({
                    url: '{{ route('ajax.rating.init') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        product_id: productId
                    },
                    success: function(response) {

                        if (response.status === 'success') {

                            if (response.status === 'success' && response.add_rating ===
                                'add rating') {
                                $('#order_id').val(response.order_id);
                                $('#product_id').val(response.product_id);
                                $('#reviewModal').modal('show');

                            }
                            $('#order_id').val(response.order_id);
                            $('#product_id').val(response.product_id);
                            // $('#review').val(response.response);

                            // Set rating if it exists
                            if (response.rating) {
                                $('#ratingValue').val(response.rating);
                                $('.star').each(function() {
                                    const starValue = $(this).data('value');
                                    if (starValue <= response.rating) {
                                        $(this).addClass(
                                        'text-warning');
                                    } else {
                                        $(this).removeClass('text-warning');
                                    }
                                });
                            } else {
                                $('.star').removeClass('text-warning');
                                $('#ratingValue').val('');
                            }


                            if (response.review) {
                                $('textarea[name="review"]').val(response.review);
                            } else {
                                $('textarea[name="review"]').val('');
                            }

                            $('#reviewModal').modal('show');
                        }

                    },
                    error: function(xhr) {
                        Swal.fire('Error', 'You have not ordered this product.', 'error');
                    }
                });
            });



            $('#chatToggleBtn').on('click', function() {
                const modal = $('#supportChatModal');
                if (modal.is(':visible')) {
                    modal.fadeOut();
                } else {
                    modal.fadeIn();
                    fetchMessages();
                }
            });


            function convertUtcToLocal(utcDateTime) {
                const utcDate = new Date(utcDateTime + ' UTC');
                return moment(utcDate).fromNow();
            }

            function markMessagesAsRead() {
                $.ajax({
                    url: "{{ route('user.markMessagesAsRead') }}",
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function() {
                        $('#unreadCountBadge').hide();
                    }
                });
            }

            function fetchMessages() {
                $.get("{{ route('user.fetchMessages') }}", function(res) {
                    $('#messages-container').html('');
                    let unreadCount = 0;

                    res.messages.forEach(function(msg) {
                        const isUser = msg.sender_id === {{ auth()->id() }};
                        const cls = isUser ? 'you' : 'other';
                        const time = convertUtcToLocal(msg.sent_at);

                        if (!isUser && msg.read_at === null) {
                            unreadCount++;
                        }

                        $('#messages-container').append(`
                    <div class="message ${cls}">
                        ${msg.message}
                        <span class="timestamp">${time}</span>
                    </div>
                    <div class="clearfix"></div>
                `);
                    });
                    $('#chatBox').scrollTop($('#chatBox')[0].scrollHeight);

                    const badge = $('#unreadCountBadge');
                    const modal = $('#supportChatModal');

                    if (!modal.is(':visible')) {
                        if (unreadCount > 0) {
                            badge.text(unreadCount).show();
                        } else {
                            badge.hide();
                        }
                    } else {
                        if (unreadCount > 0) {
                            markMessagesAsRead();
                        }
                    }

                });
            }

            fetchMessages();
            setInterval(fetchMessages, 60000);

            var channel = pusher.subscribe('my-channel');
            channel.bind('MessageSent', function(data) {
                fetchMessages();
            });

            $('#chatForm').on('submit', function(e) {
                e.preventDefault();
                const message = $('#message_content').val().trim();
                if (!message) return;

                $.post("{{ route('user.messageSave') }}", {
                    _token: '{{ csrf_token() }}',
                    message_content: message
                }, function() {
                    $('#message_content').val('');
                    fetchMessages();
                });
            });

            $('#chatToggleBtn').on('click', function() {
                const modal = $('#supportChatModal');

                modal.fadeIn();
                fetchMessages();
                markMessagesAsRead();
            });
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).on('click', '.addtocart', function(e) {
            e.preventDefault();

            const button = $(this);
            const productId = button.data('id');
            const price = button.data('price');

            if (button.hasClass('processing') || button.hasClass('added')) return;

            button.addClass('processing');

            $.ajax({
                url: "{{ route('user.saveCart', ':id') }}".replace(':id', productId),
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    quantity: 1,
                    price: price
                },
                success: function(response) {
                    if (response.success) {
                        $('.cart-count').text(response.cartCount);
                        button.addClass('added');

                        setTimeout(() => {
                            button.removeClass('added processing');
                        }, 2000);
                    } else {
                        button.removeClass('processing');
                        console.log(response);
                    }
                },
                error: function(xhr) {
                    console.log(xhr);
                    button.removeClass('processing');
                }
            });
        });
    </script>
@endsection
