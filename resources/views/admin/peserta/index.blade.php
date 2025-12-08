<!DOCTYPE html>
<html>
<head>
    <title>Admin - Daftar Peserta Magang</title>
</head>
<body>
    <nav style="margin-bottom: 16px;">
        <a href="{{ route('admin.pengajuan.index') }}">Halaman Pengajuan</a> |
        <a href="{{ route('admin.absensi.index') }}">Halaman Absensi</a> |
        <a href="{{ route('admin.peserta.index') }}">Daftar Peserta</a>
    </nav>

    <h1>Daftar Peserta Magang</h1>

    @if (session('success'))
        <p style="color: green">{{ session('success') }}</p>
    @endif

    @if (session('error'))
        <p style="color: red">{{ session('error') }}</p>
    @endif

    <form method="GET" action="{{ route('admin.peserta.index') }}" style="margin-bottom: 16px;">
        <label>Cari Peserta (Nama / Email):</label>
        <input type="text" name="q" value="{{ $q }}" placeholder="Nama atau email">
        <button type="submit">Cari</button>
    </form>

    @if ($peserta->isEmpty())
        <p>Belum ada peserta magang.</p>
    @else
        <table border="1" cellpadding="6" cellspacing="0">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Periode Magang</th>
                    <th>Instansi Asal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($peserta as $p)
                    @php
                        $req = $pengajuanPerEmail[$p->email] ?? null;
                    @endphp
                    <tr>
                        <td>{{ $p->name }}</td>
                        <td>{{ $p->email }}</td>
                        <td>
                            @if ($req && $req->tanggal_mulai && $req->tanggal_selesai)
                                {{ $req->tanggal_mulai }} s/d {{ $req->tanggal_selesai }}
                            @elseif ($req && $req->tanggal_mulai)
                                Mulai: {{ $req->tanggal_mulai }}
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if ($req)
                                {{ $req->instansi ?? '-' }}
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.peserta.show', $p->id) }}">Lihat Detail</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

</body>
</html>
