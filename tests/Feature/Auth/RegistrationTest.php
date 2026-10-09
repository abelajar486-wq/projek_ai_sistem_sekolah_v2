<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Livewire\Volt\Volt;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response
        ->assertOk()
        ->assertSeeVolt('pages.auth.register');
});

test('new users can register as murid', function () {
    $component = Volt::test('pages.auth.register')
        ->set('name', 'Test User')
        ->set('email', 'test@example.com')
        ->set('role', 'murid')
        ->set('nis_nip', '202411099')
        ->set('password', 'password')
        ->set('password_confirmation', 'password');

    $component->call('register');

    $component->assertRedirect(route('home', absolute: false));

    $this->assertAuthenticated();

    $user = User::where('email', 'test@example.com')->firstOrFail();

    expect($user->role)->toBe('murid')
        ->and($user->hasRole('murid'))->toBeTrue();
});

test('new users can register as guru', function () {
    $component = Volt::test('pages.auth.register')
        ->set('name', 'Guru Baru')
        ->set('email', 'guru@example.com')
        ->set('role', 'guru')
        ->set('password', 'password')
        ->set('password_confirmation', 'password');

    $component->call('register');

    $component->assertRedirect(route('home', absolute: false));

    $this->assertAuthenticated();

    $user = User::where('email', 'guru@example.com')->firstOrFail();

    expect($user->role)->toBe('guru')
        ->and($user->hasRole('guru'))->toBeTrue();
});
