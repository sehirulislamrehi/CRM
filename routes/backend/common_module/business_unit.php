<?php

use App\Http\Controllers\Backend\CommonModule\BusinessUnit\BusinessUnitController;
use Illuminate\Support\Facades\Route;

Route::prefix("bu")->name("bu.")->group(function () {
    Route::get("/", [BusinessUnitController::class, "index"])->name("index");
    Route::get('create_modal',[BusinessUnitController::class,'create_modal'])->name('modal.create');
    Route::post("/create", [BusinessUnitController::class, "create"])->name("create");
    Route::put("/update/{id}", [BusinessUnitController::class, "update"])->name("update");
    Route::get("/update-modal/{id}", [BusinessUnitController::class, "update_modal"])->name("modal.update");
    Route::get("data", [BusinessUnitController::class, "data"])->name('data');
});
