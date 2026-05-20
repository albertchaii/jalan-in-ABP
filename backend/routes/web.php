<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Operator\OperatorDashboardController;
use App\Http\Controllers\Operator\OperatorMapController;
use App\Http\Controllers\Operator\OperatorReportController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->role === 'admin') return redirect()->route('admin.dashboard');
    if ($user->role === 'operator') return redirect()->route('operator.dashboard');
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role.admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Pengelolaan Pengguna
    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::get('/users/add', [UserManagementController::class, 'add'])->name('users.add');
    Route::post('/users/{user}/promote', [UserManagementController::class, 'promote'])->name('users.promote');
    Route::get('/users/{user}', [UserManagementController::class, 'show'])->name('users.show');
    Route::patch('/users/{user}/role', [UserManagementController::class, 'updateRole'])->name('users.updateRole');
});

/*
|--------------------------------------------------------------------------
| Operator Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role.operator'])->prefix('operator')->name('operator.')->group(function () {
    Route::get('/dashboard', [OperatorDashboardController::class, 'index'])->name('dashboard');

    // Peta
    Route::get('/map', [OperatorMapController::class, 'index'])->name('map');
    Route::get('/map/geojson', [OperatorMapController::class, 'geojson'])->name('map.geojson');

    // Pengelolaan Laporan
    Route::get('/reports', [OperatorReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/{report}', [OperatorReportController::class, 'show'])->name('reports.show');
    Route::patch('/reports/{report}/status', [OperatorReportController::class, 'updateStatus'])->name('reports.updateStatus');
    Route::post('/reports/{report}/evidence', [OperatorReportController::class, 'uploadEvidence'])->name('reports.uploadEvidence');
});

require __DIR__ . '/auth.php';
