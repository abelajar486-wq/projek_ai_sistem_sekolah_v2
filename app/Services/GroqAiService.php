<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GroqAiService
{
    protected string $apiKey;

    protected string $model;

    public function __construct()
    {
        $this->apiKey = (string) config('services.groq.api_key');
        $this->model = (string) config('services.groq.model', 'qwen/qwen3.8-27b');
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
        $prompt = <<<PROMPT
Anda adalah asisten Guru AI profesional dan kurikulum spesialis sekolah Indonesia.
Tugas Anda adalah membuat Lembar Tugas/Soal Siswa berdasarkan MATERI yang diberikan oleh Guru di bawah ini.

MATERI PEMBELAJARAN:
---
{$material}
---
TOPIK: {$topic}
JUMLAH SOAL: {$questionCount} butir
TIPE SOAL: {$type} (contoh: pilihan ganda, esai, atau campuran)
TINGKAT KESULITAN: {$difficulty}

FORMAT KELUARAN YANG WAJIB ANDA BERIKAN:
Harap pisahkan respon menjadi DUA BAGIAN DENGAN PEMISAH TEPAT "===KUNCI_JAWABAN_GURU===":

BAGIAN 1 (UNTUK MURID):
Berikan judul tugas, petunjuk pengerjaan, dan daftar butir soal bernomor yang jelas dan rapi (dalam format Markdown). JANGAN sertakan kunci jawaban pada Bagian 1 ini!

===KUNCI_JAWABAN_GURU===

BAGIAN 2 (UNTUK GURU):
Kunci jawaban lengkap, pembahasan rinci untuk setiap butir soal, dan rubrik pedoman penilaian skor nilai.
PROMPT;

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
                'error' => 'Gagal menghubungi server AI Groq: '.$response->status(),
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
        $systemPrompt = "Anda adalah 'EduBot AI', asisten belajar dan tutor virtual pintar, ramah, dan sangat sabar untuk siswa dan guru di sekolah. ".
            'Bahasa komunikasi: Bahasa Indonesia yang ramah, jelas, menyemangati, dan edukatif. '.
            'Fungsi Anda: '.
            '1. Membantu murid memahami materi pembelajaran dan maksud butir soal tugas. '.
            '2. Memberikan petunjuk pengerjaan (hints), rumus, konsep dasar, dan contoh analogi. '.
            '3. Jika murid menanyakan jawaban langsung atau meminta bantuan menyelesaikan tugas, berikan penjelasan komprehensif, langkah-langkah logis, serta pembahasannya agar murid benar-benar mengerti konsepnya! '.
            '4. Gunakan format markdown yang rapi (bold, bullet points, code blocks jika terkait koding/rumus).';

        if (! empty($context)) {
            $systemPrompt .= "\n\nKONTEKS TUGAS / MATERI SAAT INI YANG SEDANG DIBUKA USER:\n".$context;
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
                ]);

            if ($response->successful()) {
                return $response->json('choices.0.message.content', 'Maaf, saya tidak dapat memproses jawaban saat ini.');
            }

            Log::error('Groq Chatbot Error', ['body' => $response->body()]);

            return 'Maaf, terjadi kendala komunikasi dengan server AI. Silakan coba kembali sesaat lagi.';
        } catch (\Throwable $e) {
            Log::error('Groq Chatbot Exception', ['error' => $e->getMessage()]);

            return 'Terjadi kesalahan jaringan atau server AI sedang sibuk.';
        }
    }
}
