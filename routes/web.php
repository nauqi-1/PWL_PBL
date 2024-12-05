<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\KompetensiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\TendikController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\MahasiswaAlfaController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\PeriodeController;
use App\Http\Controllers\Personal\TugasController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TugasKompenController;
use App\Http\Controllers\TugasJenisController;
use App\Http\Controllers\MhslistTugasController;
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

Route::pattern('id', '[0-9]+'); //meaning: ketika ada parameter "id" maka nilainya harus angka, yaitu dari 0 sampai 9.

Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postLogin']);
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth');

Route::middleware(['auth'])->group(function () {
    Route::get('/', [WelcomeController::class, 'index']);

    Route::group(['prefix' => 'level', 'middleware' => 'authorize:ADM'], function () {
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


    });
    Route::group(['prefix' => 'kompetensi', 'middleware' => 'authorize:ADM'], function () {
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


    });
    Route::group(['prefix' => 'periode', 'middleware' => 'authorize:ADM'], function () {
        Route::get('/', [PeriodeController::class, 'index']); //Menampilkan laman awal Kompetensi
        Route::post('/list', [PeriodeController::class, 'list']); //menampilkan data Kompetensi dalam bentuk json untuk datatables.

        Route::get('/create_ajax', [PeriodeController::class, 'create_ajax']); //Buat data Kompetensi w ajax
        Route::post('/ajax', [PeriodeController::class, 'store_ajax']); //menyimpan data Kompetensi baru w ajax

        Route::get('/{id}/show_ajax', [PeriodeController::class, 'show_ajax']);

        Route::get('/{id}/edit_ajax', [PeriodeController::class, 'edit_ajax']); //edit data Kompetensi dengan ajax
        Route::put('/{id}/update_ajax', [PeriodeController::class, 'update_ajax']); //menyimpan perubahan data dengan ajax

        Route::get('/{id}/delete_ajax', [PeriodeController::class, 'confirm_ajax']); //Munculkan pop up konfirmasi delete dengan ajax
        Route::delete('/{id}/delete_ajax', [PeriodeController::class, 'delete_ajax']); //Menghapus data user dengan ajax

        Route::get('/import', [PeriodeController::class, 'import']); //import excel
        Route::post('/import_ajax', [PeriodeController::class, 'import_ajax']); //import excel dengan ajax
        Route::get('/export_excel', [PeriodeController::class, 'export_excel']); //export excel
        Route::get('/export_pdf', [PeriodeController::class, 'export_pdf']); //export pdf


    });

    Route::group(['prefix' => 'mahasiswa', 'middleware' => 'authorize:ADM'], function () {
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


    });
    Route::group(['prefix' => 'admin', 'middleware' => 'authorize:ADM'], function () {
        Route::get('/', [AdminController::class, 'index']);
        Route::post('/list', [AdminController::class, 'list']);

        Route::get('/create_ajax', [AdminController::class, 'create_ajax']);
        Route::post('/ajax', [AdminController::class, 'store_ajax']);

        Route::get('/{id}/show_ajax', [AdminController::class, 'show_ajax']);

        Route::get('/{id}/edit_ajax', [AdminController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [AdminController::class, 'update_ajax']);

        Route::get('/{id}/delete_ajax', [AdminController::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [AdminController::class, 'delete_ajax']);

        Route::get('/import', [AdminController::class, 'import']);
        Route::post('/import_ajax', [AdminController::class, 'import_ajax']);
        Route::get('/export_excel', [AdminController::class, 'export_excel']);
        Route::get('/export_pdf', [AdminController::class, 'export_pdf']);
    });
    Route::group(['prefix' => 'mahasiswa_alfa', 'middleware' => 'authorize:ADM'], function () {
        Route::get('/', [MahasiswaAlfaController::class, 'index']);
        Route::post('/list', [MahasiswaAlfaController::class, 'list']);

        Route::get('/create_ajax', [MahasiswaAlfaController::class, 'create_ajax']);
        Route::post('/ajax', [MahasiswaAlfaController::class, 'store_ajax']);

        Route::get('/{id}/show_ajax', [MahasiswaAlfaController::class, 'show_ajax']);

        Route::get('/{id}/edit_ajax', [MahasiswaAlfaController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [MahasiswaAlfaController::class, 'update_ajax']);

        Route::get('/{id}/delete_ajax', [MahasiswaAlfaController::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [MahasiswaAlfaController::class, 'delete_ajax']);

        Route::get('/import', [MahasiswaAlfaController::class, 'import']);
        Route::post('/import_ajax', [MahasiswaAlfaController::class, 'import_ajax']);
        Route::get('/export_excel', [MahasiswaAlfaController::class, 'export_excel']);
        Route::get('/export_pdf', [MahasiswaAlfaController::class, 'export_pdf']);
    });

    Route::group(['prefix' => 'dosen', 'middleware' => 'authorize:ADM'], function () {
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
    });

    Route::group(['prefix' => 'tendik', 'middleware' => 'authorize:ADM'], function () {
        Route::get('/', [TendikController::class, 'index']); //Menampilkan laman awal tendik
        Route::post('/list', [TendikController::class, 'list']); //menampilkan data tendik dalam bentuk json untuk datatables.

        Route::get('/create_ajax', [TendikController::class, 'create_ajax']); //Buat data tendik w ajax
        Route::post('/ajax', [TendikController::class, 'store_ajax']); //menyimpan data tendik baru w ajax

        Route::get('/{id}/show_ajax', [TendikController::class, 'show_ajax']);

        Route::get('/{id}/edit_ajax', [TendikController::class, 'edit_ajax']); //edit data tendik dengan ajax
        Route::put('/{id}/update_ajax', [TendikController::class, 'update_ajax']); //menyimpan perubahan data dengan ajax

        Route::get('/{id}/delete_ajax', [TendikController::class, 'confirm_ajax']); //Munculkan pop up konfirmasi delete dengan ajax
        Route::delete('/{id}/delete_ajax', [TendikController::class, 'delete_ajax']); //Menghapus data tendik dengan ajax

        Route::get('/import', [TendikController::class, 'import']); //import excel
        Route::post('/import_ajax', [TendikController::class, 'import_ajax']); //import excel dengan ajax
        Route::get('/export_excel', [TendikController::class, 'export_excel']); //export excel
        Route::get('/export_pdf', [TendikController::class, 'export_pdf']); //export pdf


    });

    Route::group(['prefix' => 'user', 'middleware' => 'authorize:ADM'], function () {
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


    });
    Route::group(['prefix' => 'tugaskompen', 'middleware' => 'authorize:ADM,DSN,TDK'], function () {
        Route::get('/', [TugasKompenController::class, 'index']); //Menampilkan laman awal TugasKompen
        Route::post('/list', [TugasKompenController::class, 'list']); //menampilkan data TugasKompen dalam bentuk json untuk datatables.

        Route::get('/create_ajax', [TugasKompenController::class, 'create_ajax']); //Buat data TugasKompen w ajax
        Route::post('/store_ajax', [TugasKompenController::class, 'store_ajax']); //menyimpan data TugasKompen baru w ajax

        Route::get('/create_detail_ajax', [TugasKompenController::class, 'create_detail_ajax']); //buat data detail TugasKompen baru w ajax
        Route::post('/store_detail_ajax', [TugasKompenController::class, 'store_detail_ajax']); //menyimpan data TugasKompen baru w ajax

        Route::get('/{id}/show_ajax', [TugasKompenController::class, 'show_ajax']);

        Route::get('/{id}/edit_ajax', [TugasKompenController::class, 'edit_ajax']); //edit data 
        Route::put('/{id}/update_ajax', [TugasKompenController::class, 'update_ajax']); //simpan data 

        Route::get('/{id}/delete_ajax', [TugasKompenController::class, 'confirm_ajax']); //Munculkan pop up konfirmasi delete dengan ajax
        Route::delete('/{id}/delete_ajax', [TugasKompenController::class, 'delete_ajax']); //Menghapus data TugasKompen dengan ajax

        Route::get('/import', [TugasKompenController::class, 'import']); //import excel
        Route::post('/import_ajax', [TugasKompenController::class, 'import_ajax']); //import excel dengan ajax
        Route::get('/export_excel', [TugasKompenController::class, 'export_excel']); //export excel
        Route::get('/export_pdf', [TugasKompenController::class, 'export_pdf']); //export pdf


    });
    Route::group(['prefix' => 'tugasjenis', 'middleware' => 'authorize:ADM'], function () {
        Route::get('/', [TugasJenisController::class, 'index']); //Menampilkan laman awal TugasJenis
        Route::post('/list', [TugasJenisController::class, 'list']); //menampilkan data TugasJenis dalam bentuk json untuk datatables.

        Route::get('/create_ajax', [TugasJenisController::class, 'create_ajax']); //Buat data TugasJenis w ajax
        Route::post('/store_ajax', [TugasJenisController::class, 'store_ajax']); //menyimpan data TugasJenis baru w ajax

        Route::get('/create_detail_ajax', [TugasJenisController::class, 'create_detail_ajax']); //buat data detail TugasJenis baru w ajax
        Route::post('/store_detail_ajax', [TugasJenisController::class, 'store_detail_ajax']); //menyimpan data TugasJenis baru w ajax

        Route::get('/{id}/show_ajax', [TugasJenisController::class, 'show_ajax']);

        Route::get('/{id}/edit_ajax', [TugasJenisController::class, 'edit_ajax']); //edit data 
        Route::put('/{id}/update_ajax', [TugasJenisController::class, 'update_ajax']); //simpan data 

        Route::get('/{id}/delete_ajax', [TugasJenisController::class, 'confirm_ajax']); //Munculkan pop up konfirmasi delete dengan ajax
        Route::delete('/{id}/delete_ajax', [TugasJenisController::class, 'delete_ajax']); //Menghapus data TugasJenis dengan ajax

        Route::get('/import', [TugasJenisController::class, 'import']); //import excel
        Route::post('/import_ajax', [TugasJenisController::class, 'import_ajax']); //import excel dengan ajax
        Route::get('/export_excel', [TugasJenisController::class, 'export_excel']); //export excel
        Route::get('/export_pdf', [TugasJenisController::class, 'export_pdf']); //export pdf


    });
    Route::group(['prefix' => 'mhs_listtugas', 'middleware' => 'authorize:MHS'], function () {
        Route::get('/', [MhslistTugasController::class, 'index']); //Menampilkan laman awal MhslistTugas
        Route::post('/list', [MhslistTugasController::class, 'list']); //menampilkan data MhslistTugas dalam bentuk json untuk datatables.
        Route::get('/{id}/show_ajax', [MhslistTugasController::class, 'show_ajax']);
        Route::get('/{id}/confirm_ajax', [MhslistTugasController::class, 'confirm_ajax']);
        Route::post('/{id}/request_ajax', [MhslistTugasController::class, 'request_ajax']);
    });

    Route::group(['prefix' => 'personal', 'middleware' => 'authorize:ADM, DSN, TDK'], function () {
        Route::get('/', [TugasController::class, 'index']);
        Route::post('/list', [TugasController::class, 'list']);

        Route::get('/create_ajax', [TugasController::class, 'create_ajax']);
        Route::post('/store_ajax', [TugasController::class, 'store_ajax']);

        Route::get('/create_detail_ajax', [TugasController::class, 'create_detail_ajax']);
        Route::post('/store_detail_ajax', [TugasController::class, 'store_detail_ajax']);

        Route::get('/{id}/show_ajax', [TugasController::class, 'show_ajax']);

        Route::get('/{id}/edit_ajax', [TugasController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [TugasController::class, 'update_ajax']);

        Route::get('/{id}/delete_ajax', [TugasController::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [TugasController::class, 'delete_ajax']);

        //Route::get('/import', [TugasJenisController::class, 'import']); //import excel
        //Route::post('/import_ajax', [TugasJenisController::class, 'import_ajax']); //import excel dengan ajax
        //Route::get('/export_excel', [TugasJenisController::class, 'export_excel']); //export excel
        //Route::get('/export_pdf', [TugasJenisController::class, 'export_pdf']); //export pdf


    });
});
