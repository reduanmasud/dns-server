<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CaptivePortalController;

Route::get('/', function () {
    return view('welcome');
});



Route::get('/', [CaptivePortalController::class, 'showForm'])->name('portal.index');
Route::post('/subscribe', [CaptivePortalController::class, 'handleSubscription'])->name('portal.subscribe');
Route::get('/success', [CaptivePortalController::class, 'success'])->name('portal.success');
Route::get('/payment', [CaptivePortalController::class, 'payment'])->name('portal.payment');
