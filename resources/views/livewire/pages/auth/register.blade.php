<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $role = 'murid';
    public string $nis_nip = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'role' => ['required', 'string', 'in:guru,murid'],
            'nis_nip' => ['nullable', 'string', 'max:32'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $role = $validated['role'];
        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        $user->assignSchoolRole($role);

        Auth::login($user);

        $this->redirect(route('home', absolute: false), navigate: true);
    }
}; ?>

<div>
    <form wire:submit="register" class="space-y-5">
        <!-- Peran / Role (Spatie) -->
        <div>
            <x-input-label for="role" :value="__('Daftar Sebagai')" />
            <div class="grid grid-cols-2 gap-3 mt-2">
                <label
                    for="role-murid"
                    class="role-card {{ $role === 'murid' ? 'role-card-active' : '' }}"
                >
                    <input
                        wire:model="role"
                        id="role-murid"
                        type="radio"
                        name="role"
                        value="murid"
                        class="sr-only"
                    >
                    <span class="role-card-icon">S</span>
                    <span class="role-card-title">Siswa</span>
                    <span class="role-card-desc">Belajar, presensi & kerjakan tugas</span>
                </label>

                <label
                    for="role-guru"
                    class="role-card {{ $role === 'guru' ? 'role-card-active' : '' }}"
                >
                    <input
                        wire:model="role"
                        id="role-guru"
                        type="radio"
                        name="role"
                        value="guru"
                        class="sr-only"
                    >
                    <span class="role-card-icon">G</span>
                    <span class="role-card-title">Guru</span>
                    <span class="role-card-desc">Kelola materi, tugas & nilai siswa</span>
                </label>
            </div>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Nama Lengkap')" />
            <x-text-input wire:model="name" id="name" class="block mt-1 w-full" type="text" name="name" required autofocus autocomplete="name" placeholder="Ahmad Rizky Pratama" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- NIS / NIP -->
        <div>
            <x-input-label for="nis_nip" :value="__('NIS / NIP (Opsional)')" />
            <x-text-input wire:model="nis_nip" id="nis_nip" class="block mt-1 w-full" type="text" name="nis_nip" autocomplete="off" placeholder="202411001" />
            <x-input-error :messages="$errors->get('nis_nip')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="email" id="email" class="block mt-1 w-full" type="email" name="email" required autocomplete="username" placeholder="nama@sekolah.id" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input wire:model="password" id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Ulangi Password')" />

            <x-text-input wire:model="password_confirmation" id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between pt-2">
            <a class="text-sm font-semibold street-link" href="{{ route('login') }}" wire:navigate>
                Sudah punya akun? <span class="underline decoration-2 underline-offset-2">Masuk</span>
            </a>

            <x-primary-button class="ms-4">
                {{ __('Daftar Sekarang') }}
            </x-primary-button>
        </div>
    </form>
</div>
