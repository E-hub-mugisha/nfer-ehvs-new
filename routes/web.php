<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DisputeController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeeDashboardController;
use App\Http\Controllers\Employer\EmployerDashboardController;
use App\Http\Controllers\Employer\EmployerDisputeController;
use App\Http\Controllers\Employer\EmployerProfileController;
use App\Http\Controllers\EmployerController;
use App\Http\Controllers\EmploymentRecordController;
use App\Http\Controllers\GovernmentController;
use App\Http\Controllers\GovernmentDisputeController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TransferRequestController;
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

    Route::get('/onboarding', [OnboardingController::class, 'show'])
        ->name('onboarding.show');

    Route::post('/onboarding/employee', [OnboardingController::class, 'storeEmployee'])
        ->name('onboarding.employee.store');

    Route::post('/onboarding/employer', [OnboardingController::class, 'storeEmployer'])
        ->name('onboarding.employer.store');

    Route::post('/onboarding/government', [OnboardingController::class, 'storeGovernment'])
        ->name('onboarding.government.store');
});

Route::resource('employees', EmployeeController::class)
    ->middleware(['auth']);

Route::resource('employers', EmployerController::class)
    ->middleware(['auth']);

Route::get('/admin/dashboard', [AdminController::class, 'index'])
    ->middleware(['auth', 'role:admin']);
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {

    Route::get('/employees', [App\Http\Controllers\Admin\EmployeeController::class, 'index'])
        ->name('employees.index');
    Route::get('/employees/{employee}', [App\Http\Controllers\Admin\EmployeeController::class, 'show'])
        ->name('employees.show');

    Route::get('/employers', [App\Http\Controllers\Admin\EmployerController::class, 'index'])
        ->name('employers.index');
    Route::get('/employers/{employer}', [App\Http\Controllers\Admin\EmployerController::class, 'show'])
        ->name('employers.show');
    Route::post('/employers/{employer}/approve', [App\Http\Controllers\Admin\EmployerController::class, 'approve'])->name('employers.approve');
    Route::post('/employers/{employer}/reject', [App\Http\Controllers\Admin\EmployerController::class, 'reject'])->name('employers.reject');
    Route::delete('employers/{employer}', [App\Http\Controllers\Admin\EmployerController::class, 'destroy'])
        ->name('employers.destroy');
    Route::get('/disputes',                  [App\Http\Controllers\Admin\DisputeController::class, 'index'])->name('disputes.index');
    Route::get('/disputes/{dispute}',        [App\Http\Controllers\Admin\DisputeController::class, 'show'])->name('disputes.show');
    Route::patch('/disputes/{dispute}/status', [App\Http\Controllers\Admin\DisputeController::class, 'updateStatus'])->name('disputes.status');

    Route::get('employment-records',          [App\Http\Controllers\Admin\EmployeeRecordController::class, 'indexEmploymentRecord'])->name('employment-records.index');
    Route::get('employment-records/{employmentRecord}', [App\Http\Controllers\Admin\EmployeeRecordController::class, 'show'])->name('employment-records.show');

    // Transfer Requests
    Route::get('transfer-requests',          [App\Http\Controllers\Admin\TransferController::class, 'index'])->name('transfer-requests.index');
    Route::get('transfer-requests/{transferRequest}', [App\Http\Controllers\Admin\TransferController::class, 'show'])->name('transfer-requests.show');
    Route::patch('/transfer-requests/{transferRequest}/approve', [App\Http\Controllers\Admin\TransferController::class, 'approve'])->name('transfer-requests.approve');
    Route::patch('/transfer-requests/{transferRequest}/reject',  [App\Http\Controllers\Admin\TransferController::class, 'reject'])->name('transfer-requests.reject');
    Route::delete('transfer-requests/{transferRequest}', [App\Http\Controllers\Admin\TransferController::class, 'destroy'])
        ->name('transfer-requests.destroy');
    // Reports
    Route::get('/reports',              [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export/{type}', [App\Http\Controllers\Admin\ReportController::class, 'export'])->name('reports.export');

    // government users
    Route::get('/government/users', [App\Http\Controllers\Admin\GovernmentController::class, 'index'])->name('government.users.index');
    Route::get('/government/users/create', [App\Http\Controllers\Admin\GovernmentController::class, 'create'])->name('government.users.create');
    Route::post('/government/users', [App\Http\Controllers\Admin\GovernmentController::class, 'store'])->name('government.users.store');
    Route::get('/government/users/{government}', [App\Http\Controllers\Admin\GovernmentController::class, 'show'])->name('government.users.show');
    Route::get('/government/users/{government}/edit', [App\Http\Controllers\Admin\GovernmentController::class, 'edit'])->name('government.users.edit');
    Route::put('/government/users/{government}', [App\Http\Controllers\Admin\GovernmentController::class, 'update'])->name('government.users.update');
    Route::delete('/government/users/{government}', [App\Http\Controllers\Admin\GovernmentController::class, 'destroy'])->name('government.users.destroy');
    Route::patch('/government/users/{government}/verify', [App\Http\Controllers\Admin\GovernmentController::class, 'verify'])->name('government.users.verify');
    Route::patch('/government/users/{government}/unverify', [App\Http\Controllers\Admin\GovernmentController::class, 'unverify'])->name('government.users.unverify');
});

Route::get('/employer/dashboard', [EmployerDashboardController::class, 'index'])
    ->middleware(['auth', 'role:employer'])->name('employer.dashboard');

Route::get('/employee/dashboard', [EmployeeDashboardController::class, 'index'])
    ->middleware(['auth'])->name('employee.dashboard');

Route::get('/employee/profile', [EmployeeDashboardController::class, 'show'])
    ->name('employee.profile');

Route::get('/employee/profile/create', [EmployeeDashboardController::class, 'create'])
    ->middleware(['auth'])->name('employee.profile.create');

Route::post('/employee/profile/store', [EmployeeDashboardController::class, 'store'])
    ->middleware(['auth'])->name('employee.profile.store');

Route::get('/government/dashboard', [GovernmentController::class, 'index'])
    ->middleware(['auth', 'role:government'])->name('government.dashboard');

Route::get('/government/profile/create', [GovernmentController::class, 'create'])->name('government.profile.create');
Route::post('/government/profile/store', [GovernmentController::class, 'store'])
    ->name('government.profile.store');

Route::get('/government/employees', [App\Http\Controllers\Government\EmployeeController::class, 'index'])
    ->name('government.employees.index');
Route::get('/government/employees/{employee}', [App\Http\Controllers\Government\EmployeeController::class, 'show'])
    ->name('government.employees.show');

Route::get('/government/employers', [App\Http\Controllers\Government\EmployerController::class, 'index'])
    ->name('government.employers.index');
Route::get('/government/employers/{employer}', [App\Http\Controllers\Government\EmployerController::class, 'show'])
    ->name('government.employers.show');
Route::post('/government/employers/{employer}/approve', [App\Http\Controllers\Government\EmployerController::class, 'approve'])->name('government.employers.approve');
Route::post('/government/employers/{employer}/reject', [App\Http\Controllers\Government\EmployerController::class, 'reject'])->name('government.employers.reject');
Route::post('/government/employers/{employer}/delete', [App\Http\Controllers\Government\EmployerController::class, 'destroy'])->name('government.employers.destroy');
Route::put('government/employers/{employer}', [App\Http\Controllers\Government\EmployerController::class, 'update'])
    ->name('government.employers.update');

Route::resource('employment-records', EmploymentRecordController::class);

Route::prefix('government')->name('government.')->middleware(['auth'])->group(function () {

    Route::get('/disputes',                  [GovernmentDisputeController::class, 'index'])->name('disputes.index');
    Route::get('/disputes/{dispute}',        [GovernmentDisputeController::class, 'show'])->name('disputes.show');
    Route::patch('/disputes/{dispute}/status', [GovernmentDisputeController::class, 'updateStatus'])->name('disputes.updateStatus');

    Route::get('employment-records',          [GovernmentController::class, 'indexEmploymentRecord'])->name('employment-records.index');
    Route::get('employment-records/{employmentRecord}', [GovernmentController::class, 'show'])->name('employment-records.show');

    // Transfer Requests
    Route::get('transfer-requests',          [GovernmentController::class, 'indexTransferRequest'])->name('transfer-requests.index');
    Route::get('transfer-requests/{transferRequest}', [GovernmentController::class, 'showTransferRequest'])->name('transfer-requests.show');
    Route::patch('/transfer-requests/{transferRequest}/approve', [GovernmentController::class, 'approve'])->name('transfer-requests.approve');
    Route::patch('/transfer-requests/{transferRequest}/reject',  [GovernmentController::class, 'reject'])->name('transfer-requests.reject');

    // Reports
    Route::get('/reports',              [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export/{type}', [ReportController::class, 'export'])->name('reports.export');
});

Route::resource('disputes', DisputeController::class);

Route::post(
    '/government/employers/{id}/approve',
    [GovernmentController::class, 'approveEmployer']
)->middleware(['auth', 'role:government']);

Route::get('/my-employment-records', [EmployeeDashboardController::class, 'indexRecords'])
    ->name('my.employment-records.index');

Route::get('/my-employment-records/{id}', [EmployeeDashboardController::class, 'showRecord'])
    ->name('my.employment-records.show');
Route::post('employment-records/{record}/disputes', [EmployeeDashboardController::class, 'storeDispute'])
    ->name('my.disputes.store');
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

// ADD these inside the existing employer middleware group:
Route::middleware(['auth', 'role:employer'])->prefix('employer')->name('employer.')->group(function () {

    Route::get('employees',                    [App\Http\Controllers\Employer\EmployeeController::class, 'index'])->name('employees.index');
    Route::get('employees/{employee}',         [App\Http\Controllers\Employer\EmployeeController::class, 'show'])->name('employees.show');
    Route::put('employees/{employee}', [App\Http\Controllers\Employer\EmployeeController::class, 'update'])
        ->name('employees.update');
    Route::delete('employees/{employee}', [App\Http\Controllers\Employer\EmployeeController::class, 'destroy'])
        ->name('employees.destroy');
    // Employment Records
    Route::get('get/employees/records',            [App\Http\Controllers\EmploymentRecordController::class, 'index'])->name('employees.records.index');
    Route::get('get/employees/{id}/records', [App\Http\Controllers\EmploymentRecordController::class, 'show'])->name('employees.records.show');
    Route::put('get/employment-records/{record}', [App\Http\Controllers\EmploymentRecordController::class, 'update'])
        ->name('employees.records.update');
    Route::delete('get/employment-records/{record}', [App\Http\Controllers\EmploymentRecordController::class, 'destroy'])
        ->name('employees.records.destroy');
    // Employee Search
    // Route::get('search',                       [App\Http\Controllers\Employer\EmployeeSearchController::class, 'index'])->name('search.index');
    // Route::post('search',                      [App\Http\Controllers\Employer\EmployeeSearchController::class, 'search'])->name('search.query');
    // Route::get('search/result/{employee}',     [App\Http\Controllers\Employer\EmployeeSearchController::class, 'result'])->name('search.result');
    Route::get('search/create',                [App\Http\Controllers\Employer\EmployeeSearchController::class, 'create'])->name('search.create');
    Route::post('search/store',                [App\Http\Controllers\Employer\EmployeeSearchController::class, 'store'])->name('search.store');
    // Route::post('search/link/{employee}',      [App\Http\Controllers\Employer\EmployeeSearchController::class, 'link'])->name('search.link');

    // Search
    Route::get('search',              [App\Http\Controllers\Employer\EmployeeSearchController::class, 'index'])->name('search.index');
    Route::post('search',             [App\Http\Controllers\Employer\EmployeeSearchController::class, 'search'])->name('search.query');
    Route::get('search/result/{employee}',   [App\Http\Controllers\Employer\EmployeeSearchController::class, 'result'])->name('search.result');
    Route::post('search/{employee}/link',     [App\Http\Controllers\Employer\EmployeeSearchController::class, 'link'])->name('search.link');

    // Transfer requests — sent by this employer
    Route::post('search/{employee}/transfer', [TransferRequestController::class, 'store'])
        ->name('transfer.store');
    Route::get('transfers/sent',              [TransferRequestController::class, 'sent'])
        ->name('transfer.sent');

    // Transfer requests — received by this employer
    Route::get('transfers/received',          [TransferRequestController::class, 'received'])
        ->name('transfer.received');
    Route::patch('transfers/{transfer}/approve', [TransferRequestController::class, 'approve'])
        ->name('transfer.approve');
    Route::patch('transfers/{transfer}/reject',  [TransferRequestController::class, 'reject'])
        ->name('transfer.reject');
    // Profile
    Route::get('/profile/create', [EmployerProfileController::class, 'create'])
        ->name('profile.create');

    Route::post('/profile', [EmployerProfileController::class, 'store'])
        ->name('profile.store');

    Route::get('/profile/edit', [EmployerProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [EmployerProfileController::class, 'update'])
        ->name('profile.update');

    Route::get('/disputes', [EmployerDisputeController::class, 'index'])
        ->name('disputes.index');

    Route::get('/disputes/{id}', [EmployerDisputeController::class, 'show'])
        ->name('disputes.show');

    Route::patch('/disputes/{id}', [EmployerDisputeController::class, 'updateStatus'])
        ->name('disputes.updateStatus');
});


require __DIR__ . '/auth.php';
