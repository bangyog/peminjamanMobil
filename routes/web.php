<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoanRequestController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\ReturnController;                                      // ✅ User side
use App\Http\Controllers\Admin\VehicleController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReturnController as AdminReturnController;       // ✅ Admin side — alias!
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

/*
|--------------------------------------------------------------------------
| SEMUA USER AUTHENTICATED
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // ✅ RETURN — user side (di luar prefix loan-requests!)
    Route::get('/loan-requests/{loanRequest}/return',
        [ReturnController::class, 'create'])
        ->name('returns.create');

    Route::post('/loan-requests/{loanRequest}/return',
        [ReturnController::class, 'store'])                                     // ✅ bukan submitReturn
        ->name('returns.store');

    // Route::get('/returns/{vehicleReturn}',
    //     [ReturnController::class, 'show'])
    //     ->name('returns.show');

    // Loan Requests — tambahan routes (sebelum resource!)
    Route::prefix('loan-requests')->name('loan-requests.')->group(function () {
        Route::delete('/{loanRequest}/attachments/{attachment}',
            [LoanRequestController::class, 'deleteAttachment'])
            ->name('attachments.destroy');

        Route::get('/{loanRequest}/pdf',
            [LoanRequestController::class, 'exportPdf'])
            ->name('pdf');
    });
    Route::resource('loan-requests', LoanRequestController::class);

    // Profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

        // Halaman semua notifikasi
    Route::get('/notifications', [NotificationController::class, 'index'])
        ->name('notifications.index');

    // Fetch untuk bell icon (AJAX)
    Route::get('/notifications/fetch', [NotificationController::class, 'fetch'])
        ->name('notifications.fetch');

    // Mark semua dibaca
    Route::patch('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])
        ->name('notifications.readAll');

    // Mark satu notif dibaca
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
| ADMIN AKUNTANSI — APPROVAL
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin_akuntansi'])
    ->prefix('approvals')
    ->name('approvals.')
    ->group(function () {

    Route::get('/pending-akuntansi', [ApprovalController::class, 'indexAkuntansi'])
        ->name('akuntansi.index');
    Route::get('/akuntansi/{loanRequest}/approve', [ApprovalController::class, 'showApproveFormAkuntansi'])
        ->name('akuntansi.approve.form');
    Route::post('/akuntansi/{loanRequest}/approve', [ApprovalController::class, 'approveAkuntansi'])
        ->name('akuntansi.approve');
    Route::post('/akuntansi/{loanRequest}/reject', [ApprovalController::class, 'rejectAkuntansi'])
        ->name('akuntansi.reject');
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin_ga,kepala_departemen,admin_akuntansi'])
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

        // RETURNS — ✅ {return} sesuai controller method signature
        Route::prefix('returns')->name('returns.')->group(function () {
            Route::get('/', [AdminReturnController::class, 'index'])
                ->name('index');
            Route::get('/{return}', [AdminReturnController::class, 'show'])
                ->name('show');
            Route::patch('/{return}/process', [AdminReturnController::class, 'process'])
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
    Route::middleware(['role:admin_ga,kepala_departemen,admin_akuntansi'])->group(function () {
        Route::post('users/check-email', [UserController::class, 'checkEmail'])
            ->name('users.check-email');
        Route::post('users/check-phone', [UserController::class, 'checkPhone'])
            ->name('users.check-phone');
        Route::post('users/check-name', [UserController::class, 'checkName'])
            ->name('users.check-name');
        Route::resource('users', UserController::class);
    });

    // ==================== MONITORING (Admin Akuntansi) ====================
    Route::middleware(['role:admin_ga'])
        ->prefix('monitoring')
        ->name('monitoring.')
        ->group(function () {

        Route::get('/expenses/export', [MonitoringController::class, 'exportExpenses'])
            ->name('expenses.export');
        Route::get('/expenses', [MonitoringController::class, 'expenses'])
            ->name('expenses');
    });

});

require __DIR__.'/auth.php';
