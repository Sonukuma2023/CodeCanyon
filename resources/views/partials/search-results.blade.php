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
    </style>

    <div class="container-fluid mt-4">
        <div class="row">
            {{-- Left Sidebar --}}
            <div class="col-md-3">
                {{-- Category Filter --}}
                <label class="form-label">Category {{ $categories->count() }}</label>
                <div class="mb-3">
                    @if ($categories->count() > 0)
                        <select class="form-select" name="category_id">
                            {{-- <option value="">Select</option> --}}
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}">
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    @endif
                </div>
                {{-- *********************************filter price ************************************ --}}

                <form id="filter-form">
                    <h5>Filter & Refine</h5>
                    <div class="mb-3">
                        <label class="form-label">Price</label>
                        <div class="d-flex gap-2">
                            <input type="number" name="min_price" id ="min_price" class="form-control" placeholder="Min">
                            <input type="number" name="max_price" id ="max_price" class="form-control" placeholder="Max">
                            {{-- <h5 class="card-title">{{ $product->name }}</h5> --}}
                            <input type="hidden" name ="product_name" id= "product_name" value="{{$query}}">
                            <span><button type="submit" class="btn btn-primary w-100">></button></span>
                        </div>
                    </div>
                </form>



            </div>

            {{-- Right Product List --}}
            <div class="col-md-9">

                {{-- AJAX Response Container --}}
                <div id="filtered-products" class="mt-4"></div>

                {{-- Static Blade Rendered Products (only show if no AJAX response) --}}
                <div id="static-products" class="row">

                    @forelse($search_products as $index => $product)
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 shadow-sm">
                                <img src="{{ asset('storage/' . $product->thumbnail) }}" class="card-img-top"
                                    alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">{{ $product->name }}</h5>
                                    <p class="card-text text-muted">{{ Str::limit($product->description, 100) }}</p>
                                    <p class="card-text"><strong>Price:</strong> ${{ $product->regular_license_price }}</p>
                                    <form method="POST" action="{{ route('cart.add', $product->id) }}" class="mt-auto">
                                        @csrf
                                        <button class="btn btn-sm btn-success w-100">Add to Cart</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <p>No products found.</p>
                        </div>
                    @endforelse
                </div>

            </div>
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
    <script>
        $(document).ready(function() {


            $('#filter-form').on('submit', function(e) {
                e.preventDefault();

                let formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('filter.products') }}",
                    method: "GET",
                    data: formData,
                    success: function(response) {
                        let products = response.products;
                        let output = '';

                        if (products.length === 0) {
                            output = '<p>No products found.</p>';
                        } else {
                            $('#static-products').hide();
                            for (let i = 0; i < products.length; i++) {
                                // Start a new row for every 3 products
                                if (i % 3 === 0) {
                                    output += '<div class="row mb-4">';
                                }

                                let product = products[i];
                                let imageUrl = `/storage/${product.thumbnail}`;

                                output += `
                                    <div class="col-md-4">
                                        <div class="card h-100 shadow-sm">
                                            <img src="${imageUrl}" class="card-img-top" alt="${product.name}" style="height: 200px; object-fit: cover;">
                                            <div class="card-body d-flex flex-column">
                                                <h5 class="card-title">${product.name}</h5>
                                                <p class="card-text">${product.description || ''}</p>
                                                <div class="mt-auto">
                                                    <p class="fw-bold text-primary">$${product.regular_license_price}</p>
                                                    <button class="btn btn-outline-success w-100">
                                                        <i class="fas fa-cart-plus"></i> Add to Cart
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                `;

                                // Close the row after 3 products or at the end
                                if ((i + 1) % 3 === 0 || i === products.length - 1) {
                                    output += '</div>';
                                }
                            }
                        }

                        $('#filtered-products').html(output);
                    },

                    error: function(xhr) {
                        console.error(xhr.responseText);
                        alert('Something went wrong.');
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

        $(document).on('click', '.add-to-cart-btn', function(e) {
            e.preventDefault();

            const button = $(this);
            const productId = button.data('id');

            $.ajax({
                url: "{{ route('user.saveCart', ':id') }}".replace(':id', productId),
                type: "POST",
                success: function(response) {
                    if (response.success) {
                        $('.cart-count').text(response.cartCount);
                    } else {
                        console.log(response);
                    }
                },
                error: function(xhr) {
                    console.log(xhr);
                }
            });
        });
    </script>
@endsection
