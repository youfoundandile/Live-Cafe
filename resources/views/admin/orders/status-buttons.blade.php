{{-- resources/views/admin/orders/partials/status-buttons.blade.php --}}
@foreach (\App\Models\Order::TRANSITIONS[$order->status] as $next)
    <form method="POST" action="{{ route('admin.orders.status', $order) }}" style="display:inline">
        @csrf @method('PATCH')
        <input type="hidden" name="status" value="{{ $next }}">
        <button type="submit">Mark {{ $next }}</button>
    </form>
@endforeach
