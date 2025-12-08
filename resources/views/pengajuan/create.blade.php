<!DOCTYPE html>
<html>
<head>
    <title>Form Pengajuan Magang / PKL</title>
</head>
<body>
    <h1>Form Pengajuan Magang / PKL</h1>

    @if (session('success'))
        <p style="color: green">{{ session('success') }}</p>
    @endif

    @if ($errors->any())
        <ul style="color: red">
            @foreach ($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('pengajuan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div>
            <label>Nama Pengaju</label><br>
            <input type="text" name="nama_pengaju" value="{{ old('nama_pengaju') }}">
        </div>

        <div>
            <label>Email Pengaju</label><br>
            <input type="email" name="email_pengaju" value="{{ old('email_pengaju') }}">
        </div>

        <div>
            <label>Tipe Pengajuan</label><br>
            <select name="tipe">
                <option value="sekolah">Sekolah</option>
                <option value="universitas">Universitas</option>
                <option value="mandiri">Mandiri</option>
            </select>
        </div>

        <div>
            <label>Nama Instansi Asal (opsional)</label><br>
            <input type="text" name="instansi" value="{{ old('instansi') }}">
        </div>

        <div>
            <label>Tanggal Mulai Magang</label><br>
            <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}">
        </div>

         <div>
            <label>Tanggal Selesai Magang</label><br>
            <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}">
        </div>


        <div>
            <label>Surat Pengantar (PDF, opsional)</label><br>
            <input type="file" name="surat_pengantar">
        </div>

        <br>
        <button type="submit">Kirim Pengajuan</button>
    </form>
</body>
</html>
