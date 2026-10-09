<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduBot AI &mdash; Chat Mandiri</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Inter:wght@400;500;600;700;800&family=Permanent+Marker&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="wall-concrete min-h-screen">
    <div class="max-w-3xl mx-auto px-4 pt-6 pb-10">
        <div class="flex items-center justify-between gap-3 mb-5">
            <a href="{{ route('home') }}" class="btn-street bg-white px-3.5 py-2 rounded-lg font-display text-xs uppercase tracking-widest focus-street">
                &larr; Ruang Kelas
            </a>
            <span class="tag-street tag-tilt bg-spray-500 text-white">EduBot AI</span>
        </div>

        <livewire:chatbot />
    </div>
    @livewireScripts
</body>
</html>
