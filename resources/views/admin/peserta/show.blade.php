<!DOCTYPE html>
<html>
<head>
    <title>Admin - Detail Peserta Magang</title>
</head>
<body>
    <nav style="margin-bottom: 16px;">
        <a href="{{ route('admin.pengajuan.index') }}">Halaman Pengajuan</a> |
        <a href="{{ route('admin.absensi.index') }}">Halaman Absensi</a> |
        <a href="{{ route('admin.peserta.index') }}">Daftar Peserta</a>
    </nav>

    <h1>Detail Peserta Magang</h1>

    <h2>Data Peserta</h2>
    <p>
        <strong>Nama:</strong> {{ $user->name }} <br>
        <strong>Email:</strong> {{ $user->email }} <br>
        <strong>Role:</strong> {{ $user->role }} <br>
    </p>

    <h2>Data Pengajuan & Periode Magang</h2>
    @if ($pengajuan)
        <p>
            <strong>Tipe Pengajuan:</strong> {{ $pengajuan->tipe }} <br>
            <strong>Instansi Asal:</strong> {{ $pengajuan->instansi ?? '-' }} <br>
            <strong>Status Pengajuan:</strong> {{ strtoupper($pengajuan->status) }} <br>
            <strong>Periode Magang:</strong>
            @if ($pengajuan->tanggal_mulai && $pengajuan->tanggal_selesai)
                {{ $pengajuan->tanggal_mulai }} s/d {{ $pengajuan->tanggal_selesai }}
            @elseif ($pengajuan->tanggal_mulai)
                Mulai: {{ $pengajuan->tanggal_mulai }}
            @else
                -
            @endif
            <br>
        </p>
    @else
        <p>Tidak ditemukan data pengajuan yang disetujui untuk peserta ini.</p>
    @endif

    <h2>Riwayat Absensi</h2>

    <form method="GET" action="{{ route('admin.peserta.exportAbsensi', $user->id) }}" style="margin-bottom: 12px;">
        <button type="submit">Export Riwayat Absensi Peserta (CSV)</button>
    </form>

    @if ($absensi->isEmpty())
        <p>Belum ada data absensi.</p>
    @else
        <table border="1" cellpadding="6" cellspacing="0">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Jam Masuk</th>
                    <th>Jam Keluar</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($absensi as $row)
                    <tr>
                        <td>{{ $row->tanggal }}</td>
                        <td>{{ strtoupper($row->status) }}</td>
                        <td>{{ $row->jam_masuk ?? '-' }}</td>
                        <td>{{ $row->jam_keluar ?? '-' }}</td>
                        <td>{{ $row->keterangan ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

</body>
</html>
