<?php

use App\Http\Controllers\Backend\ProductModule\CategoryProduct\ProductCategoryNoteController;
use Illuminate\Support\Facades\Route;

Route::prefix('product-category-note')->name('product-category-note.')->group(function () {
    //Product note
    Route::get('note/{product_category_id}', [ProductCategoryNoteController::class, 'get_note'])->name('get_note');
    Route::get('note/create-modal/{product_category_id}',[ProductCategoryNoteController::class,'create_modal'])->name('modal.create');
    Route::post('note/create', [ProductCategoryNoteController::class, 'note_create'])->name('note.create');
    Route::get('note/data/{id}', [ProductCategoryNoteController::class, 'note_data'])->name('note.data');
    Route::get('note/edit/{id}', [ProductCategoryNoteController::class, 'note_edit'])->name('note.edit');
    Route::put('note/update/{id}', [ProductCategoryNoteController::class, 'note_update'])->name('note.update');

});
