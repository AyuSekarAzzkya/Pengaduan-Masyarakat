<?php

use App\Http\Controllers\HeadStaffController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ResponseController;
use App\Http\Controllers\StaffProvinceController;
use App\Http\Controllers\UserController;
use App\Models\StaffProvince;
use Illuminate\Support\Facades\Route;

/*
|-------------------------------------------------------------------------- 
| Web Routes
|-------------------------------------------------------------------------- 
*/


Route::get('/', function () {
    return view('welcome');
});
Route::get('/login', [UserController::class, 'index'])->name('login'); // Halaman login
Route::post('/login/proses', [UserController::class, 'postLoginOrRegistration'])->name('postLogin'); // Proses login
Route::get('/logout', [UserController::class, 'logout'])->name('logout');

// GUEST-----------------------------------------------------------------------------------------------------------------------------------
Route::middleware(['IsGuest'])->group(function () {
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::post('/reports/{reportId}/vote', [ReportController::class, 'votes'])->name('reports.vote');
    Route::post('reports/{id}/comment', [ReportController::class, 'comment'])->name('reports.comment');
   
    Route::get('/reports/create', [ReportController::class, 'create'])->name('reports.create');
    Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
    Route::get('/reports/{id}', [ReportController::class, 'show'])->name('reports.show');
    Route::get('/monitoring', [MonitoringController::class, 'index'])->name('reports.monitoring');
    Route::delete('/{id}', [MonitoringController::class, 'destroy'])->name('reports.destroy');
});

//-----------------------------------------------------------------------------------------------------------------------------------------

//STAFF------------------------------------------------------------------------------------------------------------------------------------

Route::middleware(['IsStaff'])->group(function () {
    Route::get('/staff/dashboard', [ResponseController::class, 'index'])->name('staff.index');
    Route::post('/responses/{reportId}/action', [ResponseController::class, 'update'])->name('staff.action');
    Route::get('/staff/detail/{reportId}', [ResponseController::class, 'show'])->name('staff.detail');
    Route::post('/staff/detail/{reportId}', [ResponseController::class, 'storeDetails'])->name('staff.detail.store');
    Route::delete('/staff/report/{reportId}/progress/{progressId}/delete', [ResponseController::class, 'deleteProgress'])->name('staff.deleteProgress');
    Route::post('/staff/report/{reportId}/complete', [ResponseController::class, 'complete'])->name('staff.complete');
    Route::get('staff/export', [ResponseController::class, 'export'])->name('staff.export');
});

//-----------------------------------------------------------------------------------------------------------------------------------------

//HEAD STAFF ------------------------------------------------------------------------------------------------------------------------------
Route::middleware(['IsHeadStaff'])->group(function () {
    Route::get('/index', [HeadStaffController::class, 'index'])->name('head.index');
    Route::get('/headstaff/staff', [StaffProvinceController::class, 'viewStaffByProvince'])->name('head.staff');
    Route::delete('/headstaff/staff/{id}', [StaffProvinceController::class, 'deleteStaff'])->name('head.staff.delete');
    Route::post('/headstaff/staff/{id}/reset-password', [StaffProvinceController::class, 'resetPassword'])->name('head.reset');

    Route::get('/headstaff/staff/create', [StaffProvinceController::class, 'createStaff'])->name('head.create');
    Route::post('/headstaff/staff/store', [StaffProvinceController::class, 'storeStaff'])->name('head.staff.store');
});
