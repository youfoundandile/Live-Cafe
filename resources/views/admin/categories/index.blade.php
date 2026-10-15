{{-- resources/views/admin/categories/index.blade.php --}}
@extends('admin.layout')
@section('title', 'Categories — Admin')

@section('admin-content')
<h1>Categories</h1>
<p class="admin-sub">Lower numbers show first. Deactivate a category to hide it without losing its products' history. Don't rename "Add On": the shop and the product form look for that name.</p>

@if ($errors->any())
    <div class="alert alert-error">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('admin.categories.store') }}" class="filter-bar">
    @csrf
    <label>New category
        <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Smoothies" maxlength="100" required>
    </label>
    <label>Sort order
        <input type="number" name="sort_order" value="{{ old('sort_order', $categories->max('sort_order') + 1) }}" min="0" required>
    </label>
    <button type="submit">Add category</button>
</form>

<table class="data-table">
    <tr><th>Category · sort · active</th><th>Products</th><th></th></tr>
    @foreach ($categories as $category)
        <tr>
            <td>
                <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="adjust-form">
                    @csrf @method('PUT')
                    <input type="text" name="name" value="{{ $category->name }}" maxlength="100" required>
                    <input type="number" name="sort_order" value="{{ $category->sort_order }}" min="0" required style="width:5rem">
                    <input type="hidden" name="is_active" value="0">
                    <label><input type="checkbox" name="is_active" value="1" @checked($category->is_active)> Active</label>
                    <button type="submit">Save</button>
                </form>
            </td>
            <td>{{ $category->products_count }}</td>
            <td>
                @if ($category->products_count === 0)
                    <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" style="display:inline"
                          onsubmit="return confirm('Delete this category?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-danger">Delete</button>
                    </form>
                @endif
            </td>
        </tr>
    @endforeach
</table>
@endsection