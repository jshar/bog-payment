<?php

use Illuminate\Support\Facades\Route;
use Jshar\BogPayment\Http\Controllers\StatusCallbackController;

Route::prefix('bog-payment')->group(function () {
    Route::any('/payment/callback', StatusCallbackController::class)->name('bog-payment.callback');
});
