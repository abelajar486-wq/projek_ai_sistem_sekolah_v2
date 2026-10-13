<?php

namespace App\Livewire;

use App\Services\GroqAiService;
use Livewire\Component;

class Chatbot extends Component
{
    public array $messages = [];

    public string $input = '';

    public function sendMessage(GroqAiService $aiService)
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

        // 2. Panggil AI Tutor (EduBot) yang sadar kurikulum SD-SMA
        $reply = $aiService->chatWithAiTutor($this->messages);

        // 3. Simpan balasan AI ke dalam memori
        $this->messages[] = [
            'role' => 'assistant',
            'content' => $reply,
        ];
    }

    public function render()
    {
        return view('livewire.chatbot');
    }
}
