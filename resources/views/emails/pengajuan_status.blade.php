@component('mail::message')
# Update Pengajuan Magang / PKL

Halo **{{ $item->nama_pengaju }}**,

@if($item->status === 'approved')
Pengajuan magang Anda telah **DISETUJUI**.

**Periode Magang:** {{ $item->tanggal_mulai }} s/d {{ $item->tanggal_selesai }}

@if(!empty($plainPassword))
@component('mail::panel')
### Akun SIMAGANG Anda
- **Email:** {{ $item->email_pengaju }}
- **Password sementara:** {{ $plainPassword }}
@endcomponent

Silakan login dan **wajib ganti password** saat pertama kali masuk.
@else
Akun Anda sudah tersedia. Silakan login melalui tombol di bawah ini.
@endif

@component('mail::button', ['url' => url('/login')])
Login ke SIMAGANG
@endcomponent

@elseif($item->status === 'rejected')
Pengajuan magang Anda **DITOLAK**.

@if(!empty($item->alasan_penolakan))
**Alasan:** {{ $item->alasan_penolakan }}
@endif

Jika Anda ingin mengajukan ulang, silakan isi form kembali melalui tombol di bawah ini:

@component('mail::button', ['url' => url('/pengajuan')])
Isi Form Pengajuan
@endcomponent

@else
Status pengajuan Anda saat ini: **{{ strtoupper($item->status) }}**
@endif

Terima kasih,<br>
**SIMAGANG**
@endcomponent
