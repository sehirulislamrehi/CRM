<?php

use App\Http\Controllers\Backend\CommonModule\Brand\BrandController;
use Illuminate\Support\Facades\Route;

Route::prefix('brand')->name('brand.')->group(function () {
    Route::get('/', [BrandController::class, 'index'])->name('index');
    Route::get('update-modal/{id}', [BrandController::class, 'update_modal'])->name('modal.update');
    Route::get('data', [BrandController::class, 'data'])->name('data');
    Route::get('create-modal',[BrandController::class,'create_modal'])->name('modal.create');
    Route::get('bulk_upload_modal',[BrandController::class,'bulk_modal'])->name('modal.bulk');
    Route::post('bulk_upload',[BrandController::class,'bulk_upload'])->name('bulk.upload');
    Route::post('create', [BrandController::class, 'create'])->name('create');
    Route::put('update/{id}', [BrandController::class, 'update'])->name('update');
});
