<?php

use App\Http\Controllers\Backend\ProductModule\CategoryProduct\ProductCategoryProblemController;
use Illuminate\Support\Facades\Route;

Route::prefix('product-category-problem')->name('product-category-problem.')->group(function () {
    //Product category problem
    Route::get('problem-list/{product_category_id}',[ProductCategoryProblemController::class,'get_problem_list'])->name('get_problem_list');
    Route::get('create-modal/{pc_id}',[ProductCategoryProblemController::class,'create_modal'])->name('modal.create');
    Route::post('create',[ProductCategoryProblemController::class,'create_problem'])->name('problem.create');
    Route::get('data/{id}',[ProductCategoryProblemController::class,'problem_data'])->name('problem.data');
    Route::get('edit-modal/{id}',[ProductCategoryProblemController::class,'edit_modal'])->name('modal.edit');
    Route::put('update/{id}',[ProductCategoryProblemController::class,'problem_update'])->name('problem.update');
});
