<?php

use App\Http\Controllers\KompetensiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\MahasiswaController;
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

Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postLogin']);
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth');

Route::middleware(['auth'])->group(function() {
    Route::get('/', [WelcomeController::class,'index']);

    Route::group(['prefix' => 'level', 'middleware' => 'authorize:ADM'], function() { 
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
    Route::group(['prefix' => 'kompetensi','middleware' => 'authorize:ADM'], function() { 
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

    Route::group(['prefix' => 'mahasiswa','middleware' => 'authorize:ADM'], function() { 
        Route::get('/', [MahasiswaController::class, 'index']); //Menampilkan laman awal Kompetensi
        Route::post('/list', [MahasiswaController::class, 'list']); //menampilkan data Kompetensi dalam bentuk json untuk datatables.

        Route::get('/create_ajax', [MahasiswaController::class, 'create_ajax']); //Buat data Kompetensi w ajax
        Route::post('/ajax', [MahasiswaController::class, 'store_ajax']); //menyimpan data Kompetensi baru w ajax
    
        Route::get('/{id}/show_ajax', [MahasiswaController::class, 'show_ajax']);
    
        Route::get('/{id}/edit_ajax', [MahasiswaController::class, 'edit_ajax']); //edit data Kompetensi dengan ajax
        Route::put('/{id}/update_ajax', [MahasiswaController::class, 'update_ajax']); //menyimpan perubahan data dengan ajax
    
        Route::get('/{id}/delete_ajax', [MahasiswaController::class, 'confirm_ajax']); //Munculkan pop up konfirmasi delete dengan ajax
        Route::delete('/{id}/delete_ajax', [MahasiswaController::class, 'delete_ajax']); //Menghapus data user dengan ajax
    
        Route::get('/import', [MahasiswaController::class, 'import']); //import excel
        Route::post('/import_ajax', [MahasiswaController::class, 'import_ajax']); //import excel dengan ajax
        Route::get('/export_excel', [MahasiswaController::class, 'export_excel']); //export excel
        Route::get('/export_pdf', [MahasiswaController::class, 'export_pdf']); //export pdf
    

    } );
    Route::group(['prefix' => 'dosen','middleware' => 'authorize:ADM'], function() { 
        Route::get('/', [DosenController::class, 'index']); //Menampilkan laman awal Kompetensi
        Route::post('/list', [DosenController::class, 'list']); //menampilkan data Kompetensi dalam bentuk json untuk datatables.

        Route::get('/create_ajax', [DosenController::class, 'create_ajax']); //Buat data Kompetensi w ajax
        Route::post('/ajax', [DosenController::class, 'store_ajax']); //menyimpan data Kompetensi baru w ajax
    
        Route::get('/{id}/show_ajax', [DosenController::class, 'show_ajax']);
    
        Route::get('/{id}/edit_ajax', [DosenController::class, 'edit_ajax']); //edit data Kompetensi dengan ajax
        Route::put('/{id}/update_ajax', [DosenController::class, 'update_ajax']); //menyimpan perubahan data dengan ajax
    
        Route::get('/{id}/delete_ajax', [DosenController::class, 'confirm_ajax']); //Munculkan pop up konfirmasi delete dengan ajax
        Route::delete('/{id}/delete_ajax', [DosenController::class, 'delete_ajax']); //Menghapus data user dengan ajax
    
        Route::get('/import', [DosenController::class, 'import']); //import excel
        Route::post('/import_ajax', [DosenController::class, 'import_ajax']); //import excel dengan ajax
        Route::get('/export_excel', [DosenController::class, 'export_excel']); //export excel
        Route::get('/export_pdf', [DosenController::class, 'export_pdf']); //export pdf
    

    } );

    Route::group(['prefix' => 'user','middleware' => 'authorize:ADM'], function() { 
        Route::get('/', [UserController::class, 'index']); //Menampilkan laman awal User
        Route::post('/list', [UserController::class, 'list']); //menampilkan data User dalam bentuk json untuk datatables.

        Route::get('/create_ajax', [UserController::class, 'create_ajax']); //Buat data User w ajax
        Route::post('/store_ajax', [UserController::class, 'store_ajax']); //menyimpan data User baru w ajax
        
        Route::get('/create_detail_ajax', [UserController::class, 'create_detail_ajax']); //buat data detail User baru w ajax
        Route::post('/store_detail_ajax', [UserController::class, 'store_detail_ajax']); //menyimpan data User baru w ajax

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