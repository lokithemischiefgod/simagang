<?php

namespace App\Http\Controllers;

use App\Models\InternshipRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\PengajuanStatusMail;
use App\Models\User;
use App\Mail\PengajuanBaruMail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class InternshipRequestController extends Controller
{
    public function create()
    {
        return view('pengajuan.create');
    }

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

        $raw = $request->input('no_wa');
        $digits = preg_replace('/\D+/', '', $raw);

        if (str_starts_with($digits, '08')) {
            $digits = '62' . substr($digits, 1);
        }

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
            'no_wa'           => $digits,
            'tipe'            => $request->tipe,
            'instansi'        => $request->instansi,
            'surat_pengantar' => $path,
            'tanggal_mulai'   => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'status'          => 'pending',
        ]);

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


    public function index()
    {
            $items = InternshipRequest::orderBy('created_at', 'desc')->get();

        return view('pengajuan.index', compact('items'));
    }

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

        $item->update([
            'status' => $request->status,
            'alasan_penolakan' => $request->status === 'rejected'
                ? $request->alasan_penolakan
                : null,
        ]);

        $plainPassword = null;

        if ($request->status === 'approved') {

            $user = User::where('email', $item->email_pengaju)->first();

            if (!$user) {
                $plainPassword = Str::password(length: 12);

                $user = User::create([
                    'name'                 => $item->nama_pengaju,
                    'email'                => $item->email_pengaju,
                    'password'             => Hash::make($plainPassword),
                    'role'                 => 'peserta',
                    'must_change_password' => true,
                ]);
            }
        }

        try {
            Mail::to($item->email_pengaju)
                ->send(new PengajuanStatusMail($item, $plainPassword));
        } catch (\Throwable $e) {
            logger()->error('Gagal kirim email status pengajuan', [
                'pengajuan_id' => $item->id,
                'email' => $item->email_pengaju,
                'error' => $e->getMessage(),
            ]);
        }

        return back()->with('success', 'Status pengajuan berhasil diupdate dan email notifikasi telah dikirim.');
    }

    public function destroy($id)
{
    $item = InternshipRequest::findOrFail($id);

    if ($item->status === 'approved') {
        return back()->with('error', 'Pengajuan approved harus dihapus melalui akun peserta.');
    }

    if ($item->surat_pengantar) {
        \Storage::disk('public')->delete($item->surat_pengantar);
    }

    $item->delete();

    return back()->with('success', 'Pengajuan berhasil dihapus.');
}


}
    