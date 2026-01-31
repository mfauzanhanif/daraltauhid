<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')
</head>

<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white font-sans antialiased">

    <!-- Header -->
    <nav class="fixed top-0 z-50 w-full bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
        @include('partials.header')
    </nav>

    <!-- Sidebar -->
    <aside id="logo-sidebar"
        class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 sm:translate-x-0"
        aria-label="Sidebar">
        @include('partials.sidebar')
    </aside>

    <!-- Main content -->
    <div id="main-content" class="sm:ml-64 pt-25 p-4 min-h-screen bg-gray-50 dark:bg-gray-900">

        <!-- Page Header -->
        @if(isset($header))
        <div class="mb-6">
            {{ $header }}
        </div>
        @endif

        <!-- Content -->
        <div class="rounded-lg">
            {{ $slot }}
        </div>

        <!-- Footer -->
        <footer class="mt-8 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
            <p>&copy; {{ date('Y') }} Super App Dar Al Tauhid.</p>
        </footer>

    </div>

</body>

</html>