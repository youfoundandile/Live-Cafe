{{-- resources/views/admin/partials/sidebar.blade.php --}}
@php($openConflicts = \App\Models\SyncConflict::open()->count())

<aside class="admin-sidebar" aria-label="Admin menu">
    <div class="admin-sidebar-section">
        <p class="admin-sidebar-label">Overview</p>
        <ul>
            <li><a href="{{ route('admin.dashboard') }}" @class(['active' => request()->routeIs('admin.dashboard')])>Dashboard</a></li>
        </ul>
    </div>

    <div class="admin-sidebar-section">
        <p class="admin-sidebar-label">Cafe</p>
        <ul>
            <li><a href="{{ route('admin.products.index') }}" @class(['active' => request()->routeIs('admin.products.*')])>Products</a></li>
            <li><a href="{{ route('admin.categories.index') }}" @class(['active' => request()->routeIs('admin.categories.*')])>Categories</a></li>
            <li><a href="{{ route('admin.inventory.index') }}" @class(['active' => request()->routeIs('admin.inventory.*')])>Inventory</a></li>
            <li><a href="{{ route('admin.orders.index') }}" @class(['active' => request()->routeIs('admin.orders.*')])>Orders</a></li>
            <li><a href="{{ route('admin.sales.index') }}" @class(['active' => request()->routeIs('admin.sales.*')])>POS Sales</a></li>
            <li>
                <a href="{{ route('admin.sync.index') }}" @class(['active' => request()->routeIs('admin.sync.*')])>
                    Sync conflicts
                    @if ($openConflicts)
                        <span class="status-badge status-cancelled">{{ $openConflicts }}</span>
                    @endif
                </a>
            </li>
        </ul>
    </div>

    <div class="admin-sidebar-section">
        <p class="admin-sidebar-label">Running Club</p>
        <ul>
            <li><a href="{{ route('admin.events.index') }}" @class(['active' => request()->routeIs('admin.events.*')])>Events</a></li>
            <li><a href="{{ route('admin.announcements.index') }}" @class(['active' => request()->routeIs('admin.announcements.*')])>Announcements</a></li>
        </ul>
    </div>

    <div class="admin-sidebar-section">
        <p class="admin-sidebar-label">System</p>
        <ul>
            <li><a href="{{ route('admin.users.index') }}" @class(['active' => request()->routeIs('admin.users.*')])>Users</a></li>
            <li><a href="{{ route('admin.partnerships.index') }}" @class(['active' => request()->routeIs('admin.partnerships.*')])>Partnerships</a></li>
        </ul>
    </div>
</aside>