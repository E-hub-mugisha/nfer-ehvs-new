<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DisputeController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeeDashboardController;
use App\Http\Controllers\EmployerController;
use App\Http\Controllers\EmploymentRecordController;
use App\Http\Controllers\GovernmentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('employees', EmployeeController::class)
    ->middleware(['auth']);

Route::resource('employers', EmployerController::class)
    ->middleware(['auth']);

Route::get('/admin/dashboard', [AdminController::class, 'index'])
    ->middleware(['auth', 'role:admin']);

Route::get('/employer/dashboard', [EmployerController::class, 'index'])
    ->middleware(['auth', 'role:employer']);

Route::get('/employee/dashboard', [EmployeeDashboardController::class, 'index'])
    ->middleware(['auth'])->name('employee.dashboard');

Route::get('/employee/profile', [EmployeeDashboardController::class, 'show'])
    ->name('employee.profile');

Route::get('/employee/profile/create', [EmployeeDashboardController::class, 'create'])
    ->middleware(['auth'])->name('employee.profile.create');

Route::post('/employee/profile/store', [EmployeeDashboardController::class, 'store'])
    ->middleware(['auth'])->name('employee.profile.store');

Route::get('/government/dashboard', [GovernmentController::class, 'index'])
    ->middleware(['auth', 'role:government']);

Route::resource('employment-records', EmploymentRecordController::class);

Route::resource('disputes', DisputeController::class);

Route::post(
    '/government/employers/{id}/approve',
    [GovernmentController::class, 'approveEmployer']
)->middleware(['auth', 'role:government']);

Route::get('/my-employment-records', [EmployeeDashboardController::class, 'indexRecords'])
    ->name('my.employment-records.index');

Route::get('/my-employment-records/{id}', [EmployeeDashboardController::class, 'showRecord'])
    ->name('my.employment-records.show');

// List disputes
Route::get('/my-disputes', [DisputeController::class, 'index'])
    ->name('disputes.index');

// Create dispute form
Route::get('/my-disputes/create', [DisputeController::class, 'create'])
    ->name('disputes.create');

// Store dispute
Route::post('/my-disputes', [DisputeController::class, 'store'])
    ->name('disputes.store');

// Show single dispute
Route::get('/my-disputes/{id}', [DisputeController::class, 'show'])
    ->name('disputes.show');

require __DIR__ . '/auth.php';
