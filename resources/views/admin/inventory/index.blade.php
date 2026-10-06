{{-- resources/views/admin/inventory/index.blade.php --}}
@extends('admin.layout')
@section('title', 'Inventory — Admin')

@section('admin-content')
<h1>Inventory</h1>
<p class="admin-sub">Use a positive number for deliveries and a negative number for wastage or corrections.</p>

@if ($errors->any())
    <div class="alert alert-error">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<section class="admin-section">
    <div class="admin-section-header"><h2>Ingredients</h2></div>
    <table class="data-table">
        <tr><th>Name</th><th>In stock</th><th>Reorder at</th><th>Adjust</th></tr>
        @foreach ($ingredients as $ingredient)
            <tr>
                <td>{{ $ingredient->name }}</td>
                <td>
                    {{ $ingredient->quantity }} {{ $ingredient->unit }}
                    @if ($ingredient->quantity <= $ingredient->reorder_level)
                        <span class="status-badge status-cancelled">Low</span>
                    @endif
                </td>
                <td>{{ $ingredient->reorder_level }} {{ $ingredient->unit }}</td>
                <td>
                    @include('admin.inventory.partials.adjust-form', [
                        'action' => route('admin.inventory.ingredients.adjust', $ingredient),
                        'step'   => '0.001',
                    ])
                </td>
            </tr>
        @endforeach
    </table>
</section>

<section class="admin-section">
    <div class="admin-section-header"><h2>Counted products (merch)</h2></div>
    <table class="data-table">
        <tr><th>Name</th><th>In stock</th><th>Adjust</th></tr>
        @foreach ($counted as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td>
                    {{ $product->quantity }}
                    @if ($product->quantity === 0)
                        <span class="status-badge status-cancelled">Sold out</span>
                    @endif
                </td>
                <td>
                    @include('admin.inventory.partials.adjust-form', [
                        'action' => route('admin.inventory.adjust', $product),
                        'step'   => '1',
                    ])
                </td>
            </tr>
        @endforeach
    </table>
</section>
@endsection
