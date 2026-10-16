<div class="min-h-screen wall-concrete font-sans text-asphalt-900 pb-24">
    <!-- TOP NAVIGATION BAR -->
    <header class="wall-asphalt text-white shadow-sticker sticky top-0 z-40 border-b-4 border-asphalt-950">
        <div class="absolute inset-x-0 top-0 h-1.5 hazard-stripe"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between gap-3 h-16 pt-1.5">
                <!-- Brand Logo & Title -->
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-spray-600 border-2 border-asphalt-950 flex items-center justify-center text-white shadow-sticker-sm -rotate-6">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="font-display uppercase tracking-wide text-base sm:text-lg leading-none flex items-center gap-2">
                            EduSmart LMS <span class="bg-traffic-400 text-asphalt-950 text-[10px] font-bold px-2 py-0.5 border border-asphalt-950 uppercase tracking-wider -rotate-2 inline-block">Sistem Sekolah</span>
                        </h1>
                        <p class="text-[11px] text-concrete-400 mt-0.5">Portal Pembelajaran Terpadu & Asisten Akademik</p>
                    </div>
                </div>

                <!-- Current User Pill + Logout -->
                <div class="flex items-center gap-2.5">
                    @php $roleLabel = \App\Models\User::ROLES[$currentUser->role] ?? 'Siswa'; @endphp
                    <div class="flex items-center gap-2.5 bg-asphalt-950 px-3 py-1.5 border-2 border-spray-600 text-xs text-white rotate-1">
                        <div class="w-7 h-7 bg-spray-600 text-white flex items-center justify-center font-display text-sm border border-white/40">
                            {{ substr($currentUser->name, 0, 1) }}
                        </div>
                        <div class="text-left hidden sm:block">
                            <div class="font-semibold leading-tight">{{ $currentUser->name }}</div>
                            <div class="text-[10px] text-concrete-400 capitalize">{{ $roleLabel }} &bull; {{ $currentUser->nis_nip ?: '-' }}</div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn-street bg-traffic-400 text-asphalt-950 px-3 py-1.5 text-[11px] font-display uppercase tracking-wider focus-street">
                            Keluar
                        </button>
                    </form>

                    <a href="{{ route('profile') }}" class="btn-street bg-white/95 text-asphalt-900 px-3 py-1.5 text-[11px] font-display uppercase tracking-wider focus-street">
                        Profil
                    </a>
                </div>
            </div>

            <!-- TAB MENU -->
            <div class="flex flex-wrap gap-2 py-3 border-t-2 border-dashed border-white/20 text-xs sm:text-sm">
                <button
                    wire:click="setTab('dashboard')"
                    class="btn-street px-3 py-1.5 flex items-center gap-2 focus-street {{ $activeTab === 'dashboard' ? 'bg-spray-600 text-white -rotate-1 shadow-sticker-sm' : 'bg-white/95 text-asphalt-800 hover:bg-traffic-300' }}"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </button>
                <button
                    wire:click="setTab('materi')"
                    class="btn-street px-3 py-1.5 flex items-center gap-2 focus-street {{ $activeTab === 'materi' ? 'bg-spray-600 text-white -rotate-1 shadow-sticker-sm' : 'bg-white/95 text-asphalt-800 hover:bg-traffic-300' }}"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    Materi ({{ $materials->count() }})
                </button>
                <button
                    wire:click="setTab('tugas')"
                    class="btn-street px-3 py-1.5 flex items-center gap-2 focus-street {{ $activeTab === 'tugas' ? 'bg-spray-600 text-white -rotate-1 shadow-sticker-sm' : 'bg-white/95 text-asphalt-800 hover:bg-traffic-300' }}"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Tugas ({{ $assignments->count() }})
                </button>
                <button
                    wire:click="setTab('absensi')"
                    class="btn-street px-3 py-1.5 flex items-center gap-2 focus-street {{ $activeTab === 'absensi' ? 'bg-spray-600 text-white -rotate-1 shadow-sticker-sm' : 'bg-white/95 text-asphalt-800 hover:bg-traffic-300' }}"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Presensi
                </button>

                @if($currentUser->role === 'guru')
                    <button
                        wire:click="setTab('ai_generator')"
                        class="btn-street px-3 py-1.5 flex items-center gap-2 focus-street {{ $activeTab === 'ai_generator' ? 'bg-acid-400 text-asphalt-950 rotate-1 shadow-sticker-sm' : 'bg-cyan-300 text-asphalt-950 hover:bg-cyan-200' }}"
                    >
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        Generator Soal AI
                    </button>
                @endif
            </div>
        </div>
    </header>

    <!-- FLASH NOTIFICATION -->
    @if($flashMessage)
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="flex items-center justify-between gap-3 p-3.5 rounded-xl border-2 border-asphalt-950 shadow-sticker-sm {{ $flashType === 'success' ? 'bg-acid-300 text-acid-950' : ($flashType === 'error' ? 'bg-tag-300 text-tag-950' : 'bg-cyan-300 text-asphalt-950') }}">
                <div class="flex items-center gap-2.5 text-sm font-semibold">
                    <span class="inline-block w-2.5 h-2.5 rotate-45 {{ $flashType === 'success' ? 'bg-acid-700' : ($flashType === 'error' ? 'bg-tag-700' : 'bg-asphalt-900') }}"></span>
                    <span>{{ $flashMessage }}</span>
                </div>
                <button wire:click="clearNotification" class="btn-street bg-white px-2.5 py-1 text-[11px] font-display uppercase tracking-wider">Tutup</button>
            </div>
        </div>
    @endif

    <!-- MAIN CONTENT CONTAINER -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">

        <!-- CLASSROOM SELECTOR BAR -->
        <div class="sticker rounded-xl p-3 mb-6 flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-2.5">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Ruang Kelas Aktif:</span>
                <select
                    wire:model.live="selectedClassroomId"
                    class="field px-3 py-1.5 text-xs sm:text-sm font-medium"
                >
                    @foreach($classrooms as $c)
                        <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->code }})</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-2 text-xs text-slate-500">
                <span class="inline-block w-2 h-2 rounded-full bg-emerald-500"></span>
                <span>Layanan AI Groq (Qwen 3.8-27B): <strong class="text-slate-700 font-semibold">Aktif & Siap Digunakan</strong></span>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 1: DASHBOARD -->
        <!-- ========================================== -->
        @if($activeTab === 'dashboard')
            <div class="space-y-6">
                <!-- Welcome Banner -->
                <div class="wall-brick rounded-2xl p-6 sm:p-8 text-white border-4 border-asphalt-950 shadow-sticker relative overflow-hidden">
                    <div class="max-w-3xl">
                        <div class="tag-street tag-tilt bg-traffic-400 text-asphalt-950 mb-3">
                            <span>Sistem Manajemen Pembelajaran Digital</span>
                        </div>
                        <h2 class="text-xl sm:text-2xl font-bold tracking-tight">
                            Selamat Datang, {{ $currentUser->name }}
                        </h2>
                        <p class="text-slate-300 text-sm mt-2 leading-relaxed">
                            @if($currentUser->role === 'guru')
                                Anda memiliki hak akses <strong>Guru</strong>. Anda dapat mengunggah materi pelajaran, merekap presensi kelas, dan menggunakan asisten AI untuk memproduksi soal ujian secara otomatis dari materi ajar.
                            @else
                                Anda memiliki hak akses <strong>Siswa</strong>. Silakan pelajari materi ajar, lengkapi presensi harian, selesaikan tugas yang diberikan guru, dan gunakan AI Asisten Belajar untuk konsultasi materi.
                            @endif
                        </p>

                        <div class="mt-6 flex flex-wrap gap-2.5">
                            @if($currentUser->role === 'guru')
                                <button
                                    wire:click="setTab('ai_generator')"
                                    class="btn-street bg-spray-600 text-white px-4 py-2 text-xs flex items-center gap-2"
                                >
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                                    Buat Tugas Baru via AI
                                </button>
                                <button
                                    wire:click="setTab('absensi')"
                                    class="btn-street bg-white/95 text-asphalt-900 px-4 py-2 text-xs font-medium"
                                >
                                    Lihat Rekap Presensi
                                </button>
                            @else
                                <button
                                    wire:click="setTab('tugas')"
                                    class="btn-street bg-spray-600 text-white px-4 py-2 text-xs flex items-center gap-2"
                                >
                                    Buka Lembar Tugas
                                </button>
                                <button
                                    wire:click="setTab('absensi')"
                                    class="btn-street bg-white/95 text-asphalt-900 px-4 py-2 text-xs font-medium"
                                >
                                    Isi Presensi Hari Ini
                                </button>
                            @endif
                            <button
                                wire:click="openChatWithContext('Dashboard', 'Halo, bantu saya memahami fitur pembelajaran di website ini')"
                                class="btn-street bg-white/95 text-asphalt-900 px-4 py-2 text-xs font-medium flex items-center gap-1.5"
                            >
                                <svg class="w-4 h-4 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" /></svg>
                                Konsultasi Asisten AI
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Stats Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="sticker rounded-xl p-5">
                        <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Modul Materi</div>
                        <div class="text-2xl font-bold text-slate-800 mt-1">{{ $materials->count() }} Modul</div>
                        <div class="text-[11px] text-slate-400 mt-1">Tersedia untuk dipelajari</div>
                    </div>

                    <div class="sticker rounded-xl p-5">
                        <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Tugas Aktif</div>
                        <div class="text-2xl font-bold text-slate-800 mt-1">{{ $assignments->count() }} Tugas</div>
                        <div class="text-[11px] text-slate-400 mt-1">Tenggat semester berjalan</div>
                    </div>

                    <div class="sticker rounded-xl p-5">
                        <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Kehadiran Hari Ini</div>
                        <div class="text-2xl font-bold text-slate-800 mt-1">{{ $todayAttendances->where('status', 'hadir')->count() }} / {{ $classStudents->count() }}</div>
                        <div class="text-[11px] text-slate-400 mt-1">Siswa telah melakukan presensi</div>
                    </div>

                    <div class="sticker rounded-xl p-5">
                        <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Tugas Dibuat via AI</div>
                        <div class="text-2xl font-bold text-slate-800 mt-1">{{ $assignments->where('ai_generated', true)->count() }} Tugas</div>
                        <div class="text-[11px] text-slate-400 mt-1">Otomasi generator Groq</div>
                    </div>
                </div>

                <!-- Two Column Feature Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="sticker rounded-xl p-6 flex flex-col justify-between">
                        <div>
                            <span class="text-xs font-semibold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded uppercase">Fitur Guru</span>
                            <h3 class="text-base font-bold text-slate-900 mt-2">Generator Soal Berbasis Materi</h3>
                            <p class="text-xs text-slate-600 mt-2 leading-relaxed">
                                Guru cukup memasukkan teks materi bahan ajar. Sistem AI akan mengekstrak konsep pokok, memformulasikan butir-butir pertanyaan terstruktur, menyertakan kunci jawaban untuk arsip pengajar, dan menerbitkannya langsung ke kelas siswa.
                            </p>
                        </div>
                        <div class="mt-5 pt-3 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-xs text-slate-400">Efisiensi penyusunan tugas berkala</span>
                            <button wire:click="setTab('ai_generator')" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">
                                Buka Generator Soal &rarr;
                            </button>
                        </div>
                    </div>

                    <div class="sticker rounded-xl p-6 flex flex-col justify-between">
                        <div>
                            <span class="text-xs font-semibold text-sky-700 bg-sky-50 px-2 py-0.5 rounded uppercase">Fitur Siswa</span>
                            <h3 class="text-base font-bold text-slate-900 mt-2">Asisten Akademik & Diskusi Tugas</h3>
                            <p class="text-xs text-slate-600 mt-2 leading-relaxed">
                                Siswa yang mengalami kendala pemahaman saat mengerjakan lembar soal dapat langsung mengajukan pertanyaan ke asisten cerdas untuk meminta penjelasan konsep, petunjuk pengerjaan, atau pembahasan langkah penyelesaian.
                            </p>
                        </div>
                        <div class="mt-5 pt-3 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-xs text-slate-400">Konsultasi interaktif kapan saja</span>
                            <button wire:click="openChatWithContext('Tanya Bebas', 'Halo, saya ingin bertanya materi pelajaran hari ini')" class="text-xs font-semibold text-sky-600 hover:text-sky-800">
                                Buka Panel Tanya AI &rarr;
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- ========================================== -->
        <!-- TAB 2: MATERI BELAJAR -->
        <!-- ========================================== -->
        @if($activeTab === 'materi')
            <div class="space-y-6">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Modul & Materi Pembelajaran</h2>
                        <p class="text-xs text-slate-500">Materi yang dipelajari pada {{ $activeClassroom?->name }}</p>
                    </div>

                    @if($currentUser->role === 'guru')
                        <button
                            wire:click="$set('showMaterialModal', true)"
                            class="btn-street bg-spray-600 text-white text-xs px-3.5 py-2 flex items-center gap-1.5"
                        >
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                            Tambah Materi Baru
                        </button>
                    @endif
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($materials as $mat)
                        <div class="sticker sticker-hover rounded-xl p-5 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="tag-street bg-white text-asphalt-800">
                                        {{ $mat->topic }}
                                    </span>
                                    <span class="text-xs text-slate-400">
                                        {{ $mat->created_at->format('d M Y') }}
                                    </span>
                                </div>
                                <h3 class="text-sm font-bold text-slate-800 leading-snug">
                                    {{ $mat->title }}
                                </h3>
                                <p class="text-xs text-slate-500 mt-2 line-clamp-3 leading-relaxed">
                                    {{ Str::limit(strip_tags($mat->content), 160) }}
                                </p>
                            </div>

                            <div class="mt-5 pt-3 border-t border-slate-100 flex flex-wrap items-center justify-between gap-2">
                                <button
                                    wire:click="openMaterial({{ $mat->id }})"
                                    class="btn-street text-xs font-semibold text-asphalt-800 bg-white px-3 py-1.5"
                                >
                                    Baca Modul
                                </button>

                                <div class="flex items-center gap-2">
                                    <button
                                        wire:click="openChatWithContext('Materi: {{ $mat->title }}', '{{ addslashes($mat->content) }}')"
                                        class="btn-street text-xs font-medium text-asphalt-900 bg-traffic-300 px-2.5 py-1.5"
                                        title="Konsultasi materi dengan AI"
                                    >
                                        Tanya AI
                                    </button>

                                    @if($currentUser->role === 'guru')
                                        <button
                                            wire:click="prepareAiFromMaterial({{ $mat->id }})"
                                            class="btn-street text-xs font-semibold text-asphalt-900 bg-traffic-300 px-2.5 py-1.5"
                                            title="Buat tugas otomatis dari materi ini"
                                        >
                                            Buat Tugas via AI
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 text-center py-12 rounded-xl border-2 border-dashed border-concrete-400 bg-concrete-50">
                            <div class="text-sm font-semibold text-slate-700">Belum ada modul materi untuk kelas ini.</div>
                            <p class="text-xs text-slate-400 mt-1">Guru dapat menambahkan modul materi baru menggunakan tombol di atas.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        @endif

        <!-- ========================================== -->
        <!-- TAB 3: TUGAS & EVALUASI -->
        <!-- ========================================== -->
        @if($activeTab === 'tugas')
            <div class="space-y-6">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Daftar Tugas & Lembar Kerja</h2>
                        <p class="text-xs text-slate-500">
                            @if($currentUser->role === 'guru')
                                Pantau penugasan siswa, periksa pengumpulan jawaban, dan berikan evaluasi nilai.
                            @else
                                Kerjakan tugas kelas dan gunakan bantuan asisten AI jika memerlukan penjelasan materi.
                            @endif
                        </p>
                    </div>

                    @if($currentUser->role === 'guru')
                        <button
                            wire:click="setTab('ai_generator')"
                            class="btn-street bg-spray-600 text-white text-xs px-3.5 py-2 flex items-center gap-1.5"
                        >
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                            Buat Tugas via AI
                        </button>
                    @endif
                </div>

                <div class="space-y-4">
                    @forelse($assignments as $assign)
                        @php
                            $mySub = $assign->studentSubmission($currentUserId);
                            $allSubs = $assign->submissions;
                        @endphp
                        <div class="sticker sticker-hover rounded-xl p-5">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div class="flex-1 min-w-[280px]">
                                    <div class="flex items-center gap-2 mb-1.5">
                                        @if($assign->ai_generated)
                                            <span class="text-[10px] font-semibold bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded border border-indigo-200">
                                                AI Generated
                                            </span>
                                        @endif
                                        <span class="text-[10px] font-medium bg-slate-100 text-slate-600 px-2 py-0.5 rounded">
                                            {{ $assign->topic ?: 'Umum' }}
                                        </span>
                                        <span class="text-xs text-slate-400">
                                            Batas Waktu: {{ $assign->due_date ? $assign->due_date->format('d M Y') : 'Tanpa Batas' }}
                                        </span>
                                        @if($assign->isOverdue())
                                            <span class="text-[10px] font-semibold bg-rose-50 text-rose-700 px-2 py-0.5 rounded border border-rose-200">
                                                Lewat Tenggat
                                            </span>
                                        @endif
                                    </div>
                                    <h3 class="text-sm font-bold text-slate-800">{{ $assign->title }}</h3>
                                    <p class="text-xs text-slate-600 mt-1 leading-relaxed">{{ $assign->instructions }}</p>
                                </div>

                                <div class="flex flex-col sm:items-end gap-2">
                                    @if($currentUser->role === 'murid')
                                        @if($mySub)
                                            <div class="text-right">
                                                <span class="text-xs font-semibold px-2.5 py-1 rounded {{ $mySub->status === 'graded' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-slate-100 text-slate-700 border border-slate-200' }}">
                                                    {{ $mySub->status === 'graded' ? 'Sudah Dinilai: ' . $mySub->score . '/100' : 'Menunggu Koreksi' }}
                                                </span>
                                            </div>
                                            <button
                                                wire:click="startAssignment({{ $assign->id }})"
                                                class="text-xs font-medium text-slate-700 bg-slate-100 hover:bg-slate-200 px-3 py-1.5 rounded-lg transition"
                                            >
                                                Lihat Jawaban
                                            </button>
                                        @else
                                            <span class="text-xs font-semibold px-2 py-0.5 rounded bg-rose-50 text-rose-700 border border-rose-200">
                                                Belum Dikerjakan
                                            </span>
                                            <button
                                                wire:click="startAssignment({{ $assign->id }})"
                                                class="btn-street text-xs font-semibold text-white bg-spray-600 px-3.5 py-1.5"
                                            >
                                                Kerjakan Tugas
                                            </button>
                                        @endif
                                    @else
                                        <div class="text-xs text-slate-500">
                                            Pengumpulan: <strong class="text-slate-800">{{ $allSubs->count() }}</strong> / {{ $classStudents->count() }} Siswa
                                        </div>
                                        <div class="flex flex-wrap items-center gap-2">
                                            <button
                                                wire:click="startAssignment({{ $assign->id }})"
                                                class="text-xs font-medium text-slate-700 bg-slate-100 hover:bg-slate-200 px-3 py-1.5 rounded-lg transition"
                                            >
                                                Pratinjau Soal & Kunci
                                            </button>
                                            <button
                                                wire:click="editAssignment({{ $assign->id }})"
                                                class="text-xs font-medium text-indigo-700 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 px-3 py-1.5 rounded-lg transition"
                                            >
                                                Edit
                                            </button>
                                            <button
                                                wire:click="deleteAssignment({{ $assign->id }})"
                                                wire:confirm="Hapus tugas '{{ $assign->title }}'? Jawaban siswa yang terkumpul juga akan terhapus dan tindakan ini tidak dapat dibatalkan."
                                                class="text-xs font-medium text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200 px-3 py-1.5 rounded-lg transition"
                                            >
                                                Hapus
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            @if($currentUser->role === 'guru' && $allSubs->count() > 0)
                                <div class="mt-4 pt-3 border-t border-slate-100">
                                    <div class="text-xs font-semibold text-slate-600 mb-2">Jawaban Masuk dari Siswa:</div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                                        @foreach($allSubs as $sub)
                                            <div class="bg-slate-50 border border-slate-200 p-2.5 rounded-lg text-xs flex items-center justify-between">
                                                <div>
                                                    <div class="font-semibold text-slate-800">{{ $sub->student->name }}</div>
                                                    <div class="text-[11px] text-slate-500">
                                                        {{ $sub->status === 'graded' ? 'Nilai: ' . $sub->score : 'Belum Dikoreksi' }}
                                                    </div>
                                                </div>
                                                <button
                                                    wire:click="openGrading({{ $sub->id }})"
                                                    class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 bg-white border border-indigo-200 px-2 py-1 rounded"
                                                >
                                                    Koreksi Nilai
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-12 rounded-xl border-2 border-dashed border-concrete-400 bg-concrete-50">
                            <div class="text-sm font-semibold text-slate-700">Belum ada tugas di kelas ini.</div>
                            <p class="text-xs text-slate-400 mt-1">Guru dapat menggunakan tombol "Buat Tugas via AI" di atas.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        @endif

        <!-- ========================================== -->
        <!-- TAB 4: PRESENSI KELAS -->
        <!-- ========================================== -->
        @if($activeTab === 'absensi')
            <div class="space-y-6">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Presensi & Kehadiran Kelas</h2>
                        <p class="text-xs text-slate-500">
                            Sistem presensi terintegrasi untuk {{ $activeClassroom?->name }} (Tanggal: {{ \Carbon\Carbon::parse($attendanceDate)->isoFormat('dddd, D MMMM Y') }})
                        </p>
                    </div>

                    @if($currentUser->role === 'guru')
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-medium text-slate-500">Pilih Tanggal:</span>
                            <input
                                type="date"
                                wire:model.live="attendanceDate"
                                class="field px-2.5 py-1 text-xs font-medium w-auto"
                            >
                        </div>
                    @endif
                </div>

                @if($currentUser->role === 'murid')
                    <div class="sticker rounded-xl p-6">
                        <h3 class="text-sm font-bold text-slate-800 mb-2">Presensi Kehadiran Siswa Hari Ini</h3>

                        @if($myAttendanceToday)
                            <div class="p-4 rounded-lg bg-slate-50 border border-slate-200 text-slate-800 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <span class="w-3 h-3 rounded-full {{ $myAttendanceToday->status === 'hadir' ? 'bg-emerald-500' : ($myAttendanceToday->status === 'izin' ? 'bg-amber-500' : 'bg-sky-500') }}"></span>
                                    <div>
                                        <div class="font-semibold text-xs sm:text-sm">Presensi Anda telah tercatat</div>
                                        <div class="text-xs text-slate-500 capitalize">
                                            Status: <strong>{{ strtoupper($myAttendanceToday->status) }}</strong> &bull; Keterangan: {{ $myAttendanceToday->notes }}
                                        </div>
                                    </div>
                                </div>
                                <span class="text-xs bg-slate-200 text-slate-800 font-semibold px-2.5 py-0.5 rounded uppercase">
                                    {{ $myAttendanceToday->status }}
                                </span>
                            </div>
                        @else
                            <form wire:submit="submitAttendance" class="space-y-4">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Pilih Status Kehadiran:</label>
                                    <div class="grid grid-cols-3 gap-3">
                                        <label class="cursor-pointer border rounded-lg p-2.5 flex items-center justify-center gap-2 text-xs font-semibold transition {{ $attendanceStatus === 'hadir' ? 'bg-indigo-50 border-indigo-500 text-indigo-900 ring-1 ring-indigo-500' : 'border-slate-200 text-slate-700 hover:bg-slate-50' }}">
                                            <input type="radio" wire:model="attendanceStatus" value="hadir" class="hidden">
                                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                            <span>Hadir</span>
                                        </label>
                                        <label class="cursor-pointer border rounded-lg p-2.5 flex items-center justify-center gap-2 text-xs font-semibold transition {{ $attendanceStatus === 'izin' ? 'bg-indigo-50 border-indigo-500 text-indigo-900 ring-1 ring-indigo-500' : 'border-slate-200 text-slate-700 hover:bg-slate-50' }}">
                                            <input type="radio" wire:model="attendanceStatus" value="izin" class="hidden">
                                            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                            <span>Izin</span>
                                        </label>
                                        <label class="cursor-pointer border rounded-lg p-2.5 flex items-center justify-center gap-2 text-xs font-semibold transition {{ $attendanceStatus === 'sakit' ? 'bg-indigo-50 border-indigo-500 text-indigo-900 ring-1 ring-indigo-500' : 'border-slate-200 text-slate-700 hover:bg-slate-50' }}">
                                            <input type="radio" wire:model="attendanceStatus" value="sakit" class="hidden">
                                            <span class="w-2 h-2 rounded-full bg-sky-500"></span>
                                            <span>Sakit</span>
                                        </label>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Catatan / Keterangan (Opsional):</label>
                                    <input
                                        type="text"
                                        wire:model="attendanceNotes"
                                        placeholder="Contoh: Mengikuti kelas secara daring / sakit demam"
                                        class="w-full text-xs p-2.5 border rounded-lg focus:ring-1 focus:ring-indigo-500 focus:outline-none"
                                    >
                                </div>

                                <button
                                    type="submit"
                                    class="btn-street bg-spray-600 text-white text-xs px-4 py-2"
                                >
                                    Kirim Data Presensi
                                </button>
                            </form>
                        @endif

                        <div class="mt-8 pt-5 border-t border-slate-100">
                            <h4 class="text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2.5">Riwayat Presensi Terakhir:</h4>
                            <div class="space-y-1.5">
                                @forelse($myAttendanceHistory as $att)
                                    <div class="flex items-center justify-between text-xs p-2 rounded-md bg-slate-50 border border-slate-200">
                                        <span class="font-medium text-slate-700">{{ $att->date->format('d M Y') }}</span>
                                        <span class="font-semibold uppercase px-2 py-0.5 rounded text-[10px] {{ $att->status === 'hadir' ? 'bg-emerald-50 text-emerald-800' : ($att->status === 'izin' ? 'bg-amber-50 text-amber-800' : 'bg-sky-50 text-sky-800') }}">
                                            {{ $att->status }}
                                        </span>
                                    </div>
                                @empty
                                    <div class="text-xs text-slate-400">Belum ada riwayat presensi.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                @else
                    <div class="sticker rounded-xl overflow-hidden">
                        <div class="p-3.5 bg-slate-50 border-b border-slate-200 flex flex-wrap items-center justify-between gap-3 text-xs">
                            <div class="font-bold text-slate-700">Daftar Kehadiran Siswa Kelas</div>
                            <div class="flex items-center gap-3">
                                <span class="flex items-center gap-1.5 font-medium text-slate-700">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Hadir: {{ $todayAttendances->where('status', 'hadir')->count() }}
                                </span>
                                <span class="flex items-center gap-1.5 font-medium text-slate-700">
                                    <span class="w-2 h-2 rounded-full bg-amber-500"></span> Izin: {{ $todayAttendances->where('status', 'izin')->count() }}
                                </span>
                                <span class="flex items-center gap-1.5 font-medium text-slate-700">
                                    <span class="w-2 h-2 rounded-full bg-sky-500"></span> Sakit: {{ $todayAttendances->where('status', 'sakit')->count() }}
                                </span>
                                <span class="flex items-center gap-1.5 font-medium text-slate-700">
                                    <span class="w-2 h-2 rounded-full bg-rose-500"></span> Belum: {{ $classStudents->count() - $todayAttendances->count() }}
                                </span>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs text-slate-700">
                                <thead class="bg-slate-100 text-slate-600 uppercase tracking-wider font-semibold border-b">
                                    <tr>
                                        <th class="p-3">NISN</th>
                                        <th class="p-3">Nama Siswa</th>
                                        <th class="p-3">Status</th>
                                        <th class="p-3">Keterangan</th>
                                        <th class="p-3 text-center">Ubah Status (Guru)</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($classStudents as $std)
                                        @php
                                            $att = $todayAttendances->firstWhere('student_id', $std->id);
                                        @endphp
                                        <tr class="hover:bg-slate-50 transition">
                                            <td class="p-3 font-mono text-slate-500">{{ $std->nis_nip ?: '-' }}</td>
                                            <td class="p-3 font-semibold text-slate-800">{{ $std->name }}</td>
                                            <td class="p-3">
                                                @if($att)
                                                    <span class="px-2 py-0.5 rounded text-[10px] font-semibold uppercase {{ $att->status === 'hadir' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : ($att->status === 'izin' ? 'bg-amber-50 text-amber-800 border border-amber-200' : 'bg-sky-50 text-sky-800 border border-sky-200') }}">
                                                        {{ $att->status }}
                                                    </span>
                                                @else
                                                    <span class="px-2 py-0.5 rounded text-[10px] font-semibold uppercase bg-slate-100 text-slate-500">
                                                        Belum Absen
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="p-3 text-slate-500">{{ $att?->notes ?: '-' }}</td>
                                            <td class="p-3 text-center">
                                                @if($att)
                                                    <div class="inline-flex gap-1">
                                                        <button wire:click="updateStudentAttendanceByTeacher({{ $att->id }}, 'hadir')" class="px-2 py-1 rounded bg-slate-100 hover:bg-emerald-100 text-slate-700 hover:text-emerald-800 font-semibold text-[10px]" title="Set Hadir">H</button>
                                                        <button wire:click="updateStudentAttendanceByTeacher({{ $att->id }}, 'izin')" class="px-2 py-1 rounded bg-slate-100 hover:bg-amber-100 text-slate-700 hover:text-amber-800 font-semibold text-[10px]" title="Set Izin">I</button>
                                                        <button wire:click="updateStudentAttendanceByTeacher({{ $att->id }}, 'sakit')" class="px-2 py-1 rounded bg-slate-100 hover:bg-sky-100 text-slate-700 hover:text-sky-800 font-semibold text-[10px]" title="Set Sakit">S</button>
                                                        <button wire:click="updateStudentAttendanceByTeacher({{ $att->id }}, 'alpha')" class="px-2 py-1 rounded bg-slate-100 hover:bg-rose-100 text-slate-700 hover:text-rose-800 font-semibold text-[10px]" title="Set Alpha">A</button>
                                                    </div>
                                                @else
                                                    <span class="text-[11px] text-slate-400 italic">Menunggu siswa</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <!-- ========================================== -->
        <!-- TAB 5: AI AUTO-TUGAS GENERATOR (GURU) -->
        <!-- ========================================== -->
        @if($activeTab === 'ai_generator' && $currentUser->role === 'guru')
            <div class="space-y-6">
                <div class="wall-brick rounded-2xl p-6 sm:p-8 text-white border-4 border-asphalt-950 shadow-sticker relative overflow-hidden">
                    <div class="max-w-3xl">
                        <div class="inline-flex items-center gap-1.5 bg-indigo-500/20 text-indigo-300 px-2.5 py-0.5 rounded text-xs font-medium uppercase tracking-wider mb-2 border border-indigo-400/30">
                            Generator Soal Otomatis
                        </div>
                        <h2 class="text-xl sm:text-2xl font-bold tracking-tight">
                            Penyusunan Tugas Otomatis via AI
                        </h2>
                        <p class="text-slate-300 text-xs sm:text-sm mt-1.5 leading-relaxed">
                            Tempelkan bahan ajar atau materi pertemuan di bawah. AI akan membaca teks, merumuskan butir-butir soal ujian, menyiapkan kunci jawaban guru, dan langsung menerbitkannya sebagai tugas kelas bagi seluruh siswa.
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    <div class="lg:col-span-6 sticker rounded-xl p-6 space-y-4">
                        <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-1.5">
                            Parameter Penugasan & Materi Sumber
                        </h3>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Target Kelas:</label>
                                <select wire:model="aiTargetClassroomId" class="field text-xs p-2">
                                    @foreach($classrooms as $c)
                                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Topik Bahasan:</label>
                                <input
                                    type="text"
                                    wire:model="aiTopic"
                                    placeholder="Contoh: Pemrograman Berorientasi Objek"
                                    class="w-full text-xs p-2 border rounded-lg focus:ring-1 focus:ring-indigo-500 focus:outline-none"
                                >
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Jumlah Butir:</label>
                                <select wire:model="aiQuestionCount" class="field text-xs p-2">
                                    <option value="2">2 Butir Soal</option>
                                    <option value="3">3 Butir Soal</option>
                                    <option value="5">5 Butir Soal</option>
                                    <option value="10">10 Butir Soal</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Format Soal:</label>
                                <select wire:model="aiQuestionType" class="field text-xs p-2">
                                    <option value="campuran">Campuran (PG & Esai)</option>
                                    <option value="esai">Esai Analisis</option>
                                    <option value="pilihan ganda">Pilihan Ganda</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Tingkat Kesulitan:</label>
                                <select wire:model="aiDifficulty" class="field text-xs p-2">
                                    <option value="mudah">Dasar / Pemula</option>
                                    <option value="sedang">Menengah / Standar</option>
                                    <option value="menantang">HOTS / Analitis</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label class="block text-xs font-semibold text-slate-700">Teks Materi Pembelajaran:</label>
                                <span class="text-[10px] text-slate-400">Rangkuman bahan bacaan siswa</span>
                            </div>
                            <textarea
                                wire:model="aiMaterial"
                                rows="8"
                                placeholder="Tempelkan materi teks di sini... Contoh: 'Dalam rekayasa perangkat lunak dengan arsitektur MVC, pemisahan tanggung jawab dibagi ke Model, View, dan Controller...'"
                                class="w-full text-xs p-3 border rounded-lg font-mono focus:ring-1 focus:ring-indigo-500 focus:outline-none"
                            ></textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Batas Waktu Pengumpulan (Deadline):</label>
                            <input
                                type="date"
                                wire:model="aiDueDate"
                                class="text-xs p-2 border rounded-lg w-full"
                            >
                        </div>

                        <div class="pt-2">
                            <button
                                wire:click="generateAndPublishAssignment"
                                wire:loading.attr="disabled"
                                class="btn-street w-full bg-spray-600 text-white font-semibold text-xs sm:text-sm py-2.5 px-4 inline-flex items-center justify-center gap-2 whitespace-nowrap disabled:opacity-50"
                            >
                                <svg wire:loading wire:target="generateAndPublishAssignment" class="animate-spin h-4 w-4 text-white shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                <span wire:loading.remove wire:target="generateAndPublishAssignment">Buat & Terbitkan Soal AI</span>
                                <span wire:loading wire:target="generateAndPublishAssignment">Menyusun & menerbitkan...</span>
                            </button>
                        </div>
                    </div>

                    <div class="lg:col-span-6 sticker rounded-xl p-6 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between border-b pb-3 mb-4">
                                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">
                                    Pratinjau Lembar Soal & Kunci Jawaban
                                </h3>
                                @if($aiGeneratedQuestions)
                                    <span class="text-[10px] bg-emerald-50 text-emerald-800 border border-emerald-200 font-semibold px-2 py-0.5 rounded">
                                        Telah Diterbitkan
                                    </span>
                                @endif
                            </div>

                            @if($isAiGenerating)
                                <div class="py-16 text-center">
                                    <div class="inline-block p-3 rounded-lg bg-indigo-50 text-indigo-600 mb-2">
                                        <svg class="animate-spin h-6 w-6 text-indigo-600 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                    </div>
                                    <div class="text-xs font-bold text-slate-800">{{ $aiGenerateStatus }}</div>
                                    <p class="text-[11px] text-slate-400 mt-1">Model: Groq Qwen 3.8-27B</p>
                                </div>
                            @elseif($aiGeneratedQuestions)
                                <div class="space-y-4 max-h-[460px] overflow-y-auto pr-2 scrollbar-thin">
                                    <div class="p-4 rounded-lg bg-slate-50 border border-slate-200">
                                        <div class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Lembar Soal Siswa:</div>
                                        <div class="prose prose-sm prose-slate max-w-none text-xs leading-relaxed">
                                            {!! Str::markdown($aiGeneratedQuestions) !!}
                                        </div>
                                    </div>

                                    @if($aiGeneratedAnswerKey)
                                        <div class="p-4 rounded-lg bg-amber-50/60 border border-amber-200">
                                            <div class="text-xs font-bold text-amber-900 uppercase tracking-wider mb-2">Kunci Jawaban & Rubrik Pengajar:</div>
                                            <div class="prose prose-sm prose-amber max-w-none text-xs leading-relaxed">
                                                {!! Str::markdown($aiGeneratedAnswerKey) !!}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="py-16 text-center text-slate-400">
                                    <div class="text-xs font-medium">Belum ada pratinjau soal yang dibuat.</div>
                                    <p class="text-[11px] text-slate-400 mt-1 max-w-xs mx-auto">
                                        Masukkan teks materi pada form di sebelah kiri lalu klik tombol "Buat & Terbitkan Soal AI".
                                    </p>
                                </div>
                            @endif
                        </div>

                        @if($aiGeneratedQuestions)
                            <div class="pt-4 border-t border-slate-100 mt-4 flex items-center justify-between">
                                <span class="text-xs text-slate-500">Soal telah tersimpan ke sistem</span>
                                <button
                                    wire:click="publishExistingGenerated"
                                    class="btn-street bg-white/95 text-asphalt-900 text-xs font-medium px-3.5 py-2"
                                >
                                    Terbitkan Ulang
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

    </main>

    <!-- ========================================== -->
    <!-- MODAL: PENGERJAAN TUGAS (MURID / PRATINJAU GURU) -->
    <!-- ========================================== -->
    @if($showAssignmentModal && $activeAssignment)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-asphalt-950/70 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="sticker rounded-2xl max-w-4xl w-full overflow-hidden flex flex-col max-h-[90vh]">
                <div class="wall-asphalt text-white p-5 flex items-center justify-between border-b-4 border-asphalt-950">
                    <div>
                        <div class="text-xs text-slate-400 font-medium">
                            {{ $activeAssignment->classroom->name }} &bull; {{ $activeAssignment->topic }}
                        </div>
                        <h3 class="text-base font-bold leading-tight text-slate-100 mt-0.5">{{ $activeAssignment->title }}</h3>
                    </div>
                    <button wire:click="closeAssignmentModal" class="text-slate-400 hover:text-white text-sm font-semibold p-1">Tutup</button>
                </div>

                <div class="p-6 overflow-y-auto flex-1 space-y-6">
                    <div class="p-4 rounded-lg bg-slate-50 border border-slate-200">
                        <div class="flex items-center justify-between mb-3 border-b pb-2">
                            <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">
                                Lembar Soal & Petunjuk
                            </span>
                            <button
                                wire:click="openChatWithContext('Tugas: {{ $activeAssignment->title }}', '{{ addslashes($activeAssignment->questions_content) }}')"
                                class="btn-street text-xs font-semibold text-asphalt-900 bg-traffic-300 px-3 py-1.5"
                            >
                                Tanya AI Mengenai Soal Ini
                            </button>
                        </div>
                        <div class="prose prose-sm prose-slate max-w-none text-xs leading-relaxed">
                            {!! Str::markdown($activeAssignment->questions_content) !!}
                        </div>
                    </div>

                    @if($currentUser->role === 'murid')
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="text-xs font-bold text-slate-800 uppercase tracking-wider">Lembar Jawaban Siswa:</label>
                                <span class="text-[11px] text-slate-400">Tuliskan jawaban per nomor dengan jelas</span>
                            </div>
                            <textarea
                                wire:model="studentAnswerInput"
                                rows="8"
                                placeholder="Tuliskan jawaban Anda di sini...&#10;&#10;Nomor 1: ...&#10;Nomor 2: ..."
                                class="w-full text-xs p-3.5 border rounded-lg focus:ring-1 focus:ring-indigo-500 focus:outline-none font-sans"
                            ></textarea>
                        </div>
                    @else
                        @if($activeAssignment->answer_key)
                            <div class="p-4 rounded-lg bg-amber-50/70 border border-amber-200">
                                <div class="text-xs font-bold text-amber-900 uppercase tracking-wider mb-2">Kunci Jawaban & Rubrik Guru:</div>
                                <div class="prose prose-sm prose-amber max-w-none text-xs leading-relaxed">
                                    {!! Str::markdown($activeAssignment->answer_key) !!}
                                </div>
                            </div>
                        @endif
                    @endif
                </div>

                <div class="p-4 bg-slate-50 border-t border-slate-200 flex flex-wrap items-center justify-between gap-2">
                    <div class="flex items-center gap-2">
                        <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                            Unduh{{ $currentUser->role === 'guru' ? ' (Soal + Kunci)' : ' Soal' }}:
                        </span>
                        <a
                            href="{{ route('assignments.download', ['assignment' => $activeAssignment->id, 'format' => 'word']) }}"
                            class="btn-street bg-white text-asphalt-900 text-xs font-semibold px-3 py-2"
                            title="Unduh sebagai dokumen Word (.docx)"
                        >
                            Word (.docx)
                        </a>
                        <a
                            href="{{ route('assignments.download', ['assignment' => $activeAssignment->id, 'format' => 'pdf']) }}"
                            class="btn-street bg-white text-asphalt-900 text-xs font-semibold px-3 py-2"
                            title="Unduh sebagai dokumen PDF"
                        >
                            PDF
                        </a>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        @if($currentUser->role === 'guru')
                            <button
                                wire:click="editAssignment({{ $activeAssignment->id }})"
                                class="btn-street bg-indigo-50 text-indigo-700 text-xs font-medium px-4 py-2"
                            >
                                Edit Tugas
                            </button>
                            <button
                                wire:click="deleteAssignment({{ $activeAssignment->id }})"
                                wire:confirm="Hapus tugas '{{ $activeAssignment->title }}'? Jawaban siswa yang terkumpul juga akan terhapus."
                                class="btn-street bg-rose-50 text-rose-700 text-xs font-medium px-4 py-2"
                            >
                                Hapus Tugas
                            </button>
                        @endif

                        <button wire:click="closeAssignmentModal" class="btn-street bg-white text-asphalt-800 text-xs font-medium px-4 py-2">
                            Batal
                        </button>

                        @if($currentUser->role === 'murid')
                            <button
                                wire:click="submitAssignment"
                                class="btn-street bg-spray-600 text-white font-semibold text-xs px-5 py-2"
                            >
                                Kirim Jawaban Tugas
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- ========================================== -->
    <!-- MODAL: EDIT TUGAS (GURU) -->
    <!-- ========================================== -->
    @if($showEditAssignmentModal)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-asphalt-950/70 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="sticker sticker-taped rounded-2xl max-w-2xl w-full p-6 space-y-4">
                <div class="flex items-center justify-between border-b pb-3">
                    <h3 class="text-sm font-bold text-slate-800">Edit Tugas</h3>
                    <button wire:click="closeEditAssignmentModal" class="text-slate-400 hover:text-slate-600 text-sm font-semibold">Tutup</button>
                </div>

                <form wire:submit="updateAssignment" class="space-y-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Judul Tugas:</label>
                        <input type="text" wire:model="editTitle" class="w-full text-xs p-2.5 border rounded-lg" placeholder="Contoh: Tugas AI: Pemahaman MVC">
                        @error('editTitle') <span class="text-[11px] text-rose-600">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Kategori / Topik:</label>
                            <input type="text" wire:model="editTopic" class="w-full text-xs p-2.5 border rounded-lg" placeholder="Contoh: Pemrograman Web Lanjutan">
                            @error('editTopic') <span class="text-[11px] text-rose-600">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Kelas:</label>
                            <select wire:model="editClassroomId" class="w-full text-xs p-2.5 border rounded-lg">
                                @foreach($classrooms as $classroom)
                                    <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                                @endforeach
                            </select>
                            @error('editClassroomId') <span class="text-[11px] text-rose-600">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Batas Waktu (opsional):</label>
                        <input type="date" wire:model="editDueDate" class="w-full text-xs p-2.5 border rounded-lg">
                        @error('editDueDate') <span class="text-[11px] text-rose-600">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Petunjuk Pengerjaan:</label>
                        <textarea wire:model="editInstructions" rows="3" class="w-full text-xs p-2.5 border rounded-lg" placeholder="Petunjuk singkat untuk siswa"></textarea>
                        @error('editInstructions') <span class="text-[11px] text-rose-600">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Butir Soal (mendukung Markdown):</label>
                        <textarea wire:model="editQuestionsContent" rows="6" class="w-full text-xs p-2.5 border rounded-lg font-sans" placeholder="1. ...&#10;2. ..."></textarea>
                        @error('editQuestionsContent') <span class="text-[11px] text-rose-600">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Kunci Jawaban & Rubrik (opsional):</label>
                        <textarea wire:model="editAnswerKey" rows="4" class="w-full text-xs p-2.5 border rounded-lg font-sans" placeholder="Kunci jawaban untuk guru"></textarea>
                        @error('editAnswerKey') <span class="text-[11px] text-rose-600">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-1">
                        <button type="button" wire:click="closeEditAssignmentModal" class="btn-street bg-white text-asphalt-800 text-xs font-medium px-4 py-2">
                            Batal
                        </button>
                        <button type="submit" class="btn-street bg-spray-600 text-white font-semibold text-xs px-4 py-2">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- ========================================== -->
    <!-- MODAL: BACA MODUL MATERI -->
    <!-- ========================================== -->
    @if($activeMaterial)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-asphalt-950/70 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="sticker rounded-2xl max-w-3xl w-full overflow-hidden flex flex-col max-h-[90vh]">
                <div class="wall-asphalt text-white p-5 flex items-center justify-between border-b-4 border-asphalt-950">
                    <div>
                        <span class="text-xs text-slate-400 font-medium uppercase">{{ $activeMaterial->topic }}</span>
                        <h3 class="text-base font-bold text-slate-100 mt-0.5">{{ $activeMaterial->title }}</h3>
                    </div>
                    <button wire:click="closeMaterial" class="text-slate-400 hover:text-white text-sm font-semibold">Tutup</button>
                </div>
                <div class="p-6 overflow-y-auto flex-1 prose prose-sm prose-slate max-w-none text-xs leading-relaxed">
                    {!! Str::markdown($activeMaterial->content) !!}
                </div>
                <div class="p-4 bg-slate-50 border-t border-slate-200 flex flex-wrap items-center justify-between gap-2">
                    <div class="flex items-center gap-2">
                        <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Unduh Materi:</span>
                        <a
                            href="{{ route('materials.download', ['material' => $activeMaterial->id, 'format' => 'word']) }}"
                            class="btn-street bg-white text-asphalt-900 text-xs font-semibold px-3 py-2"
                            title="Unduh sebagai dokumen Word (.docx)"
                        >
                            Word (.docx)
                        </a>
                        <a
                            href="{{ route('materials.download', ['material' => $activeMaterial->id, 'format' => 'pdf']) }}"
                            class="btn-street bg-white text-asphalt-900 text-xs font-semibold px-3 py-2"
                            title="Unduh sebagai dokumen PDF"
                        >
                            PDF
                        </a>
                    </div>
                    <div class="flex items-center gap-2">
                        <button wire:click="closeMaterial" class="btn-street bg-white text-asphalt-800 text-xs font-medium px-4 py-2">
                            Selesai Membaca
                        </button>
                        <button
                            wire:click="openChatWithContext('Materi: {{ $activeMaterial->title }}', '{{ addslashes($activeMaterial->content) }}')"
                            class="btn-street bg-spray-600 text-white font-semibold text-xs px-4 py-2"
                        >
                            Tanya AI Terkait Materi Ini
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- ========================================== -->
    <!-- MODAL: PENILAIAN TUGAS (GURU) -->
    <!-- ========================================== -->
    @if($showGradingModal)
        @php
            $sub = App\Models\Submission::with(['student', 'assignment'])->find($activeSubmissionId);
        @endphp
        @if($sub)
            <div class="fixed inset-0 z-50 overflow-y-auto bg-asphalt-950/70 backdrop-blur-sm flex items-center justify-center p-4">
                <div class="sticker sticker-taped rounded-2xl max-w-2xl w-full p-6 space-y-4">
                    <div class="flex items-center justify-between border-b pb-3">
                        <div>
                            <span class="text-xs text-indigo-700 font-semibold uppercase">Form Koreksi Tugas</span>
                            <h3 class="text-sm font-bold text-slate-800">Siswa: {{ $sub->student->name }}</h3>
                        </div>
                        <button wire:click="$set('showGradingModal', false)" class="text-slate-400 hover:text-slate-600 text-sm font-semibold">Tutup</button>
                    </div>

                    <div>
                        <div class="text-xs font-semibold text-slate-700 mb-1">Jawaban Siswa:</div>
                        <div class="p-3 bg-slate-50 border rounded-lg text-xs whitespace-pre-wrap max-h-48 overflow-y-auto font-sans">
                            {{ $sub->student_answers }}
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Nilai (0 - 100):</label>
                            <input
                                type="number"
                                min="0"
                                max="100"
                                wire:model="gradeScore"
                                class="w-full text-xs p-2.5 border rounded-lg font-bold focus:ring-1 focus:ring-indigo-500"
                            >
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Catatan Evaluasi / Feedback:</label>
                            <input
                                type="text"
                                wire:model="gradeFeedback"
                                placeholder="Analisis tepat, pemahaman konsep sudah sesuai"
                                class="w-full text-xs p-2.5 border rounded-lg focus:ring-1 focus:ring-indigo-500"
                            >
                        </div>
                    </div>

                    <div class="pt-2 flex justify-end gap-2">
                        <button wire:click="$set('showGradingModal', false)" class="btn-street bg-white text-asphalt-800 text-xs font-medium px-4 py-2">
                            Batal
                        </button>
                        <button wire:click="saveGrading" class="btn-street bg-spray-600 text-white font-semibold text-xs px-4 py-2">
                            Simpan Nilai
                        </button>
                    </div>
                </div>
            </div>
        @endif
    @endif

    <!-- ========================================== -->
    <!-- MODAL: TAMBAH MATERI BARU (GURU) -->
    <!-- ========================================== -->
    @if($showMaterialModal)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-asphalt-950/70 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="sticker sticker-taped rounded-2xl max-w-2xl w-full p-6 space-y-4">
                <div class="flex items-center justify-between border-b pb-3">
                    <h3 class="text-sm font-bold text-slate-800">Tambah Modul Materi Pembelajaran</h3>
                    <button wire:click="$set('showMaterialModal', false)" class="text-slate-400 hover:text-slate-600 text-sm font-semibold">Tutup</button>
                </div>

                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Judul Modul:</label>
                        <input type="text" wire:model="newMaterialTitle" placeholder="Contoh: Arsitektur Model-View-Controller" class="w-full text-xs p-2.5 border rounded-lg">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Kategori / Topik:</label>
                        <input type="text" wire:model="newMaterialTopic" placeholder="Contoh: Pemrograman Web Lanjutan" class="w-full text-xs p-2.5 border rounded-lg">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Isi Materi Bahan Ajar:</label>
                        <textarea wire:model="newMaterialContent" rows="7" placeholder="Tuliskan modul materi di sini (mendukung format Markdown)..." class="w-full text-xs p-3 border rounded-lg font-mono"></textarea>
                    </div>
                </div>

                <div class="pt-2 flex justify-end gap-2">
                    <button wire:click="$set('showMaterialModal', false)" class="btn-street bg-white text-asphalt-800 text-xs font-medium px-4 py-2">Batal</button>
                    <button wire:click="saveNewMaterial" class="btn-street bg-spray-600 text-white font-semibold text-xs px-4 py-2">Simpan Modul</button>
                </div>
            </div>
        </div>
    @endif

    <!-- ========================================== -->
    <!-- FLOATING AI ASSISTANT / CHATBOT WIDGET -->
    <!-- ========================================== -->
    <div class="fixed bottom-6 right-6 z-50">
        <button
            wire:click="toggleChat"
            class="w-12 h-12 rounded-full bg-spray-600 hover:bg-spray-500 text-white shadow-sticker transition-all flex items-center justify-center border-2 border-asphalt-950"
            title="Buka Asisten AI"
        >
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
            </svg>
        </button>
    </div>

    @if($isChatOpen)
        <div class="fixed bottom-20 right-4 sm:right-6 z-50 w-[94vw] sm:w-[420px] bg-white rounded-2xl border-2 border-asphalt-950 shadow-sticker flex flex-col overflow-hidden h-[540px] max-h-[80vh]">
            <!-- Header -->
            <div class="wall-asphalt text-white p-3.5 flex items-center justify-between border-b-4 border-asphalt-950">
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded bg-indigo-600 flex items-center justify-center text-white">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div>
                        <div class="font-semibold text-xs text-slate-100 flex items-center gap-1.5">
                            <span>EduBot AI Asisten</span>
                            <span class="text-[9px] bg-slate-800 text-slate-300 px-1 rounded border border-slate-700">Groq</span>
                        </div>
                        <div class="text-[10px] text-slate-400 truncate max-w-[200px]">
                            Konteks: {{ $chatContextTitle }}
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-1">
                    <button
                        wire:click="clearChatHistory"
                        class="text-slate-400 hover:text-white text-xs px-2 py-1 rounded hover:bg-slate-800 transition"
                        title="Reset Percakapan"
                    >
                        Reset
                    </button>
                    <button
                        wire:click="toggleChat"
                        class="text-slate-400 hover:text-white text-xs px-2 py-1 rounded hover:bg-slate-800 font-bold"
                    >
                        Tutup
                    </button>
                </div>
            </div>

            <!-- Quick Suggestions -->
            <div class="p-2 bg-slate-50 border-b border-slate-200 flex gap-1.5 overflow-x-auto scrollbar-none text-[11px]">
                <button
                    wire:click="askQuickQuestion('Jelaskan maksud soal atau materi ini secara ringkas dan mudah dipahami')"
                    class="whitespace-nowrap px-2.5 py-1 bg-white border border-slate-200 rounded-md text-slate-700 hover:border-slate-400 transition"
                >
                    Maksud Soal?
                </button>
                <button
                    wire:click="askQuickQuestion('Berikan petunjuk konsep atau langkah pengerjaan untuk menyelesaikan soal ini')"
                    class="whitespace-nowrap px-2.5 py-1 bg-white border border-slate-200 rounded-md text-slate-700 hover:border-slate-400 transition"
                >
                    Petunjuk Pengerjaan
                </button>
                <button
                    wire:click="askQuickQuestion('Berikan contoh pendekatan jawaban beserta pembahasannya secara lengkap')"
                    class="whitespace-nowrap px-2.5 py-1 bg-white border border-slate-200 rounded-md text-slate-700 hover:border-slate-400 transition"
                >
                    Contoh Jawaban
                </button>
            </div>

            <!-- Chat Messages Stream -->
            <div class="flex-1 overflow-y-auto p-4 space-y-3 bg-slate-50/50 text-xs" id="chat-messages" data-autoscroll>
                @foreach($chatMessages as $msg)
                    <div class="flex {{ $msg['role'] === 'user' ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-[85%] p-3 rounded-xl {{ $msg['role'] === 'user' ? 'bg-spray-600 text-white rounded-br-none border-2 border-asphalt-950 shadow-sticker-sm' : 'bg-white text-asphalt-900 rounded-bl-none border-2 border-asphalt-950 shadow-sticker-sm' }}">
                            @if($msg['role'] === 'assistant')
                                <div class="font-semibold text-[10px] text-indigo-700 mb-1">
                                    EduBot AI
                                </div>
                                <div class="prose prose-xs prose-slate max-w-none leading-relaxed">
                                    {!! Str::markdown($msg['content']) !!}
                                </div>
                            @else
                                <div class="whitespace-pre-wrap">{{ $msg['content'] }}</div>
                            @endif
                        </div>
                    </div>
                @endforeach

                <div wire:loading wire:target="sendChatMessage,askQuickQuestion" class="flex justify-start">
                    <div class="bg-white p-3 rounded-xl rounded-bl-none shadow-sm border border-slate-200 text-slate-500 flex items-center gap-2">
                        <svg class="animate-spin h-3.5 w-3.5 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                        <span class="italic text-[11px]">Asisten AI sedang menyusun tanggapan...</span>
                    </div>
                </div>
            </div>

            <!-- Input Form -->
            <form wire:submit="sendChatMessage" class="p-2.5 bg-white border-t border-slate-200 flex items-center gap-2">
                <input
                    type="text"
                    wire:model="chatInput"
                    placeholder="Tuliskan pertanyaan Anda di sini..."
                    class="field flex-1 p-2 text-xs"
                    required
                >
                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    class="btn-street bg-spray-600 text-white px-3 py-2 disabled:opacity-50 text-xs font-semibold"
                >
                    Kirim
                </button>
            </form>
        </div>
    @endif
</div>
