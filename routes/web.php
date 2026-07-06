<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;

Route::view('/', 'welcome');

Route::group (['middleware' => ['auth', 'verified']], function () {
    Route::get('/dashboard', [IndexController::class, 'index'])->name('dashboard');
    Route::get('/all-applications', [IndexController::class, 'viewAll'])->name('all-applications');
    Route::get('/applications/{application}', [IndexController::class, 'show'])->name('applications.show');

});



Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
