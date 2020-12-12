<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">

    @livewireStyles

    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}" defer></script>
</head>
<body class="font-sans antialiased">
<div class="min-h-screen bg-gray-100">
    @livewire('navigation-dropdown')

    <!-- Page Heading -->
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap justify-center">
                <img src="https://tubecrush.net/wp-content/uploads/2018/08/cropped-Logo-TC-BlkBk.jpg" alt="Site Banner"
                     class="shadow rounded max-w-full h-auto align-middle border-none"/>
            </div>
        </div>
    </header>

    <!-- Page Content -->
    <main class="bg-white">
        {{ $slot }}
    </main>

    @livewire('footer')
</div>

@stack('modals')

@livewireScripts
</body>
</html>
