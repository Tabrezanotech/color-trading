<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\OtpController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\User\GameController;
use App\Http\Controllers\User\Dashboard1Controller;
use App\Http\Controllers\User\Dashboard5Controller;
use App\Http\Controllers\User\Dashboard3Controller;
use App\Http\Controllers\User\Dashboard30Controller;



Route::get('/', function () {
    return view('welcome');
});


// Register & Login
Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::get('/verify-otp', [RegisterController::class, 'showOtpForm'])->name('otp.verify.form');
Route::post('/verify-otp', [RegisterController::class, 'verifyOtp'])->name('otp.verify');

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
})->name('logout');


Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/userlist', [UserController::class, 'index'])->name('admin.userlist');
    Route::delete('/userlist/{id}', [UserController::class, 'destroy'])->name('admin.userlist.delete');

    Route::get('/adminlist', [AdminController::class, 'index'])->name('admin.adminlist');
    Route::delete('/adminlist/{id}', [AdminController::class, 'destroy'])->name('admin.adminlist.delete');
});


// User Dashboard (only for authenticated users)
// Route::get('/user/dashboard', function () {
//     return view('user.dashboard');
// })->middleware('auth')->name('user.dashboard');
Route::middleware(['auth'])->group(function () {
    Route::get('/user/dashboard', function () {
        return view('user.dashboard');
    })->name('user.dashboard');

    Route::get('/game/{type}', [GameController::class, 'showGame'])->name('game.play');
    Route::post('/game/submit', [GameController::class, 'store'])->name('game.submit');
    Route::get('/game/result/{type}', [GameController::class, 'checkResult'])->name('game.result.check');
    Route::get('/game/play/{type}', [GameController::class, 'play'])->name('game.play');

    Route::post('/submit-color-bet', [GameController::class, 'submitColorBet'])->name('color.bet.submit');
    Route::get('/check-color-result', [GameController::class, 'checkColorResult'])->name('color.bet.result');

    Route::post('/color-bet', [GameController::class, 'submitColorBet'])->name('color.bet.submit');
    
    Route::get('/user/dashboard1', [Dashboard1Controller::class, 'index'])->name('user.dashboard1');
    Route::post('/game1/submit', [Dashboard1Controller::class, 'store'])->name('game1.submit');
    Route::get('/game1/result/{type}', [Dashboard1Controller::class, 'checkResult'])->name('game1.result.check');
    Route::get('/game1/check-color-result', [Dashboard1Controller::class, 'checkColorResult'])->name('game1.color.result');
    Route::get('/current-round1', [Dashboard1Controller::class, 'getCurrentRound']);
     


     Route::get('/user/dashboard3', [Dashboard3Controller::class, 'index'])->name('user.dashboard3');
     Route::get('/current-round3', [Dashboard3Controller::class, 'getCurrentRound']);
     Route::post('/game3/submit', [Dashboard3Controller::class, 'store'])->name('game3.submit');
     Route::get('/game/result3/{type}', [Dashboard3Controller::class, 'checkResult']);
     Route::get('/check-color-result3', [Dashboard3Controller::class, 'checkColorResult']);




     Route::get('/user/dashboard5', [Dashboard5Controller::class, 'index'])->name('user.dashboard5');
    Route::post('/game5/submit', [Dashboard5Controller::class, 'store'])->name('game5.submit');

     Route::get('/current-round5', [Dashboard5Controller::class, 'getCurrentRound']);
     Route::get('/game5/result/{type}', [Dashboard5Controller::class, 'checkResult']);
     Route::get('/check-color-result5', [Dashboard5Controller::class, 'checkColorResult']);



     Route::get('/user/dashboard30', [Dashboard30Controller::class, 'index'])->name('user.dashboard30');
    Route::post('/game30/submit', [Dashboard30Controller::class, 'store'])->name('game30.submit');

     Route::get('/current-round30', [Dashboard30Controller::class, 'getCurrentRound']);
     Route::get('/game/result30/{type}', [Dashboard30Controller::class, 'checkResult']);
     Route::get('/check-color-result30', [Dashboard30Controller::class, 'checkColorResult']);


});

// Super Admin Routes
Route::get('/super-admin/register', [\App\Http\Controllers\Auth\SuperAdminRegisterController::class, 'showRegisterForm'])->name('superadmin.register');
Route::post('/super-admin/register', [\App\Http\Controllers\Auth\SuperAdminRegisterController::class, 'register']);

Route::get('/super-admin/login', [\App\Http\Controllers\Auth\SuperAdminLoginController::class, 'showLoginForm'])->name('superadmin.login');
Route::post('/super-admin/login', [\App\Http\Controllers\Auth\SuperAdminLoginController::class, 'login']);

Route::get('/super-admin/dashboard', function () {
    return view('superadmin.dashboard');
})->middleware(['auth', 'role:super_admin'])->name('superadmin.dashboard');




// Route::get('/big', [GameController::class, 'showBig'])->name('game.big');
// Route::post('/big', [GameController::class, 'store'])->name('game.big.store');