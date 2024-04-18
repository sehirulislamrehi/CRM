<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;


Route::prefix('api-internal')->group(function () {
    Route::get('ticket-status-count', [DashboardController::class, 'getStatusCount'])->name('ticket.status.count');
    Route::get('ticket-progress/{bu_id}', [DashboardController::class, 'getTicketProgressByBusinessUnit'])->name('ticket.progress.by.business_unit');
    Route::get('lastFive-ticket', [DashboardController::class, 'lastFiveTicket'])->name('ticket.latest.five');
    Route::get('get-top-agent-by-ticket', [DashboardController::class, 'getLastFiveAgentByTicket'])->name('last.five-agent.by-ticket');
    Route::get('most-arise-category-problem',[DashboardController::class,'getMostAriseCategoryProblem'])->name('get.most.arise.category.problem');
    Route::get('most-arise-problem',[DashboardController::class,'getMostAriseProblem'])->name('get.most.arise.problem');
});
