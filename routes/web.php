<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InternshipRequestController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AdminAttendanceController;
use App\Http\Controllers\AdminParticipantController;
use App\Http\Controllers\SuperadminUserController;
use App\Http\Controllers\WorkLogController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/pengajuan', [InternshipRequestController::class, 'create'])
    ->name('pengajuan.create');

Route::post('/pengajuan', [InternshipRequestController::class, 'store'])
    ->name('pengajuan.store');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'force.password'])->name('dashboard');

Route::middleware(['auth', 'force.password'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'force.password', 'admin'])->group(function() {
    Route::get('/admin/pengajuan', [InternshipRequestController::class, 'index'])
        ->name('admin.pengajuan.index');

    Route::post('/admin/pengajuan/{id}/status', [InternshipRequestController::class, 'updateStatus'])
        ->name('admin.pengajuan.updateStatus');

    Route::get('/admin/absensi', [AdminAttendanceController::class, 'index'])
        ->name('admin.absensi.index');

    Route::get('/admin/absensi/export', [AdminAttendanceController::class, 'exportCsv'])
        ->name('admin.absensi.export');

    Route::get('/admin/peserta', [AdminParticipantController::class, 'index'])
        ->name('admin.peserta.index');

    Route::get('/admin/peserta/{id}', [AdminParticipantController::class, 'show'])
        ->name('admin.peserta.show');

    Route::get('/admin/peserta/{id}/export-absensi', [AdminParticipantController::class, 'exportAbsensi'])
        ->name('admin.peserta.exportAbsensi');

    Route::get('/admin/absensi/{attendance}/aktivitas', [AdminAttendanceController::class, 'aktivitas'])
        ->name('admin.absensi.aktivitas');

    Route::delete('/admin/peserta/bulk-delete', [AdminParticipantController::class, 'bulkDestroy'])
        ->name('admin.peserta.bulkDestroy');

    Route::delete('/admin/pengajuan/{id}', [InternshipRequestController::class, 'destroy'])
        ->name('admin.pengajuan.destroy');

});

Route::middleware(['auth', 'force.password', 'peserta'])->group(function () {
    Route::get('/peserta/dashboard', [AttendanceController::class, 'index'])->name('peserta.dashboard');
    Route::post('/peserta/absensi/check-in', [AttendanceController::class, 'checkIn'])->name('peserta.checkin');
    Route::post('/peserta/absensi/check-out', [AttendanceController::class, 'checkOut'])->name('peserta.checkout');
    Route::post('/peserta/absensi/izin', [AttendanceController::class, 'izin'])->name('peserta.izin');
    Route::post('/peserta/absensi/turun-lapangan', [AttendanceController::class, 'turunLapangan'])->name('peserta.turunlapangan');
    Route::post('/peserta/absensi/kembali-kantor', [AttendanceController::class, 'kembaliKantor'])->name('peserta.kembaliKantor');
    Route::post('/peserta/work-log', [WorkLogController::class, 'store'])->name('peserta.worklog.store');
    Route::post('/peserta/work-log/{id}/finish', [WorkLogController::class, 'finish'])->name('peserta.worklog.finish');
    Route::get('/peserta/work-log', [WorkLogController::class, 'index'])->name('peserta.worklog.index');
    


});

Route::middleware(['auth', 'force.password', 'superadmin'])->group(function () {
    Route::get('/superadmin/admins', [SuperadminUserController::class, 'index'])
        ->name('superadmin.admins.index');

    Route::get('/superadmin/admins/create', [SuperadminUserController::class, 'create'])
        ->name('superadmin.admins.create');

    Route::post('/superadmin/admins', [SuperadminUserController::class, 'store'])
        ->name('superadmin.admins.store');

    Route::delete('/superadmin/admins/{id}', [SuperadminUserController::class, 'destroy'])
        ->name('superadmin.admins.destroy');
    
    Route::patch('/superadmin/admins/{id}/promote',[SuperadminUserController::class, 'promoteToSuperadmin'])
        ->name('superadmin.admins.promote');

});


require __DIR__.'/auth.php';
