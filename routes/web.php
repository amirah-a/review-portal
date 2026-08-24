<?php

use App\Http\Controllers\CentreController;
use App\Http\Controllers\CoordinatorController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;
use App\Livewire\ApplicationShow;
use Illuminate\Support\Facades\Storage;
use App\Livewire\Coordinator\AttendanceRegister;
use App\Http\Controllers\ChairmanController;

Route::view('/', 'welcome');

Route::middleware(['auth', 'role:reviewer,chairman'])->group(function () {
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

    Route::get('/email/send-test', [IndexController::class, 'testMail'])->name('testMail');
    Route::get('/email/send-live', [IndexController::class, 'liveMail'])->name('liveMail');
});

Route::middleware(['auth', 'role:coordinator'])->group(function () {
    Route::get('/coordinator/dashboard', [CoordinatorController::class, 'index'])->name('coordinator.dashboard');
    Route::get('/coordinator/attendance', AttendanceRegister::class)->name('coordinator.attendance');
});

Route::middleware(['auth', 'role:chairman'])
    ->prefix('chairman')
    ->name('chairman.')
    ->group(function () {
        /*
         * Attendance centre list
         */
        Route::get('/attendance', [ChairmanController::class, 'attendanceCentres'])->name('attendance.centres');

        /*
         * Attendance days for a centre
         */
        Route::get('/attendance/centre/{centre}', [ChairmanController::class, 'attendanceDays'])->name('attendance.days');

        /*
         * Students and attendance for a particular day
         */
        Route::get('/attendance/centre/{centre}/date/{date}', [ChairmanController::class, 'attendanceDay'])->name('attendance.day');
    });

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__ . '/auth.php';
