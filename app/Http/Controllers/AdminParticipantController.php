<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Attendance;
use App\Models\InternshipRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminParticipantController extends Controller
{
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

        $pengajuanApproved = InternshipRequest::where('status', 'approved')->get();

        $pengajuanPerEmail = $pengajuanApproved->keyBy('email_pengaju');

        if ($statusFilter !== 'all') {
            $peserta = $peserta->filter(function ($p) use ($pengajuanPerEmail, $statusFilter, $today) {
                $req = $pengajuanPerEmail[$p->email] ?? null;

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

    public function show($id)
    {
        $user = User::where('role', 'peserta')
            ->where('id', $id)
            ->firstOrFail();

        $pengajuan = InternshipRequest::where('email_pengaju', $user->email)
            ->where('status', 'approved')
            ->first();

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

    $users = User::where('role', 'peserta')
        ->whereIn('id', $request->ids)
        ->get();

    if ($users->isEmpty()) {
        return back()->with('error', 'Tidak ada peserta yang valid.');
    }

    foreach ($users as $user) {

        Attendance::where('user_id', $user->id)->delete();

        \App\Models\WorkLog::where('user_id', $user->id)->delete();

        InternshipRequest::where('email_pengaju', $user->email)->delete();

        $user->delete();
    }

    return back()->with('success', 'Peserta terpilih berhasil dihapus beserta seluruh data terkait.');
}

}
