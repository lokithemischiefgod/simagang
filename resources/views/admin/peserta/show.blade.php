<x-app-layout>

<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">
                    Detail Peserta Magang
                </h1>
                <p class="text-sm text-gray-600 mt-1">
                    Informasi lengkap peserta dan riwayat absensi.
                </p>
            </div>
            <div>
                <a href="{{ route('admin.peserta.index') }}"
                   class="inline-flex items-center px-3 py-2 rounded-lg text-xs font-semibold border border-gray-300 text-gray-700 hover:bg-gray-50 transition">
                    &larr; Kembali ke Daftar Peserta
                </a>
            </div>
        </div>

        {{-- Data Peserta --}}
        <div class="bg-white shadow rounded-xl p-6">
            <h2 class="text-sm font-semibold text-gray-800 mb-4">
                Data Peserta
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-500 text-xs uppercase">Nama</p>
                    <p class="mt-1 text-gray-900 font-medium">{{ $user->name }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-xs uppercase">Email</p>
                    <p class="mt-1 text-gray-900">{{ $user->email }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">WhatsApp</p>
                    @if ($pengajuan?->no_wa)
                    <a href="https://wa.me/{{ $pengajuan->no_wa }}"
                    target="_blank"
                    class="text-emerald-600 hover:underline font-medium">
                        {{ $pengajuan->no_wa }}
                    </a>
                @else
                    <p class="text-sm text-gray-500 italic">Belum ada nomor WhatsApp.</p>
                @endif
                </div>
                <div>
                    <p class="text-gray-500 text-xs uppercase">Role</p>
                    <p class="mt-1 text-gray-900">{{ $user->role }}</p>
                </div>
            </div>
        </div>

        {{-- Data Pengajuan & Periode Magang --}}
        <div class="bg-white shadow rounded-xl p-6">
            <h2 class="text-sm font-semibold text-gray-800 mb-4">
                Data Pengajuan & Periode Magang
            </h2>

            @if ($pengajuan)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500 text-xs uppercase">Tipe Pengajuan</p>
                        <p class="mt-1 text-gray-900">{{ $pengajuan->tipe }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs uppercase">Instansi Asal</p>
                        <p class="mt-1 text-gray-900">{{ $pengajuan->instansi ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs uppercase">Status Pengajuan</p>
                        @php
                            $statusText = strtoupper($pengajuan->status);
                            $statusClass = match($pengajuan->status) {
                                'pending' => 'bg-amber-100 text-amber-800',
                                'approved' => 'bg-emerald-100 text-emerald-800',
                                'rejected' => 'bg-red-100 text-red-800',
                                default => 'bg-gray-100 text-gray-800',
                            };
                        @endphp
                        <p class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $statusClass }}">
                                {{ $statusText }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs uppercase">Periode Magang</p>
                        <p class="mt-1 text-gray-900">
                            @if ($pengajuan->tanggal_mulai && $pengajuan->tanggal_selesai)
                                {{ $pengajuan->tanggal_mulai }} s/d {{ $pengajuan->tanggal_selesai }}
                            @elseif ($pengajuan->tanggal_mulai)
                                Mulai: {{ $pengajuan->tanggal_mulai }}
                            @else
                                -
                            @endif
                        </p>
                    </div>
                    @if ($pengajuan->alasan_penolakan)
                        <div class="md:col-span-2">
                            <p class="text-gray-500 text-xs uppercase">Alasan Penolakan</p>
                            <p class="mt-1 text-gray-900">
                                {{ $pengajuan->alasan_penolakan }}
                            </p>
                        </div>
                    @endif
                </div>
            @else
                <p class="text-sm text-gray-600">
                    Tidak ditemukan data pengajuan yang disetujui untuk peserta ini.
                </p>
            @endif
        </div>

        {{-- Export Absensi --}}
        <div class="flex justify-between items-center">
            <h2 class="text-sm font-semibold text-gray-800">
                Riwayat Absensi
            </h2>
            <form method="GET" action="{{ route('admin.peserta.exportAbsensi', $user->id) }}">
                <button type="submit"
                        class="inline-flex items-center px-4 py-2.5 rounded-lg text-xs font-semibold border border-gray-300 text-gray-700 hover:bg-gray-50 transition">
                    Export Riwayat Absensi (CSV)
                </button>
            </form>
        </div>

        {{-- Tabel Riwayat Absensi --}}
        <div class="bg-white shadow rounded-xl p-6">
            @if ($absensi->isEmpty())
                <p class="text-sm text-gray-600">
                    Belum ada data absensi untuk peserta ini.
                </p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm border border-gray-200 rounded-lg overflow-hidden">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-2 text-left font-semibold text-gray-700 border-b text-xs">Tanggal</th>
                                <th class="px-3 py-2 text-left font-semibold text-gray-700 border-b text-xs">Status</th>
                                <th class="px-3 py-2 text-left font-semibold text-gray-700 border-b text-xs">Jam Masuk</th>
                                <th class="px-3 py-2 text-left font-semibold text-gray-700 border-b text-xs">Jam Keluar</th>
                                <th class="px-3 py-2 text-left font-semibold text-gray-700 border-b text-xs">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @foreach ($absensi as $row)
                                @php
                                    $statusUpper = strtoupper($row->status);
                                    $badgeClass = match($row->status) {
                                        'hadir' => 'bg-emerald-100 text-emerald-800',
                                        'izin' => 'bg-amber-100 text-amber-800',
                                        'turun_lapangan' => 'bg-blue-100 text-blue-800',
                                        default => 'bg-gray-100 text-gray-800',
                                    };
                                @endphp
                                <tr class="border-t border-gray-200">
                                    <td class="px-3 py-2 font-mono text-xs text-gray-700">
                                        {{ $row->tanggal }}
                                    </td>
                                    <td class="px-3 py-2 text-xs">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full font-semibold {{ $badgeClass }}">
                                            {{ $statusUpper }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-2 font-mono text-xs text-gray-700">
                                        {{ $row->jam_masuk ?? '-' }}
                                    </td>
                                    <td class="px-3 py-2 font-mono text-xs text-gray-700">
                                        {{ $row->jam_keluar ?? '-' }}
                                    </td>
                                    <td class="px-3 py-2 text-xs text-gray-700">
                                        {{ $row->keterangan ?? '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

    </div>
</div>
</x-app-layout>
