<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\WorkLog;
use Illuminate\Http\Request;
use App\Models\InternshipRequest;

class AttendanceController extends Controller
{
    protected function cekPeriodeMagang()
    {
        $user = auth()->user();
        $today = now()->toDateString();

        $req = InternshipRequest::where('email_pengaju', $user->email)
            ->where('status', 'approved')
            ->first();

        if (!$req) return 'Pengajuan magang Anda belum disetujui atau tidak ditemukan.';
        if ($req->tanggal_mulai && $today < $req->tanggal_mulai) return 'Periode magang Anda belum dimulai.';
        if ($req->tanggal_selesai && $today > $req->tanggal_selesai) return 'Periode magang Anda sudah berakhir.';

        return null;
    }

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

    public function checkIn()
    {
        $error = $this->cekPeriodeMagang();
        if ($error) return back()->with('error', $error);

        $user = auth()->user();
        $today = now()->toDateString();

        $existing = Attendance::where('user_id', $user->id)
            ->where('tanggal', $today)
            ->first();

        if ($existing) {
            if ($existing->status === 'izin') return back()->with('error', 'Anda hari ini berstatus izin, tidak dapat check-in.');
            if ($existing->status === 'checkout' || $existing->jam_keluar) return back()->with('error', 'Anda sudah check-out hari ini.');
            return back()->with('error', 'Anda sudah check-in hari ini.');
        }

        Attendance::create([
            'user_id'   => $user->id,
            'tanggal'   => $today,
            'status'    => 'standby_kantor',
            'jam_masuk' => now()->toTimeString(),
            'keterangan'=> null,
        ]);

        return back()->with('success', 'Check-in berhasil. Status Anda sekarang: standby kantor.');
    }

    public function turunLapangan(Request $request)
    {
        $error = $this->cekPeriodeMagang();
        if ($error) return back()->with('error', $error);

        $request->validate([
            'keterangan' => 'required|string',
        ]);

        $user = auth()->user();
        $today = now()->toDateString();

        $attendance = Attendance::where('user_id', $user->id)
            ->where('tanggal', $today)
            ->first();

        if (!$attendance) return back()->with('error', 'Anda harus check-in terlebih dahulu sebelum menandai turun lapangan.');
        if ($attendance->status === 'izin') return back()->with('error', 'Anda hari ini berstatus izin, tidak dapat menandai turun lapangan.');
        if ($attendance->status === 'checkout' || $attendance->jam_keluar) return back()->with('error', 'Anda sudah melakukan check-out hari ini.');

        if ($attendance->status !== 'standby_kantor') {
            return back()->with('error', 'Turun lapangan hanya bisa dilakukan saat status Anda standby kantor.');
        }

        $attendance->status = 'turun_lapangan';
        $attendance->keterangan = $request->keterangan;
        $attendance->save();

        WorkLog::create([
            'user_id'       => $user->id,
            'attendance_id' => $attendance->id,
            'aktivitas'     => 'Turun lapangan: ' . $request->keterangan,
            'jam_mulai'     => now()->toTimeString(),
            'jam_selesai'   => null,
        ]);

        return back()->with('success', 'Status turun lapangan berhasil dicatat.');
    }

    public function kembaliKantor()
    {
        $error = $this->cekPeriodeMagang();
        if ($error) return back()->with('error', $error);

        $user = auth()->user();
        $today = now()->toDateString();

        $attendance = Attendance::where('user_id', $user->id)
            ->where('tanggal', $today)
            ->first();

        if (!$attendance) return back()->with('error', 'Anda belum check-in hari ini.');
        if ($attendance->status === 'checkout' || $attendance->jam_keluar) return back()->with('error', 'Anda sudah melakukan check-out hari ini.');
        if ($attendance->status !== 'turun_lapangan') return back()->with('error', 'Anda tidak sedang berstatus turun lapangan.');

        $openLapanganLog = WorkLog::where('attendance_id', $attendance->id)
            ->whereNull('jam_selesai')
            ->where('aktivitas', 'like', 'Turun lapangan:%')
            ->orderByDesc('jam_mulai')
            ->first();

        if ($openLapanganLog) {
            $openLapanganLog->update([
                'jam_selesai' => now()->toTimeString(),
            ]);
        }

        $attendance->status = 'standby_kantor';
        $attendance->keterangan = null;
        $attendance->save();

        return back()->with('success', 'Berhasil kembali ke kantor. Status Anda sekarang: standby kantor.');
    }

    public function checkOut()
    {
        $error = $this->cekPeriodeMagang();
        if ($error) return back()->with('error', $error);

        $user = auth()->user();
        $today = now()->toDateString();

        $attendance = Attendance::where('user_id', $user->id)
            ->where('tanggal', $today)
            ->first();

        if (!$attendance) return back()->with('error', 'Anda belum check-in atau mengisi status hari ini.');
        if ($attendance->jam_keluar || $attendance->status === 'checkout') return back()->with('error', 'Anda sudah melakukan check-out hari ini.');
        if ($attendance->status === 'izin') return back()->with('error', 'Anda hari ini berstatus izin, sehingga tidak dapat melakukan check-out.');

        if (!in_array($attendance->status, ['standby_kantor', 'turun_lapangan'], true)) {
            return back()->with('error', 'Status absensi Anda hari ini tidak valid untuk check-out.');
        }

        $attendance->jam_keluar = now()->toTimeString();
        $attendance->status = 'checkout';
        $attendance->save();

        return back()->with('success', 'Check-out berhasil dicatat.');
    }

    public function izin(Request $request)
    {
        $error = $this->cekPeriodeMagang();
        if ($error) return back()->with('error', $error);

        $request->validate([
            'keterangan' => 'required|string',
        ]);

        $user = auth()->user();
        $today = now()->toDateString();

        $existing = Attendance::where('user_id', $user->id)
            ->where('tanggal', $today)
            ->first();

        if ($existing) return back()->with('error', 'Anda sudah mengisi absensi hari ini.');

        Attendance::create([
            'user_id'   => $user->id,
            'tanggal'   => $today,
            'status'    => 'izin',
            'keterangan'=> $request->keterangan,
        ]);

        return back()->with('success', 'Status izin berhasil dicatat.');
    }
}
