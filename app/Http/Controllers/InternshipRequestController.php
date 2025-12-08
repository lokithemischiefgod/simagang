<?php

namespace App\Http\Controllers;

use App\Models\InternshipRequest;
use Illuminate\Http\Request;

class InternshipRequestController extends Controller
{
    // Tampilkan form pengajuan untuk umum
    public function create()
    {
        return view('pengajuan.create');
    }

    // Simpan pengajuan
    public function store(Request $request)
    {
        $request->validate([
            'nama_pengaju'    => 'required',
            'email_pengaju'   => 'required|email',
            'tipe'            => 'required|in:sekolah,universitas,mandiri',
            'surat_pengantar' => 'nullable|file|mimes:pdf',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        $path = null;
        if ($request->hasFile('surat_pengantar')) {
            $path = $request->file('surat_pengantar')->store('surat', 'public');
        }

        InternshipRequest::create([
            'nama_pengaju'    => $request->nama_pengaju,
            'email_pengaju'   => $request->email_pengaju,
            'tipe'            => $request->tipe,
            'instansi'        => $request->instansi,
            'surat_pengantar' => $path,
            'tanggal_mulai'   => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
        ]);

        return redirect()->back()->with('success', 'Pengajuan berhasil dikirim!');
    }

    // ✅ TAMPILKAN DAFTAR PENGAJUAN (HALAMAN ADMIN)
    public function index()
    {
        // ambil semua pengajuan, urutkan terbaru di atas
        $items = InternshipRequest::orderBy('created_at', 'desc')->get();

        return view('pengajuan.index', compact('items'));
    }

    // ✅ UBAH STATUS PENGAJUAN (APPROVE / REJECT)
    public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:approved,rejected',
        'alasan_penolakan' => 'nullable|string',
    ]);

    $item = InternshipRequest::findOrFail($id);

    if ($item->status !== 'pending') {
        return back()->with('error', 'Status pengajuan sudah final dan tidak dapat diubah lagi.');
    }
    
    $dataUpdate = [
        'status' => $request->status,
    ];

    if ($request->status === 'rejected') {
        $dataUpdate['alasan_penolakan'] = $request->alasan_penolakan;
    } else {
        $dataUpdate['alasan_penolakan'] = null;
    }

    $item->update($dataUpdate);

    // ⭐ Buat akun peserta otomatis jika disetujui
    if ($request->status === 'approved') {

        $existingUser = \App\Models\User::where('email', $item->email_pengaju)->first();

        if (!$existingUser) {
            \App\Models\User::create([
                'name' => $item->nama_pengaju,
                'email' => $item->email_pengaju,
                'password' => bcrypt('password123'),  // default password
                'role' => 'peserta',
            ]);
        }
    }

    return back()->with('success', 'Status pengajuan berhasil diupdate.');
}

}
    