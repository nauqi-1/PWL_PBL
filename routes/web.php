<?php

use App\Http\Controllers\KompetensiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\UserController;
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

    Route::group(['prefix' => 'level'], function() { 
        Route::get('/', [LevelController::class, 'index']); //Menampilkan laman awal level
        Route::post('/list', [LevelController::class, 'list']); //menampilkan data level dalam bentuk json untuk datatables.

        Route::get('/create_ajax', [LevelController::class, 'create_ajax']); //Buat data level w ajax
        Route::post('/ajax', [LevelController::class, 'store_ajax']); //menyimpan data level baru w ajax
    
        Route::get('/{id}/show_ajax', [LevelController::class, 'show_ajax']);
    
        Route::get('/{id}/edit_ajax', [LevelController::class, 'edit_ajax']); //edit data level dengan ajax
        Route::put('/{id}/update_ajax', [LevelController::class, 'update_ajax']); //menyimpan perubahan data dengan ajax
    
        Route::get('/{id}/delete_ajax', [LevelController::class, 'confirm_ajax']); //Munculkan pop up konfirmasi delete dengan ajax
        Route::delete('/{id}/delete_ajax', [LevelController::class, 'delete_ajax']); //Menghapus data user dengan ajax
    
        Route::get('/import', [LevelController::class, 'import']); //import excel
        Route::post('/import_ajax', [LevelController::class, 'import_ajax']); //import excel dengan ajax
        Route::get('/export_excel', [LevelController::class, 'export_excel']); //export excel
        Route::get('/export_pdf', [LevelController::class, 'export_pdf']); //export pdf
    

    } );
    Route::group(['prefix' => 'kompetensi'], function() { 
        Route::get('/', [KompetensiController::class, 'index']); //Menampilkan laman awal Kompetensi
        Route::post('/list', [KompetensiController::class, 'list']); //menampilkan data Kompetensi dalam bentuk json untuk datatables.

        Route::get('/create_ajax', [KompetensiController::class, 'create_ajax']); //Buat data Kompetensi w ajax
        Route::post('/ajax', [KompetensiController::class, 'store_ajax']); //menyimpan data Kompetensi baru w ajax
    
        Route::get('/{id}/show_ajax', [KompetensiController::class, 'show_ajax']);
    
        Route::get('/{id}/edit_ajax', [KompetensiController::class, 'edit_ajax']); //edit data Kompetensi dengan ajax
        Route::put('/{id}/update_ajax', [KompetensiController::class, 'update_ajax']); //menyimpan perubahan data dengan ajax
    
        Route::get('/{id}/delete_ajax', [KompetensiController::class, 'confirm_ajax']); //Munculkan pop up konfirmasi delete dengan ajax
        Route::delete('/{id}/delete_ajax', [KompetensiController::class, 'delete_ajax']); //Menghapus data user dengan ajax
    
        Route::get('/import', [KompetensiController::class, 'import']); //import excel
        Route::post('/import_ajax', [KompetensiController::class, 'import_ajax']); //import excel dengan ajax
        Route::get('/export_excel', [KompetensiController::class, 'export_excel']); //export excel
        Route::get('/export_pdf', [KompetensiController::class, 'export_pdf']); //export pdf
    

    } );

    Route::group(['prefix' => 'user'], function() { 
        Route::get('/', [UserController::class, 'index']); //Menampilkan laman awal User
        Route::post('/list', [UserController::class, 'list']); //menampilkan data User dalam bentuk json untuk datatables.

        Route::get('/create_ajax', [UserController::class, 'create_ajax']); //Buat data User w ajax
        Route::post('/ajax', [UserController::class, 'store_ajax']); //menyimpan data User baru w ajax
    
        Route::get('/{id}/show_ajax', [UserController::class, 'show_ajax']);
    
        Route::get('/{id}/edit_ajax', [UserController::class, 'edit_ajax']); //edit data 
        Route::put('/{id}/update_ajax', [UserController::class, 'update_ajax']); //simpan data 
    
        Route::get('/{id}/delete_ajax', [UserController::class, 'confirm_ajax']); //Munculkan pop up konfirmasi delete dengan ajax
        Route::delete('/{id}/delete_ajax', [UserController::class, 'delete_ajax']); //Menghapus data user dengan ajax
    
        Route::get('/import', [UserController::class, 'import']); //import excel
        Route::post('/import_ajax', [UserController::class, 'import_ajax']); //import excel dengan ajax
        Route::get('/export_excel', [UserController::class, 'export_excel']); //export excel
        Route::get('/export_pdf', [UserController::class, 'export_pdf']); //export pdf
    

    } );

});