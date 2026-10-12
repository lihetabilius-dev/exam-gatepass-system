<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController; 
use App\Http\Controllers\GatepassController; 


Route::get('/', function () {
    return view('frontpage'); 
})->name('frontpage');


Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


Route::post('/dashboard/upload/accountant', [DashboardController::class, 'uploadByAccountant'])->name('upload.accountant');
Route::post('/dashboard/upload/dean', [DashboardController::class, 'uploadByDean'])->name('upload.dean');


Route::post('/generate-gatepass', [DashboardController::class, 'generateGatepass'])->name('generate.gatepass');
Route::get('/download-gatepass/{id}', [DashboardController::class, 'downloadGatepass'])->name('download.gatepass');


Route::get('/gatepass', [GatepassController::class, 'create'])->name('gatepass.form');
Route::post('/gatepass/upload', [GatepassController::class, 'uploadPassport'])->name('gatepass.upload');
Route::post('/gatepass/store', [GatepassController::class, 'store'])->name('gatepass.store');


Route::get('/check-eligibility', [GatepassController::class, 'checkEligibilityApi'])->name('check.eligibility');


Route::resource('students', StudentController::class);


Route::middleware(['auth'])->group(function () {
   
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

   
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
    Route::post('/admin/users/add', [AdminController::class, 'addUser'])->name('admin.addUser');
    Route::post('/admin/users/block/{id}', [AdminController::class, 'blockUser'])->name('admin.blockUser');
    Route::delete('/admin/users/delete/{id}', [AdminController::class, 'deleteUser'])->name('admin.deleteUser');

  
    Route::get('/admin/students', [AdminController::class, 'students'])->name('admin.students');
    Route::delete('/admin/students/delete/{id}', [AdminController::class, 'deleteStudent'])->name('admin.deleteStudent');
    Route::delete('/admin/students/delete-selected', [AdminController::class, 'deleteSelectedStudents'])->name('admin.deleteSelectedStudents');
});