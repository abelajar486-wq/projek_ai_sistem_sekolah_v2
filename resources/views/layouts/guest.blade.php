<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name', 'EduSmart LMS') }} &mdash; Portal Sekolah</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Anton&family=Inter:wght@400;500;600;700;800&family=Permanent+Marker&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="wall-concrete min-h-screen">
        <div class="min-h-screen lg:grid lg:grid-cols-[1.05fr_1fr]">
            <!-- DINDING GRAFFITI (KIRI) -->
            <aside class="relative hidden lg:flex flex-col justify-between p-10 xl:p-12 wall-brick overflow-hidden border-r-4 border-asphalt-950">
                <span class="spray-blob spray-blob-pink" style="width: 320px; height: 320px; top: -80px; right: -60px;"></span>
                <span class="spray-blob spray-blob-cyan" style="width: 260px; height: 260px; bottom: 40px; left: -70px;"></span>
                <span class="spray-blob spray-blob-acid" style="width: 180px; height: 180px; top: 45%; right: 15%; opacity: .35;"></span>

                <div class="relative z-10">
                    <div class="inline-flex items-center gap-2 hazard-stripe px-4 py-1.5 border-2 border-asphalt-950">
                        <span class="bg-asphalt-950 text-traffic-300 font-display text-[11px] tracking-[0.2em] px-2 py-0.5 uppercase">Sistem Sekolah</span>
                    </div>

                    <h1 class="mt-7 text-5xl xl:text-6xl leading-none text-concrete-50">
                        <span class="brand-mark block text-6xl xl:text-7xl">EduSmart</span>
                        <span class="block mt-3 text-concrete-100">Portal Belajar</span>
                        <span class="block text-spray-500">Jalanan Digital</span>
                    </h1>

                    <p class="mt-5 max-w-md text-sm leading-relaxed text-concrete-300">
                        Ruang kelas, materi, tugas, presensi, dan asisten AI &mdash; semuanya ditempel rapi di satu tembok digital untuk guru dan siswa SMK.
                    </p>

                    <div class="mt-7 flex flex-wrap gap-2.5">
                        <span class="tag-street tag-tilt bg-traffic-400 text-asphalt-950">Materi &amp; Tugas</span>
                        <span class="tag-street tag-tilt-right bg-acid-400 text-asphalt-950">Presensi Kelas</span>
                        <span class="tag-street tag-tilt bg-spray-500 text-white">AI Groq Tutor</span>
                        <span class="tag-street tag-tilt-right bg-sky-300 text-asphalt-950">Generator Soal</span>
                    </div>
                </div>

                <div class="relative z-10 flex items-end justify-between gap-4">
                    <p class="text-[11px] uppercase tracking-[0.18em] text-concrete-400">&copy; {{ date('Y') }} EduSmart LMS &bull; SMK RPL</p>
                    <span class="font-marker text-lg text-traffic-300 -rotate-3">keep learning!</span>
                </div>

                <div class="absolute bottom-0 left-0 right-0 h-3 hazard-stripe border-t-2 border-asphalt-950"></div>
            </aside>

            <!-- FORMULIR (KANAN) -->
            <main class="flex items-center justify-center px-5 py-10 sm:px-8">
                <div class="w-full max-w-md">
                    <div class="flex items-center justify-between lg:justify-start gap-3">
                        <a href="{{ route('home') }}" class="flex items-center gap-3 focus-street rounded-lg">
                            <span class="w-11 h-11 border-2 border-asphalt-950 bg-spray-600 text-white flex items-center justify-center font-display text-xl shadow-sticker-sm -rotate-6">E</span>
                            <span class="font-display text-xl uppercase tracking-wide text-asphalt-900">EduSmart LMS</span>
                        </a>
                    </div>

                    <div class="mt-7 sticker sticker-taped rounded-xl p-6 sm:p-8">
                        {{ $slot }}
                    </div>

                    <p class="mt-6 text-center text-[11px] text-concrete-500">
                        Akses terbatas untuk warga sekolah &bull; Data Anda aman di server sendiri.
                    </p>
                </div>
            </main>
        </div>
    </body>
</html>
