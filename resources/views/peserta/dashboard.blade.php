@extends('layouts.peserta')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div class="bg-white shadow rounded-xl p-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">
                    Dashboard Peserta Magang
                </h1>
                <p class="text-gray-600 text-sm mt-1">
                    Halo, <span class="font-medium">{{ $user->name }}</span> ({{ $user->email }})
                </p>
            </div>
            <div class="text-right text-xs text-gray-500">
                Tanggal hari ini<br>
                <span class="font-semibold text-gray-700">{{ now()->toDateString() }}</span>
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

        {{-- Status Hari Ini --}}
        <div class="bg-white shadow rounded-xl p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                Status Hari Ini
            </h2>

            @if ($todayAttendance)
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500 text-xs uppercase">Status</p>
                        <p class="font-semibold mt-1">
                            @php
                                $status = strtoupper($todayAttendance->status);
                                $badgeClass = match($todayAttendance->status) {
                                    'hadir' => 'bg-emerald-100 text-emerald-800',
                                    'izin' => 'bg-yellow-100 text-yellow-800',
                                    'turun_lapangan' => 'bg-blue-100 text-blue-800',
                                    default => 'bg-gray-100 text-gray-800'
                                };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badgeClass }}">
                                {{ $status }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs uppercase">Jam Masuk</p>
                        <p class="mt-1 font-mono text-sm">
                            {{ $todayAttendance->jam_masuk ?? '-' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs uppercase">Jam Keluar</p>
                        <p class="mt-1 font-mono text-sm">
                            {{ $todayAttendance->jam_keluar ?? '-' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs uppercase">Keterangan</p>
                        <p class="mt-1 text-sm">
                            {{ $todayAttendance->keterangan ?? '-' }}
                        </p>
                    </div>
                </div>
            @else
                <p class="text-sm text-gray-600">
                    Belum ada absensi yang tercatat untuk hari ini.
                </p>
            @endif
        </div>

        {{-- Aksi Absensi --}}
        <div class="bg-white shadow rounded-xl p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                Aksi Absensi
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                {{-- Check-in Hadir --}}
                <form action="{{ route('peserta.checkin') }}" method="POST" class="flex flex-col gap-2">
                    @csrf
                    <p class="text-sm text-gray-600">
                        Tekan tombol ini saat Anda <span class="font-medium">datang ke kantor</span>.
                    </p>
                    <button type="submit"
                        class="inline-flex items-center justify-center rounded-lg px-4 py-2.5 text-sm font-semibold bg-emerald-600 text-white hover:bg-emerald-700 transition">
                        Check-In (Hadir)
                    </button>
                </form>

                {{-- Check-out --}}
                <form action="{{ route('peserta.checkout') }}" method="POST" class="flex flex-col gap-2">
                    @csrf
                    <p class="text-sm text-gray-600">
                        Tekan tombol ini saat Anda <span class="font-medium">pulang dari kantor</span>.
                    </p>
                    <button type="submit"
                        class="inline-flex items-center justify-center rounded-lg px-4 py-2.5 text-sm font-semibold bg-slate-600 text-white hover:bg-slate-700 transition">
                        Check-Out
                    </button>
                </form>

                {{-- Izin --}}
                <form action="{{ route('peserta.izin') }}" method="POST" class="flex flex-col gap-2">
                    @csrf
                    <p class="text-sm text-gray-600">
                        Gunakan saat Anda <span class="font-medium">tidak masuk</span> dan perlu mencatat izin resmi.
                    </p>
                    <div class="flex flex-col gap-2">
                        <input
                            type="text"
                            name="keterangan"
                            placeholder="Alasan izin..."
                            class="border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400"
                        >
                        <button type="submit"
                            class="inline-flex items-center justify-center rounded-lg px-4 py-2.5 text-sm font-semibold bg-yellow-500 text-white hover:bg-yellow-600 transition">
                            Izin
                        </button>
                    </div>
                </form>

                {{-- Turun Lapangan --}}
                <form action="{{ route('peserta.turunlapangan') }}" method="POST" class="flex flex-col gap-2">
                    @csrf
                    <p class="text-sm text-gray-600">
                        Gunakan saat Anda <span class="font-medium">ditugaskan turun lapangan</span> oleh kantor.
                    </p>
                    <div class="flex flex-col gap-2">
                        <input
                            type="text"
                            name="keterangan"
                            placeholder="Lokasi / kegiatan lapangan..."
                            class="border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400"
                        >
                        <button type="submit"
                            class="inline-flex items-center justify-center rounded-lg px-4 py-2.5 text-sm font-semibold bg-blue-500 text-white hover:bg-blue-600 transition">
                            Turun Lapangan
                        </button>
                    </div>
                </form>

            </div>
        </div>

        {{-- Riwayat 7 Hari Terakhir --}}
        <div class="bg-white shadow rounded-xl p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                Riwayat 7 Hari Terakhir
            </h2>

            @if ($history->isEmpty())
                <p class="text-sm text-gray-600">
                    Belum ada riwayat absensi.
                </p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm border border-gray-200 rounded-lg overflow-hidden">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left font-semibold text-gray-700 border-b">Tanggal</th>
                                <th class="px-4 py-2 text-left font-semibold text-gray-700 border-b">Status</th>
                                <th class="px-4 py-2 text-left font-semibold text-gray-700 border-b">Jam Masuk</th>
                                <th class="px-4 py-2 text-left font-semibold text-gray-700 border-b">Jam Keluar</th>
                                <th class="px-4 py-2 text-left font-semibold text-gray-700 border-b">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($history as $row)
                                @php
                                    $rowStatus = strtoupper($row->status);
                                    $rowClass = match($row->status) {
                                        'hadir' => 'text-emerald-700',
                                        'izin' => 'text-yellow-700',
                                        'turun_lapangan' => 'text-blue-700',
                                        default => 'text-gray-700',
                                    };
                                @endphp
                                <tr class="border-t">
                                    <td class="px-4 py-2 font-mono text-xs text-gray-700">
                                        {{ $row->tanggal }}
                                    </td>
                                    <td class="px-4 py-2 {{ $rowClass }} font-semibold">
                                        {{ $rowStatus }}
                                    </td>
                                    <td class="px-4 py-2 font-mono text-xs text-gray-700">
                                        {{ $row->jam_masuk ?? '-' }}
                                    </td>
                                    <td class="px-4 py-2 font-mono text-xs text-gray-700">
                                        {{ $row->jam_keluar ?? '-' }}
                                    </td>
                                    <td class="px-4 py-2 text-gray-700">
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
@endsection
