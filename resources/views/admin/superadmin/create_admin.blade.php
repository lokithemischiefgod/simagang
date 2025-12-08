<!DOCTYPE html>
<html>
<head>
    <title>Superadmin - Tambah Admin Baru</title>
</head>
<body>
    <nav style="margin-bottom: 16px;">
        <a href="{{ route('admin.pengajuan.index') }}">Halaman Pengajuan</a> |
        <a href="{{ route('admin.absensi.index') }}">Halaman Absensi</a> |
        <a href="{{ route('admin.peserta.index') }}">Daftar Peserta</a> |
        <a href="{{ route('superadmin.admins.index') }}">Manajemen Admin</a>
    </nav>

    <h1>Tambah Admin Baru</h1>

    @if ($errors->any())
        <ul style="color:red;">
            @foreach ($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('superadmin.admins.store') }}">
        @csrf

        <div>
            <label>Nama:</label><br>
            <input type="text" name="name" value="{{ old('name') }}">
        </div>

        <div>
            <label>Email:</label><br>
            <input type="email" name="email" value="{{ old('email') }}">
        </div>

        <div>
            <label>Password:</label><br>
            <input type="password" name="password">
        </div>

        <br>
        <button type="submit">Simpan Admin</button>
    </form>

</body>
</html>
