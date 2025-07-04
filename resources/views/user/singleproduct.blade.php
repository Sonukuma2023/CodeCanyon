@extends('user.layouts.master')

@section('content')
    <div class="container product-page">

        <div class="product-grid">
            <!-- Product Gallery -->
            <div class="product-gallery">
                <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}" loading="lazy">
                <div class="thumbnail-grid">
                    <img src="{{ asset('storage/' . $product->inline_preview) }}" alt="{{ $product->name }}" loading="lazy">
                    <img src="{{ asset('storage/' . $product->inline_preview) }}" alt="{{ $product->name }}" loading="lazy">
                    <img src="{{ asset('storage/' . $product->inline_preview) }}" alt="{{ $product->name }}" loading="lazy">
                    <img src="{{ asset('storage/' . $product->inline_preview) }}" alt="{{ $product->name }}" loading="lazy">
                </div>
            </div>

            <!-- Product Info -->
            <div class="product-info">
                <h1 class="product-title">{{ $product->name }}</h1>
                
                <div class="product-meta">
                    <div class="rating-badge">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                        <span>(128 reviews)</span>
                    </div>
                    <div class="sales-badge">
                        <i class="fas fa-chart-line"></i>
                        <span>450+ sales</span>
                    </div>
                </div>

                <div class="price-section">
                    <span class="current-price">${{ number_format($product->regular_license_price, 2) }}</span>
                    <span class="original-price">${{ number_format($product->extended_license_price, 2) }}</span>
                    <span class="discount-badge">DISCOUNT</span>
                </div>

                <div class="">
                    <ul style="list-style: none;padding-left: 0;">
                        <li>Included: Quality checked by All In OneScript</li>
                        <li>Included: Future updates</li>
                        <li>Included: 6 months support from the author</li>
                    </ul>
                    <div class="mt-1">
                        <input type="checkbox" id="extended_price" />
                        <span>Extend support to 12 months</span>
                        <span style="font-size: 20px;">${{ number_format($product->extended_license_price, 2) }}</span>
                    </div>
                </div>

                <div class="product-actions">
                    <div class="price"></div>
                        <form action="{{ route('cart.add', $product->id) }}" method="POST">
                            @csrf
                            <button class="btn btn-primary w-100" style="border:none;"><i class="fas fa-shopping-cart me-2"></i>

                            Add to Cart ${{ number_format($product->regular_license_price, 2) }}</button>
                        </form>
                </div>

                <div class="product-card">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle"></i>Product Details
                    </h3>
                    <p>{{ $product->description }}</p>
                    <ul style="margin-top: 15px; padding-left: 20px;">
                        <li>Built with Laravel 10</li>
                        <li>Vue.js 3 integration</li>
                        <li>Tailwind CSS styling</li>
                        <li>Dark mode support</li>
                        <li>50+ pre-built pages</li>
                    </ul>
                </div>

                <div class="product-card">
                    <h3 class="card-title">
                        <i class="fas fa-microchip"></i>Technical Specifications
                    </h3>
                    <div class="specs-grid">
                        <div class="spec-item">
                            <span>Framework</span>
                            <span>Laravel 10</span>
                        </div>
                        <div class="spec-item">
                            <span>PHP Version</span>
                            <span>8.1+</span>
                        </div>
                        <div class="spec-item">
                            <span>Database</span>
                            <span>MySQL</span>
                        </div>
                        <div class="spec-item">
                            <span>Frontend</span>
                            <span>Vue.js 3</span>
                        </div>
                        <div class="spec-item">
                            <span>CSS Framework</span>
                            <span>Tailwind CSS</span>
                        </div>
                        <div class="spec-item">
                            <span>Responsive</span>
                            <span>Yes</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        <section class="related-products">
            <h2 class="section-title">Related Templates</h2>
            <div class="products-grid">
                @foreach ($relatedProducts as $relatedProduct)
                    <div class="product-card">
                        <div class="product-image">
                            <img src="{{ asset('storage/' . $relatedProduct->thumbnail) }}" alt="{{ $relatedProduct->name }}" loading="lazy">
                            <a href="{{ route('user.singleproduct', $relatedProduct->id) }}" class="quick-view" data-product-id="{{ $relatedProduct->id }}">Quick View</a>
                        </div>
                        
                        <div class="product-details">
                            <h3 class="product-title">{{ $relatedProduct->name }}</h3>
                            <div class="product-author">by <a href="#">{{ $relatedProduct->name }}</a></div>
                            
                            <div class="product-meta">
                                <div class="rating">
                                    <div class="stars">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star-half-alt"></i>
                                        <span>(124)</span>
                                    </div>
                                </div>
                                <div class="sales">
                                    <i class="fas fa-chart-line"></i> 450 sales
                                </div>
                            </div>
                            
                            <div class="product-footer">
                                <div class="price">${{ number_format($relatedProduct->regular_license_price, 2) }}</div>
                                <a href="{{ route('user.singleproduct', $relatedProduct->id) }}" class="btn btn-primary btn-sm" style="border:none;">
                                    View Product
                                </a>
                            </div>
                        </div>
                    </div>

                @endforeach
            </div>
        </section>

       @php
            $approvedReviews = $reviews->where('status', 'approved');
            $userReview = null;
            if(auth()->check()){
                $userReview = $reviews->where('user_id', auth()->id())
                                    ->whereIn('status', ['pending', 'rejected'])
                                    ->first();
            }
        @endphp

        {{-- Approved Reviews --}}
        @if($approvedReviews->count() > 0)
            <div class="container mt-3 mb-3" style="box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);padding:2em;">
                <h4 class="mb-4">Customer Reviews</h4>
                @foreach($approvedReviews as $review)
                    <div class="border p-3 mb-3">
                        <strong>{{ $review->user->name }}</strong> <br>
                        <p>{{ $review->review }}</p>
                        <small>Posted on {{ $review->created_at->format('d M, Y') }}</small>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- User's Own Review Status (Only if pending or rejected) --}}
        @if($userReview)
            <div class="container mt-3 mb-3" style="box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);padding:2em;">
                @if($userReview->status == 'pending')
                    <div class="alert alert-info">
                        <strong>Your Review (Status):</strong>
                        <small>Waiting for admin approval.</small>
                    </div>
                @elseif($userReview->status == 'rejected')
                    <div class="alert alert-danger">
                        <strong>Your Review (Status):</strong>
                        <small>Rejected by admin. Please consider editing and resubmitting.</small>
                    </div>
                @endif
            </div>
        @endif

        {{-- No Reviews at All --}}
        @if($approvedReviews->count() == 0 && !$userReview)
            <div class="container mt-3 mb-3" style="box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);padding:2em;">
                <h5>No reviews available yet.</h5>
            </div>
        @endif


        <section>
            <div class="container mt-3 mb-3" style="box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);padding:2em;">
                <h4 class="mb-4">Add Review</h4>
                <form action="{{ route('user.submitreview') }}" method="POST" class="forms-sample">
                    @csrf
                    <div class="form-group mb-3">
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                    </div>
                    <div class="form-group mb-3">
                        <textarea class="form-control" name="review" rows="4" placeholder="Write your review in brief." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>  
                </form>
            </div>
        </section>

    </div>

@endsection