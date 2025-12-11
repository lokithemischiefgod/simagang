@component('mail::message')
# Status Pengajuan Magang / PKL

Halo {{ $pengajuan->nama_pengaju }},

Berikut adalah status terbaru pengajuan magang/PKL Anda di **{{ $pengajuan->instansi ?? 'Instansi Tujuan' }}**.

@switch($pengajuan->status)
    @case('approved')
        **Status: DITERIMA ✅**

        Pengajuan magang/PKL Anda telah **disetujui**.

        @if ($pengajuan->tanggal_mulai && $pengajuan->tanggal_selesai)
        Periode magang Anda adalah:

        **{{ $pengajuan->tanggal_mulai }} s/d {{ $pengajuan->tanggal_selesai }}**
        @endif

        Silakan menunggu informasi lebih lanjut dari pihak kantor terkait teknis pelaksanaan magang/PKL.
        @break

    @case('rejected')
        **Status: DITOLAK ❌**

        Mohon maaf, pengajuan magang/PKL Anda **belum dapat kami terima** saat ini.

        @if ($pengajuan->alasan_penolakan)
        **Alasan penolakan:**
        > {{ $pengajuan->alasan_penolakan }}
        @endif

        Anda dapat menghubungi pihak kantor jika membutuhkan informasi lebih lanjut.
        @break

    @default
        **Status:** {{ strtoupper($pengajuan->status) }}
@endswitch

Terima kasih,

Salam,  
**Sistem Magang Kantor**
@endcomponent
