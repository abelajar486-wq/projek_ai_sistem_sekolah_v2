<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('home', absolute: false), navigate: true);
    }
}; ?>

<div>
    <span class="tag-street tag-tilt bg-traffic-400 text-asphalt-950">Masuk ke Akun</span>

    <h1 class="mt-4 text-3xl text-asphalt-900 leading-none">
        Selamat <span class="text-spray-600">Balik</span>!
    </h1>
    <p class="mt-2 text-xs text-concrete-600 leading-relaxed">
        Gunakan email sekolah Anda untuk membuka ruang kelas, materi, tugas, dan asisten AI.
    </p>

    <!-- Session Status -->
    <x-auth-session-status class="mt-4" :status="session('status')" />

    <form wire:submit="login" class="mt-6 space-y-4">
        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email Sekolah')" />
            <x-text-input wire:model="form.email" id="email" class="block mt-1.5 w-full" type="email" name="email" required autofocus autocomplete="username" placeholder="nama@sekolah.id" />
            <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input wire:model="form.password" id="password" class="block mt-1.5 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;" />

            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between gap-3 pt-1">
            <label for="remember" class="inline-flex items-center gap-2 cursor-pointer select-none">
                <input wire:model="form.remember" id="remember" type="checkbox" name="remember"
                       class="w-4 h-4 border-2 border-asphalt-950 rounded text-spray-600 focus:ring-spray-500 cursor-pointer">
                <span class="text-xs font-semibold text-concrete-700">Ingat saya</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-xs font-semibold street-link underline decoration-2 underline-offset-2" href="{{ route('password.request') }}" wire:navigate>
                    Lupa password?
                </a>
            @endif
        </div>

        <div class="pt-2">
            <x-primary-button class="w-full justify-center py-3 text-sm">
                {{ __('Masuk') }}
            </x-primary-button>
        </div>
    </form>

    @if (config('demo.enabled'))
        <div class="mt-6 pt-4 border-t-2 border-dashed border-concrete-300">
            <div class="flex items-center gap-2">
                <span class="tag-street tag-tilt-right bg-acid-400 text-asphalt-950">Mode Demo</span>
                <span class="text-[11px] text-concrete-600 leading-tight">Coba tanpa daftar &mdash; masuk instan sebagai:</span>
            </div>

            <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                @foreach (config('demo.accounts') as $role => $account)
                    <form method="POST" action="{{ route('demo.login', $role) }}" class="flex">
                        @csrf
                        <button
                            type="submit"
                            class="btn-street {{ $role === 'guru' ? 'btn-spray' : 'btn-asphalt' }} w-full h-full px-4 py-2.5 rounded-lg"
                        >
                            <span class="flex flex-col items-start text-left w-full">
                                <span class="font-display text-xs uppercase tracking-[0.12em]">{{ $account['label'] }}</span>
                                <span class="block text-[10px] font-sans font-normal normal-case tracking-normal opacity-80">{{ $account['description'] }}</span>
                            </span>
                        </button>
                    </form>
                @endforeach
            </div>
        </div>
    @endif

    <div class="mt-6 pt-4 border-t-2 border-dashed border-concrete-300 flex items-center justify-between gap-3">
        <span class="text-xs text-concrete-600">Belum punya akun sekolah?</span>
        <a class="text-xs font-display uppercase tracking-widest street-link underline decoration-2 underline-offset-2" href="{{ route('register') }}" wire:navigate>
            Daftar &rarr;
        </a>
    </div>
</div>
