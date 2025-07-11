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
            <!-- Sidebar Filter -->
            <div class="col-md-3">
                <form id="filter-form" action="{{ route('products.search') }}" method="GET">
                    <h5 class="mb-3">Filter & Refine</h5>

                    <!-- Category -->
                    <label class="form-label">Category ({{ $categories->count() }})</label>
                    <div class="mb-3">
                        @if ($categories->count() > 0)
                            <select class="form-select" name="category_id">
                                <option value="">Select Category</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        @endif
                    </div>

                    <!-- Price -->
                    <div class="mb-3">
                        <label class="form-label">Price</label>
                        <div class="d-flex gap-2">
                            <input type="number" name="min_price" class="form-control" placeholder="Min">
                            <input type="number" name="max_price" class="form-control" placeholder="Max">
                        </div>
                    </div>

                    <input type="hidden" name="product_name" value="{{ $query }}">

                    <!-- On Sale -->
                    <div class="mb-3">
                        <h5 class="form-label">On Sale</h5>
                        <input type="checkbox" name="onsale[]" value="1"
                            {{ in_array('1', request()->get('onsale', [])) ? 'checked' : '' }}>
                        <label>Yes</label>
                    </div>

                    <!-- Sales Levels -->
                    <div class="mb-3">
                        <h5>Sales</h5>
                        @php
                            $salesOptions = ['no sale', 'low', 'medium', 'high', 'top sellers'];
                        @endphp
                        @foreach ($salesOptions as $option)
                            <div>
                                <input type="checkbox" name="sales[]" value="{{ $option }}"
                                    {{ in_array($option, request()->get('sales', [])) ? 'checked' : '' }}>
                                <label>{{ ucfirst($option) }}</label>
                            </div>
                        @endforeach
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-filter-circle me-1"></i> Apply Filters
                        </button>
                    </div>
                </form>
            </div>

            <!-- Product List -->
            <div class="col-md-9">
                <div id="filtered-products" class="mt-4"></div>
                <div id="search_sales_products" class="mt-4"></div>
                <div id="static-products" class="row">
                    @forelse($search_products as $index => $product)
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 shadow-sm">
                                <img src="{{ asset('storage/' . $product->thumbnail) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
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
                            <div class="alert alert-warning text-center shadow-sm rounded">
                                <h5 class="mb-1"><i class="bi bi-emoji-frown text-warning"></i> No Products Found</h5>
                                <p class="mb-0">Try adjusting your filters or search keywords.</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
               <script>
                function clearPriceFilter() {
                    // Clear input values
                    $('#min_price').val('');
                    $('#max_price').val('');

                    // Remove the badge
                    $('#price-badge').remove();

                    // Re-submit the form to reload filtered results
                   $('#filter-form').trigger('submit');
                }

               function clearFilter(type) {
                    if (type === 'onsale') {

                        $('input[name="onsale[]"]').prop('checked', false);


                        $('#onsale-badge').remove();
                    }


                    $('#salesFilterForm input[type="checkbox"]').trigger('change');
                }

              function clearSingleSales(value) {
                    // Uncheck the checkbox
                    $(`input[name="sales[]"][value="${value}"]`).prop('checked', false);

                    // Remove the badge (optional if you added ID)
                    let safeValue = value.replace(/\s+/g, '-');
                    $(`#sales-badge-${safeValue}`).remove();

                    // Trigger the AJAX filter
                    $('#salesFilterForm input[type="checkbox"]').trigger('change');
                }



                </script>

    <script>
        $(document).ready(function() {



            // $('#salesFilterForm input[type="checkbox"]').on('change', function() {

            //     //     let form = $('#salesFilterForm');
            //     //     let actionUrl = form.attr('action');

            //     //     let formData = form.serialize();

            //     //     $.ajax({

            //     //         url: actionUrl,
            //     //         type: 'GET',
            //     //         data: formData,

            //     //         success: function(response) {
            //     //             let products = response.products;
            //     //             let output = '';

            //     //             if (products.length === 0) {
            //     //                 output = '<p>No products found.</p>';
            //     //             } else {
            //     //                 $('#static-products').hide();
            //     //                 $('#filtered-products').hide();

            //     //                 for (let i = 0; i < products.length; i++) {
            //     //                     // Start a new row for every 3 products
            //     //                     if (i % 3 === 0) {
            //     //                         output += '<div class="row mb-4">';
            //     //                     }

            //     //                     let product = products[i];
            //     //                     let imageUrl = `/storage/${product.thumbnail}`;

            //     //                     output += `
        //     //                         <div class="col-md-4">
        //     //                             <div class="card h-100 shadow-sm">
        //     //                                 <img src="${imageUrl}" class="card-img-top" alt="${product.name}" style="height: 200px; object-fit: cover;">
        //     //                                 <div class="card-body d-flex flex-column">
        //     //                                     <h5 class="card-title">${product.name}</h5>
        //     //                                     <p class="card-text">${product.description || ''}</p>
        //     //                                     <div class="mt-auto">
        //     //                                         <p class="fw-bold text-primary">$${product.regular_license_price}</p>
        //     //                                         <button class="btn btn-outline-success w-100">
        //     //                                             <i class="fas fa-cart-plus"></i> Add to Cart
        //     //                                         </button>
        //     //                                     </div>
        //     //                                 </div>
        //     //                             </div>
        //     //                         </div>
        //     //                     `;

            //     //                     // Close the row after 3 products or at the end
            //     //                     if ((i + 1) % 3 === 0 || i === products.length - 1) {
            //     //                         output += '</div>';
            //     //                     }
            //     //                 }
            //     //             }

            //     //             $('#search_sales_products').html(output);
            //     //         },
            //     //         error: function(xhr) {
            //     //             console.error('Error:', xhr.responseText);
            //     //         }
            //     //     });
            // });

            $('#salesFilterForm input[type="checkbox"]').on('change', function() {
                let form = $('#salesFilterForm');
                let actionUrl = form.attr('action');
                let formData = form.serialize();

                const startTime = performance.now();

                $.ajax({
                    url: actionUrl,
                    type: 'GET',
                    data: formData,
                    success: function(response) {
                        const endTime = performance.now();
                        const responseTime = (endTime - startTime).toFixed(2);

                        let products = response.products;
                        let output = '';
                        let filterBadges = [];

                        // Onsale filters
                        $('input[name="onsale[]"]:checked').each(function() {
                            filterBadges.push(
                                `<span class="badge bg-light text-dark border me-2">On Sale: Yes <span class="ms-1 text-danger" style="cursor:pointer;" onclick="clearFilter('onsale')">×</span></span>`
                            );
                        });

                        // Sales filters
                        $('input[name="sales[]"]:checked').each(function() {
                            filterBadges.push(
                                `<span class="badge bg-light text-dark border me-2">Sales: ${$(this).val()} <span class="ms-1 text-danger" style="cursor:pointer;" onclick="clearSingleSales('${$(this).val()}')">×</span></span>`
                            );
                        });

                        // Response time badge
                        filterBadges.push(

                        );

                        $('#relevant').html(filterBadges.join(' '));

                        // Render Products
                        if (products.length === 0) {
                            output = '<p>No products found.</p>';
                        } else {
                            $('#static-products').hide();
                            $('#filtered-products').hide(); // optional
                            $('#search_sales_products').show();

                            for (let i = 0; i < products.length; i++) {
                                if (i % 3 === 0) output += '<div class="row mb-4">';

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

                                if ((i + 1) % 3 === 0 || i === products.length - 1) {
                                    output += '</div>';
                                }
                            }
                        }

                        $('#search_sales_products').html(output);
                    },
                    error: function(xhr) {
                        console.error('Error:', xhr.responseText);
                    }
                });
            });




            // $('#filter-form').on('submit', function(e) {
            //     e.preventDefault();

            //     //     //     let formData = $(this).serialize();

            //     //     //     $.ajax({
            //     //     //         url: "{{ route('filter.products') }}",
            //     //     //         method: "GET",
            //     //     //         data: formData,
            //     //     //         success: function(response) {
            //     //     //             let products = response.products;
            //     //     //             let output = '';

            //     //     //             if (products.length === 0) {
            //     //     //                 output = '<p>No products found.</p>';
            //     //     //             } else {
            //     //     //                 $('#static-products').hide();
            //     //     //                  $('#search_sales_products').show();

            //     //     //                 for (let i = 0; i < products.length; i++) {
            //     //     //                     // Start a new row for every 3 products
            //     //     //                     if (i % 3 === 0) {
            //     //     //                         output += '<div class="row mb-4">';
            //     //     //                     }

            //     //     //                     let product = products[i];
            //     //     //                     let imageUrl = `/storage/${product.thumbnail}`;

            //     //     //                     output += `
        //     //     //                         <div class="col-md-4">
        //     //     //                             <div class="card h-100 shadow-sm">
        //     //     //                                 <img src="${imageUrl}" class="card-img-top" alt="${product.name}" style="height: 200px; object-fit: cover;">
        //     //     //                                 <div class="card-body d-flex flex-column">
        //     //     //                                     <h5 class="card-title">${product.name}</h5>
        //     //     //                                     <p class="card-text">${product.description || ''}</p>
        //     //     //                                     <div class="mt-auto">
        //     //     //                                         <p class="fw-bold text-primary">$${product.regular_license_price}</p>
        //     //     //                                         <button class="btn btn-outline-success w-100">
        //         //     //                                             <i class="fas fa-cart-plus"></i> Add to Cart
        //     //     //                                         </button>
        //     //     //                                     </div>
        //     //     //                                 </div>
        //     //     //                             </div>
        //     //     //                         </div>
        //     //     //                     `;

            //     //     //                     // Close the row after 3 products or at the end
            //     //     //                     if ((i + 1) % 3 === 0 || i === products.length - 1) {
            //     //     //                         output += '</div>';
            //     //     //                     }
            //     //     //                 }
            //     //     //             }

            //     //     //             $('#filtered-products').html(output);

            //     //     //         },

            //     //     //         error: function(xhr) {
            //     //     //             console.error(xhr.responseText);
            //     //     //             alert('Something went wrong.');
            //     //     //         }
            //     //     //     });
            // });

            $('#filter-form').on('submit', function(e) {
                e.preventDefault();

                let formData = $(this).serialize();

                const startTime = performance.now(); // Start timing

                $.ajax({
                    url: "{{ route('filter.products') }}",
                    method: "GET",
                    data: formData,
                    success: function(response) {
                        const endTime = performance.now(); // End timing
                        const responseTime = (endTime - startTime).toFixed(2); // in ms

                        // Show response time in console or UI
                        console.log(`Response Time: ${responseTime} ms`);

                        // Optional: Show response time in the UI
                        $('#relevant1').append(`
                            <span class="badge bg-info text-dark ms-2">
                                // Response: ${responseTime} ms
                            </span>
                        `);

                        // Continue with your existing logic...
                        let products = response.products;
                        let output = '';

                        let minPrice = $('#min_price').val();
                        let maxPrice = $('#max_price').val();
                        let relevantHTML = '';

                        if (minPrice || maxPrice) {
                            let rangeText = '$';
                            rangeText += minPrice ? minPrice : '0';
                            rangeText += ' - $';
                            rangeText += maxPrice ? maxPrice : '∞';

                            relevantHTML += `
                                <span class="badge bg-light text-dark border me-2">
                                    price:
                                    ${rangeText}

                                    <span class="ms-1 text-danger" style="cursor:pointer;" onclick="clearPriceFilter()">×</span>
                                </span>
                            `;
                        }

                        $('#relevant1').html(relevantHTML);

                        if (products.length === 0) {
                            output = '<p>No products found.</p>';
                        } else {
                            $('#static-products').hide();
                            $('#search_sales_products').show();

                            for (let i = 0; i < products.length; i++) {
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
