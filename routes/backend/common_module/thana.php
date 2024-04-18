<?php

use App\Http\Controllers\Backend\CommonModule\Thana\ThanaController;
use Illuminate\Support\Facades\Route;

Route::prefix("thana")->name('thana.')->controller(ThanaController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('data', 'data')->name('data');
    Route::get('create-modal','create_modal')->name('modal.create');
    Route::get('bulk-modal','bulk_modal')->name('modal.bulk');
    Route::post('upload-bulk','upload_bulk')->name('upload.bulk');
    Route::post('create','create')->name('create');
    Route::get('update-modal/{id}', 'update_modal')->name('modal.update');
    Route::put('update/{id}', 'update')->name('update');
});
