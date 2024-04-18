<?php


use App\Http\Controllers\Backend\UserModule\Role\RoleController;
use Illuminate\Support\Facades\Route;

Route::prefix('role')->name('role.')->controller(RoleController::class)->group(function(){
    Route::get("/",'index')->name('index');
    Route::get("/data",'data')->name('data');
    Route::get('create-modal','create_modal')->name('modal.create');
    Route::post("/create",'create')->name('create');
    Route::get("/edit-modal/{id}",'edit_modal')->name('modal.edit');
    Route::put("/update/{id}",'update')->name('update');
});
