<!DOCTYPE html>
<html lang="en" class="h-full bg-[#FAFAFB]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'CampusLynk - Authentication' }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="icon" type="image/svg+xml" href="{{ asset('logo.svg') }}">
    <!-- Vite Asset Pipeline -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#FAFAFB] flex flex-col justify-between items-center p-4 sm:p-6 font-['Poppins'] antialiased">
    <!-- Top Minimal Brand Header -->
    <header class="w-full max-w-5xl flex items-center justify-between py-4">
        <a href="/modern/login" class="flex items-center gap-3">
            <img src="{{ asset('logo.svg') }}" alt="CampusLynk Logo" class="w-9 h-9 object-contain rounded-xl shadow-xs border border-slate-100" />
            <div>
                <span class="text-sm font-bold text-slate-900 leading-tight">CampusLynk</span>
                <span class="text-[10px] text-slate-500 font-semibold ml-1.5 uppercase">AMS</span>
            </div>
        </a>

        <div class="text-xs text-slate-500 hidden sm:flex items-center gap-2">
            <span>Carmel Polytechnic College</span>
            <span class="w-1 h-1 rounded-full bg-slate-300"></span>
            <span class="text-emerald-700 font-medium">Systems Active</span>
        </div>
    </header>

    <!-- Main Container -->
    <main class="w-full max-w-md my-auto py-8">
        {{ $slot }}
    </main>

    <!-- Bottom Footer -->
    <footer class="w-full text-center py-4 text-xs text-slate-400">
        <p>© 2026 CampusLynk AMS • Revision 2026 Academic Standard</p>
    </footer>
</body>
</html>
