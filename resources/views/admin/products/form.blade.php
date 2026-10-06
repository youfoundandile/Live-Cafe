{{-- resources/views/admin/products/form.blade.php --}}
@extends('admin.layout')
@section('title', ($product->exists ? 'Edit product' : 'New product') . ' — Admin')

@section('admin-content')
@php
    // Rows to show: what was just typed (if saving failed), otherwise the saved recipe
    $recipe = old('ingredients', $product->ingredients->map(fn ($i) => [
        'id'       => $i->id,
        'quantity' => $i->pivot->quantity_required,
    ])->all());
@endphp

<h1>{{ $product->exists ? 'Edit ' . $product->name : 'New product' }}</h1>

@if ($errors->any())
    <div class="alert alert-error">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" class="admin-form"
      action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}">
    @csrf
    @if ($product->exists)
        @method('PUT')
    @endif

    <label>Name
        <input type="text" name="name" value="{{ old('name', $product->name) }}" maxlength="150" required>
    </label>

    <label>Description
        <textarea name="description" rows="3">{{ old('description', $product->description) }}</textarea>
    </label>

    <label>Category
        <select name="category_id" required>
            <option value="">Choose a category</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </label>

    <label>Price (R)
        <input type="number" name="price" value="{{ old('price', $product->price) }}" step="0.01" min="0" required>
    </label>

    <label>Stock count (merch only)
        <input type="number" name="quantity" value="{{ old('quantity', $product->quantity ?? 0) }}" step="1" min="0" required>
    </label>

    <input type="hidden" name="prod_availability" value="0">
    <label class="checkbox">
        <input type="checkbox" name="prod_availability" value="1"
               @checked(old('prod_availability', $product->exists ? $product->prod_availability : true))>
        Available for sale
    </label>

    <fieldset>
        <legend>Recipe</legend>
        <p class="hint">Leave empty for merch. Amounts are for one product, in the ingredient's unit.</p>

        <div id="recipe-rows">
            @foreach ($recipe as $i => $row)
                @include('admin.products.partials.recipe-row', ['index' => $i, 'row' => $row])
            @endforeach
        </div>

        <button type="button" id="add-ingredient">Add ingredient</button>
    </fieldset>

    <button type="submit">{{ $product->exists ? 'Save changes' : 'Add product' }}</button>
    <a href="{{ route('admin.products.index') }}">Cancel</a>
</form>

<template id="recipe-row-template">
    @include('admin.products.partials.recipe-row', ['index' => '__i__', 'row' => []])
</template>
@endsection

@push('scripts')
<script>
    const recipeRows = document.getElementById('recipe-rows');
    const rowTemplate = document.getElementById('recipe-row-template');

    // Add: copy the blank row and give it a new number so its name is unique
    document.getElementById('add-ingredient').addEventListener('click', () => {
        recipeRows.insertAdjacentHTML('beforeend', rowTemplate.innerHTML.replaceAll('__i__', Date.now()));
    });

    // Remove: delete the row whose Remove button was clicked
    recipeRows.addEventListener('click', (event) => {
        if (event.target.classList.contains('recipe-remove')) {
            event.target.closest('.recipe-row').remove();
        }
    });
</script>
@endpush
