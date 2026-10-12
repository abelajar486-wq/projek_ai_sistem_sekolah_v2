<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DemoLoginController extends Controller
{
    /**
     * Masuk sebagai akun demo sesuai peran yang dipilih (Guru / Siswa).
     */
    public function __invoke(Request $request, string $role): RedirectResponse
    {
        abort_unless(config('demo.enabled'), 404);

        $account = config("demo.accounts.{$role}");
        abort_unless($account, 404);

        $user = User::where('email', $account['email'])->first();

        if (! $user) {
            return redirect()->route('login')->withErrors([
                'form.email' => 'Akun demo belum tersedia. Jalankan "php artisan db:seed" terlebih dahulu.',
            ]);
        }

        Auth::login($user, remember: true);
        $request->session()->regenerate();

        return redirect()->intended(route('home', absolute: false));
    }
}
