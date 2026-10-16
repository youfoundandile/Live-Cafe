{{-- resources/views/admin/products/index.blade.php --}}
@extends('admin.layout')
@section('title', 'Products — Admin')

@section('admin-content')
<div class="admin-section-header">
    <h1>Products</h1>
    <a href="{{ route('admin.products.create') }}" class="btn">New product</a>
</div>
<p class="admin-sub">Made items get their stock from ingredients; merch is counted. To hide a product, open it and untick "Available for sale".</p>

<table class="data-table">
    <tr><th>Name</th><th>Category</th><th>Price</th><th>Stock from</th><th>Can sell</th><th></th></tr>
    @forelse ($products as $p)
        <tr>
            <td>
                {{ $p->name }}
                @unless ($p->prod_availability)
                    <span class="status-badge status-pending">Hidden</span>
                @endunless
            </td>
            <td>{{ $p->category->name }}</td>
            <td>R {{ number_format($p->price, 2) }}</td>
            <td>{{ $p->isMade() ? 'Ingredients' : 'Count' }}</td>
            <td>
                @if ($p->availableQuantity() === 0)
                    <span class="status-badge status-cancelled">Sold out</span>
                @else
                    {{ $p->availableQuantity() }}
                @endif
            </td>
            <td><a href="{{ route('admin.products.edit', $p) }}">Edit</a></td>
        </tr>
    @empty
        <tr><td colspan="6" class="empty">No products yet. Use "New product" to add the first one.</td></tr>
    @endforelse
</table>
{{ $products->links('pagination::bootstrap-4') }}
@endsection