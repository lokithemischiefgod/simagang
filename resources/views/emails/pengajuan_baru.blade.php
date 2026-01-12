@component('mail::message')
# Pengajuan Magang / PKL Baru

Telah masuk pengajuan magang/PKL baru dengan detail sebagai berikut:

- **Nama Pengaju:** {{ $pengajuan->nama_pengaju }}
- **Email Pengaju:** {{ $pengajuan->email_pengaju }}
- **Tipe Pengajuan:** {{ $pengajuan->tipe }}
- **Instansi Asal:** {{ $pengajuan->instansi ?? '-' }}

@isset($pengajuan->tanggal_mulai)
- **Periode Usulan Magang:**
  @if ($pengajuan->tanggal_mulai && $pengajuan->tanggal_selesai)
    {{ $pengajuan->tanggal_mulai }} s/d {{ $pengajuan->tanggal_selesai }}
  @else
    Mulai: {{ $pengajuan->tanggal_mulai }}
  @endif
@endisset

Silakan melakukan review dan menentukan status pengajuan (setujui / tolak) melalui sistem.

Terima kasih,

**SIMAGANG**
@endcomponent
