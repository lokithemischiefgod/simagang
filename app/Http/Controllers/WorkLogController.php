<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\WorkLog;
use Illuminate\Http\Request;

class WorkLogController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $tanggal = $request->input('tanggal', now()->toDateString());

        $logs = WorkLog::with('attendance')
            ->where('user_id', $user->id)
            ->whereHas('attendance', function ($q) use ($tanggal) {
                $q->where('tanggal', $tanggal);
            })
            ->orderByDesc('jam_mulai')
            ->paginate(10)
            ->appends($request->only('tanggal'));

        return view('peserta.worklogs.index', compact('logs', 'tanggal'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'aktivitas' => 'required|string',
        ]);

        $user = auth()->user();
        $today = now()->toDateString();

        $attendance = Attendance::where('user_id', $user->id)
            ->where('tanggal', $today)
            ->first();

        if (!$attendance || $attendance->status !== 'standby_kantor') {
            return back()->with('error', 'Aktivitas hanya bisa dicatat saat status standby kantor.');
        }

        WorkLog::create([
            'user_id' => $user->id,
            'attendance_id' => $attendance->id,
            'aktivitas' => $request->aktivitas,
            'jam_mulai' => now()->toTimeString(),
        ]);

        return back()->with('success', 'Aktivitas kerja berhasil dicatat.');
    }

    public function finish($id)
    {
        $user = auth()->user();

        $log = WorkLog::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        if ($log->jam_selesai) {
            return back()->with('error', 'Aktivitas ini sudah selesai.');
        }

        $log->update([
            'jam_selesai' => now()->toTimeString(),
        ]);

        return back()->with('success', 'Aktivitas kerja diselesaikan.');
    }
}
