<?php

use App\Http\Controllers\Backend\CommonModule\ServiceCenter\ServiceCenterController;
use Illuminate\Support\Facades\Route;

Route::prefix("service-center")->name('service-center.')->group(function () {
    Route::get('/', [ServiceCenterController::class, 'index'])->name('index');
    Route::get('data', [ServiceCenterController::class, 'data'])->name('data');
    Route::get('bulk-upload-modal',[ServiceCenterController::class,'bulk_upload_modal'])->name('bulk.upload.modal');
    Route::post('bulk-upload', [ServiceCenterController::class, 'bulk_upload'])->name('bulk.upload');
    Route::get('create-modal', [ServiceCenterController::class, 'create_modal'])->name('modal.create');
    Route::post('create', [ServiceCenterController::class, 'create'])->name('create');
    Route::get('update-modal/{id}', [ServiceCenterController::class, 'details'])->name('modal.update');
    Route::put('update/{id}', [ServiceCenterController::class, 'update'])->name('update');
});
