<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display text-2xl uppercase tracking-wide leading-none">
            Profil Saya
        </h2>
        <p class="text-xs text-white/80 mt-1">Perbarui data akun, password, atau hapus akun sekolah Anda.</p>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="sticker sticker-taped rounded-xl p-5 sm:p-8">
                <div class="max-w-xl">
                    <livewire:profile.update-profile-information-form />
                </div>
            </div>

            <div class="sticker sticker-taped-left rounded-xl p-5 sm:p-8">
                <div class="max-w-xl">
                    <livewire:profile.update-password-form />
                </div>
            </div>

            <div class="sticker rounded-xl p-5 sm:p-8" style="box-shadow: 5px 5px 0 rgba(224, 11, 86, 0.88);">
                <div class="max-w-xl">
                    <livewire:profile.delete-user-form />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
