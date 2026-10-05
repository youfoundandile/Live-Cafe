
{{--resources/views/admin/layout.blade.php--}}
@extends('layouts.app')

@push('styles')
@include('admin.partials.styles')                              

@endpush

@section('content')
    <div class="admin-wrap">
        @include('admin.partials.sidebar')

        <main class="admin-main">
            @if(session('success'))<div class= "alert alert-success">{{session('success') }}</div> @endif
            @if(session('error'))<div class= "alert alert-error">{{session('error') }}</div> @endif
            @yield('admin-content')
        </main>
    </div>
@endsection
