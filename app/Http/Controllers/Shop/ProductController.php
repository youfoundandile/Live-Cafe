<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        $categories = Category::with(['children' => function ($query) {
            $query->where('is_active', true)
                ->where('name', '!=', 'Add Ons')
                ->orderBy('sort_order');
        }])
            ->whereNull('parent_id')
            ->whereIn('name', ['Food', 'Drinks', 'Merch'])
            ->orderBy('sort_order')
            ->get();

        return view('shop.index', compact('categories'));
    }

    public function show(Product $product): View|RedirectResponse
    {
        // Out-of-stock products have no details page.
        if (! $product->prod_availability || $product->quantity < 1) {
            return redirect()
                ->route('shop.index')
                ->with('error', $product->name.' is currently out of stock.');
        }

        $product->load(['category', 'images']);

        return view('shop.show', compact('product'));
    }

    public function category(Category $category): View|RedirectResponse
    {
        // Only allow customers to open actual subcategories.
        if (! $category->parent_id) {
            return redirect()->route('shop.index');
        }

        $products = Product::with('images')
            ->where('category_id', $category->id)
            ->orderByRaw('(prod_availability = 0 OR quantity < 1) ASC')
            ->orderBy('name')
            ->get();

        $categoryImages = [
            'Sandwiches' => 'images/menu/sandwiches.jpeg',
            'Pastries' => 'images/menu/pastries.jpeg',
            'Salad' => 'images/menu/salad.jpg',
            'Wraps' => 'images/menu/wraps.jpg',
            'Hot Drinks' => 'images/menu/hot-drinks.png',
            'Ice Ice Baby' => 'images/menu/ice-ice-baby.jpg',
            'Signature' => 'images/menu/signature.jpeg',
        ];

        $imagePath = $categoryImages[$category->name] ?? null;

        return view('shop.category', compact('category', 'products', 'imagePath'));
    }

    /**
     * Position of a category on the page (lower = higher up).
     */
    private function categoryRank(string $name): int
    {
        $name = strtolower($name);

        return match (true) {
            str_contains($name, 'food') => 0,
            str_contains($name, 'drink') => 1,
            str_contains($name, 'add') => 2,
            str_contains($name, 'merch') => 3,
            default => 9,
        };
    }
}
