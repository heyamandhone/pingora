<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\MessageController;



Route::inertia('/', 'Welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
    Route::resource(
        'conversations',
        ConversationController::class
    );
    Route::resource('messages', MessageController::class);
});
Route::get('/slow', function () {
    sleep(30);
    return 'Done';
});


require __DIR__.'/settings.php';
