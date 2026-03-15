<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoanRequestController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\Admin\VehicleController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReturnController as AdminReturnController;
use App\Http\Controllers\Admin\LoanRequestController as AdminLoanRequestController;
use App\Http\Controllers\Admin\MonitoringController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| ROOT
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/logout', function () {
    return redirect()->route('login')->with('logout_warning', true);
});

/*
|--------------------------------------------------------------------------
| SEMUA USER AUTHENTICATED
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/loan-requests/{loanRequest}/return',
        [ReturnController::class, 'create'])
        ->name('returns.create');

    Route::post('/loan-requests/{loanRequest}/return',
        [ReturnController::class, 'store'])
        ->name('returns.store');

    Route::prefix('loan-requests')->name('loan-requests.')->group(function () {
        Route::delete('/{loanRequest}/attachments/{attachment}',
            [LoanRequestController::class, 'deleteAttachment'])
            ->name('attachments.destroy');

        Route::get('/{loanRequest}/pdf',
            [LoanRequestController::class, 'exportPdf'])
            ->name('pdf');
    });
    Route::resource('loan-requests', LoanRequestController::class);

    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

    Route::get('/notifications', [NotificationController::class, 'index'])
        ->name('notifications.index');
    Route::get('/notifications/fetch', [NotificationController::class, 'fetch'])
        ->name('notifications.fetch');
    Route::patch('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])
        ->name('notifications.readAll');
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])
        ->name('notifications.read');
});

/*
|--------------------------------------------------------------------------
| KEPALA DEPARTEMEN — APPROVAL
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:kepala_departemen'])
    ->prefix('approvals')
    ->name('approvals.')
    ->group(function () {

    Route::get('/pending', [ApprovalController::class, 'indexKepala'])
        ->name('kepala.index');
    Route::get('/{loanRequest}/approve', [ApprovalController::class, 'showApproveForm'])
        ->name('kepala.approve.form');
    Route::post('/{loanRequest}/approve', [ApprovalController::class, 'approveKepala'])
        ->name('kepala.approve');
    Route::post('/{loanRequest}/reject', [ApprovalController::class, 'rejectKepala'])
        ->name('kepala.reject');
});

/*
|--------------------------------------------------------------------------
| ADMIN GA — APPROVAL
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin_ga'])
    ->prefix('approvals')
    ->name('approvals.')
    ->group(function () {

    Route::get('/pending-ga', [AdminLoanRequestController::class, 'index'])
        ->name('approvals.ga.index');
    Route::get('/ga/{loanRequest}/approve', [AdminLoanRequestController::class, 'showApproveForm'])
        ->name('ga.approve.form');
    Route::post('/ga/{loanRequest}/approve', [AdminLoanRequestController::class, 'approve'])
        ->name('ga.approve');
    Route::post('/ga/{loanRequest}/reject', [AdminLoanRequestController::class, 'reject'])
        ->name('ga.reject');
});

/*
|--------------------------------------------------------------------------
| ADMIN HR — APPROVAL                              ✅ ganti dari admin_akuntansi
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin_hr'])                           // ✅ admin_hr
    ->prefix('approvals')
    ->name('approvals.')
    ->group(function () {

    Route::get('/pending-hr', [ApprovalController::class, 'indexHR']) // ✅ indexHR
        ->name('hr.index');                                            // ✅ hr.index
    Route::get('/hr/{loanRequest}/approve', [ApprovalController::class, 'showApproveFormHR'])
        ->name('hr.approve.form');                                     // ✅ hr.approve.form
    Route::post('/hr/{loanRequest}/approve', [ApprovalController::class, 'approveHR'])
        ->name('hr.approve');                                          // ✅ hr.approve
    Route::post('/hr/{loanRequest}/reject', [ApprovalController::class, 'rejectHR'])
        ->name('hr.reject');                                           // ✅ hr.reject
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin_ga,kepala_departemen,admin_hr']) // ✅ admin_hr
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

    // ==================== ADMIN GA ONLY ====================
    Route::middleware(['role:admin_ga'])->group(function () {

        // VEHICLES
        Route::post('vehicles/check-plate-no', [VehicleController::class, 'checkPlateNo'])
            ->name('vehicles.check-plate-no');
        Route::resource('vehicles', VehicleController::class);

        // UNITS
        Route::post('units/check-name', [UnitController::class, 'checkName'])
            ->name('units.check-name');
        Route::resource('units', UnitController::class);

        // RETURNS
        Route::prefix('returns')->name('returns.')->group(function () {
            Route::get('/', [AdminReturnController::class, 'index'])
                ->name('index');
            Route::get('/{return}', [AdminReturnController::class, 'show'])
                ->name('show');
            Route::post('/{return}/process', [AdminReturnController::class, 'process'])
                ->name('process');
        });

        // LOAN REQUESTS (Admin GA)
        Route::prefix('loan-requests')->name('loan-requests.')->group(function () {
            Route::get('/', [AdminLoanRequestController::class, 'index'])
                ->name('index');
            Route::get('/{loanRequest}', [AdminLoanRequestController::class, 'show'])
                ->name('show');
            Route::post('/{loanRequest}/approve', [AdminLoanRequestController::class, 'approve'])
                ->name('approve');
            Route::post('/{loanRequest}/reject', [AdminLoanRequestController::class, 'reject'])
                ->name('reject');
            Route::post('/{loanRequest}/assign', [AdminLoanRequestController::class, 'assign'])
                ->name('assign');
            Route::post('/{loanRequest}/mark-in-use', [AdminLoanRequestController::class, 'markInUse'])
                ->name('mark-in-use');
            Route::post('/{loanRequest}/cancel', [AdminLoanRequestController::class, 'cancel'])
                ->name('cancel');
        });
    });

    // ==================== USERS (Multi-role) ====================
    Route::middleware(['role:admin_ga,kepala_departemen,admin_hr'])->group(function () { // ✅ admin_hr
        Route::post('users/check-email', [UserController::class, 'checkEmail'])
            ->name('users.check-email');
        Route::post('users/check-phone', [UserController::class, 'checkPhone'])
            ->name('users.check-phone');
        Route::post('users/check-name', [UserController::class, 'checkName'])
            ->name('users.check-name');
        Route::resource('users', UserController::class);
    });

    // ==================== MONITORING (Admin HR) ====================
    Route::middleware(['role:admin_hr'])                               // ✅ admin_hr (bukan admin_ga)
        ->prefix('monitoring')
        ->name('monitoring.')
        ->group(function () {

        Route::get('/', [MonitoringController::class, 'index'])        // ✅ list history
            ->name('index');
        Route::get('/export-pdf', [MonitoringController::class, 'exportPdf'])
            ->name('export-pdf');                                      // ✅ export PDF
        Route::get('/{loanRequest}', [MonitoringController::class, 'show'])
            ->name('show');                                            // ✅ detail record
    
            });

});

require __DIR__.'/auth.php';