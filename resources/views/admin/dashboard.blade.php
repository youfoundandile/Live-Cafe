@extends('layouts.app')

@section('title', 'Admin Dashboard — Live Cafe')

@push('styles')
  @include('admin.partials.styles')
  
@endpush

@section('content')

<div class="admin-wrap">

    {{-- Sidebar --}}
    @include('admin.partials.sidebar')


    {{-- Main --}}
    <div class="admin-main">

        <h1>Dashboard</h1>
        <p class="admin-sub">Welcome back, {{ auth()->user()->name }}.</p>

        {{-- Stats --}}
        <div class="stat-grid">
            <div class="stat-card">
                <p class="stat-card-label">Total users</p>
                <p class="stat-card-value">{{ $stats['totalUsers'] }}</p>
                <p class="stat-card-note">registered accounts</p>
            </div>

            <div class="stat-card">
                <p class="stat-card-label">Products</p>
                <p class="stat-card-value">{{ $stats['totalProducts'] }}</p>
                <p class="stat-card-note">{{ $stats['availableProducts'] }} available</p>
            </div>

            <div class="stat-card">
                <p class="stat-card-label">Orders today</p>
                <p class="stat-card-value">{{ $stats['ordersToday'] }}</p>
                <p class="stat-card-note">pending + confirmed</p>
            </div>

            <div class="stat-card">
                <p class="stat-card-label">Revenue today</p>
                <p class="stat-card-value">R{{ number_format($stats['revenueToday'], 0) }}</p>
                <p class="stat-card-note">POS sales</p>
            </div>

            <div class="stat-card">
                <p class="stat-card-label">Upcoming events</p>
                <p class="stat-card-value">{{ $stats['upcomingEvents'] }}</p>
                <p class="stat-card-note">next 30 days</p>
            </div>

            <div class="stat-card">
                <p class="stat-card-label">Total RSVPs</p>
                <p class="stat-card-value">{{ $stats['totalRsvps'] }}</p>
                <p class="stat-card-note">upcoming events</p>
            </div>
        </div>

        {{-- Recent Orders --}}
        <div class="admin-section">
            <div class="admin-section-header">
                <h2>Recent orders</h2>
                <a href="{{ route('admin.orders.index') }}">View all</a>
            </div>

            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Collection time</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->user->name }} {{ $order->user->surname }}</td>
                            <td>R{{ number_format($order->total, 2) }}</td>
                            <td>{{ \Carbon\Carbon::parse($order->collection_time)->format('d M, H:i') }}</td>
                            <td>
                                <span class="status-badge status-{{ $order->status }}">
                                    {{ $order->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" style="color:var(--slate);padding:1.5rem 1rem;">No recent orders.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Recent POS Sales --}}
        <div class="admin-section">
            <div class="admin-section-header">
                <h2>Recent POS sales</h2>
                <a href="{{ route('admin.sales.index') }}">View all</a>
            </div>

            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Staff</th>
                        <th>Total</th>
                        <th>Payment</th>
                        <th>Time</th>
                        <th>Offline</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentSales as $sale)
                        <tr>
                            <td>{{ $sale->id }}</td>
                            <td>{{ $sale->user->name }}</td>
                            <td>R{{ number_format($sale->total, 2) }}</td>
                            <td style="text-transform:capitalize;">{{ $sale->payment_method }}</td>
                            <td>{{ \Carbon\Carbon::parse($sale->occurred_at)->format('d M, H:i') }}</td>
                            <td>{{ $sale->is_offline ? 'Yes' : '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" style="color:var(--slate);padding:1.5rem 1rem;">No recent sales.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

@endsection