<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
    // return view('welcome');
    return redirect()->route('login');
});

Auth::routes();

// Artikel Views
Route::get('/artikel/view', [App\Http\Controllers\Admin\Artikel\ArtikelController::class, 'view'])->name('artikel.view');

Route::group(['middleware' => ['auth', 'role:1']], function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    // Monitor
    Route::get('/absensi', [App\Http\Controllers\Admin\Absensi\AbsensiController::class, 'index'])->name('absensi');
    Route::get('/track_maps', [App\Http\Controllers\Admin\Absensi\AbsensiController::class, 'track_maps'])->name('track_maps');
    // Event
    Route::get('/event', [App\Http\Controllers\Admin\Event\EventController::class, 'index'])->name('event');
    // Artikel
    Route::get('/artikel', [App\Http\Controllers\Admin\Artikel\ArtikelController::class, 'index'])->name('artikel');
    Route::get('/artikel/create', [App\Http\Controllers\Admin\Artikel\ArtikelController::class, 'create'])->name('artikel.create');
    // E-Learning
    Route::get('/e-learning', [App\Http\Controllers\Admin\ELearning\ELearningController::class, 'index'])->name('e-learning');
    // QrCode
    Route::get('/qrcode', [App\Http\Controllers\Admin\QrCode\QrCodeController::class, 'index'])->name('qrcode');
    Route::get('/generate', [App\Http\Controllers\Admin\QrCode\QrCodeController::class, 'generate'])->name('generate');
    // Pengguna
    Route::get('/pengguna', [App\Http\Controllers\Admin\Pengguna\PenggunaController::class, 'index'])->name('pengguna');
    Route::get('/pengguna/view', [App\Http\Controllers\Admin\Pengguna\PenggunaController::class, 'view'])->name('pengguna.view');
    // Pejabat
    Route::get('/pejabat', [App\Http\Controllers\Admin\Pejabat\PejabatController::class, 'index'])->name('pejabat');
    // Report
    Route::get('/report/absensi', [App\Http\Controllers\Admin\Report\ReportController::class, 'absensi'])->name('report.absensi');
    Route::get('/report/perizinan', [App\Http\Controllers\Admin\Report\ReportController::class, 'perizinan'])->name('report.perizinan');
    Route::get('/report/ranpur', [App\Http\Controllers\Admin\Report\ReportController::class, 'ranpur'])->name('report.ranpur');
    Route::get('/report/kendaraan', [App\Http\Controllers\Admin\Report\ReportController::class, 'kendaraan'])->name('report.kendaraan');
    Route::get('/report/gudang_senjata', [App\Http\Controllers\Admin\Report\ReportController::class, 'gudang_senjata'])->name('report.gudang_senjata');
    Route::get('/report/logistik', [App\Http\Controllers\Admin\Report\ReportController::class, 'logistik'])->name('report.logistik');
    // Pengaturan
    Route::get('/pengaturan', [App\Http\Controllers\Admin\Pengaturan\PengaturanController::class, 'index'])->name('pengaturan');
});
