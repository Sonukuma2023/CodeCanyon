<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; // Make sure this matches your model namespace
use App\Models\Category;

class SearchController extends Controller
{
    /**
     * Handle AJAX search request
     */
    // public function search(Request $request)
    // {
    //     $query = $request->input('query');
    //     // dd($query);
    //     $categories = Category::latest()->get();
    //     $products = Product::with('category')->get();
    //     // view()->share('categories', $categories);
    //     $navbarCategories = Category::orderBy('created_at', 'asc')->get();


    //     if (!empty($query)) {

    //         $search_products = Product::where('name', 'LIKE','%'. $query . '%')->get();
    //         if(empty($search_products)){

    //             return back();
    //         }

    //     }else{
    //          return back();
    //     }



    //         // $search_products = Product::where('name', 'LIKE','%'. $query . '%')->get();
    //     return view('partials.search-results', compact('products', 'query', 'categories', 'navbarCategories','search_products'));
    // }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $navbarCategories = Category::orderBy('created_at', 'asc')->get();


        // If query is empty, redirect back
        if (empty($query)) {
            return back();
        }

        // Search products by name
        $search_products = Product::with('category')
            ->where('name', 'LIKE', '%'.$query . '%')
            ->get();

        // If no matching products
        // if ($search_products->isEmpty()) {
        //     return back();
        // }

        // ✅ Get only categories of matched products
        $categoryIds = $search_products->pluck('category_id')->unique();
        $categories = Category::whereIn('id', $categoryIds)->get();

        return view('partials.search-results', compact('search_products', 'categories', 'query', 'navbarCategories'));
    }


    //     return view('partials.search-results', compact('search_products', 'categories', 'query', 'navbarCategories'));
    // }



    // public function filterProducts(Request $request)
    // {
    //     $product = Product::get();
    //     if ($product) {

    //         $product_search = Product::where('name', 'like', '%' . $request->product_name . '%')
    //             ->whereBetween('regular_license_price', [$request->min_price, $request->max_price])
    //             ->get();

    //         if ($product_search) {
    //             return response()->json(['products' => $product_search]);
    //         }
    //     }
    // }

public function product_sale_search(Request $request)
{
    $query = Product::query();



    if ($request->filled('product_name')) {
        $query->where('name', 'like', '%' . $request->product_name . '%');
    }


    if ($request->filled('sales') && $request->filled('on_sale')) {
        $query->whereIn('sales', $request->sales)
              ->where('on_sale', $request->onsale);
    } elseif ($request->filled('sales')) {

        $query->whereIn('sales', $request->sales);

    } elseif ($request->filled('onsale')) {

        $query->where('on_sale', $request->onsale);

    }


    $products = $query->get();
    
       return response()->json(['products' => $products]);
}

    public function filterProducts(Request $request)
    {
        $query = Product::query();

        if ($request->filled('min_price')) {
            $query->where('regular_license_price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('regular_license_price', '<=', $request->max_price);
        }

        $products = $query->get();

        return response()->json(['products' => $products]);

    }

    public function allProductPage()
    {
        $products = Product::with('category')->latest()->get();
        $navbarCategories = Category::orderBy('created_at', 'asc')->get();

        $categories = Category::all();
        return view('user.all_products', compact('categories','products', 'navbarCategories'));
    }

    public function allProductFilter(Request $request)
    {
        $query = Product::with(['category', 'wishlistedBy'])->latest();

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('min_price')) {
            $query->where('regular_license_price', '>=', (float) $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('regular_license_price', '<=', (float) $request->max_price);
        }


        $products = $query->get();

        if (auth()->check()) {
            $userId = auth()->id();
            $products->each(function ($product) use ($userId) {
                $product->is_wishlisted = $product->wishlistedBy->contains($userId);
            });
        } else {
            $products->each(function ($product) {
                $product->is_wishlisted = false;
            });
        }

        return view('user.partials.product-list', compact('products'))->render();
    }


    // public function product_on_sale_search(Request $request)
    // {



    //     // $query = Product::query();

    //     // $product = Product::find($request->sales);


    //     if ($request) {

    //         $products = Product::where('name', 'like', '%' . $request->product_name . '%')
    //             ->where('on_sale', $request->sales)->get();

    //         if ($products) {

    //             return response()->json(['products' => $products]);
    //         }
    //     }
    // }
}
