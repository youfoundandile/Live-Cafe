
 <aside class="admin-sidebar">
        <div class="admin-sidebar-section">
            <p class="admin-sidebar-label">Overview</p>
            <ul>
                <li><a href="{{ route('admin.dashboard') }}" class="active">Dashboard</a></li>
            </ul>
        </div>

        <div class="admin-sidebar-section">
            <p class="admin-sidebar-label">Cafe</p>
            <ul>
                <li><a href="{{ route('admin.products.index') }}">Products</a></li>
                <li><a href="{{ route('admin.inventory.index') }}">Inventory</a></li>
                <li><a href="{{ route('admin.orders.index') }}">Orders</a></li>
                <li><a href="{{ route('admin.sales.index') }}">POS Sales</a></li>
            </ul>
        </div>

        <div class="admin-sidebar-section">
            <p class="admin-sidebar-label">Running Club</p>
            <ul>
                <li><a href="{{ route('admin.events.index') }}">Events</a></li>
                <li><a href="{{ route('admin.announcements.index') }}">Announcements</a></li>
            </ul>
        </div>

        <div class="admin-sidebar-section">
            <p class="admin-sidebar-label">System</p>
            <ul>
                <li><a href="{{ route('admin.users.index') }}">Users</a></li>
                <li><a href="{{ route('admin.partnerships.index') }}">Partnerships</a></li>
            </ul>
        </div>
</aside>