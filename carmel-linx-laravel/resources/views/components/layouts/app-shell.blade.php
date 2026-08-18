<!DOCTYPE html>
<html lang="en" class="h-full bg-[#FAFAFB]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'CampusLynk - AMS' }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Vite Asset Pipeline -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [data-lucide] { display: inline-block; width: 1.25rem; height: 1.25rem; vertical-align: middle; }
    </style>
</head>
<body class="min-h-screen bg-[#FAFAFB] text-slate-900 flex antialiased">
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
</body>
</html>
