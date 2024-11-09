<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::pattern('id','[0-9]+'); //meaning: ketika ada parameter "id" maka nilainya harus angka, yaitu dari 0 sampai 9.

Route::get('login', [AuthController::class, 'login']) -> name('login');
Route::post('login', [AuthController::class, 'postLogin']);
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth');

Route::middleware(['auth'])->group(function() {
    Route::get('/', [WelcomeController::class,'index']);
});