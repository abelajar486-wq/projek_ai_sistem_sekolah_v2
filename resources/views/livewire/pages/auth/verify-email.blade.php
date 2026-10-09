<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    /**
     * Send an email verification notification to the user.
     */
    public function sendVerification(): void
    {
        if (Auth::user()->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);

            return;
        }

        Auth::user()->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div>
    <span class="tag-street tag-tilt bg-acid-400 text-asphalt-950">Verifikasi Email</span>

    <h1 class="mt-4 text-3xl text-asphalt-900 leading-none">
        Cek <span class="text-spray-600">Inbox</span> Anda
    </h1>

    <div class="mt-2 text-xs leading-relaxed text-concrete-600">
        Terima kasih sudah mendaftar! Sebelum mulai, silakan verifikasi email Anda dengan mengeklik tautan yang baru saja kami kirimkan. Tidak menerima email? Kami siap mengirim ulang.
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="tag-street bg-acid-200 text-acid-900 mt-4">
            Tautan verifikasi baru telah dikirim ke email Anda.
        </div>
    @endif

    <div class="mt-6 space-y-3">
        <x-primary-button class="w-full justify-center py-3 text-sm" wire:click="sendVerification">
            {{ __('Kirim Ulang Email Verifikasi') }}
        </x-primary-button>

        <button wire:click="logout" type="submit" class="btn-street w-full bg-white px-4 py-2.5 rounded-lg font-display text-xs uppercase tracking-[0.12em] text-asphalt-800 focus-street transition">
            {{ __('Keluar') }}
        </button>
    </div>
</div>
