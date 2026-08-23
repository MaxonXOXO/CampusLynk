@props([
    'title' => 'Faculty Desk',
    'subtitle' => 'Academic workspace and classroom tools',
    'activeNav' => 'my_batches',
    'showSearch' => true,
])

<!DOCTYPE html>
<html lang="en" class="h-full bg-[#FAFAFB]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} — Carmel Linx</title>

    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <!-- Pre-Paint Synchronous Sidebar State Hydration (Anti-FOUC) -->
    <script>
        (function() {
            try {
                var isCollapsed = localStorage.getItem('campuslynk_sidebar_collapsed') === 'true' || 
                                  document.cookie.indexOf('campuslynk_sidebar_collapsed=true') !== -1;
                if (isCollapsed && window.innerWidth >= 1024) {
                    document.documentElement.classList.add('sidebar-is-collapsed');
                }
            } catch(e) {}
        })();
    </script>

    <!-- Vite Asset Pipeline -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; }
        .transition-premium { transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); }
        .scrollbar-hidden::-webkit-scrollbar { display: none; }
        .scrollbar-hidden { -ms-overflow-style: none; scrollbar-width: none; }
    </style>

    @stack('styles')
</head>
<body class="bg-[#FAFAFB] text-slate-900 min-h-screen font-sans antialiased sidebar-preload">

    <!-- Master Shell Container -->
    <div class="flex min-h-screen">

        <!-- Global Sidebar Navigation Component (Faculty Workspace items) -->
        <x-layout.sidebar role="faculty" :active="$activeNav" />

        <!-- Main Viewport Container -->
        <div class="flex-1 flex flex-col min-w-0">

            <!-- Global Topbar Header Component -->
            <x-layout.topbar :title="$title" :subtitle="$subtitle" :showSearch="$showSearch" />

            <!-- Main Workspace Canvas -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8 space-y-6">
                {{ $slot }}
            </main>
        </div>
    </div>

    <script>
        requestAnimationFrame(function() {
            document.body.classList.remove('sidebar-preload');
        });
    </script>

    @stack('scripts')
</body>
</html>
