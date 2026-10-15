{{-- resources/views/admin/dashboard.blade.php --}}
@extends('admin.layout')
@section('title', 'Admin Dashboard — Live Cafe')

@section('admin-content')

    {{-- Main --}}
    <div class="admin-main">

        <h1>Dashboard</h1>
        <p class="admin-sub">Welcome back, {{ auth()->user()->name }}.</p>

        {{-- Stats --}}
         <p class="admin-sub">Last updated <span id="stat-updated">{{ $stats['updated_at'] }}</span></p>

        <div class="stat-grid">
            {{-- live: these 4 refresh every 30 s --}}
            <div class="stat-card">
                <p class="stat-card-label">Revenue today</p>
                <p class="stat-card-value" id="stat-revenue">R {{ number_format($stats['online_revenue'] + $stats['pos_revenue'], 2) }}</p>
                <p class="stat-card-note">
                    Online R <span id="stat-online">{{ number_format($stats['online_revenue'], 2) }}</span> ·
                    Till R <span id="stat-pos">{{ number_format($stats['pos_revenue'], 2) }}</span>
                </p>
            </div>

            <div class="stat-card">
                <p class="stat-card-label">Pending orders</p>
                <p class="stat-card-value" id="stat-pending">{{ $stats['pending_orders'] }}</p>
            </div>

            <div class="stat-card">
                <p class="stat-card-label">Low-stock ingredients</p>
                <p class="stat-card-value" id="stat-low">{{ $stats['low_stock'] }}</p>
            </div>

            <a class="stat-card" href="{{ route('admin.sync.index') }}" style="text-decoration:none;">
                <p class="stat-card-label">Open sync conflicts</p>
                <p class="stat-card-value" id="stat-conflicts">{{ $stats['open_conflicts'] }}</p>
            </a>

            {{-- page-load only: the Fix 2 cards --}}
            <div class="stat-card">
                <p class="stat-card-label">Total users</p>
                <p class="stat-card-value">{{ $stats['totalUsers'] }}</p>
                <p class="stat-card-note">{{ $stats['activeUsers'] }} active in the last 15 min</p>
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

       {{-- Recent RSVPs --}}
         <div class="admin-section">
            <div class="admin-section-header">
                 <h2>Recent RSVPs</h2>
                <a href="{{ route('admin.events.index') }}">View events</a>
                </div>

            <table class="data-table">
                <thead>
                    <tr><th>Member</th><th>Event</th><th>Extras</th><th>RSVP'd</th></tr>
                </thead>
                <tbody>
                    @forelse ($recentRsvps as $rsvp)
                        <tr>
                            <td>{{ $rsvp->user->name }} {{ $rsvp->user->surname }}</td>
                            <td>{{ $rsvp->event->event_date->format('d M') }}, {{ $rsvp->event->address }}</td>
                            <td>{{ $rsvp->extras }}</td>
                            <td>{{ $rsvp->created_at->format('d M, H:i') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" style="color:var(--slate);padding:1.5rem 1rem;">No RSVPs yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

@endsection
@push('scripts')
    <script>
        const money = (n) => 'R ' + Number(n).toLocaleString('en-ZA', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        const set = (id, value) => { const el = document.getElementById(id); if (el) el.textContent = value; };

        async function refreshStats() {
            try {
                const res = await fetch('{{ route('admin.stats') }}', { headers: { Accept: 'application/json' } });
                if (!res.ok) return;                       // logged out or server error: keep old numbers
                const s = await res.json();

                set('stat-revenue', money(s.online_revenue + s.pos_revenue));
                set('stat-online', Number(s.online_revenue).toFixed(2));
                set('stat-pos', Number(s.pos_revenue).toFixed(2));
                set('stat-pending', s.pending_orders);
                set('stat-low', s.low_stock);
                set('stat-conflicts', s.open_conflicts);
                set('stat-updated', s.updated_at);

                document.getElementById('stat-conflicts')
                    ?.classList.toggle('danger', s.open_conflicts > 0);
            } catch (e) {
                // offline for a moment: try again next round
            }
        }

        setInterval(refreshStats, 30000);
    </script>
@endpush