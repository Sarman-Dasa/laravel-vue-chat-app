<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::group(['middleware' => ['auth']], function () {
    Route::controller(UserController::class)->group(function() {
        Route::get('/dashboard','list')->name('dashboard');
    });

    Route::controller(ChatController::class)->group(function () {
        Route::get('chat/{user}', 'viewChat')->name('chat');
        Route::get('/messages/{id}','receiveMessages')->name('message');
        Route::post('sendMessage','sendMessage');
    });
});

require __DIR__.'/auth.php';