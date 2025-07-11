<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\Whislist;
use App\Models\Collection;
use App\Models\CollectionProduction;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function userOrderHistory()
    {
        $categories = Category::latest()->get();
        $products = Product::with('category')->latest()->get();
        // view()->share('categories', $categories);
        $navbarCategories = Category::orderBy('created_at', 'asc')->get();
        return view('user.profile.order_history', compact('categories', 'products', 'navbarCategories',));
    }

    public function fetchOrdersHistory(Request $request)
    {
        $search = $request->input('search.value');

        $orders = Order::with(['items.product'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        $data = [];

        foreach ($orders as $order) {
            $products = $order->items->map(function ($item) {
                return $item->product->name ?? 'N/A';
            });

            if ($search) {
                $match = false;
                foreach ($products as $productName) {
                    if (stripos($productName, $search) !== false) {
                        $match = true;
                        break;
                    }
                }
                if (!$match) continue;
            }

            $data[] = [
                'id' => $order->id,
                'products' => $products->implode(', '),
                'total' => '$' . number_format($order->total, 2),
                'status' => ucfirst($order->status),
                'date' => $order->created_at->format('d M Y'),
                'action' => '<a href="' . route('user.orderDetailsView', $order->id) . '" class="btn btn-sm btn-primary">View</a>',
            ];
        }

        return response()->json([
            "draw" => intval($request->get('draw')),
            "recordsTotal" => count($data),
            "recordsFiltered" => count($data),
            "data" => $data,
        ]);
    }

    public function userWishlist(){
        $categories = Category::latest()->get();
        $products = Product::with('category')->latest()->get();
        // view()->share('categories', $categories);
        $navbarCategories = Category::orderBy('created_at', 'asc')->get();
        return view('user.profile.whislist_list', compact('categories', 'products', 'navbarCategories',));
    }


    public function fetchWishlistItems(Request $request)
    {
        $wishlistItems = Whislist::with('product')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        $data = [];

        foreach ($wishlistItems as $item) {
            $product = $item->product;

            $data[] = [
                'id' => $item->id,
                'product_name' => $product->name ?? 'N/A',
                'price' => '$' . number_format($product->regular_license_price ?? 0, 2),
                'added_on' => $item->created_at->format('d M Y'),
                'action' => '<button class="btn btn-danger btn-sm remove-item" data-id="' . $item->id . '">Remove</button>',
            ];
        }

        return response()->json([
            "draw" => intval($request->get('draw')),
            "recordsTotal" => count($data),
            "recordsFiltered" => count($data),
            "data" => $data,
        ]);
    }

    public function deleteWishlist(Request $request)
    {
        $wishlistId = $request->input('id');

        $wishlistItem = Whislist::find($wishlistId);

        if (!$wishlistItem) {
            return response()->json([
                'success' => false,
                'message' => 'Wishlist item not found!'
            ]);
        }

        $wishlistItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Wishlist deleted successfully.'
        ]);
    }


    public function userProfileEdit()
    {
        $categories = Category::latest()->get();
        $products = Product::with('category')->latest()->get();
        // view()->share('categories', $categories);
        $navbarCategories = Category::orderBy('created_at', 'asc')->get();

        $user = Auth::user();
        return view('user.profile.edit_profile', compact('categories', 'products', 'navbarCategories','user'));
    }

    public function userProfileUpdate(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return response()->json([
            'message' => 'Profile updated successfully.'
        ]);
    }


    public function orderDetailsView($id)
    {
        $categories = Category::latest()->get();
        $products = Product::with('category')->latest()->get();
        // view()->share('categories', $categories);
        $navbarCategories = Category::orderBy('created_at', 'asc')->get();

        $order = Order::with(['items.product'])->where('user_id', auth()->id())->findOrFail($id);
        return view('user.profile.order_view', compact('order','categories', 'products', 'navbarCategories'));
    }

    public function myCollectionsPage()
    {
        $categories = Category::latest()->get();
        $products = Product::with('category')->latest()->get();
        // view()->share('categories', $categories);
        $navbarCategories = Category::orderBy('created_at', 'asc')->get();

        return view('user.profile.collections', compact('categories', 'products', 'navbarCategories'));
    }

    public function fetchCollectionItems(Request $request)
    {
        $userId = Auth::id();

        $collections = Collection::where('user_id', $userId)->pluck('id');

        $data = CollectionProduction::with('product')
            ->whereIn('collection_id', $collections)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->product->id,
                    'product_name' => $item->product->name,
                    'price' => '$' . number_format($item->product->regular_license_price, 2),
                    'added_on' => $item->created_at->format('d M Y'),
                    'action' => '<button class="btn btn-danger btn-sm remove-item" data-id="'.$item->id.'" data-href="' . route('user.deleteCollectionProduct', $item->id) . '">Remove</button>',
                ];
            });

        return response()->json(['data' => $data]);
    }

    public function deleteCollectionProduct(Request $request)
    {
        $item = CollectionProduction::find($request->id);
        if ($item && $item->collection->user_id === Auth::id()) {
            $item->delete();
            return response()->json(['success' => true, 'message' => 'Product removed from collection.']);
        }
        return response()->json(['success' => false, 'message' => 'Unauthorized or not found.']);
    }








}
