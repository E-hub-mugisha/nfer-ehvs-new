<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DisputeController;
use App\Http\Controllers\EmployeeController;
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

Route::get('/employee/dashboard', [EmployeeController::class, 'index'])
    ->middleware(['auth', 'role:employee']);

Route::get('/government/dashboard', [GovernmentController::class, 'index'])
    ->middleware(['auth', 'role:government']);

Route::resource('employment-records', EmploymentRecordController::class);

Route::resource('disputes', DisputeController::class);

Route::post(
    '/government/employers/{id}/approve',
    [GovernmentController::class, 'approveEmployer']
)->middleware(['auth', 'role:government']);

require __DIR__ . '/auth.php';
