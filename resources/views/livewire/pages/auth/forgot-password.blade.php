<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $this->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));

            return;
        }

        $this->reset('email');

        session()->flash('status', __($status));
    }
}; ?>

<div>
    <span class="tag-street tag-tilt bg-cyan-300 text-asphalt-950">Lupa Password</span>

    <h1 class="mt-4 text-3xl text-asphalt-900 leading-none">
        Atur <span class="text-spray-600">Ulang</span>
    </h1>

    <div class="mt-2 text-xs leading-relaxed text-concrete-600">
        Masukkan email sekolah Anda. Kami akan mengirimkan tautan untuk membuat password baru.
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mt-4" :status="session('status')" />

    <form wire:submit="sendPasswordResetLink" class="mt-6 space-y-4">
        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email Sekolah')" />
            <x-text-input wire:model="email" id="email" class="block mt-1.5 w-full" type="email" name="email" required autofocus placeholder="nama@sekolah.id" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="pt-2">
            <x-primary-button class="w-full justify-center py-3 text-sm">
                {{ __('Kirim Tautan Reset') }}
            </x-primary-button>
        </div>
    </form>

    <div class="mt-6 pt-4 border-t-2 border-dashed border-concrete-300 flex items-center justify-between gap-3">
        <span class="text-xs text-concrete-600">Ingat password Anda?</span>
        <a class="text-xs font-display uppercase tracking-widest street-link underline decoration-2 underline-offset-2" href="{{ route('login') }}" wire:navigate>
            &larr; Kembali Masuk
        </a>
    </div>
</div>
