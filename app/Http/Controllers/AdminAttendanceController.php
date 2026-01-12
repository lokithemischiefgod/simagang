<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;

class AdminAttendanceController extends Controller
{
    public function index(Request $request)
    {
        $tanggal = $request->input('tanggal', now()->toDateString());
        $status  = $request->input('status', '');
        $q       = $request->input('q', '');

        $query = Attendance::with(['user', 'latestWorkLog']) // <—
        ->where('tanggal', $tanggal)
        ->orderBy('jam_masuk', 'asc');

        // ✅ status baru: standby_kantor
        $allowedStatus = ['standby_kantor', 'izin', 'turun_lapangan', 'checkout']; // checkout opsional
        if ($status && in_array($status, $allowedStatus, true)) {
            $query->where('status', $status);
        }

        if ($q) {
            $query->whereHas('user', function ($sub) use ($q) {
                $sub->where('name', 'like', '%'.$q.'%')
                    ->orWhere('email', 'like', '%'.$q.'%');
            });
        }

        $items = $query->get();

        return view('admin.absensi.index', compact('items', 'tanggal', 'status', 'q'));
    }

    public function exportCsv(Request $request)
    {
        $tanggal = $request->input('tanggal', now()->toDateString());
        $status  = $request->input('status', '');
        $q       = $request->input('q', '');

        $query = Attendance::with(['user', 'latestWorkLog'])
        ->where('tanggal', $tanggal)
        ->orderBy('jam_masuk', 'asc');


        $allowedStatus = ['standby_kantor', 'izin', 'turun_lapangan', 'checkout'];
        if ($status && in_array($status, $allowedStatus, true)) {
            $query->where('status', $status);
        }

        if ($q) {
            $query->whereHas('user', function ($sub) use ($q) {
                $sub->where('name', 'like', '%'.$q.'%')
                    ->orWhere('email', 'like', '%'.$q.'%');
            });
        }

        $items = $query->get();

        $fileName = 'absensi_' . $tanggal . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$fileName.'"',
        ];

        $callback = function () use ($items) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Nama Peserta',
                'Email',
                'Tanggal',
                'Status',
                'Jam Masuk',
                'Jam Keluar',
                'Keterangan',
                'Aktivitas Terakhir',
                'Waktu Aktivitas',
            ]);

            foreach ($items as $row) {
                $lastLog = $row->workLogs->first(); // karena limit 1
                fputcsv($handle, [
                    $row->user->name ?? '',
                    $row->user->email ?? '',
                    $row->tanggal,
                    $row->status,
                    $row->jam_masuk,
                    $row->jam_keluar,
                    $row->keterangan,
                    $lastLog->aktivitas ?? '',
                    $lastLog?->created_at?->format('H:i:s') ?? '',
                ]);
            }

            fclose($handle);
        };

        return response()->streamDownload($callback, $fileName, $headers);
    }

    public function aktivitas(Attendance $attendance)
    {
        $attendance->load(['user', 'workLogs' => fn($q) => $q->orderBy('created_at', 'desc')]);
        return view('admin.absensi.aktivitas', compact('attendance'));
    }
}
