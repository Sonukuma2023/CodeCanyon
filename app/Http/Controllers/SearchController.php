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
        ->where('name', 'LIKE', '%'.$query . '%')
        ->get();

    $categoryIds = $search_products->pluck('category_id')->unique();
    $categories = Category::whereIn('id', $categoryIds)->get();

    return view('partials.search-results', compact('search_products', 'categories', 'query', 'navbarCategories'));
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




}
