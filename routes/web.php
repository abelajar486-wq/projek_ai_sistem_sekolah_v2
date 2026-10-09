<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::view('/', 'welcome')->name('home');
    Route::view('dashboard', 'welcome')->name('dashboard');
    Route::view('standalone-chat', 'chat-standalone')->name('standalone-chat');
});

Route::view('profile', 'profile')
    ->middleware('auth')
    ->name('profile');

require __DIR__.'/auth.php';
