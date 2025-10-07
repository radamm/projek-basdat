<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <style>
        /* Tombol Utama (Biru) */
        .btn-primary { background-color: #3b82f6; color: white; padding: 8px 16px; border-radius: 8px; font-weight: 600; transition: all 0.2s; display: inline-flex; align-items: center; }
        .btn-primary:hover { background-color: #2563eb; transform: translateY(-1px); box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .btn-primary-sm { background-color: #3b82f6; color: white; padding: 4px 10px; border-radius: 6px; font-size: 0.875rem; font-weight: 600; transition: background-color 0.2s; }
        .btn-primary-sm:hover { background-color: #2563eb; }

        /* Tombol Sekunder (Abu-abu) */
        .btn-secondary-sm { background-color: #e5e7eb; color: #374151; padding: 4px 10px; border-radius: 6px; font-size: 0.875rem; font-weight: 600; transition: background-color 0.2s; }
        .btn-secondary-sm:hover { background-color: #d1d5db; }
        
        /* Tombol Outline (Transparan dengan border) */
        .btn-outline { border: 1px solid #d1d5db; color: #4b5563; padding: 8px 16px; border-radius: 8px; font-weight: 600; transition: all 0.2s; display: inline-flex; align-items: center; }
        .btn-outline:hover { background-color: #f3f4f6; border-color: #9ca3af; }

        /* Tombol Warning (Kuning) */
        .btn-warning-sm { background-color: #f59e0b; color: white; padding: 4px 10px; border-radius: 6px; font-size: 0.875rem; font-weight: 600; transition: background-color 0.2s; }
        .btn-warning-sm:hover { background-color: #d97706; }

        /* Tombol Danger (Merah) */
        .btn-danger-sm { background-color: #ef4444; color: white; padding: 4px 10px; border-radius: 6px; font-size: 0.875rem; font-weight: 600; transition: background-color 0.2s; }
        .btn-danger-sm:hover { background-color: #dc2626; }
    </style>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Lost & Found') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Added Tailwind CSS CDN for immediate styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'jakarta': ['Plus Jakarta Sans', 'sans-serif'],
                        'inter': ['Inter', 'sans-serif'],
                    },
                    colors: {
                        'primary-blue': '#2563eb',
                        'primary-blue-dark': '#1d4ed8',
                        'accent-yellow': '#f59e0b',
                        'accent-yellow-dark': '#d97706',
                    }
                }
            }
        }
    </script>
    
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    @stack('styles')
</head>
<body class="font-jakarta antialiased bg-gray-50">
    <div id="app">
        @if (!isset($hideNavigation))
            @auth
                @include('layouts.navigation')
            @endauth
        @endif
        
        <main @if(Auth::check() && !isset($hideNavigation)) class="pt-16" @endif>
             @yield('content')
        </main>
    </div>
    
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
