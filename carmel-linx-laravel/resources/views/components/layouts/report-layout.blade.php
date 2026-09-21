@props([
    'title' => 'Official Report',
    'documentNo' => '',
    'orientation' => 'portrait'
])

<!DOCTYPE html>
<html lang="en" class="bg-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>

    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <!-- Tailwind CSS / Vite -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        @page {
            size: A4 {{ $orientation }};
            margin: 8mm;
        }
        @media print {
            body { background: white !important; padding: 0 !important; }
            .no-print { display: none !important; }
            .page-break { page-break-after: always; break-after: page; }
        }
    </style>
</head>
<body class="p-4 md:p-8 font-['Poppins'] antialiased text-slate-900 bg-slate-100">
    <!-- Action Controls (Hidden when printing) -->
    <div class="{{ $orientation === 'landscape' ? 'max-w-[297mm]' : 'max-w-[210mm]' }} mx-auto mb-4 flex justify-between items-center no-print">
        <button onclick="window.history.back()" class="px-4 py-2 bg-white border border-slate-300 rounded-xl text-sm font-medium hover:bg-slate-50 transition shadow-sm">
            ← Back
        </button>
        <div class="flex items-center gap-3">
            @if($documentNo)
                <span class="text-xs text-slate-500 font-mono">Doc: {{ $documentNo }}</span>
            @endif
            <button onclick="window.print()" class="px-5 py-2 bg-blue-600 text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition shadow-sm flex items-center gap-1.5">
                <span>🖨</span>
                <span>Print / Save PDF</span>
            </button>
        </div>
    </div>

    <!-- Standard A4 Sheet Document Container -->
    <div class="{{ $orientation === 'landscape' ? 'max-w-[297mm] min-h-[210mm]' : 'max-w-[210mm] min-h-[297mm]' }} mx-auto bg-white p-6 md:p-8 shadow-lg border border-slate-200 rounded-2xl print:shadow-none print:border-none print:p-0 print:max-w-none">
        {{ $slot }}
    </div>
</body>
</html>

