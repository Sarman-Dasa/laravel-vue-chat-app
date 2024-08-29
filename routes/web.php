<?php

use App\Http\Controllers\ChatController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/dashboard', function () {
    return view('dashboard',[
        'users' => User::whereNot('id', auth()->id())->get()
    ]);
})->middleware(['auth'])->name('dashboard');

Route::controller(ChatController::class)->group(function () {
    Route::get('chat/{user}', 'viewChat')->name('chat');
    Route::get('/messages/{id}','receiveMessages')->name('message');
    Route::post('sendMessage','sendMessage');
});

require __DIR__.'/auth.php';