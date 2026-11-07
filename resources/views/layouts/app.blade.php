<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name', 'ARCHIVA') }} — SDN Jatipulo 07</title>

        <!-- Prevent Theme Flash -->
        <script>
            if (localStorage.getItem('archiva_theme') === 'dark' || (!('archiva_theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="h-full bg-surface text-on-surface font-sans antialiased">
        <div class="min-h-full flex flex-col">
            <!-- Sidebar Navigation -->
            <x-sidebar :active="$active ?? null" />

            <!-- Main Shell (Offset menyesuaikan status collapse sidebar: w-64 vs w-20) -->
            <div
                :class="$store.sidebar.collapsed ? 'lg:pl-20' : 'lg:pl-64'"
                class="flex flex-col min-h-screen transition-all duration-200 ease-in-out"
            >
                <!-- Topbar -->
                <x-topbar />

                <!-- Optional Header Banner / Breadcrumb -->
                @isset($header)
                    <header class="bg-surface-lowest border-b border-border-subtle py-4 px-4 sm:px-6 lg:px-8">
                        <div class="max-w-7xl mx-auto">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Flash Status Messages -->
                @if (session('status'))
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 w-full">
                        <div class="flex items-center gap-2 p-4 rounded-xl bg-success-container text-on-success-container border border-success/30 text-sm font-medium" role="status">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>{{ session('status') }}</span>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 w-full">
                        <div class="flex items-center gap-2 p-4 rounded-xl bg-error-container text-on-error-container border border-error/30 text-sm font-medium" role="alert">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" stroke-width="2"></circle>
                                <line x1="12" y1="8" x2="12" y2="12" stroke-width="2"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16" stroke-width="2"></line>
                            </svg>
                            <span>{{ session('error') }}</span>
                        </div>
                    </div>
                @endif

                <!-- Page Content -->
                <main class="flex-1 max-w-7xl w-full mx-auto p-4 sm:p-6 lg:p-8">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
