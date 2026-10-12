<?php

use App\Models\User;
use Database\Seeders\SchoolSeeder;

test('demo login buttons are shown on the login screen', function () {
    $this->seed(SchoolSeeder::class);

    $this->get('/login')
        ->assertOk()
        ->assertSee('Mode Demo');
});

test('visitor can log in as the demo teacher', function () {
    $this->seed(SchoolSeeder::class);

    $response = $this->post(route('demo.login', 'guru'));

    $response->assertRedirect(route('home', absolute: false));

    $this->assertAuthenticatedAs(
        User::where('email', config('demo.accounts.guru.email'))->first()
    );
});

test('visitor can log in as the demo student', function () {
    $this->seed(SchoolSeeder::class);

    $response = $this->post(route('demo.login', 'murid'));

    $response->assertRedirect(route('home', absolute: false));

    $this->assertAuthenticatedAs(
        User::where('email', config('demo.accounts.murid.email'))->first()
    );
});

test('demo login is not available when the demo mode is disabled', function () {
    config()->set('demo.enabled', false);

    $this->post(route('demo.login', 'guru'))->assertNotFound();
});

test('demo login reports an error when the demo account is missing', function () {
    $response = $this->post(route('demo.login', 'guru'));

    $response
        ->assertRedirect(route('login'))
        ->assertSessionHasErrors('form.email');

    $this->assertGuest();
});

test('demo login rejects an unknown role', function () {
    $this->post(route('demo.login', 'admin'))->assertNotFound();
});
