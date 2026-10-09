<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\Material;
use App\Models\Submission;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SchoolSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Akun Guru
        $guru = User::firstOrCreate(
            ['email' => 'guru@sekolah.id'],
            [
                'name' => 'Pak Budi Pratama, S.Kom., M.T.',
                'password' => Hash::make('password'),
                'role' => 'guru',
                'nis_nip' => '198507122010011005',
            ]
        );

        // 2. Buat Akun Murid
        $murid1 = User::firstOrCreate(
            ['email' => 'ahmad@sekolah.id'],
            [
                'name' => 'Ahmad Rizky Pratama',
                'password' => Hash::make('password'),
                'role' => 'murid',
                'nis_nip' => '202411001',
            ]
        );

        $murid2 = User::firstOrCreate(
            ['email' => 'siti@sekolah.id'],
            [
                'name' => 'Siti Rahma Nurfadilah',
                'password' => Hash::make('password'),
                'role' => 'murid',
                'nis_nip' => '202411002',
            ]
        );

        $murid3 = User::firstOrCreate(
            ['email' => 'dimas@sekolah.id'],
            [
                'name' => 'Dimas Arya Saputra',
                'password' => Hash::make('password'),
                'role' => 'murid',
                'nis_nip' => '202411003',
            ]
        );

        // 2b. Sinkronkan peran Spatie untuk seluruh akun sekolah
        $guru->assignSchoolRole('guru');
        $murid1->assignSchoolRole('murid');
        $murid2->assignSchoolRole('murid');
        $murid3->assignSchoolRole('murid');

        // 3. Buat Kelas
        $kelasRpl = Classroom::firstOrCreate(
            ['code' => 'RPL-XI-WEB'],
            [
                'name' => 'Kelas XI RPL 1 - Pemrograman Web & Laravel',
                'description' => 'Mata pelajaran kejuruan Rekayasa Perangkat Lunak fokus pada framework web modern Laravel dan Livewire.',
                'teacher_id' => $guru->id,
            ]
        );

        $kelasBd = Classroom::firstOrCreate(
            ['code' => 'RPL-XI-BD'],
            [
                'name' => 'Kelas XI RPL 1 - Basis Data Relasional & SQL',
                'description' => 'Pendalaman konsep basis data, perancangan ERD, dan normalisasi tabel relasional.',
                'teacher_id' => $guru->id,
            ]
        );

        // 4. Buat Materi Ajar
        $materi1 = Material::firstOrCreate(
            ['title' => 'Konsep Fundamental MVC & Routing pada Laravel'],
            [
                'classroom_id' => $kelasRpl->id,
                'teacher_id' => $guru->id,
                'topic' => 'Arsitektur Web Modern',
                'content' => "### 1. Apa itu MVC (Model - View - Controller)?\n".
                    "MVC adalah pola desain perangkat lunak yang membagi tanggung jawab aplikasi menjadi 3 bagian inti:\n\n".
                    "- **Model (M)**: Merepresentasikan struktur data, entitas database, dan aturan validasi/bisnis. Di Laravel, Model menggunakan Eloquent ORM.\n".
                    "- **View (V)**: Bertanggung jawab atas representasi visual atau antarmuka (UI) kepada pengguna menggunakan Blade template engine.\n".
                    "- **Controller (C)**: Bertindak sebagai penghubung dan pengatur alur logika. Controller menerima HTTP Request dari Route, mengambil data lewat Model, dan meneruskannya ke View.\n\n".
                    "### 2. Routing di Laravel\n".
                    "Routing memetakan URL yang diakses browser ke Controller atau Closure tertentu. Berkas routing web didefinisikan pada file `routes/web.php`.\n".
                    "Contoh:\n```php\nRoute::get('/kelas', [ClassroomController::class, 'index']);\n```\n\n".
                    "### 3. Keuntungan Arsitektur MVC:\n".
                    "1. Kode lebih terstruktur dan mudah dirawat (*maintainability*).\n".
                    "2. Kolaborasi tim lebih mudah (front-end fokus di View, back-end di Controller & Model).\n".
                    '3. Mempermudah proses pengujian perangkat lunak (*unit testing*).',
            ]
        );

        Material::firstOrCreate(
            ['title' => 'Prinsip Normalisasi Basis Data (1NF, 2NF, 3NF)'],
            [
                'classroom_id' => $kelasBd->id,
                'teacher_id' => $guru->id,
                'topic' => 'Desain Basis Data',
                'content' => "### Apa itu Normalisasi Database?\n".
                    "Normalisasi adalah proses pengelompokan atribut-atribut data yang menyusun suatu entitas sehingga tercipta relasi yang fleksibel dan bebas dari anomali data (insert, update, delete anomaly).\n\n".
                    "### Tahapan Normalisasi:\n".
                    "1. **Bentuk Normal Pertama (1NF)**: Setiap kolom hanya memiliki nilai tunggal (*atomic value*) dan tidak ada kolom yang berulang.\n".
                    "2. **Bentuk Normal Kedua (2NF)**: Memenuhi syarat 1NF dan setiap atribut bukan kunci (*non-key*) harus bergantung penuh pada *primary key* (*fully functional dependency*).\n".
                    '3. **Bentuk Normal Ketiga (3NF)**: Memenuhi syarat 2NF dan tidak ada ketergantungan transitif (*transitive dependency*) antar kolom non-kunci.',
            ]
        );

        // 5. Buat Tugas Awal
        $tugas1 = Assignment::firstOrCreate(
            ['title' => 'Tugas 1: Analisis Penerapan MVC pada Framework Laravel'],
            [
                'classroom_id' => $kelasRpl->id,
                'teacher_id' => $guru->id,
                'topic' => 'Arsitektur Web Modern',
                'material_source' => $materi1->content,
                'instructions' => 'Bacalah materi tentang MVC yang telah disediakan. Jawablah soal-soal berikut dengan analisis yang jelas dan gunakan bahasa sendiri. Jika ragu atau butuh petunjuk, gunakan AI Chatbot Tutor di samping!',
                'questions_content' => "### LEMBAR SOAL TUGAS\n\n".
                    "**Soal 1 (Esai - 30 Poin):**\n".
                    "Jelaskan alur perjalanan sebuah data ketika pengguna mengakses halaman daftar siswa di aplikasi Laravel, mulai dari pengguna mengetikkan URL di browser hingga halaman muncul di layar berdasarkan konsep MVC!\n\n".
                    "**Soal 2 (Studi Kasus - 35 Poin):**\n".
                    "Seorang programmer meletakkan seluruh kode *query database* (seperti `DB::select(...)`) langsung di dalam berkas template View Blade. Menurut pemahamanmu mengenai prinsip pemisahan tanggung jawab (*separation of concerns*), apa dampak buruk praktik tersebut dan bagaimana seharusnya struktur yang benar?\n\n".
                    "**Soal 3 (Implementasi - 35 Poin):**\n".
                    'Sebutkan peran dan keuntungan menggunakan Eloquent ORM pada Model Laravel dibandingkan menulis sintaks SQL mentah secara manual!',
                'answer_key' => "### PANDUAN PENILAIAN & KUNCI JAWABAN (GURU)\n\n".
                    "**Kunci Soal 1:**\n".
                    "Alur: Request HTTP -> routes/web.php -> Controller terkait -> Model (Eloquent query ke DB) -> DB kembalikan data ke Model -> Model serahkan data ke Controller -> Controller inject data ke Blade View -> View di-render menjadi HTML -> Dikirimkan kembali ke browser pengguna.\n\n".
                    "**Kunci Soal 2:**\n".
                    "Dampak buruk: Melanggar MVC, kode menjadi sangat sulit di-maintain, View menjadi lambat dan rentan bug keamanan. Solusi: Query data harus dilakukan di Model atau Controller/Repository, kemudian di-passing ke View sebagai variabel bersih.\n\n".
                    "**Kunci Soal 3:**\n".
                    'Keuntungan Eloquent: Sintaks ekspresif & berorientasi objek (OOP), otomatis proteksi dari SQL Injection (PDO binding), hubungan relasi mudah (hasMany, belongsTo), dan mendukung migrasi antar DBMS tanpa mengubah sintaks.',
                'due_date' => Carbon::now()->addDays(7)->toDateString(),
                'status' => 'published',
                'ai_generated' => true,
            ]
        );

        // 6. Buat Submission Contoh (Ahmad sudah mengerjakan)
        Submission::firstOrCreate(
            [
                'assignment_id' => $tugas1->id,
                'student_id' => $murid1->id,
            ],
            [
                'student_answers' => "Jawaban Tugas 1 - Ahmad Rizky:\n\n".
                    "1. Alur request dimulai dari URL browser yang masuk ke web.php. Routing mencari Controller yang sesuai, Controller memanggil Model untuk mengambil data dari tabel database, lalu Model menyerahkan data ke Controller. Controller meneruskan data ke view Blade untuk ditampilkan kepada user.\n\n".
                    "2. Menaruh query langsung di View adalah kesalahan arsitektur karena membuat View berantakan dan sulit diuji. Seharusnya query ditaruh di Model atau Controller.\n\n".
                    '3. Eloquent ORM membuat koding lebih cepat, aman dari SQL injection, dan mudah dipahami karena menggunakan method OOP seperti Siswa::all().',
                'status' => 'graded',
                'score' => 95,
                'feedback' => 'Penjelasan sangat sistematis dan pemahaman konsep MVC sangat baik! Pertahankan prestasi belajarmu, Ahmad.',
                'submitted_at' => Carbon::now()->subDay(),
            ]
        );

        // 7. Buat Absensi Hari Ini
        $today = Carbon::today()->toDateString();
        Attendance::firstOrCreate(
            [
                'classroom_id' => $kelasRpl->id,
                'student_id' => $murid1->id,
                'date' => $today,
            ],
            [
                'status' => 'hadir',
                'notes' => 'Hadir tepat waktu di kelas',
            ]
        );

        Attendance::firstOrCreate(
            [
                'classroom_id' => $kelasRpl->id,
                'student_id' => $murid2->id,
                'date' => $today,
            ],
            [
                'status' => 'hadir',
                'notes' => 'Hadir via lab komputer',
            ]
        );

        Attendance::firstOrCreate(
            [
                'classroom_id' => $kelasRpl->id,
                'student_id' => $murid3->id,
                'date' => $today,
            ],
            [
                'status' => 'izin',
                'notes' => 'Izin mengikuti lomba sains antar sekolah',
            ]
        );
    }
}
