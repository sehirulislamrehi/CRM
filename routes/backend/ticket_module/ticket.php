<?php

use App\Http\Controllers\Backend\TicketingModule\Ticket\TicketController;
use Illuminate\Support\Facades\Route;

Route::prefix('agent-panel')->group(function () {
    Route::get('/', [TicketController::class, 'agent'])->name('agent');
    Route::get('ticket-details-modal/{ticket_id}', [TicketController::class, 'getTicketDetailsModal'])->name('get.ticket.details.modal');
    Route::post('save_additional_info', [TicketController::class, 'saveAdditionalInfo'])->name('save.additional.info');
//Ticket api
    Route::prefix('api/api-internal')->group(function () {
        Route::get('get-prerequisite-data/{phone?}', [TicketController::class, 'getPrerequisiteData'])->name('get-prerequisite-data');
        Route::get('service-center-thana-wise/{thana_id}', [TicketController::class, 'getServiceCenterDataByThanaId'])->name('get.service-center.by.thanaId');
        Route::get('service-center-category-wise/{thana_id}/{bu_id}/{category_id?}', [TicketController::class, 'getServiceCenterDataByBuCategoryId'])->name('get.service-center.by.bu.categoryId');
        Route::get('product-category-problem-note/{category_id}', [TicketController::class, 'getProductCategoryProblemWithNote'])->name('get.product-category-problem-note');
        Route::get('thana/{dist_id}', [TicketController::class, 'getThanaByDistrictId'])->name('get.thana-by-dist-id');
        Route::get('customer-info/{phone}', [TicketController::class, 'getCustomerInfo'])->name('get.customer.info');
        Route::post('submit-ticket', [TicketController::class, 'submitTicket'])->name('ticket.submit');
        Route::post('update-ticket', [TicketController::class, 'updateTicket'])->name('ticket.update');
        Route::get('ticket-all/{agent}/{phone}/{channel}', [TicketController::class, 'getTicket'])->name('ticket.get');
        Route::get('ticket-edit/{agent_id}/{ticket_id}', [TicketController::class, 'edit_ticket_page'])->name('get.ticket.edit');
        Route::post('save-customer', [TicketController::class, 'saveCustomer'])->name('customer.save');
    });
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('ticket-index', [TicketController::class, 'ticket_index'])->name('ticket.index');
    Route::get('ticket-index-data', [TicketController::class, 'ticket_admin_data'])->name('ticket.admin.data');
    Route::get('ticket-details-admin/{ticket_id}', [TicketController::class, 'ticket_details_admin'])->name('ticket-details.admin-modal');
    Route::get('ticket-status-modal/update/{ticket_id}', [TicketController::class, 'update_ticket_admin'])->name('ticket-update.admin.modal');
    Route::put('ticket-status/update', [TicketController::class, 'change_status'])->name('ticket.status.change');
    Route::post('/export-excel', [TicketController::class, 'exportData'])->name('export.data');

});
