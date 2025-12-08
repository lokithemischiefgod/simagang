<!DOCTYPE html>
<html>
<head>
    <title>Admin - Log Absensi Peserta</title>
</head>
<body>
    <nav style="margin-bottom: 16px;">
        <a href="{{ route('admin.pengajuan.index') }}">Halaman Pengajuan</a> |
        <a href="{{ route('admin.absensi.index') }}">Halaman Absensi</a> |
        <a href="{{ route('admin.peserta.index') }}">Daftar Peserta</a>

    </nav>

    <h1>Log Absensi Peserta</h1>

    @if (session('success'))
        <p style="color: green">{{ session('success') }}</p>
    @endif

    @if (session('error'))
        <p style="color: red">{{ session('error') }}</p>
    @endif

    <h3>Filter</h3>

    <form method="GET" action="{{ route('admin.absensi.index') }}" style="margin-bottom: 16px;">
        <label>Tanggal:</label>
        <input type="date" name="tanggal" value="{{ $tanggal }}">

        <label>Status:</label>
        <select name="status">
            <option value="">-- Semua --</option>
            <option value="hadir" {{ $status === 'hadir' ? 'selected' : '' }}>Hadir</option>
            <option value="izin" {{ $status === 'izin' ? 'selected' : '' }}>Izin</option>
            <option value="turun_lapangan" {{ $status === 'turun_lapangan' ? 'selected' : '' }}>Turun Lapangan</option>
        </select>

        <label>Cari Peserta (Nama/Email):</label>
        <input type="text" name="q" value="{{ $q }}" placeholder="Nama atau email">

        <button type="submit">Terapkan Filter</button>
    </form>

    <form method="GET" action="{{ route('admin.absensi.export') }}" style="margin-bottom: 16px;">
        <input type="hidden" name="tanggal" value="{{ $tanggal }}">
        <input type="hidden" name="status" value="{{ $status }}">
        <input type="hidden" name="q" value="{{ $q }}">
        <button type="submit">Export CSV (berdasarkan filter ini)</button>
    </form>

    <h3>Data Absensi Tanggal: {{ $tanggal }}</h3>

    @if ($items->isEmpty())
        <p>Tidak ada data absensi untuk filter ini.</p>
    @else
        <table border="1" cellpadding="6" cellspacing="0">
            <thead>
                <tr>
                    <th>Nama Peserta</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Jam Masuk</th>
                    <th>Jam Keluar</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $row)
                    <tr>
                        <td>{{ $row->user->name ?? '-' }}</td>
                        <td>{{ $row->user->email ?? '-' }}</td>
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
