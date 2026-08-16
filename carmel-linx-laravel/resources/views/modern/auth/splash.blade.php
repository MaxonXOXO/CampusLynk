<!DOCTYPE html>
<html lang="en" class="h-full bg-[#FAFAFB]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusLynk - Connect. Manage. Empower.</title>

    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Vite Asset Pipeline -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#FAFAFB] flex flex-col justify-between items-center p-6 select-none font-['Poppins'] antialiased">
    <!-- Top Institution Badge -->
    <div class="pt-8">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white border border-slate-200 shadow-sm text-xs font-medium text-slate-700">
            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
            Carmel Polytechnic College • Academic Management
        </div>
    </div>

    <!-- Center Hero Branding & Loading (Calm Neutral Typography) -->
    <div class="max-w-md w-full text-center space-y-6">
        <!-- Logo Mark -->
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-blue-600 text-white font-bold text-3xl shadow-sm mx-auto">
            C
        </div>

        <!-- Typography -->
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">CampusLynk</h1>
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-widest mt-1">AMS Platform</p>
            <p class="text-sm text-slate-600 mt-3 font-normal leading-relaxed">
                Connect. Manage. Empower.<br>
                <span class="text-xs text-slate-400">A unified institutional management suite to simplify operations and enhance campus life.</span>
            </p>
        </div>

        <!-- Progress Loading Indicator -->
        <div class="w-48 mx-auto space-y-2 pt-2">
            <div class="w-full h-1.5 bg-slate-200 rounded-full overflow-hidden">
                <div class="h-full bg-blue-600 rounded-full animate-pulse w-3/4"></div>
            </div>
            <p class="text-[11px] font-medium text-slate-400">Initializing secure session...</p>
        </div>

        <!-- Continue Action (Single Action Blue CTA) -->
        <div class="pt-2">
            <a href="/modern/login" class="group inline-flex items-center justify-center font-medium rounded-xl text-sm bg-blue-600 hover:bg-blue-700 text-white min-h-[44px] px-6 py-2.5 shadow-sm transition-all">
                <span>Enter Portal</span>
                <i data-lucide="arrow-right" class="w-4 h-4 ml-2 animate-hover-arrow-right"></i>
            </a>
        </div>
    </div>

    <!-- Bottom Institutional Standards -->
    <div class="pb-6 text-center text-xs text-slate-400">
        <p>© 2026 CampusLynk AMS • Revision 2026 Academic Standard</p>
    </div>
</body>
</html>
