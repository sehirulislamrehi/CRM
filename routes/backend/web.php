<?php

use App\Http\Controllers\Auth\AuthenticationController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;


Route::middleware('guest')->group(function () {
    Route::get('/', [AuthenticationController::class, 'show_login'])->name('admin.show-login');
    Route::post('/do-login', [AuthenticationController::class, 'do_login'])->name('admin.do-login');
});

Route::middleware('auth')->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        //Dashboard
        Route::get("dashboard", [DashboardController::class, 'index'])->name("dashboard.index");
        Route::prefix('dashboard/api')->group(function(){
            require_once 'dashboard.php';
        });
        //Common module
        Route::prefix("common-module")->name('common-module.')->group(function () {
            //Business unit
            require_once 'common_module/business_unit.php';
            //District
            require_once 'common_module/district.php';
            //Thana
            require_once 'common_module/thana.php';
            //Service Center
            require_once 'common_module/service_center.php';
            //Brand
            require_once 'common_module/brand.php';
            //channel
            require_once 'common_module/channel.php';
        });
        //User module
        Route::prefix("user-module")->name('user-module.')->group(function () {
            //user
            require_once 'user_module/user.php';
            //Role
            require_once 'user_module/role.php';
        });
        //Product module
        Route::prefix('product-module')->name('product-module.')->group(function () {
            //Product category
            require_once 'product_module/product_category.php';
            //Product note
            require_once 'product_module/product_note.php';
            //Product problem
            require_once 'product_module/product_problem.php';
        });
        Route::any('logout', [AuthenticationController::class, 'do_logout'])->name('logout');

    });

    Route::prefix('ticket-module')->name('ticket.')->group(function () {
        Route::post('/change-locale', [\App\Http\Controllers\LocalizationController::class, 'changeLocale'])->name('change.locale');
        Route::middleware('lang')->group(function () {
            require_once 'ticket_module/ticket.php';
        });
    });

});

