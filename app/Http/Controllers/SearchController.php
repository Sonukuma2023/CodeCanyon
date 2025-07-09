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


        if (empty($query)) {
            return back();
        }


        $search_products = Product::with('category')
            ->where('name', 'LIKE', '%' . $query . '%')
            ->get();

        $categoryIds = $search_products->pluck('category_id')->unique();
        $categories = Category::whereIn('id', $categoryIds)->get();

        return view('partials.search-results', compact('search_products', 'categories', 'query', 'navbarCategories'));
    }


    public function filterProducts(Request $request)
    {
        $product = Product::get();
        if ($product) {

            $product_search = Product::where('name', 'like', '%' . $request->product_name . '%')
                ->whereBetween('regular_license_price', [$request->min_price, $request->max_price])
                ->get();

            if ($product_search) {
                return response()->json(['products' => $product_search]);
            }
        }
    }

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
