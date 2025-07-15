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
use App\Events\MessageSent;
use App\Events\NotificationSent;
use App\Events\CommunityCreated;

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

	public function showCategoryProducts($slug)
	{
        // dd($slug);
		$categories = Category::latest()->get();
        $products = Product::with('category')->latest()->get();
        // view()->share('categories', $categories);
        $navbarCategories = Category::orderBy('created_at', 'asc')->get();

		$category = Category::where('name', $slug)->firstOrFail();
		$products = $category->products()->latest()->get();

		return view('user.category-products', compact('category', 'products', 'categories', 'products', 'navbarCategories'));
	}


}
