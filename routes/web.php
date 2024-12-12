<?php

use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|-------------------------------------------------------------------------- 
| Web Routes
|-------------------------------------------------------------------------- 
*/

Route::get('/', [UserController::class, 'index'])->name('login'); // Halaman login dan register
Route::post('/', [UserController::class, 'postLoginOrRegistration'])->name('loginregister'); // Proses login dan registrasi

// Logout Route
Route::get('/logout', [UserController::class, 'logout'])->name('logout');

// Reports Routes (Protected by 'auth' middleware to ensure only logged-in users can access them)
Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
Route::get('/reports/create', [ReportController::class, 'create'])->name('reports.create')->middleware('auth');
Route::post('/reports', [ReportController::class, 'store'])->name('reports.store')->middleware('auth');
Route::post('/reports/{reportId}/vote', [ReportController::class, 'votes'])->name('reports.vote')->middleware('auth');
Route::get('/reports/{id}', [ReportController::class, 'show'])->name('reports.show')->middleware('auth');
Route::post('reports/{id}/comment', [ReportController::class, 'comment'])->name('reports.comment')->middleware('auth');

Route::get('/monitoring', [MonitoringController::class, 'index'])->name('reports.monitoring')->middleware('auth');