<?php

use App\Http\Controllers\Api\DosenController;
use App\Http\Controllers\Api\KompetensiController;
use App\Http\Controllers\Api\MahasiswaController;
use App\Http\Controllers\Api\PengumpulanController;
use App\Http\Controllers\Api\TendikController;
use App\Http\Controllers\Api\TugasController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', \App\Http\Controllers\Api\RegisterController::class)->name('register');
Route::post('/login', \App\Http\Controllers\Api\LoginController::class)->name('login');
Route::post('/logout', \App\Http\Controllers\Api\LogoutController::class)->name('logout');



Route::middleware('auth:api')->group(function () {
    Route::group(['prefix' => 'user', 'middleware' => 'authorize:ADM'], function() {
        Route::get('/', [UserController::class, 'index']);
        Route::post('/', [UserController::class, 'store']);
        Route::get('/{user}', [UserController::class, 'show']);
        Route::put('/{user}', [UserController::class, 'update']);
        Route::delete('/{user}', [UserController::class, 'destroy']);
    });
    Route::group(['prefix' => 'tugas', 'middleware' => 'authorize:ADM,DSN,TDK,MHS'], function() {
        Route::get('/', [TugasController::class, 'index']);
        Route::post('/', [TugasController::class, 'store']);
        Route::get('/{tugas}', [TugasController::class, 'show']);
        Route::put('/{tugas}', [TugasController::class, 'update']);
        Route::delete('/{tugas}', [TugasController::class, 'destroy']);
    });
    
    Route::group(['prefix' => 'kompetensi', 'middleware' => 'authorize:ADM'], function() {
        Route::get('/', [KompetensiController::class, 'index']);
        Route::post('/', [KompetensiController::class, 'store']);
        Route::get('/{kompetensi}', [KompetensiController::class, 'show']);
        Route::put('/{kompetensi}', [KompetensiController::class, 'update']);
        Route::delete('/{kompetensi}', [KompetensiController::class, 'destroy']);
    });

    Route::group(['prefix' => 'pengumpulan', 'middleware' => 'authorize:ADM,MHS'], function() {
        Route::get('/', [PengumpulanController::class, 'index']);
        Route::post('/', [PengumpulanController::class, 'store']);
        Route::get('/{pengumpulan}', [PengumpulanController::class, 'show']);
        Route::put('/{pengumpulan}', [PengumpulanController::class, 'update']);
        Route::delete('/{pengumpulan}', [PengumpulanController::class, 'destroy']);
    });

    Route::group(['prefix' => 'mahasiswa', 'middleware' => 'authorize:ADM,MHS'], function() {
        Route::get('/', [MahasiswaController::class, 'index']);
        Route::post('/', [MahasiswaController::class, 'store']);
        Route::get('/{mahasiswa}', [MahasiswaController::class, 'show']);
        Route::put('/{mahasiswa}', [MahasiswaController::class, 'update']);
        Route::delete('/{mahasiswa}', [MahasiswaController::class, 'destroy']);
    });

    Route::group(['prefix' => 'dosen', 'middleware' => 'authorize:ADM,DSN'], function() {
        Route::get('/', [DosenController::class, 'index']);
        Route::post('/', [DosenController::class, 'store']);
        Route::get('/{dosen}', [DosenController::class, 'show']);
        Route::put('/{dosen}', [DosenController::class, 'update']);
        Route::delete('/{dosen}', [DosenController::class, 'destroy']);
    });

    Route::group(['prefix' => 'tendik', 'middleware' => 'authorize:ADM,TDK'], function() {
        Route::get('/', [TendikController::class, 'index']);
        Route::post('/', [TendikController::class, 'store']);
        Route::get('/{tendik}', [TendikController::class, 'show']);
        Route::put('/{tendik}', [TendikController::class, 'update']);
        Route::delete('/{tendik}', [TendikController::class, 'destroy']);
    });

    
});