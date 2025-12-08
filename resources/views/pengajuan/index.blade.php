<!DOCTYPE html>
<html>
<head>
    <title>Admin - Daftar Pengajuan Magang</title>
</head>
<body>
    <nav style="margin-bottom: 16px;">
        <a href="{{ route('admin.pengajuan.index') }}">Halaman Pengajuan</a> |
        <a href="{{ route('admin.absensi.index') }}">Halaman Absensi</a> |
        <a href="{{ route('admin.peserta.index') }}">Daftar Peserta</a>

    </nav>
    
    @if (session('success'))
        <p style="color: green">{{ session('success') }}</p>
    @endif

    @if (session('error'))
        <p style="color: red">{{ session('error') }}</p>
    @endif

    <h1>Daftar Pengajuan Magang / PKL</h1>

    @if (session('success'))
        <p style="color: green">{{ session('success') }}</p>
    @endif

    @if ($items->isEmpty())
        <p>Belum ada pengajuan.</p>
    @else
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Pengaju</th>
                    <th>Email</th>
                    <th>Tipe</th>
                    <th>Instansi</th>
                    <th>Periode Magang</th>
                    <th>Surat Pengantar</th>
                    <th>Status</th>
                    <th>Alasan Penolakan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                    <tr>    
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->nama_pengaju }}</td>
                        <td>{{ $item->email_pengaju }}</td>
                        <td>{{ $item->tipe }}</td>
                        <td>{{ $item->instansi ?? '-' }}</td>
                        <td>
                            @if ($item->tanggal_mulai && $item->tanggal_selesai)
                                {{ $item->tanggal_mulai }} s/d {{ $item->tanggal_selesai }}
                            @elseif ($item->tanggal_mulai)
                                Mulai: {{ $item->tanggal_mulai }}
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if ($item->surat_pengantar)
                                <a href="{{ asset('storage/' . $item->surat_pengantar) }}" target="_blank">Lihat Surat</a>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if ($item->status === 'pending')
                                <span style="color: orange">PENDING</span>
                            @elseif ($item->status === 'approved')
                                <span style="color: green">APPROVED</span>
                            @else
                                <span style="color: red">REJECTED</span>
                            @endif
                        </td>
                        <td>
                            {{ $item->alasan_penolakan ?? '-' }}
                        </td>
                        <td>
                        @if ($item->status === 'pending')
                            <!-- Form Approve -->
                            <form action="{{ route('admin.pengajuan.updateStatus', $item->id) }}" method="POST" style="display:inline-block">
                                @csrf
                                <input type="hidden" name="status" value="approved">
                                <button type="submit">Setujui</button>
                            </form>

                            <!-- Form Reject -->
                            <form action="{{ route('admin.pengajuan.updateStatus', $item->id) }}" method="POST" style="display:inline-block; margin-top:4px">
                                @csrf
                                <input type="hidden" name="status" value="rejected">
                                <input type="text" name="alasan_penolakan" placeholder="Alasan..." style="width:120px">
                                <button type="submit">Tolak</button>
                            </form>
                        @else
                            <!-- Kalau sudah approved/rejected, tidak ada tombol aksi -->
                            <em>Status final</em>
                        @endif
                    </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>
