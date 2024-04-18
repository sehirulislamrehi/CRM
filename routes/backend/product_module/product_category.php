<?php

use App\Http\Controllers\Backend\ProductModule\CategoryProduct\ProductCategoryController;
use Illuminate\Support\Facades\Route;

Route::prefix('product-category')->name('product-category.')->group(function () {
    Route::get('index', [ProductCategoryController::class, 'index'])->name('index');
    Route::get('create-modal',[ProductCategoryController::class,'create_modal'])->name('modal.create');
    Route::get('bulk-modal',[ProductCategoryController::class,'bulk_modal'])->name('modal.bulk');
    Route::post('bulk-upload',[ProductCategoryController::class,'bulk_upload'])->name('bulk_upload');
    Route::post('create', [ProductCategoryController::class, 'create'])->name('create');
    Route::get('data', [ProductCategoryController::class, 'data'])->name('data');
    Route::get('details/{id}', [ProductCategoryController::class, 'details'])->name('details');
    Route::put('update/{id}', [ProductCategoryController::class, 'update'])->name('update');
});
