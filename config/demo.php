<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Mode Demo
    |--------------------------------------------------------------------------
    |
    | Saat aktif, pengunjung dapat masuk sebagai akun demo (Guru / Siswa)
    | tanpa registrasi melalui tombol satu-klik pada halaman login.
    | Nonaktifkan di lingkungan produksi dengan menyetel DEMO_MODE=false.
    |
    */

    'enabled' => (bool) env('DEMO_MODE', true),

    /*
    |--------------------------------------------------------------------------
    | Akun Demo
    |--------------------------------------------------------------------------
    |
    | Setiap peran memetakan ke akun yang dibuat oleh SchoolSeeder. Pastikan
    | alias pada kunci array cocok dengan parameter route demo login.
    |
    */

    'accounts' => [
        'guru' => [
            'email' => env('DEMO_GURU_EMAIL', 'guru@sekolah.id'),
            'label' => 'Guru',
            'description' => 'Kelola materi, presensi, dan generator soal AI',
        ],
        'murid' => [
            'email' => env('DEMO_MURID_EMAIL', 'ahmad@sekolah.id'),
            'label' => 'Siswa',
            'description' => 'Kerjakan tugas & konsultasi dengan asisten AI',
        ],
    ],

];
