<?php

use App\Http\Controllers\Backend\CommonModule\District\DistrictController;
use Illuminate\Support\Facades\Route;

Route::prefix("district")->name('district.')->group(function(){
    Route::get('/',[DistrictController::class,'index'])->name("index");
    Route::get('create-modal',[DistrictController::class,'create_modal'])->name('modal.create');
    Route::post('create',[DistrictController::class,'create'])->name('create');
    Route::get('details/{id}',[DistrictController::class,'details'])->name("details");
    Route::put('update/{id}',[DistrictController::class,'update'])->name('update');
    Route::get('data',[DistrictController::class,'data'])->name('data');
});
