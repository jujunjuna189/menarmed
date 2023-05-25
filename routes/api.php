<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Auth
Route::post('/login', [App\Http\Controllers\Api\Auth\AuthController::class, 'login']);
Route::post('/register', [App\Http\Controllers\Api\Auth\AuthController::class, 'register']);
// Profile
Route::post('/profile/update', [App\Http\Controllers\Api\Pengguna\ProfileController::class, 'update']);
// Pengguna
Route::post('/pengguna/show', [App\Http\Controllers\Api\Pengguna\PenggunaController::class, 'show']);
// Pengguna Kemampuan
Route::post('/pengguna/kemampuan/show', [App\Http\Controllers\Api\Pengguna\KemampuanController::class, 'show']);
Route::post('/pengguna/kemampuan/store', [App\Http\Controllers\Api\Pengguna\KemampuanController::class, 'store']);
// Absensi
Route::post('/absensi/show', [App\Http\Controllers\Api\Absensi\AbsensiController::class, 'show']);
Route::post('/absensi/show_today_presence', [App\Http\Controllers\Api\Absensi\AbsensiController::class, 'showTodayPresence']);
Route::post('/absensi/store', [App\Http\Controllers\Api\Absensi\AbsensiController::class, 'store']);
Route::post('/absensi/update', [App\Http\Controllers\Api\Absensi\AbsensiController::class, 'update']);
Route::post('/absensi/delete', [App\Http\Controllers\Api\Absensi\AbsensiController::class, 'delete']);
// Perizinan
Route::post('/perizinan/show', [App\Http\Controllers\Api\Perizinan\PerizinanController::class, 'show']);
Route::post('/perizinan/store', [App\Http\Controllers\Api\Perizinan\PerizinanController::class, 'store']);
// Perizinan Ranpur
Route::post('/perizinan/ranpur/show', [App\Http\Controllers\Api\Perizinan\PerizinanRanpurController::class, 'show']);
Route::post('/perizinan/ranpur/store', [App\Http\Controllers\Api\Perizinan\PerizinanRanpurController::class, 'store']);
// Perizinan kendaraan
Route::post('/perizinan/kendaraan/show', [App\Http\Controllers\Api\Perizinan\PerizinanKendaraanController::class, 'show']);
Route::post('/perizinan/kendaraan/store', [App\Http\Controllers\Api\Perizinan\PerizinanKendaraanController::class, 'store']);
// Gudang Senjata
Route::post('/gudang_senjata/store', [App\Http\Controllers\Api\GudangSenjata\GudangSenjataController::class, 'store']);
// Logistik
Route::post('/logistik/store', [App\Http\Controllers\Api\Logistik\LogistikController::class, 'store']);
// Staff
Route::post('/staff/show', [App\Http\Controllers\Api\Staff\StaffController::class, 'show']);
//Pejabat armed
Route::post('/armed/show', [App\Http\Controllers\Api\Pejabat\ArmedController::class, 'show']);
Route::post('/armed/store', [App\Http\Controllers\Api\Pejabat\ArmedController::class, 'store']);
Route::post('/armed/update', [App\Http\Controllers\Api\Pejabat\ArmedController::class, 'update']);
Route::post('/armed/delete', [App\Http\Controllers\Api\Pejabat\ArmedController::class, 'delete']);
//Pejabat kostrad
Route::post('/kostrad/show', [App\Http\Controllers\Api\Pejabat\KostradController::class, 'show']);
Route::post('/kostrad/store', [App\Http\Controllers\Api\Pejabat\KostradController::class, 'store']);
Route::post('/kostrad/update', [App\Http\Controllers\Api\Pejabat\KostradController::class, 'update']);
Route::post('/kostrad/delete', [App\Http\Controllers\Api\Pejabat\KostradController::class, 'delete']);
// Event
Route::post('/event/show', [App\Http\Controllers\Api\Event\EventController::class, 'show']);
Route::post('/event/store', [App\Http\Controllers\Api\Event\EventController::class, 'store']);
Route::post('/event/delete', [App\Http\Controllers\Api\Event\EventController::class, 'delete']);
// Artikel
Route::post('/artikel/show', [App\Http\Controllers\Api\Artikel\ArtikelController::class, 'show']);
Route::post('/artikel/store', [App\Http\Controllers\Api\Artikel\ArtikelController::class, 'store']);
// E-Learning
Route::post('/e-learning/show', [App\Http\Controllers\Api\ELearning\ELearningController::class, 'show']);
Route::post('/e-learning/store', [App\Http\Controllers\Api\ELearning\ELearningController::class, 'store']);
// Bujuk
Route::post('/bujuk/show', [App\Http\Controllers\Api\Bujuk\BujukController::class, 'show']);
//Rekap Data
Route::post('/report/absensi', [App\Http\Controllers\Api\Report\ReportController::class, 'absensi']);
Route::post('/report/perizinan', [App\Http\Controllers\Api\Report\ReportController::class, 'perizinan']);
Route::post('/report/ranpur', [App\Http\Controllers\Api\Report\ReportController::class, 'ranpur']);
Route::post('/report/kendaraan', [App\Http\Controllers\Api\Report\ReportController::class, 'kendaraan']);
Route::post('/report/gudang_senjata', [App\Http\Controllers\Api\Report\ReportController::class, 'gudang_senjata']);
Route::post('/report/logistik', [App\Http\Controllers\Api\Report\ReportController::class, 'logistik']);
// Pengaturan
Route::post('/setting/slider/store', [App\Http\Controllers\Api\Pengaturan\SliderController::class, 'store']);
Route::post('/setting/slider/delete', [App\Http\Controllers\Api\Pengaturan\SliderController::class, 'delete']);
// Fitur
Route::post('/feature/slider/show', [App\Http\Controllers\Api\Pengaturan\SliderController::class, 'show']);
