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
        Route::post('saveToken','saveToken');
        Route::post('userList','userList');
    });

    Route::controller(ChatController::class)->group(function () {
        Route::get('chat/{user}', 'viewChat')->name('chat');
        Route::get('/messages/{id}','receiveMessages')->name('message');
        Route::post('sendMessage','sendMessage');
        Route::put('updateMessage/{id}','updateMessage');
        Route::delete('deleteMessage/{id}','deleteMessage');
        Route::post('sendScheduleMessage','sendScheduleMessage');
        Route::post('uploadAttachment','uploadAttachment');
        Route::delete('deleteFile/{message_id}/{file_id}','deleteFile');
    });
});

require __DIR__.'/auth.php';