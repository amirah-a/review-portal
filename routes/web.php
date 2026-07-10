<?php

use App\Http\Controllers\CentreController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;
use App\Livewire\ApplicationShow;
use Illuminate\Support\Facades\Storage;

Route::view('/', 'welcome');

Route::group(['middleware' => ['auth', 'verified']], function () {
    Route::get('/dashboard', [IndexController::class, 'index'])->name('dashboard');
    Route::get('/all-applications', [IndexController::class, 'viewAll'])->name('all-applications');
    Route::get('/centres/{centre}', [CentreController::class, 'show'])->name('centres.show');
    Route::get('/applications/{id}', ApplicationShow::class)->name('applications.show');

    Route::get('/documents/{path}', function ($path) {
        $disk = Storage::build([
            'driver' => 'local',
            'root' => '/var/www/html/rapp_lead_up/storage/app/private',
        ]);

        abort_unless($disk->exists($path), 404);

        return response()->file($disk->path($path));
    })
        ->where('path', '.*')
        ->name('documents.show');
});

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__ . '/auth.php';
