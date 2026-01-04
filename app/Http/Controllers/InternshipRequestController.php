<?php

namespace App\Http\Controllers;

use App\Models\InternshipRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\PengajuanStatusMail;
use App\Models\User;
use App\Mail\PengajuanBaruMail;

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
            'nama_pengaju'    => 'required|string|max:255',
            'email_pengaju'   => 'required|email|max:255',
            'no_wa'           => ['required', 'string', 'max:20', 'regex:/^(\+62|62|08)[0-9]{8,13}$/'],
            'tipe'            => 'required|in:sekolah,universitas,mandiri',
            'instansi'        => 'nullable|string|max:255',
            'surat_pengantar' => 'nullable|file|mimes:pdf|max:2048',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ], [
            'no_wa.regex' => 'Nomor WhatsApp harus diawali 08, 62, atau +62 dan hanya berisi angka.',
        ]);

        // ✅ Normalisasi nomor WA: simpan sebagai 62xxxxxxxxxx
        $raw = $request->input('no_wa');
        $digits = preg_replace('/\D+/', '', $raw); // buang selain angka

        // 08xxxx -> 62xxxx
        if (str_starts_with($digits, '08')) {
            $digits = '62' . substr($digits, 1);
        }

        // kalau user input +62xxxx, preg_replace sudah jadi 62xxxx (aman)
        // pastikan tetap 62...
        if (!str_starts_with($digits, '62')) {
            return back()
                ->withErrors(['no_wa' => 'Nomor WhatsApp harus menggunakan format 08 / 62 / +62.'])
                ->withInput();
        }

        $path = null;
        if ($request->hasFile('surat_pengantar')) {
            $path = $request->file('surat_pengantar')->store('surat_pengantar', 'public');
        }

        $pengajuan = InternshipRequest::create([
            'nama_pengaju'    => $request->nama_pengaju,
            'email_pengaju'   => $request->email_pengaju,
            'no_wa'           => $digits, // ✅ WA tersimpan
            'tipe'            => $request->tipe,
            'instansi'        => $request->instansi,
            'surat_pengantar' => $path,
            'tanggal_mulai'   => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'status'          => 'pending',
        ]);

        // ✅ KIRIM EMAIL NOTIFIKASI KE ADMIN & SUPERADMIN
        try {
            $admins = User::whereIn('role', ['admin', 'superadmin'])->get();

            foreach ($admins as $admin) {
                Mail::to($admin->email)->send(new PengajuanBaruMail($pengajuan));
            }
        } catch (\Exception $e) {
            // logger()->error('Gagal kirim email pengajuan baru: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Pengajuan magang berhasil dikirim. Silakan menunggu proses verifikasi.');
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

        // Hanya boleh ubah kalau masih pending
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

        // Update status & alasan di database
        $item->update($dataUpdate);

        // Jika status disetujui, buat akun peserta otomatis (seperti sebelumnya)
        if ($request->status === 'approved') {

            $existingUser = \App\Models\User::where('email', $item->email_pengaju)->first();

            if (!$existingUser) {
                \App\Models\User::create([
                    'name'                 => $item->nama_pengaju,
                    'email'                => $item->email_pengaju,
                    'password'             => bcrypt('password123'),  // default password
                    'role'                 => 'peserta',
                    'must_change_password' => true,                   // kalau pakai mekanisme force change password
                ]);
            }
        }

        // ✅ Kirim email pemberitahuan ke pengaju
        try {
            Mail::to($item->email_pengaju)->send(new PengajuanStatusMail($item));
        } catch (\Exception $e) {
            // Kalau gagal kirim email, sistem tetap jalan, tapi kita bisa info admin
            // dd($e->getMessage()); // untuk debug kalau perlu
        }

        return back()->with('success', 'Status pengajuan berhasil diupdate dan email notifikasi telah dikirim (jika konfigurasi email sudah benar).');
    }


}
    