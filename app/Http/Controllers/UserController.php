<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use App\Models\Messages;
use App\Models\Notifications;
use App\Models\Community;
use App\Models\Coupons;
use App\Models\Cart;
use App\Models\Whislist;
use App\Events\MessageSent;
use App\Events\NotificationSent;
use App\Events\CommunityCreated;
use Illuminate\Support\Facades\Session;
use App\Models\Collection;
use App\Models\CollectionProduction;

class UserController extends Controller
{
    public function dashboard() {
        $categories = Category::latest()->get();
        $products = Product::with('category')->latest()->get();
        // view()->share('categories', $categories);
        $navbarCategories = Category::orderBy('created_at', 'asc')->get();
        return view('user.dashboard', compact('categories', 'products', 'navbarCategories',));
    }

    public function singleproduct($id) {
        // $product = Product::findOrFail($id);
        $product = Product::findOrFail($id);
        $relatedProducts = Product::where('category_id', $product->category_id)
                               ->where('id', '!=', $product->id)
                               ->take(3)
                               ->get();

        $reviews = Review::with('user')
            ->where('product_id', $product->id)
            ->latest()
            ->get();

        $navbarCategories = Category::orderBy('created_at', 'asc')->get();
        return view('user.singleproduct', compact('product', 'relatedProducts', 'navbarCategories', 'reviews'));
    }

