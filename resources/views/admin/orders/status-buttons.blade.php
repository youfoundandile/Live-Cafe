{{-- resources/views/admin/orders/status-buttons.blade.php --}}
@foreach (\App\Models\Order::TRANSITIONS[$order->status] as $next)
    <form method="POST" action="{{ route('admin.orders.status', $order) }}" style="display:inline"
          @if ($next === 'cancelled') onsubmit="return confirm('Cancel order #{{ $order->id }}? Its stock goes back on the shelf.')" @endif>
        @csrf @method('PATCH')
        <input type="hidden" name="status" value="{{ $next }}">
        <button type="submit" @class(['btn-danger' => $next === 'cancelled'])>Mark {{ $next }}</button>
    </form>
@endforeach