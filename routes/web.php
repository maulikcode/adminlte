<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});
Route::post('/user/registration', [App\Http\Controllers\auth\RegisterController::class,'register'])->name('register.create');
Route::post('/user/checkEmailExist', [App\Http\Controllers\auth\RegisterController::class,'checkEmailExist'])->name('register.checkEmailExist');
Auth::routes(['verify' => true]);
Route::middleware(['auth','verified'])->group(function(){

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::put('/user/updateProfile/{user}',[UserController::class,'updateProfile'])->name('user.updateProfile');
    Route::resource('user', UserController::class);

});

