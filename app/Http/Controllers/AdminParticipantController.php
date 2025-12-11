<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Attendance;
use App\Models\InternshipRequest;
use Illuminate\Http\Request;

class AdminParticipantController extends Controller
{
    // Daftar semua peserta magang (role = peserta)
    public function index(Request $request)
    {
        $q = $request->input('q', '');

        $query = User::where('role', 'peserta');

        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', '%'.$q.'%')
                    ->orWhere('email', 'like', '%'.$q.'%');
            });
        }

        $peserta = $query->orderBy('name', 'asc')->get();

        // Ambil data pengajuan untuk mendapatkan periode magang, via email
        $pengajuanPerEmail = InternshipRequest::where('status', 'approved')
            ->get()
            ->keyBy('email_pengaju');

        return view('admin.peserta.index', compact('peserta', 'pengajuanPerEmail', 'q'));
    }

    // Detail satu peserta + periode + riwayat absensi
    public function show($id)
    {
        $user = User::where('role', 'peserta')
            ->where('id', $id)
            ->firstOrFail();


        // Cari pengajuan yang disetujui berdasarkan email peserta
        $pengajuan = InternshipRequest::where('email_pengaju', $user->email)
            ->where('status', 'approved')
            ->first();

        // Riwayat absensi peserta
        $absensi = Attendance::where('user_id', $user->id)
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('admin.peserta.show', compact('user', 'pengajuan', 'absensi'));
    }

    public function exportAbsensi($id)
{
    $user = User::where('role', 'peserta')
        ->where('id', $id)
        ->firstOrFail();

    $absensi = Attendance::where('user_id', $user->id)
        ->orderBy('tanggal', 'asc')
        ->get();

    $fileName = 'absensi_' . str_replace(' ', '_', strtolower($user->name)) . '.csv';

    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="'.$fileName.'"',
    ];

    $callback = function () use ($user, $absensi) {
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

        foreach ($absensi as $row) {
            fputcsv($handle, [
                $user->name,
                $user->email,
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
