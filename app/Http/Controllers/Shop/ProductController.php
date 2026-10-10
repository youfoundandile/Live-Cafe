<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        // Categories for the quick-jump links (Add-ons are excluded)
        $categories = Category::where('name', 'not like', '%add-on%')
            ->where('name', 'not like', '%addon%')
            ->orderBy('name')
            ->get();

        // All products. Available ones come first, sold-out ones last.
        // Sold-out products stay visible so customers can see them.
        $productsByCategory = Product::with('category')
            ->orderByRaw('(prod_availability = 0 OR quantity < 1) ASC')
            ->orderBy('name')
            ->get()
            ->filter(fn (Product $p) => $p->category !== null)
            ->groupBy(fn (Product $p) => $p->category->name); // @phpstan-ignore-line

        return view('shop.index', compact('categories', 'productsByCategory'));
    }

    public function show(Product $product): View
    {
        $product->load('category');

        return view('shop.show', compact('product'));
    }
}
