<?php

use App\Http\Controllers\HeadStaffController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ResponseController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|-------------------------------------------------------------------------- 
| Web Routes
|-------------------------------------------------------------------------- 
*/

Route::get('/', [UserController::class, 'index'])->name('login'); // Halaman login
Route::post('/login', [UserController::class, 'postLoginOrRegistration'])->name('postLogin'); // Proses login


// GUEST-----------------------------------------------------------------------------------------------------------------------------------
Route::middleware(['IsGuest'])->group(function () {
    Route::get('/logout', [UserController::class, 'logout'])->name('logout');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/create', [ReportController::class, 'create'])->name('reports.create');
    Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
    Route::post('/reports/{reportId}/vote', [ReportController::class, 'votes'])->name('reports.vote');
    Route::get('/reports/{id}', [ReportController::class, 'show'])->name('reports.show');
    Route::post('reports/{id}/comment', [ReportController::class, 'comment'])->name('reports.comment');

    Route::get('/monitoring', [MonitoringController::class, 'index'])->name('reports.monitoring');
    Route::delete('/{id}', [MonitoringController::class, 'destroy'])->name('reports.destroy');
});

//-----------------------------------------------------------------------------------------------------------------------------------------

//STAFF------------------------------------------------------------------------------------------------------------------------------------

Route::middleware(['IsStaff'])->group(function () {
    Route::get('/index', [ResponseController::class, 'index'])->name('staff.index');
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

});
