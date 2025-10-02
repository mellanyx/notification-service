<?php

use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

Route::prefix('/notifications')->group(function () {
    Route::post('', [NotificationController::class, 'store']);
    Route::get('', [NotificationController::class, 'index']);
});
