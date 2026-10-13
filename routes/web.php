<?php

use App\Http\Controllers\DocumentController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::view('/', 'welcome')->name('home');
    Route::view('dashboard', 'welcome')->name('dashboard');
    Route::view('standalone-chat', 'chat-standalone')->name('standalone-chat');

    Route::get('materials/{material}/download/{format}', [DocumentController::class, 'material'])
        ->whereIn('format', ['word', 'pdf'])
        ->name('materials.download');

    Route::get('assignments/{assignment}/download/{format}', [DocumentController::class, 'assignment'])
        ->whereIn('format', ['word', 'pdf'])
        ->name('assignments.download');
});

Route::view('profile', 'profile')
    ->middleware('auth')
    ->name('profile');

require __DIR__.'/auth.php';
