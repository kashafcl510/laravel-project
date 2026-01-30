<?php

use Illuminate\Support\Facades\Route;
use  App\Http\Controllers\AuthenticationController;



Route::get('/', function () {

    return view('authentication.signin');

});



Route::middleware(['guest'])->group(function(){
Route::get('/forget', [AuthenticationController::class, 'forgetPage'])->name('forget.page');
Route::post('/forget-password' , [AuthenticationController::class, 'forgetPassword'])->name('forget.password');

Route::get('/reset', [AuthenticationController::class, 'resetPage'])->name('reset.page');
Route::post('/reset-password' , [AuthenticationController::class, 'resetPassword'])->name('reset.password');


Route::get('/signup', [AuthenticationController::class, 'signupPage'])->name('signup.page');
Route::post('/register', [AuthenticationController::class, 'register'])->name('register');

Route::get('/login', [AuthenticationController::class, 'loginPage'])->name('login');
Route::post('/signin', [AuthenticationController::class, 'login'])->name('signin');
});

Route::middleware(['auth'])->group(function() {
    Route::post('/logout', [AuthenticationController::class, 'logout'])->name('logout');
});







//user routes
Route::middleware(['auth', 'role:user'])->group(function(){
Route::get('/dashboard' , [AuthenticationController::class, 'dashboardPage'])->name('site.dashboard');
});


// admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function(){

Route::get('/dashboard' , [AuthenticationController::class, 'adminDashboardPage'])->name('admin.dashboard');
});
// 123456kK


