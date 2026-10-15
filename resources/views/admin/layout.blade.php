{{-- resources/views/admin/layout.blade.php --}}
@extends('layouts.app')

@push('styles')
    @include('admin.partials.styles')
@endpush

@section('content')
    <div class="admin-wrap">
        @include('admin.partials.sidebar')

        {{-- Success and error messages already show in the flash bar from layouts/app, so they aren't repeated here.
             A div, not <main>: layouts/app already has the page's one <main>. --}}
        <div class="admin-main">
            @yield('admin-content')
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Stop a double click from sending a form twice (e.g. recording the same delivery twice)
    document.addEventListener('submit', (event) => {
        if (event.defaultPrevented) {
            return;   // the confirm() box was cancelled, so nothing is being sent
        }
        event.target.querySelectorAll('button[type="submit"]').forEach((button) => {
            button.disabled = true;
        });
    });

    // The browser's Back button can bring back the page with its buttons still switched off
    window.addEventListener('pageshow', () => {
        document.querySelectorAll('.admin-main button[disabled]').forEach((button) => {
            button.disabled = false;
        });
    });
</script>
@endpush