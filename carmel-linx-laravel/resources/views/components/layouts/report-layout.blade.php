@props([
    'title' => 'Official Report',
    'documentNo' => ''
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

    <!-- Vite Asset Pipeline -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @media print {
            body { background: white !important; }
            .no-print { display: none !important; }
            .page-break { page-break-after: always; }
        }
    </style>
</head>
<body class="p-4 md:p-8 font-['Poppins'] antialiased">
    <!-- Action Controls (Hidden when printing) -->
    <div class="max-w-[210mm] mx-auto mb-4 flex justify-between items-center no-print">
        <button onclick="window.history.back()" class="px-4 py-2 bg-white border border-slate-300 rounded-xl text-sm font-medium hover:bg-slate-50">
            ← Back
        </button>
        <button onclick="window.print()" class="px-5 py-2 bg-blue-600 text-white rounded-xl text-sm font-semibold hover:bg-blue-700 shadow-sm">
            🖨 Print / Save PDF
        </button>
    </div>

    <!-- Standard A4 Sheet Document Container -->
    <div class="max-w-[210mm] min-h-[297mm] mx-auto bg-white p-8 md:p-12 shadow-lg border border-slate-200 rounded-2xl print:shadow-none print:border-none print:p-0">
        {{ $slot }}
    </div>
</body>
</html>
