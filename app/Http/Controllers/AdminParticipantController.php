<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Attendance;
use App\Models\InternshipRequest;
use Illuminate\Http\Request;

class AdminParticipantController extends Controller
{
    // Daftar semua peserta magang (role = peserta)
    // Daftar semua peserta magang (role = peserta)
    public function index(Request $request)
    {
        $q = $request->input('q', '');
        $statusFilter = $request->input('status', 'all'); // all | active | inactive
        $today = now()->toDateString();

        $query = User::where('role', 'peserta');

        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', '%'.$q.'%')
                    ->orWhere('email', 'like', '%'.$q.'%');
            });
        }

        $peserta = $query->orderBy('name', 'asc')->get();

        // Ambil data pengajuan approved untuk periode magang, via email
        $pengajuanApproved = InternshipRequest::where('status', 'approved')->get();

        // Key by email
        $pengajuanPerEmail = $pengajuanApproved->keyBy('email_pengaju');

        // ✅ Filter aktif / tidak aktif berbasis tanggal_selesai
        if ($statusFilter !== 'all') {
            $peserta = $peserta->filter(function ($p) use ($pengajuanPerEmail, $statusFilter, $today) {
                $req = $pengajuanPerEmail[$p->email] ?? null;

                // kalau tidak ada pengajuan approved, anggap "tidak bisa ditentukan"
                // biar aman: masukkan ke "active" saja? atau exclude? -> aku exclude dari active/inactive
                if (!$req || !$req->tanggal_selesai) {
                    return false;
                }

                $isInactive = $req->tanggal_selesai < $today;

                if ($statusFilter === 'inactive') return $isInactive;
                if ($statusFilter === 'active')   return !$isInactive;

                return true;
            })->values();
        }

        return view('admin.peserta.index', compact('peserta', 'pengajuanPerEmail', 'q', 'statusFilter'));
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

public function bulkDestroy(Request $request)
{
    $request->validate([
        'ids' => 'required|array',
        'ids.*' => 'integer',
    ]);

    $today = now()->toDateString();

    // Ambil peserta yang dipilih (pastikan role peserta)
    $users = User::where('role', 'peserta')
        ->whereIn('id', $request->ids)
        ->get();

    if ($users->isEmpty()) {
        return back()->with('error', 'Tidak ada peserta yang valid untuk dihapus.');
    }

    // Ambil pengajuan approved untuk email-email tersebut
    $emails = $users->pluck('email')->values()->all();

    $reqByEmail = InternshipRequest::where('status', 'approved')
        ->whereIn('email_pengaju', $emails)
        ->get()
        ->keyBy('email_pengaju');

    // Hanya boleh hapus yang benar-benar tidak aktif (tanggal_selesai < hari ini)
    $deletableIds = [];
    $skipped = 0;

    foreach ($users as $u) {
        $req = $reqByEmail[$u->email] ?? null;

        if (!$req || !$req->tanggal_selesai) {
            $skipped++;
            continue;
        }

        $isInactive = $req->tanggal_selesai < $today;
        if (!$isInactive) {
            $skipped++;
            continue;
        }

        $deletableIds[] = $u->id;
    }

    if (empty($deletableIds)) {
        return back()->with('error', 'Tidak ada peserta “Tidak Aktif” yang bisa dihapus dari pilihan tersebut.');
    }

    // Hapus (cascade akan ikut hapus attendance/work_logs jika FK kamu benar)
    $deletedCount = User::whereIn('id', $deletableIds)->delete();

    $msg = "Berhasil menghapus {$deletedCount} peserta tidak aktif.";
    if ($skipped > 0) {
        $msg .= " ({$skipped} peserta dilewati karena masih aktif / tidak punya periode selesai).";
    }

    return back()->with('success', $msg);
}


}
