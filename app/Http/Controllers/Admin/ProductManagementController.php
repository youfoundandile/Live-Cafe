<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ProductManagementController extends Controller
{
    // index method to return the view for products

    public function index()
    {
        $products = Product::with(['category', 'ingredients'])->orderBy('name')->paginate(20);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        Gate::authorize('create', Product::class);

        return view('admin.products.form', $this->formData(new Product));
    }

    public function store(ProductRequest $request)
    {
        DB::transaction(function () use ($request) {
            $product = Product::create($request->safe()->except('ingredients'));
            $this->syncRecipe($product, $request);
        });

        return redirect()->route('admin.products.index')->with('success', 'Product added.');
    }

    public function show(Product $product)
    {
        return redirect()->route('admin.products.edit', $product);
    }

    public function edit(Product $product)
    {
        Gate::authorize('update', $product);

        return view('admin.products.form', $this->formData($product->load('ingredients')));
    }

    public function update(ProductRequest $request, Product $product)
    {
        DB::transaction(function () use ($request, $product) {
            $product->update($request->safe()->except('ingredients'));
            $this->syncRecipe($product, $request);
        });

        return redirect()->route('admin.products.index')->with('success', 'Product updated.');
    }

    public function destroy(Product $product)
    {
        Gate::authorize('delete', $product);

        // order_details and sales_details use restrictOnDelete, so sold products can't be deleted
        $product->update(['prod_availability' => false]);

        return back()->with('success', 'Product hidden from sale.');
    }

    private function formData(Product $product): array
    {
        return [
            'product' => $product,
            'categories' => Category::orderBy('sort_order')->get(),
            'ingredients' => Ingredient::orderBy('name')->get(),
        ];
    }

    private function syncRecipe(Product $product, ProductRequest $request): void
    {
        $recipe = collect($request->validated('ingredients', []))
            ->mapWithKeys(fn ($row) => [$row['id'] => ['quantity_required' => $row['quantity']]]);

        $product->ingredients()->sync($recipe);   // empty recipe = merch//
    }
}
