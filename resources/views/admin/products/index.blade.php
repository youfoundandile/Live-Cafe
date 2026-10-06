{{-- resources/views/admin/products/index.blade.php --}}
@extends('admin.layout')
@section('title', 'Products — Admin')

@section('admin-content')
<a href="{{ route('admin.products.create') }}">New product</a>
<table>
    <tr><th>Name</th><th>Category</th><th>Price</th><th>Stock from</th><th>Can sell</th><th></th></tr>
    @foreach ($products as $p)
        <tr>
            <td>{{ $p->name }}</td>
            <td>{{ $p->category->name }}</td>
            <td>R {{ number_format($p->price, 2) }}</td>
            <td>{{ $p->isMade() ? 'Ingredients' : 'Count' }}</td>
            <td @class(['low' => $p->availableQuantity() === 0])>{{ $p->availableQuantity() }}</td>
            <td><a href="{{ route('admin.products.edit', $p) }}">Edit</a></td>
        </tr>
    @endforeach
</table>
{{ $products->links() }}
@endsection