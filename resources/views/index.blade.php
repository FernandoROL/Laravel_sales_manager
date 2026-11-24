<!doctype html>
<html lang="{{ request()->routeIs('dashboard.index') ? 'pt-BR' : 'en' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sales Manager</title>
    <link rel="icon" href="{{ asset('icones/favicon-32x32.png') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    @unless (request()->routeIs('dashboard.index'))
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css">
    @endunless
    @yield('styles')
</head>
<body>
    <header class="app-bar">
        <a class="app-name" href="{{ route('dashboard.index') }}">Sales Manager</a>
        <button class="btn btn-sm btn-outline-light d-md-none" type="button" data-bs-toggle="collapse"
                data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Menu">Menu</button>
    </header>
    <div class="app-layout">
        @include('components.navegacao')
        <main class="app-main">@yield('content')</main>
    </div>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    @unless (request()->routeIs('dashboard.index'))
        <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.blockUI/2.70/jquery.blockUI.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js"></script>
        <script src="{{ asset('js/project.js') }}"></script>
        {!! Toastr::message() !!}
    @endunless
    @yield('scripts')
</body>
</html>
