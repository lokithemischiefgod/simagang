<x-app-layout>

<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">
                    Log Absensi Peserta
                </h1>
                <p class="text-sm text-gray-600 mt-1">
                    Pantau kehadiran peserta magang per tanggal, status, dan nama.
                </p>
            </div>
            <div class="text-right text-xs text-gray-500">
                Tanggal filter saat ini<br>
                <span class="font-semibold text-gray-700">{{ $tanggal }}</span>
            </div>
        </div>

        {{-- Notifikasi --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-200 text-green-800 text-sm px-4 py-3 rounded-xl">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-200 text-red-800 text-sm px-4 py-3 rounded-xl">
                {{ session('error') }}
            </div>
        @endif

        {{-- Filter --}}
        <div class="bg-white shadow rounded-xl p-6 space-y-4">
            <h2 class="text-sm font-semibold text-gray-800">
                Filter Data Absensi
            </h2>

            <form method="GET" action="{{ route('admin.absensi.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ $tanggal }}"
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-lintasarta-navy focus:border-lintasarta-navy">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                    <select name="status"
                            class="w-full border rounded-lg px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-lintasarta-navy focus:border-lintasarta-navy">
                        <option value="">Semua</option>
                        <option value="standby_kantor" {{ $status === 'standby_kantor' ? 'selected' : '' }}>Standby Kantor</option>
                        <option value="izin" {{ $status === 'izin' ? 'selected' : '' }}>Izin</option>
                        <option value="turun_lapangan" {{ $status === 'turun_lapangan' ? 'selected' : '' }}>Turun Lapangan</option>
                        {{-- opsional kalau kamu ingin filter checkout --}}
                        <option value="checkout" {{ $status === 'checkout' ? 'selected' : '' }}>Checkout</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Cari Peserta (Nama / Email)</label>
                    <input type="text" name="q" value="{{ $q }}" placeholder="Nama atau email"
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-lintasarta-navy focus:border-lintasarta-navy">
                </div>

                <div class="flex gap-2">
                    <button type="submit"
                            class="inline-flex items-center justify-center px-4 py-2.5 rounded-lg text-sm font-semibold bg-lintasarta-blue text-white hover:bg-lintasarta-navy transition w-full">
                        Terapkan Filter
                    </button>
                </div>
            </form>

            {{-- Tombol Export CSV --}}
            <form method="GET" action="{{ route('admin.absensi.export') }}" class="flex justify-end">
                <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                <input type="hidden" name="status" value="{{ $status }}">
                <input type="hidden" name="q" value="{{ $q }}">
                <button type="submit"
                        class="inline-flex items-center px-4 py-2.5 rounded-lg text-sm font-semibold border border-gray-300 text-gray-700 hover:bg-gray-50 transition">
                    Export CSV (berdasarkan filter)
                </button>
            </form>
        </div>

        {{-- Tabel Absensi --}}
        <div class="bg-white shadow rounded-xl p-6">
            <h2 class="text-sm font-semibold text-gray-800 mb-4">
                Data Absensi Tanggal: <span class="font-mono">{{ $tanggal }}</span>
            </h2>

            @if ($items->isEmpty())
                <p class="text-sm text-gray-600">
                    Tidak ada data absensi untuk filter ini.
                </p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm border border-gray-200 rounded-lg overflow-hidden">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-2 text-left font-semibold text-gray-700 border-b text-xs">Nama Peserta</th>
                                <th class="px-3 py-2 text-left font-semibold text-gray-700 border-b text-xs">Email</th>
                                <th class="px-3 py-2 text-left font-semibold text-gray-700 border-b text-xs">Status</th>
                                <th class="px-3 py-2 text-left font-semibold text-gray-700 border-b text-xs">Jam Masuk</th>
                                <th class="px-3 py-2 text-left font-semibold text-gray-700 border-b text-xs">Jam Keluar</th>
                                <th class="px-3 py-2 text-left font-semibold text-gray-700 border-b text-xs">Keterangan</th>
                                <th class="px-3 py-2 text-left font-semibold text-gray-700 border-b text-xs">Aktivitas Terakhir</th>
                                <th class="px-3 py-2 text-left font-semibold text-gray-700 border-b text-xs">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @foreach ($items as $row)
                                @php
                                    $statusUpper = strtoupper($row->status);
                                    $badgeClass = match($row->status) {
                                        'standby_kantor' => 'bg-emerald-100 text-emerald-800',
                                        'izin' => 'bg-amber-100 text-amber-800',
                                        'turun_lapangan' => 'bg-blue-100 text-blue-800',
                                        'checkout' => 'bg-slate-100 text-slate-800',
                                        default => 'bg-gray-100 text-gray-800',
                                    };

                                    $lastLog = $row->workLogs->first();
                                @endphp
                                <tr class="border-t border-gray-200">
                                    <td class="px-3 py-2 text-sm text-gray-900">
                                        {{ $row->user->name ?? '-' }}
                                    </td>
                                    <td class="px-3 py-2 text-xs text-gray-700">
                                        {{ $row->user->email ?? '-' }}
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
                                    <td class="px-3 py-2 text-xs text-gray-700">
                                        @if ($row->latestWorkLog)
                                            {{ $row->latestWorkLog->jam_mulai }} — {{ $row->latestWorkLog->aktivitas }}
                                        @else
                                            -
                                        @endif
                                    </td>

                                    <td class="px-3 py-2 text-xs">
                                        <a href="{{ route('admin.absensi.aktivitas', $row->id) }}"
                                        class="text-lintasarta-blue hover:underline font-semibold">
                                            Detail
                                        </a>
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