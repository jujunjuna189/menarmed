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
    Route::get('/absensi/template', [App\Http\Controllers\Admin\Absensi\AbsensiController::class, 'downloadTemplate'])->name('absensi.template');
    Route::post('/absensi/import', [App\Http\Controllers\Admin\Absensi\AbsensiController::class, 'import'])->name('absensi.import');
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
    Route::get('/pengguna/json', [App\Http\Controllers\Admin\Pengguna\PenggunaController::class, 'indexJson'])->name('pengguna.json');
    Route::get('/pengguna/view', [App\Http\Controllers\Admin\Pengguna\PenggunaController::class, 'view'])->name('pengguna.view');
    Route::post('/pengguna/update-role', [App\Http\Controllers\Admin\Pengguna\PenggunaController::class, 'updateRole'])->name('pengguna.update_role');
    Route::post('/pengguna/import', [App\Http\Controllers\Admin\Pengguna\PenggunaController::class, 'import'])->name('pengguna.import');
    Route::get('/pengguna/template', [App\Http\Controllers\Admin\Pengguna\PenggunaController::class, 'downloadTemplate'])->name('pengguna.template');
    // Pejabat
    Route::get('/pejabat', [App\Http\Controllers\Admin\Pejabat\PejabatController::class, 'index'])->name('pejabat');
    // Saran
    Route::get('/saran', [App\Http\Controllers\Admin\Saran\SaranController::class, 'index'])->name('saran');
    // Report
    Route::get('/report/absensi', [App\Http\Controllers\Admin\Report\ReportController::class, 'absensi'])->name('report.absensi');
    Route::put('/report/absensi/{id}', [App\Http\Controllers\Admin\Report\ReportController::class, 'updateAbsensi'])->name('report.absensi.update');
    Route::get('/report/absensi/export', [App\Http\Controllers\Admin\Report\ReportController::class, 'exportAbsensi'])->name('report.absensi.export');
    Route::get('/report/absensi/pdf', [App\Http\Controllers\Admin\Report\ReportController::class, 'exportAbsensiPdf'])->name('report.absensi.pdf');
    Route::get('/report/perizinan', [App\Http\Controllers\Admin\Report\ReportController::class, 'perizinan'])->name('report.perizinan');
    Route::put('/report/perizinan/{id}', [App\Http\Controllers\Admin\Report\ReportController::class, 'updatePerizinan'])->name('report.perizinan.update');
    Route::get('/report/perizinan/export', [App\Http\Controllers\Admin\Report\ReportController::class, 'exportPerizinan'])->name('report.perizinan.export');
    Route::get('/report/perizinan/pdf', [App\Http\Controllers\Admin\Report\ReportController::class, 'exportPerizinanPdf'])->name('report.perizinan.pdf');
    Route::get('/report/ranpur', [App\Http\Controllers\Admin\Report\ReportController::class, 'ranpur'])->name('report.ranpur');
    Route::put('/report/ranpur/{id}', [App\Http\Controllers\Admin\Report\ReportController::class, 'updateRanpur'])->name('report.ranpur.update');
    Route::get('/report/ranpur/export', [App\Http\Controllers\Admin\Report\ReportController::class, 'exportRanpur'])->name('report.ranpur.export');
    Route::get('/report/ranpur/pdf', [App\Http\Controllers\Admin\Report\ReportController::class, 'exportRanpurPdf'])->name('report.ranpur.pdf');
    Route::get('/report/kendaraan', [App\Http\Controllers\Admin\Report\ReportController::class, 'kendaraan'])->name('report.kendaraan');
    Route::put('/report/kendaraan/{id}', [App\Http\Controllers\Admin\Report\ReportController::class, 'updateKendaraan'])->name('report.kendaraan.update');
    Route::get('/report/kendaraan/export', [App\Http\Controllers\Admin\Report\ReportController::class, 'exportKendaraan'])->name('report.kendaraan.export');
    Route::get('/report/kendaraan/pdf', [App\Http\Controllers\Admin\Report\ReportController::class, 'exportKendaraanPdf'])->name('report.kendaraan.pdf');
    Route::get('/report/gudang_senjata', [App\Http\Controllers\Admin\Report\ReportController::class, 'gudang_senjata'])->name('report.gudang_senjata');
    Route::put('/report/gudang_senjata/{id}', [App\Http\Controllers\Admin\Report\ReportController::class, 'updateGudangSenjata'])->name('report.gudang_senjata.update');
    Route::get('/report/gudang_senjata/export', [App\Http\Controllers\Admin\Report\ReportController::class, 'exportGudangSenjata'])->name('report.gudang_senjata.export');
    Route::get('/report/gudang_senjata/pdf', [App\Http\Controllers\Admin\Report\ReportController::class, 'exportGudangSenjataPdf'])->name('report.gudang_senjata.pdf');
    Route::get('/report/logistik', [App\Http\Controllers\Admin\Report\ReportController::class, 'logistik'])->name('report.logistik');
    Route::put('/report/logistik/{id}', [App\Http\Controllers\Admin\Report\ReportController::class, 'updateLogistik'])->name('report.logistik.update');
    Route::get('/report/logistik/export', [App\Http\Controllers\Admin\Report\ReportController::class, 'exportLogistik'])->name('report.logistik.export');
    Route::get('/report/logistik/pdf', [App\Http\Controllers\Admin\Report\ReportController::class, 'exportLogistikPdf'])->name('report.logistik.pdf');
    // Pengaturan
    Route::get('/pengaturan', [App\Http\Controllers\Admin\Pengaturan\PengaturanController::class, 'index'])->name('pengaturan');
});