    public function submitReview(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'review' => 'required|string',
        ]);

        $existing = Review::where('user_id', Auth::id())
                    ->where('product_id', $request->product_id)
                    ->first();

        if ($existing) {
            return redirect()->back()->with('error', 'You have already submitted a review for this product.');
        }

        // if no existing review
        Review::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
            'review' => $request->review,
        ]);

        return redirect()->back()->with('success', 'Review submitted successfully.');
    }

	public function messagePage()
	{
		$categories = Category::latest()->get();
        $products = Product::with('category')->latest()->get();
        // view()->share('categories', $categories);
        $navbarCategories = Category::orderBy('created_at', 'asc')->get();
		return view('user.messages', compact('categories', 'products', 'navbarCategories',));
	}

	public function fetchMessages(Request $request)
	{
		try {
			$authId = auth()->id();
			$adminId = 12;

			$messages = Messages::where(function ($query) use ($authId, $adminId) {
				$query->where('sender_id', $authId)
					  ->where('receiver_id', $adminId);
			})
			->orWhere(function ($query) use ($authId, $adminId) {
				$query->where('sender_id', $adminId)
					  ->where('receiver_id', $authId);
			})
			->orderBy('sent_at', 'asc')
			->get();

			return response()->json(['messages' => $messages]);

		} catch (\Exception $e) {
			\Log::error('Fetch Messages Error: ' . $e->getMessage());
			return response()->json(['error' => 'Server error'], 500);
		}
	}


	public function messageSave(Request $request)
	{
		$request->validate([
			'message_content' => 'required|string|max:1000',
		]);

		$message = Messages::create([
			'sender_id'   => auth()->id(),
			'receiver_id' => 12,
			'message'     => $request->message_content,
			'sent_at'     => now(),
		]);

		$url = route('admin.messagePage',['id' => auth()->user()->id ]);

		$notification = Notifications::create([
			'sender_id'   => auth()->id(),
			'receiver_id' => 12,
			'content'     => 'New message from: ' . auth()->user()->name,
			'url'         => $url,
			'sent_at'     => now(),
		]);

		event(new MessageSent($message));
		event(new NotificationSent($notification));

		return response()->json([
			'success' => true,
			'message' => 'Message and notification saved successfully'
		]);
	}

	public function markMessagesAsRead()
	{
		Messages::where('receiver_id', auth()->id())
			->whereNull('read_at')
			->update(['read_at' => now()]);

		return response()->json(['status' => 'success']);
	}

	public function scriptRunnerPage()
	{
		$categories = Category::latest()->get();
        $products = Product::with('category')->latest()->get();
        // view()->share('categories', $categories);
        $navbarCategories = Category::orderBy('created_at', 'asc')->get();

		return view('user.script-runner', compact('categories', 'products', 'navbarCategories',));
	}

	public function runScript(Request $request)
	{
		$code = trim($request->input('code'));

		if (str_starts_with($code, '<?php') || str_contains($code, '<?php')) {
			$folder = public_path('temp_scripts');
			if (!file_exists($folder)) {
				mkdir($folder, 0755, true);
			}

			$filename = $folder . '/temp_script_' . time() . '.php';

			if (file_put_contents($filename, $code) === false) {
				return response()->json([
					'output' => "<div style='color:red;'>Error: Unable to write file. Check folder permissions.</div>"
				]);
			}

			$output = shell_exec("php \"$filename\" 2>&1");

			@unlink($filename);

			return response()->json([
				'output' => "<!DOCTYPE html><html><body>{$output}</body></html>"
			]);
		}

		return response()->json([
			'output' => $code
		]);
	}

	public function communityPage()
	{
		$categories = Category::latest()->get();
        $products = Product::with('category')->latest()->get();
        // view()->share('categories', $categories);
        $navbarCategories = Category::orderBy('created_at', 'asc')->get();

		return view('user.community', compact('categories', 'products', 'navbarCategories',));
	}


	public function createCommunity(Request $request)
	{
		$request->validate([
			'complaint' => 'required|string|max:255',
			'comment' => 'required|string',
		]);

		$community = Community::create([
			'user_id' => auth()->id(),
			'comment' => $request->comment,
			'complaint' => $request->complaint,
		]);

		event(new CommunityCreated($community));

		return response()->json([
			'status' => 'success',
			'message' => 'Community complaint created successfully.',
			'data' => $community
		]);
	}

	public function communityList()
	{
		$categories = Category::latest()->get();
        $products = Product::with('category')->latest()->get();
        // view()->share('categories', $categories);
        $navbarCategories = Category::orderBy('created_at', 'asc')->get();

		return view('user.community-list', compact('categories', 'products', 'navbarCategories',));
	}


	public function fetchCommunityList()
	{
		$communities = Community::with('user')->latest()->get()->map(function ($community) {
			return [
				'id' => $community->id,
				'complaint' => $community->complaint,
				'comment' => $community->comment,
				'user' => $community->user ? ['name' => $community->user->name] : null,
				'created_at_human' => $community->created_at->diffForHumans(),
				'admin_reply' => $community->admin_reply,
				'developer_reply' => $community->developer_reply
			];
		});

		return response()->json($communities);
	}


	// public function showCategoryProducts($slug)
	// {
	// 	$categories = Category::latest()->get();
    //     $products = Product::with('category')->latest()->get();
    //     // view()->share('categories', $categories);
    //     $navbarCategories = Category::orderBy('created_at', 'asc')->get();

	// 	$category = Category::where('name', $slug)->firstOrFail();
	// 	$products = $category->products()->latest()->get();

	// 	return view('user.category-products', compact('category', 'products', 'categories', 'products', 'navbarCategories'));
	// }

	public function showCategoryProducts($slug, Request $request)
	{
		$category = Category::where('name', $slug)->firstOrFail();
		$categories = Category::latest()->get();
		$navbarCategories = Category::orderBy('created_at', 'asc')->get();

		$query = $category->products()->with(['category', 'wishlistedBy'])->latest();

		if ($request->filled('min_price')) {
			$query->where('regular_license_price', '>=', $request->min_price);
		}

		if ($request->filled('max_price')) {
			$query->where('regular_license_price', '<=', $request->max_price);
		}

		// if ($request->filled('rating')) {
		// 	$query->where('rating', '>=', $request->rating);
		// }

		$products = $query->get();

		// Mark each product as wishlisted
		$products->each(function ($product) {
			$product->is_wishlisted = auth()->check() && $product->wishlistedBy->contains(auth()->id());
		});

		if ($request->ajax()) {
			$html = view('user.partials.product-cards', compact('products'))->render();
			return response()->json(['html' => $html]);
		}

		return view('user.category-products', compact('category', 'products', 'categories', 'navbarCategories'));
	}
	

	public function addWhislist(Request $request)
	{
		$request->validate([
			'product_id' => 'required|exists:products,id'
		]);

		$user = auth()->user();
		$productId = $request->product_id;

		if ($user->wishlist()->where('product_id', $productId)->exists()) {
			$user->wishlist()->detach($productId);
			return response()->json(['status' => 'removed']);
		} else {
			$user->wishlist()->attach($productId);
			return response()->json(['status' => 'added']);
		}
	}

	public function singleDetailsCategory($id){
		$product = Product::findOrFail($id);

		$categories = Category::latest()->get();
        $products = Product::with('category')->latest()->get();
        // view()->share('categories', $categories);
        $navbarCategories = Category::orderBy('created_at', 'asc')->get();

		return view('user.single_category', compact('product', 'categories', 'products', 'navbarCategories'));
	}


	public function applyCoupon(Request $request)
	{
		$code = $request->coupon_code;
		$userId = auth()->id();

		$coupon = Coupons::where('code', $code)
			->where('status', 'active') 
			->first();

		if (!$coupon) {
			return response()->json([
				'success' => false,
				'message' => 'Invalid or inactive coupon code.'
			]);
		}

		if ($coupon->expires_at && now()->greaterThan($coupon->expires_at)) {
			return response()->json([
				'success' => false,
				'message' => 'This coupon has expired.'
			]);
		}

		if (!is_null($coupon->usage_limit) && $coupon->usage_limit <= 0) {
			return response()->json([
				'success' => false,
				'message' => 'This coupon has reached its usage limit.'
			]);
		}

		$cartItems = Cart::with('product')
			->where('user_id', $userId)
			->get();

		$subtotal = $cartItems->sum(function ($item) {
			return $item->price * $item->quantity;
		});

		if ($subtotal < $coupon->minimum_order_amount) {
			return response()->json([
				'success' => false,
				'message' => 'Minimum order amount not met for this coupon.'
			]);
		}

		$discount = 0;
		if (!is_null($coupon->discount_amount)) {
			$discount = $coupon->discount_amount;
		} elseif (!is_null($coupon->discount_percentage)) {
			$discount = $subtotal * ($coupon->discount_percentage / 100);
		}

		$tax = $subtotal * 0.1;
		$total = ($subtotal + $tax) - $discount;

		return response()->json([
			'success' => true,
			'message' => 'Coupon applied successfully!',
			'summary' => [
				'subtotal' => number_format($subtotal, 2),
				'discount' => number_format($discount, 2),
				'tax' => number_format($tax, 2),
				'total' => number_format($total, 2),
				'totalItems' => $cartItems->sum('quantity'),
			]
		]);
	}

	public function loadMore(Request $request)
	{
		$perPage = 4;
		$page = $request->page ?? 1;

		$products = Product::where('status', '!=', 'pending')
			->latest()
			->skip(($page - 1) * $perPage)
			->take($perPage + 1)
			->get();

		$hasMore = $products->count() > $perPage;
		$products = $products->take($perPage);

		$userId = auth()->id();
		$wishlistIds = [];

		if ($userId) {
			$wishlistIds = Whislist::where('user_id', $userId)
				->pluck('product_id')
				->toArray();
		}

		$html = '';

		foreach ($products as $product) {

			$isWishlisted = in_array($product->id, $wishlistIds);
			$wishlistIcon = $isWishlisted ? 'bi-heart-fill text-danger' : 'bi-heart';
			
			$html .= '
			<div class="product-card position-relative">

			<div class="position-absolute top-0 end-0 m-2">
					<button type="button" class="btn btn-light btn-sm p-1 rounded-circle shadow-sm add-to-wishlist" data-id="' . $product->id . '">
						<i class="bi ' . $wishlistIcon . '"></i>
					</button>
				</div>

				<div class="product-image">
					<img src="' . asset('storage/uploads/thumbnails/' . $product->thumbnail) . '" alt="' . $product->name . '" loading="lazy">
					<a href="' . route('user.singleproduct', $product->id) . '" class="quick-view" data-product-id="' . $product->id . '">Quick View</a>
				</div>

				<div class="product-details">
					<h3 class="product-title">' . $product->name . '</h3>
					<div class="product-author">by <a href="#">' . $product->name . '</a></div>

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
						<div class="price">$' . number_format($product->regular_license_price, 2) . '</div>
						<button class="addtocart" data-id="' . $product->id . '" data-price="' . $product->regular_license_price . '">
							<div class="pretext">
								<i class="fas fa-cart-plus"></i> ADD TO CART
							</div>
							<div class="done">
								<div class="posttext"><i class="fas fa-check"></i> ADDED</div>
							</div>
						</button>
					</div>
				</div>
			</div>';
		}

		return response()->json([
			'html' => $html,
			'hasMore' => $hasMore
		]);
	}


	public function addToCollection(Request $request)
    {
		$request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $user = Auth::user();

		$collection = Collection::where('user_id', $user->id)->first();

		if(!$collection){
			return response()->json(['hasCollections' => false]);
		}

		$exists = CollectionProduction::where('collection_id', $collection->id)
                ->where('product_id', $request->product_id)
                ->exists();

		if ($exists) {
			return response()->json([
				'status' => 'exists',
				'message' => 'Product already exists in your collection.'
			]);
		}
		
        CollectionProduction::firstOrCreate([
            'collection_id' => $collection->id,
            'product_id' => $request->product_id,
        ]);

        return response()->json(['status' => 'success', 'message' => 'Product added to collection.']);
    }

    public function createCollection(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'product_id' => 'required|exists:products,id',
        ]);

        $collection = Collection::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
        ]);

        CollectionProduction::create([
            'collection_id' => $collection->id,
            'product_id' => $request->product_id,
        ]);

        return response()->json(['message' => 'Collection created and product added.']);
    }




		

}
