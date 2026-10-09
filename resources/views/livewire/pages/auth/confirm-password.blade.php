<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $password = '';

    /**
     * Confirm the current user's password.
     */
    public function confirmPassword(): void
    {
        $this->validate([
            'password' => ['required', 'string'],
        ]);

        if (! Auth::guard('web')->validate([
            'email' => Auth::user()->email,
            'password' => $this->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        session(['auth.password_confirmed_at' => time()]);

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <span class="tag-street tag-tilt bg-traffic-400 text-asphalt-950">Area Aman</span>

    <h1 class="mt-4 text-3xl text-asphalt-900 leading-none">
        Konfirmasi <span class="text-spray-600">Dulu</span>
    </h1>

    <div class="mt-2 text-xs leading-relaxed text-concrete-600">
        Ini adalah area aman aplikasi. Masukkan password Anda untuk melanjutkan.
    </div>

    <form wire:submit="confirmPassword" class="mt-6 space-y-4">
        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input wire:model="password"
                          id="password"
                          class="block mt-1.5 w-full"
                          type="password"
                          name="password"
                          required autocomplete="current-password" placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="pt-2">
            <x-primary-button class="w-full justify-center py-3 text-sm">
                {{ __('Lanjutkan') }}
            </x-primary-button>
        </div>
    </form>
</div>
