<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Peserta Magang</title>
</head>
<body>
    <h1>Dashboard Peserta Magang</h1>
    <p>Halo, {{ $user->name }} ({{ $user->email }})</p>

    @if (session('success'))
        <p style="color: green">{{ session('success') }}</p>
    @endif

    @if (session('error'))
        <p style="color: red">{{ session('error') }}</p>
    @endif

    <h2>Status Hari Ini ({{ now()->toDateString() }})</h2>

    @if ($todayAttendance)
        <p>
            Status: <strong>{{ strtoupper($todayAttendance->status) }}</strong><br>
            Jam Masuk: {{ $todayAttendance->jam_masuk ?? '-' }}<br>
            Jam Keluar: {{ $todayAttendance->jam_keluar ?? '-' }}<br>
            Keterangan: {{ $todayAttendance->keterangan ?? '-' }}
        </p>
    @else
        <p>Belum ada absensi hari ini.</p>
    @endif

    <hr>

    <h2>Aksi Absensi</h2>

    <!-- Check-in Hadir -->
    <form action="{{ route('peserta.checkin') }}" method="POST" style="margin-bottom:8px;">
        @csrf
        <button type="submit">Check-In (Hadir)</button>
    </form>

    <!-- Check-out -->
    <form action="{{ route('peserta.checkout') }}" method="POST" style="margin-bottom:8px;">
        @csrf
        <button type="submit">Check-Out</button>
    </form>

    <!-- Izin -->
    <form action="{{ route('peserta.izin') }}" method="POST" style="margin-bottom:8px;">
        @csrf
        <input type="text" name="keterangan" placeholder="Alasan izin..." style="width:200px;">
        <button type="submit">Izin</button>
    </form>

    <!-- Turun Lapangan -->
    <form action="{{ route('peserta.turunlapangan') }}" method="POST" style="margin-bottom:8px;">
        @csrf
        <input type="text" name="keterangan" placeholder="Lokasi / kegiatan lapangan..." style="width:250px;">
        <button type="submit">Turun Lapangan</button>
    </form>

    <hr>

    <h2>Riwayat 7 Hari Terakhir</h2>

    @if ($history->isEmpty())
        <p>Belum ada riwayat absensi.</p>
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
                @foreach ($history as $row)
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
