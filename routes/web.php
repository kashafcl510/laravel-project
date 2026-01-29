<?php

use Illuminate\Support\Facades\Route;
use  App\Http\Controllers\AuthenticationController;



Route::get('/', function () {
    
    return view('authentication.signin');

});


Route::get('/signup', [AuthenticationController::class, 'signupPage'])->name('signup.page');
Route::post('/register', [AuthenticationController::class, 'register'])->name('register');

Route::get('/login', [AuthenticationController::class, 'loginPage'])->name('signin.page');
Route::post('/signin', [AuthenticationController::class, 'login'])->name('signin');

Route::post('/logout', [AuthenticationController::class, 'logout'])->name('logout');








//user routes
Route::middleware(['auth', 'role:user'])->group(function(){
Route::get('/dashboard' , [AuthenticationController::class, 'dashboardPage'])->name('site.dashboard');
});


// admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function(){
    
});


