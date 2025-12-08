<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;

class AdminAttendanceController extends Controller
{
    public function index(Request $request)
    {
        // Ambil filter dari request
        $tanggal = $request->input('tanggal', now()->toDateString());
        $status  = $request->input('status', '');
        $q       = $request->input('q', '');

        // Query dasar: join dengan user
        $query = Attendance::with('user')
            ->where('tanggal', $tanggal)
            ->orderBy('jam_masuk', 'asc');

        // Filter status jika diisi
        if ($status && in_array($status, ['hadir', 'izin', 'turun_lapangan'])) {
            $query->where('status', $status);
        }

        // Filter pencarian nama/email peserta
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

    $query = Attendance::with('user')
        ->where('tanggal', $tanggal)
        ->orderBy('jam_masuk', 'asc');

    if ($status && in_array($status, ['hadir', 'izin', 'turun_lapangan'])) {
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

        // Header kolom
        fputcsv($handle, [
            'Nama Peserta',
            'Email',
            'Tanggal',
            'Status',
            'Jam Masuk',
            'Jam Keluar',
            'Keterangan',
        ]);

        foreach ($items as $row) {
            fputcsv($handle, [
                $row->user->name ?? '',
                $row->user->email ?? '',
                $row->tanggal,
                $row->status,
                $row->jam_masuk,
                $row->jam_keluar,
                $row->keterangan,
            ]);
        }

        fclose($handle);
    };

    return response()->streamDownload($callback, $fileName, $headers);
}

}
