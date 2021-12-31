<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    @livewireStyles
    @stack('styles')
</head>
<body class="font-sans antialiased">
<div class="min-h-screen bg-gray-100">
    <x-navigation-dropdown/>

    <!-- Page Heading -->
    <header class="bg-white">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            {{ $header }}
        </div>
    </header>

    <!-- Page Content -->
    <main class="bg-white pb-6">
        {{ $slot }}
    </main>

    @livewire('footer')
</div>

@livewire('cookie-consent')

@stack('modals')

<!-- Scripts -->
<script src="{{ mix('js/app.js') }}" defer></script>
@livewireScripts
@stack('scripts')
</body>
</html>
