<!DOCTYPE html>
<html>
<head>
    <title>Superadmin - Manajemen Admin</title>
</head>
<body>
    <nav style="margin-bottom: 16px;">
        <a href="{{ route('admin.pengajuan.index') }}">Halaman Pengajuan</a> |
        <a href="{{ route('admin.absensi.index') }}">Halaman Absensi</a> |
        <a href="{{ route('admin.peserta.index') }}">Daftar Peserta</a> |
        <a href="{{ route('superadmin.admins.index') }}">Manajemen Admin</a>
    </nav>

    <h1>Manajemen Admin</h1>

    @if (session('success'))
        <p style="color: green">{{ session('success') }}</p>
    @endif

    @if (session('error'))
        <p style="color: red">{{ session('error') }}</p>
    @endif

    <p>
        <a href="{{ route('superadmin.admins.create') }}">+ Tambah Admin Baru</a>
    </p>

    @if ($admins->isEmpty())
        <p>Belum ada admin terdaftar.</p>
    @else
        <table border="1" cellpadding="6" cellspacing="0">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($admins as $admin)
                    <tr>
                        <td>{{ $admin->name }}</td>
                        <td>{{ $admin->email }}</td>
                        <td>
                            <form action="{{ route('superadmin.admins.destroy', $admin->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus admin ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Hapus Admin</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

</body>
</html>
