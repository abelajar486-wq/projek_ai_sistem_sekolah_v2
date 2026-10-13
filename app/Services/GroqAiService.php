<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GroqAiService
{
    protected string $apiKey;

    protected string $model;

    protected int $maxTokens;

    public function __construct()
    {
        $this->apiKey = (string) config('services.groq.api_key');
        $this->model = (string) config('services.groq.model', 'qwen/qwen3.8-27b');
        $this->maxTokens = (int) config('services.groq.max_tokens', 900);
    }

    /**
     * Meminta AI untuk membuat soal otomatis berdasarkan materi tugas yang diinput guru.
     */
    public function generateAssignment(
        string $material,
        string $topic = '',
        int $questionCount = 5,
        string $type = 'campuran',
        string $difficulty = 'sedang'
    ): array {
        $template = <<<'PROMPT'
Anda adalah asisten Guru AI profesional dan kurikulum spesialis sekolah Indonesia.
Tugas Anda adalah membuat Lembar Tugas/Soal Siswa berdasarkan MATERI yang diberikan oleh Guru di bawah ini.

MATERI PEMBELAJARAN:
---
{{MATERIAL}}
---
TOPIK: {{TOPIC}}
JUMLAH SOAL: {{QUESTION_COUNT}} butir
TIPE SOAL: {{TYPE}} (contoh: pilihan ganda, esai, atau campuran)
TINGKAT KESULITAN: {{DIFFICULTY}}

FORMAT KELUARAN YANG WAJIB ANDA BERIKAN:
Harap pisahkan respon menjadi DUA BAGIAN DENGAN PEMISAH TEPAT "===KUNCI_JAWABAN_GURU===":

BAGIAN 1 (UNTUK MURID):
Berikan judul tugas, petunjuk pengerjaan, dan daftar butir soal bernomor yang jelas dan rapi (dalam format Markdown). JANGAN sertakan kunci jawaban pada Bagian 1 ini!

===KUNCI_JAWABAN_GURU===

BAGIAN 2 (UNTUK GURU):
Kunci jawaban lengkap, pembahasan rinci untuk setiap butir soal, dan rubrik pedoman penilaian skor nilai.

ATURAN PENULISAN MATEMATIKA (WAJIB):
Tulis semua simbol dan rumus matematika/ipa dengan notasi LaTeX standar: gunakan $...$ untuk inline dan $$...$$ untuk rumus satu baris tersendiri (contoh: $x^2$, $\frac{a}{b}$, $a \times b$). JANGAN membungkus rumus matematika di dalam code block dan jangan memakai simbol ketikan biasa.
PROMPT;

        $prompt = str_replace(
            ['{{MATERIAL}}', '{{TOPIC}}', '{{QUESTION_COUNT}}', '{{TYPE}}', '{{DIFFICULTY}}'],
            [$material, $topic, (string) $questionCount, $type, $difficulty],
            $template
        );

        try {
            $response = Http::withToken($this->apiKey)
                ->withoutVerifying()
                ->timeout(45)
                ->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => $this->model,
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'Anda adalah AI Asisten Guru untuk pembuatan soal tugas sekolah berkualitas tinggi berbahasa Indonesia.',
                        ],
                        [
                            'role' => 'user',
                            'content' => $prompt,
                        ],
                    ],
                    'temperature' => 0.7,
                    'max_tokens' => $this->maxTokens,
                ]);

            if ($response->successful()) {
                $fullContent = $response->json('choices.0.message.content', '');

                $parts = explode('===KUNCI_JAWABAN_GURU===', $fullContent);
                $questions = trim($parts[0]);
                $answerKey = isset($parts[1]) ? trim($parts[1]) : 'Kunci jawaban belum tersedia secara terpisah.';

                return [
                    'success' => true,
                    'questions' => $questions,
                    'answer_key' => $answerKey,
                ];
            }

            Log::error('Groq Assignment Generation Failed', ['response' => $response->body()]);

            return [
                'success' => false,
                'error' => $this->friendlyError($response),
            ];
        } catch (\Throwable $e) {
            Log::error('Groq Exception in generateAssignment', ['message' => $e->getMessage()]);

            return [
                'success' => false,
                'error' => 'Koneksi error: '.$e->getMessage(),
            ];
        }
    }

    /**
     * Chatbot AI pendamping belajar untuk siswa & guru.
     */
    public function chatWithAiTutor(array $messages, ?string $context = null): string
    {
        $systemPrompt = <<<'PROMPT'
Anda adalah "EduBot AI", tutor virtual paling cerdas dan seorang guru privat terbaik untuk siswa Sekolah Dasar (SD), Sekolah Menengah Pertama (SMP), hingga Sekolah Menengah Atas/Kejuruan (SMA/SMK) di Indonesia.

## PENGUASAAN MATERI (SEMUA MATA PELAJARAN)
Anda menguasai seluruh mata pelajaran kurikulum nasional dari jenjang SD sampai menengah, meliputi:
- Matematika (aritmetika, aljabar, geometri, statistika, peluang, trigonometri, kalkulus dasar, limit, turunan, integral).
- Ilmu Pengetahuan Alam / IPA (Fisika, Kimia, Biologi) dan Ilmu Pengetahuan Alam dan Sosial (IPAS).
- Ilmu Pengetahuan Sosial / IPS (Sejarah, Geografi, Ekonomi, Sosiologi, Antropologi).
- Bahasa Indonesia (tata bahasa, ejaan EYD/PUEBI, sastra, teks, puisi, karya ilmiah).
- Bahasa Inggris (grammar, tenses, vocabulary, reading, writing, listening, speaking) serta bahasa asing lain bila ditanyakan.
- Pendidikan Pancasila / PPKn / Kewarganegaraan.
- Pendidikan Agama dan Budi Pekerti.
- Informatika / TIK (logika, algoritma, dasar pemrograman, literasi digital).
- Seni Budaya, Prakarya, PJOK, dan muatan lokal.

## PRINSIP KECERDASAN & AKURASI (WAJIB)
1. Jawablah dengan BENAR, akurat, dan dapat dipertanggungjawabkan secara keilmuan. Periksa ulang perhitungan dan logika sebelum menjawab.
2. Jangan berhalusinasi. Jika informasi tidak pasti atau berada di luar pengetahuan Anda, katakan dengan jujur bahwa Anda tidak yakin dan arahkan ke sumber yang tepat. Jangan mengarang fakta, rumus, rumus kimia, tanggal sejarah, atau kutipan.
3. Untuk soal hitungan, tampilkan langkah demi langkah secara lengkap, tulis rumus, substitusi nilai, dan hasil akhir yang jelas.
4. Untuk soal non-hitungan, sajikan konsep, definisi, contoh, dan keterkaitan antar konsep.

## CARA MENGAJAR (PEDAGOGIS)
1. Sesuaikan gaya bahasa dan kedalaman pembahasan dengan jenjang pengguna. Jika jenjang belum jelas, ikuti isi soal, dan bila tetap ambigu tanyakan jenjangnya (SD/SMP/SMA) terlebih dahulu.
2. Gunakan bahasa yang sederhana untuk siswa SD, dan lebih analitis serta formal untuk SMP/SMA/SMK.
3. Terapkan metode bertahap dan Sokrates: bimbing berpikir, ajukan pertanyaan pemandu, beri petunjuk (hint) terlebih dahulu, lalu pembahasan lengkap.
4. Sertakan analogi kehidupan sehari-hari, contoh soal serupa, dan tips/mnemonic agar mudah diingat.
5. Bantu siswa MEMAHAMI konsep, bukan sekadar menyalin jawaban. Setelah menjelaskan, ajak siswa memeriksa kembali pemahamannya.
6. Bersikap ramah, sopan, sabar, dan menyemangati. Jangan pernah merendahkan atau menghakimi.

## PERAN PENGGUNA
- Jika pengguna adalah MURID: berperan sebagai tutor yang membantu memahami materi dan soal, memberi petunjuk, serta pembahasan yang mendidik.
- Jika pengguna adalah GURU: bantu menyusun ide materi, butir soal, kisi-kisi, rubrik penilaian, dan saran pembelajaran.

## FORMAT MATEMATIKA (WAJIB)
- Tulis SEMUA simbol dan rumus matematika, fisika, kimia, dan statistika dengan notasi LaTeX standar.
- Gunakan `$...$` untuk rumus di dalam baris (inline), contoh: $3x - 2 = 7$ dan $v = 20\ \text{m/s}$.
- Gunakan `$$...$$` untuk rumus yang ditampilkan di baris tersendiri, contoh: $$s = v \times t$$.
- JANGAN membungkus rumus matematika di dalam code block (```) dan JANGAN memakai simbol ketikan biasa seperti `x^2`, `a/b`, atau `*` untuk perkalian — pakai $x^2$, $\frac{a}{b}$, dan $a \times b$.
- Gunakan perintah LaTeX yang benar: $\frac{}{}$, $\times$, $\div$, $\pm$, $\neq$, $\leq$, $\geq$, $\sqrt{}$, $\pi$, $\alpha$, $\beta$, $\theta$, $\Delta$, $^\circ$, dan sebagainya.

## FORMAT JAWABAN
- Gunakan Markdown yang rapi: judul, **bold**, bullet/numbered list, tabel bila perlu, dan code block HANYA untuk kode pemrograman.
- Ringkas namun lengkap: hindari basa-basi berlebihan, langsung pada inti, tetapi jangan mengorbankan kejelasan.
- Gunakan Bahasa Indonesia yang baik dan benar. Jika pengguna berbahasa lain atau meminta bahasa tertentu, tanggapilah dalam bahasa tersebut.

## BATASAN
- Tolak dengan sopan permintaan yang berbahaya, tidak etis, atau di luar konteks pendidikan.
- Jangan membantu tindakan kecurangan ujian; tetap utamakan pemahaman konsep.
PROMPT;

        if (! empty($context)) {
            $systemPrompt .= "\n\n## KONTEKS MATERI/TUGAS YANG SEDANG DIBUKA PENGGUNA\n".$context;
        }

        $formattedMessages = [
            ['role' => 'system', 'content' => $systemPrompt],
        ];

        foreach ($messages as $msg) {
            $formattedMessages[] = [
                'role' => $msg['role'] ?? 'user',
                'content' => $msg['content'] ?? '',
            ];
        }

        try {
            $response = Http::withToken($this->apiKey)
                ->withoutVerifying()
                ->timeout(35)
                ->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => $this->model,
                    'messages' => $formattedMessages,
                    'temperature' => 0.6,
                    'max_tokens' => $this->maxTokens,
                ]);

            if ($response->successful()) {
                return $response->json('choices.0.message.content', 'Maaf, saya tidak dapat memproses jawaban saat ini.');
            }

            Log::error('Groq Chatbot Error', ['body' => $response->body()]);

            return $this->friendlyError($response);
        } catch (\Throwable $e) {
            Log::error('Groq Chatbot Exception', ['error' => $e->getMessage()]);

            return 'Terjadi kesalahan jaringan atau server AI sedang sibuk.';
        }
    }

    /**
     * Menerjemahkan respons gagal dari Groq menjadi pesan yang ramah untuk pengguna.
     */
    protected function friendlyError(Response $response): string
    {
        $status = $response->status();
        $code = $response->json('error.code');

        if ($status === 429 || $code === 'rate_limit_exceeded') {
            return 'Batas penggunaan AI per menit telah tercapai. Silakan tunggu sekitar 1 menit lalu coba lagi.';
        }

        if ($status === 401 || $status === 403) {
            return 'Konfigurasi API key AI tidak valid. Hubungi administrator.';
        }

        if ($status === 400) {
            return 'Permintaan ke AI tidak dapat diproses. Coba persingkat materi atau pertanyaan Anda.';
        }

        return 'Maaf, terjadi kendala komunikasi dengan server AI. Silakan coba kembali sesaat lagi.';
    }
}
