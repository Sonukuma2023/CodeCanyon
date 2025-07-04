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
                    <img src="{{ asset('frontend/images/5.jpg') }}" alt="Thumbnail" style="width: 80%;mix-blend-mode: multiply;">
                </div>
            </div>
            
        </div>
    </section>
	
	
	<!-- Custom Tools Section -->
	<section class="tools-section bg-white">
		<div class="container">
			<div class="row g-4">

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
			</div>
		</div>
	</section>


    <!-- Categories Section -->
    <section class="categories-section">
        <div class="container">
            <h2 class="section-title">Popular Categories</h2>
            <div class="categories-grid">
                @foreach ($categories as $category)
                    <a href="{{ route('user.showCategoryProducts', ['slug' => strtolower($category->name)]) }}" class="category-card">
                        <div class="category-icon">
                            @if(strtolower($category->name) == 'html5')
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
                    @if($product->status != 'pending')
                        <div class="product-card">
                            <div class="product-image">
                                <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}" loading="lazy">
                                <a href="{{ route('user.singleproduct', $product->id) }}" class="quick-view" data-product-id="{{ $product->id }}">Quick View</a>
                            </div>
                            
                            <div class="product-details">
                                <h3 class="product-title">{{ $product->name }}</h3>
                                <div class="product-author">by <a href="#">{{ $product->name }}</a></div>
                                
                                <div class="product-meta">
                                    <div class="rating">
                                        <div class="stars">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star-half-alt"></i>
                                        </div>
                                    </div>
                                    <div class="sales">
                                        <i class="fas fa-chart-line"></i> 1200+ sales
                                    </div>
                                </div>
                                
                                <div class="product-footer">
                                    <!-- Product price -->
                                    <div class="price">${{ number_format($product->regular_license_price, 2) }}</div>
                                        <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                            @csrf
                                            <button class="btn btn-primary btn-sm" style="border:none;">
                                            Add to Cart</button>
                                        </form>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

        </div>
    </section>
	
	@auth
		<!-- Floating Chat Button -->
		<div id="chatToggleBtn" style="position: fixed; bottom: 30px; right: 30px; z-index: 9999;">
			<button class="btn btn-primary rounded-circle" style="width: 60px; height: 60px;">
				<i class="fas fa-comment-alt" style="font-size: 20px;"></i>
				<span id="unreadCountBadge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 12px; display: none;">
					0
				</span>
			</button>
		</div>

		<!-- Custom Floating Chat Modal -->
		<div id="supportChatModal" style="position: fixed;bottom: 100px; right: 30px; width: 320px; background: #fff;border-radius: 10px; box-shadow: 0 5px 25px rgba(0, 0, 0, 0.2); z-index: 9999; display: none; overflow: hidden;">
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
						<textarea class="form-control mb-2" rows="2" id="message_content" name="message_content" placeholder="Type your message..."></textarea>
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
$(document).ready(function () {
	$('#chatToggleBtn').on('click', function () {
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
			success: function () {
				$('#unreadCountBadge').hide();
			}
		});
	}

    function fetchMessages() {
        $.get("{{ route('user.fetchMessages') }}", function (res) {
            $('#messages-container').html('');
			let unreadCount = 0;
			
            res.messages.forEach(function (msg) {
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

    $('#chatForm').on('submit', function (e) {
        e.preventDefault();
        const message = $('#message_content').val().trim();
        if (!message) return;

        $.post("{{ route('user.messageSave') }}", {
            _token: '{{ csrf_token() }}',
            message_content: message
        }, function () {
            $('#message_content').val('');
            fetchMessages();
        });
    });
	
	$('#chatToggleBtn').on('click', function () {
		const modal = $('#supportChatModal');

		modal.fadeIn();
		fetchMessages();
		markMessagesAsRead();
	});
});
</script>
@endsection