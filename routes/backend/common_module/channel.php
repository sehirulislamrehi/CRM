<?php

use App\Http\Controllers\Backend\CommonModule\Channel\ChannelController;
use Illuminate\Support\Facades\Route;

Route::prefix('channel')->name('channel.')->controller(ChannelController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/data', 'data')->name('data');
    Route::get('update-modal/{id}', 'update_modal')->name('modal.update');
    Route::get('create-modal','create_modal')->name('modal.create');
    Route::post('create', 'create')->name('create');
    Route::put('update/{id}', 'update')->name('update');
});
