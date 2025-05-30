<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
        @livewireScripts
        <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
        <script>
            Livewire.on('notify', (data) => {
                const { type, message } = data[0];

                const colors = {
                    success: {
                        bg: "#16a34a", // Tailwind green-600
                    },
                    error: {
                        bg: "#dc2626", // Tailwind red-600
                    },
                    info: {
                        bg: "#2563eb", // Tailwind blue-600
                    }
                };

                Toastify({
                    text: message,
                    duration: 1000,
                    close: true,
                    gravity: "top", // 'top' or 'bottom'
                    position: "right", // 'left', 'center' or 'right'
                    stopOnFocus: true,
                    style: {
                        background: colors[type]?.bg || "#6b7280", // fallback to Tailwind gray-500
                        color: "#fff",
                        borderRadius: "6px",
                        padding: "12px 16px",
                        fontSize: "14px",
                        boxShadow: "0 4px 8px rgba(0, 0, 0, 0.1)",
                        zIndex: 9999,
                    },
                }).showToast();
            });
        </script>
    </body>
</html>
