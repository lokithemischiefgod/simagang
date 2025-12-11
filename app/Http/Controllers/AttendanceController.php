<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Models\InternshipRequest;

class AttendanceController extends Controller
{
    protected function cekPeriodeMagang()
{
    $user = auth()->user();
    $today = now()->toDateString();

    // Cari pengajuan yang approved berdasarkan email peserta
    $req = InternshipRequest::where('email_pengaju', $user->email)
        ->where('status', 'approved')
        ->first();

    if (!$req) {
        return 'Pengajuan magang Anda belum disetujui atau tidak ditemukan.';
    }

    if ($req->tanggal_mulai && $today < $req->tanggal_mulai) {
        return 'Periode magang Anda belum dimulai.';
    }

    if ($req->tanggal_selesai && $today > $req->tanggal_selesai) {
        return 'Periode magang Anda sudah berakhir.';
    }

    return null; // aman
}

    // Dashboard peserta: lihat status hari ini + riwayat singkat
    public function index()
    {
        $user = auth()->user();
        $today = now()->toDateString();

        $todayAttendance = Attendance::where('user_id', $user->id)
            ->where('tanggal', $today)
            ->first();

        $history = Attendance::where('user_id', $user->id)
            ->orderBy('tanggal', 'desc')
            ->limit(7)
            ->get();

        return view('peserta.dashboard', compact('user', 'todayAttendance', 'history'));
    }

    // Check-in hadir
    public function checkIn()
    {
        $error = $this->cekPeriodeMagang();
        if ($error) {
            return back()->with('error', $error);
        }

        $user = auth()->user();
        $today = now()->toDateString();

        $attendance = Attendance::firstOrNew([
            'user_id' => $user->id,
            'tanggal' => $today,
        ]);

        // Kalau sudah ada status selain hadir (misal izin), jangan diubah
        if ($attendance->exists && $attendance->status !== 'hadir') {
            return back()->with('error', 'Anda sudah mengisi status hari ini sebagai ' . $attendance->status);
        }

        $attendance->status = 'hadir';
        if (!$attendance->jam_masuk) {
            $attendance->jam_masuk = now()->toTimeString();
        }
        $attendance->save();

        return back()->with('success', 'Check-in berhasil dicatat.');
    }

    // Check-out
    public function checkOut()
    {
        $error = $this->cekPeriodeMagang();
        if ($error) {
            return back()->with('error', $error);
        }

        $user = auth()->user();
        $today = now()->toDateString();

        $attendance = Attendance::where('user_id', $user->id)
            ->where('tanggal', $today)
            ->first();

        if (!$attendance) {
            return back()->with('error', 'Anda belum check-in atau mengisi status hari ini.');
        }

        if ($attendance->jam_keluar) {
            return back()->with('error', 'Anda sudah melakukan check-out hari ini.');
        }

        // ❌ TIDAK BOLEH checkout jika status = izin
        if ($attendance->status === 'izin') {
            return back()->with('error', 'Anda hari ini berstatus izin, sehingga tidak dapat melakukan check-out.');
        }

        // (opsional) kalau mau lebih ketat: hanya izinkan checkout kalau status hadir / turun_lapangan
        if (!in_array($attendance->status, ['hadir', 'turun_lapangan'])) {
            return back()->with('error', 'Status absensi Anda hari ini tidak valid untuk check-out.');
        }

        $attendance->jam_keluar = now()->toTimeString();
        $attendance->save();

        return back()->with('success', 'Check-out berhasil dicatat.');
    }

    // Izin
    public function izin(Request $request)
    {
        $error = $this->cekPeriodeMagang();
        if ($error) {
            return back()->with('error', $error);
        }

        $request->validate([
            'keterangan' => 'required|string',
        ]);

        $user = auth()->user();
        $today = now()->toDateString();

        $existing = Attendance::where('user_id', $user->id)
            ->where('tanggal', $today)
            ->first();

        if ($existing) {
            return back()->with('error', 'Anda sudah mengisi absensi hari ini.');
        }

        Attendance::create([
            'user_id'   => $user->id,
            'tanggal'   => $today,
            'status'    => 'izin',
            'keterangan'=> $request->keterangan,
        ]);

        return back()->with('success', 'Status izin berhasil dicatat.');
    }

    // Turun lapangan
    public function turunLapangan(Request $request)
    {
        $error = $this->cekPeriodeMagang();
        if ($error) {
            return back()->with('error', $error);
        }

        $request->validate([
            'keterangan' => 'required|string',
        ]);

        $user = auth()->user();
        $today = now()->toDateString();

        // Ambil absensi hari ini
        $attendance = Attendance::where('user_id', $user->id)
            ->where('tanggal', $today)
            ->first();

        // ❌ Harus sudah check-in dulu
        if (!$attendance) {
            return back()->with('error', 'Anda harus check-in terlebih dahulu sebelum menandai turun lapangan.');
        }

        // ❌ Tidak boleh kalau sudah checkout
        if ($attendance->jam_keluar) {
            return back()->with('error', 'Anda sudah melakukan check-out hari ini, tidak dapat menandai turun lapangan.');
        }

        // ❌ Tidak boleh kalau hari ini izin
        if ($attendance->status === 'izin') {
            return back()->with('error', 'Anda hari ini berstatus izin, tidak dapat menandai turun lapangan.');
        }

        // ✅ Hanya izinkan dari status "hadir" atau sudah "turun_lapangan"
        if (!in_array($attendance->status, ['hadir', 'turun_lapangan'])) {
            return back()->with('error', 'Status absensi Anda hari ini tidak valid untuk turun lapangan.');
        }

        // Set status ke turun_lapangan, tetap pertahankan jam_masuk
        $attendance->status = 'turun_lapangan';

        // Kalau belum ada jam_masuk (jaga-jaga), isi sekarang
        if (!$attendance->jam_masuk) {
            $attendance->jam_masuk = now()->toTimeString();
        }

        // Update keterangan (lokasi / agenda lapangan)
        $attendance->keterangan = $request->keterangan;

        $attendance->save();

        return back()->with('success', 'Status turun lapangan berhasil dicatat.');
    }

}
