<!DOCTYPE html>
<html lang="en" class="h-full bg-[#FAFAFB]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'CampusLynk - AMS' }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts: Poppins & Material Symbols -->
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
</head>
<body class="min-h-screen bg-[#FAFAFB] text-slate-900 flex antialiased sidebar-preload">
    <!-- Sidebar Navigation (Sidebar.v1) -->
    <x-layout.sidebar :active="$activeNav ?? 'dashboard'" />

    <!-- Main Workspace Container -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        <!-- Topbar (TopBar.v1) -->
        <x-layout.topbar />

        <!-- Main Content Area -->
        <main class="flex-1 overflow-y-auto p-6 md:p-8 max-w-[1600px] w-full mx-auto">
            {{ $slot }}
        </main>
    </div>

    <script>
        requestAnimationFrame(function() {
            document.body.classList.remove('sidebar-preload');
        });
    </script>
</body>
</html>
