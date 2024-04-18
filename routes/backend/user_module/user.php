<?php

use App\Http\Controllers\Backend\UserModule\User\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('user')->name('user.')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::get('data',[UserController::class,'data'])->name('data');
    Route::get('edit/{id}',[UserController::class,'edit'])->name('edit');
    Route::get('create/modal',[UserController::class,'create_modal'])->name('modal.create');
    Route::post('create',[UserController::class,'create'])->name('create');
    Route::put('update/{id}',[UserController::class,'update'])->name('update');
    Route::prefix('api/api-internal')->group(function(){
       Route::post('get-service-center-by-bu',[UserController::class,'getServiceCenterByBu'])->name("get-service-center-by-bu");
    });
});
