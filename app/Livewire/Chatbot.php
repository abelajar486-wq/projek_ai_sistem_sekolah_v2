<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Chatbot extends Component
{
    public array $messages = [];

    public string $input = '';

    public function sendMessage()
    {
        if (empty(trim($this->input))) {
            return;
        }

        // 1. Simpan pesan user ke dalam array memori
        $this->messages[] = [
            'role' => 'user',
            'content' => $this->input,
        ];

        $this->input = ''; // Kosongkan input field

        try {
            // 2. Kirim seluruh riwayat obrolan ke Groq
            $response = Http::withToken(config('services.groq.api_key'))
                ->withoutVerifying() // <--- Pastikan baris ini ditambahkan di sini
                ->timeout(30)
                ->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => config('services.groq.model', 'qwen/qwen3.8-27b'),
                    'messages' => $this->messages,
                ]);

            if ($response->successful()) {
                $reply = $response->json('choices.0.message.content');

                // 3. Simpan balasan AI ke dalam memori
                $this->messages[] = [
                    'role' => 'assistant',
                    'content' => $reply,
                ];
            } else {
                Log::error('Groq API Error', ['response' => $response->body()]);
                $this->messages[] = [
                    'role' => 'assistant',
                    'content' => 'Maaf, terjadi kesalahan dari server Groq.',
                ];
            }
        } catch (\Exception $e) {
            Log::error('Chatbot Exception', ['error' => $e->getMessage()]);
            $this->messages[] = [
                'role' => 'assistant',
                'content' => 'Sistem sedang sibuk atau offline.',
            ];
        }
    }

    public function render()
    {
        return view('livewire.chatbot');
    }
}
