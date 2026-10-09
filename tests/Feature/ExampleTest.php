<?php

use App\Models\User;

it('redirects guests to the login page', function () {
    $this->get('/')->assertRedirect('/login');
});

it('returns a successful response for authenticated users', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/');

    $response->assertStatus(200);
});
