<?php

use App\Http\Controllers\BulkTicketingController;
use Illuminate\Support\Facades\Route;

Route::prefix('ticketing')->group(function () {
    Route::post(
        'bulkedit',
        [BulkTicketingController::class, 'edit']
    )->name('ticketing.bulkedit');

    Route::post(
        'bulkdelete',
        [BulkTicketingController::class, 'destroy']
    )->name('ticketing.bulkdelete');

    Route::post(
        'bulkrestore',
        [BulkTicketingController::class, 'restore']
    )->name('ticketing.bulkrestore');

    Route::post(
        'bulksave',
        [BulkTicketingController::class, 'update']
    )->name('ticketing.bulksave');
});