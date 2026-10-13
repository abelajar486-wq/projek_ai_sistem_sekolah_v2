<div class="flex flex-col h-screen bg-concrete-100 max-w-3xl mx-auto border-x-4 border-asphalt-950 shadow-sticker font-sans">
    <!-- Header -->
    <div class="wall-asphalt text-white p-4 border-b-4 border-asphalt-950">
        <div class="flex items-center justify-center gap-3">
            <span class="w-8 h-8 bg-spray-600 border-2 border-asphalt-950 flex items-center justify-center text-white font-display text-sm -rotate-6">E</span>
            <span class="font-display uppercase tracking-[0.12em] text-sm">EduBot AI &mdash; Groq Assistant</span>
        </div>
        <div class="hazard-stripe h-1.5 mt-3"></div>
    </div>

    <!-- Area Chat -->
    <div class="flex-1 overflow-y-auto p-4 space-y-4" id="chat-container" data-autoscroll>
        @if(empty($messages))
            <div class="text-center text-concrete-500 mt-10">
                <span class="tag-street tag-tilt bg-traffic-300 text-asphalt-950">Halo!</span>
                <p class="mt-3 text-sm">Silakan tanya sesuatu untuk memulai percakapan.</p>
            </div>
        @else
            @foreach($messages as $msg)
                <div class="flex {{ $msg['role'] === 'user' ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-[75%] p-3 rounded-lg {{ $msg['role'] === 'user' ? 'bg-spray-600 text-white border-2 border-asphalt-950 rounded-br-none shadow-sticker-sm' : 'bg-white text-asphalt-900 border-2 border-asphalt-950 rounded-bl-none shadow-sticker-sm' }}">
                        @if($msg['role'] === 'assistant')
                            <div class="prose prose-sm prose-slate max-w-none leading-relaxed">
                                {!! \Illuminate\Support\Str::markdown($msg['content']) !!}
                            </div>
                        @else
                            <p class="whitespace-pre-wrap text-sm leading-relaxed">{{ $msg['content'] }}</p>
                        @endif
                    </div>
                </div>
            @endforeach
        @endif

        <!-- Indikator Loading -->
        <div wire:loading wire:target="sendMessage" class="flex justify-start">
            <div class="bg-white p-3 rounded-lg rounded-bl-none border-2 border-asphalt-950 shadow-sticker-sm text-concrete-500 animate-pulse text-sm">
                AI sedang mengetik...
            </div>
        </div>
    </div>

    <!-- Form Input -->
    <form wire:submit="sendMessage" class="p-4 bg-white border-t-4 border-asphalt-950 flex gap-2">
        <input
            type="text"
            wire:model="input"
            placeholder="Ketik pesan di sini..."
            class="field flex-1 px-3 py-2.5 text-sm"
            required
        >
        <button
            type="submit"
            wire:loading.attr="disabled"
            class="btn-street btn-spray px-4 py-2.5 rounded-lg font-display text-xs uppercase tracking-wider focus-street"
        >
            Kirim
        </button>
    </form>
</div>
