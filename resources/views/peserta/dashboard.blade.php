<x-app-layout>
    <div class="max-w-5xl mx-auto px-4 py-6">
        <div class="space-y-6">

            {{-- Header --}}
            <div class="bg-white shadow rounded-xl p-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
                {{-- Nama & Email --}}
                <div>
                    <h1 class="text-xl md:text-2xl font-semibold text-gray-900">
                        Dashboard Peserta Magang
                    </h1>
                    <p class="text-gray-600 text-sm mt-1">
                        Halo, <span class="font-medium">{{ $user->name }}</span> <span class="hidden sm:inline">({{ $user->email }})</span>
                    </p>
                </div>

                {{-- Tanggal & Tombol --}}
                <div class="flex flex-row items-center justify-between md:justify-end md:gap-6 border-t md:border-t-0 pt-4 md:pt-0">
                    <div class="text-left md:text-right text-xs text-gray-500">
                        <span class="block">Tanggal hari ini</span>
                        <span class="font-semibold text-gray-700">{{ now()->toDateString() }}</span>
                    </div>
                    
                    <a href="{{ route('peserta.worklog.index') }}"
                    class="inline-flex items-center px-4 py-2 rounded-lg border border-gray-300 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                        Riwayat Aktivitas
                    </a>
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

                @php
                    $todayStatus = $todayAttendance?->status;
                    $isCheckout = $todayAttendance && ($todayAttendance->status === 'checkout' || $todayAttendance->jam_keluar);
                @endphp

                @if ($todayAttendance)
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">
                        <div>
                            <p class="text-gray-500 text-xs uppercase">Status</p>
                            <p class="font-semibold mt-1">
                                @php
                                    $status = strtoupper($todayAttendance->status);
                                    $badgeClass = match($todayAttendance->status) {
                                        'standby_kantor' => 'bg-emerald-100 text-emerald-800',
                                        'izin' => 'bg-yellow-100 text-yellow-800',
                                        'turun_lapangan' => 'bg-blue-100 text-blue-800',
                                        'checkout' => 'bg-slate-100 text-slate-800',
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

            @if ($todayAttendance && $todayAttendance->status === 'standby_kantor')
            <div class="bg-white shadow rounded-xl p-6">
                <h2 class="text-lg font-semibold mb-3">Aktivitas Kerja Hari Ini</h2>

                <form action="{{ route('peserta.worklog.store') }}" method="POST" class="flex gap-2 mb-4">
                    @csrf
                    <input type="text" name="aktivitas" required
                        placeholder="Contoh: Input data absensi, Arsip surat..."
                        class="flex-1 border rounded-lg px-3 py-2 text-sm">
                    <button class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm">
                        Tambah
                    </button>
                </form>

                <div class="space-y-2 text-sm">
                    @foreach ($todayAttendance->workLogs as $log)
                        <div class="flex justify-between items-center border rounded-lg px-3 py-2">
                            <div>
                                <p class="font-medium">{{ $log->aktivitas }}</p>
                                <p class="text-xs text-gray-500">
                                    {{ $log->jam_mulai }} - {{ $log->jam_selesai ?? 'berjalan' }}
                                </p>
                            </div>

                            @if (!$log->jam_selesai)
                            <form action="{{ route('peserta.worklog.finish', $log->id) }}" method="POST">
                                @csrf
                                <button class="text-xs text-red-600 hover:underline">
                                    Selesai
                                </button>
                            </form>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            @endif


            {{-- Aksi Absensi --}}
            <div class="bg-white shadow rounded-xl p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">
                    Aksi Absensi
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    {{-- Check-in => standby_kantor --}}
                    <form action="{{ route('peserta.checkin') }}" method="POST" class="flex flex-col gap-2">
                        @csrf
                        <p class="text-sm text-gray-600">
                            Tekan tombol ini saat Anda <span class="font-medium">datang ke kantor</span>.
                        </p>

                        @php
                            $disableCheckIn = $todayAttendance !== null; // kalau sudah ada record hari ini (izin / standby / lapangan / checkout)
                        @endphp

                        <button type="submit"
                            @disabled($disableCheckIn)
                            class="inline-flex items-center justify-center rounded-lg px-4 py-2.5 text-sm font-semibold
                                   {{ $disableCheckIn ? 'bg-gray-300 text-gray-600 cursor-not-allowed' : 'bg-emerald-600 text-white hover:bg-emerald-700' }}
                                   transition">
                            Check-In (Standby Kantor)
                        </button>

                        @if ($disableCheckIn)
                            <p class="text-xs text-gray-500">
                                Anda sudah memiliki status hari ini, sehingga check-in tidak tersedia.
                            </p>
                        @endif
                    </form>

                    {{-- Check-out: boleh dari standby_kantor atau turun_lapangan --}}
                    <form action="{{ route('peserta.checkout') }}" method="POST" class="flex flex-col gap-2">
                        @csrf
                        <p class="text-sm text-gray-600">
                            Tekan tombol ini saat Anda <span class="font-medium">pulang dari kantor</span>.
                        </p>

                        @php
                            $disableCheckout = !$todayAttendance
                                || $isCheckout
                                || $todayStatus === 'izin'
                                || !in_array($todayStatus, ['standby_kantor','turun_lapangan'], true);
                        @endphp

                        <button type="submit"
                            @disabled($disableCheckout)
                            class="inline-flex items-center justify-center rounded-lg px-4 py-2.5 text-sm font-semibold
                                   {{ $disableCheckout ? 'bg-gray-300 text-gray-600 cursor-not-allowed' : 'bg-slate-700 text-white hover:bg-slate-800' }}
                                   transition">
                            Check-Out
                        </button>

                        @if ($disableCheckout)
                            <p class="text-xs text-gray-500">
                                Checkout hanya tersedia jika status Anda standby kantor / turun lapangan dan belum checkout.
                            </p>
                        @endif
                    </form>

                    {{-- Izin: hanya jika belum ada record hari ini --}}
                    <form action="{{ route('peserta.izin') }}" method="POST" class="flex flex-col gap-2">
                        @csrf
                        <p class="text-sm text-gray-600">
                            Gunakan saat Anda <span class="font-medium">tidak masuk</span> dan perlu mencatat izin resmi.
                        </p>

                        @php
                            $disableIzin = $todayAttendance !== null;
                        @endphp

                        <div class="flex flex-col gap-2">
                            <input
                                type="text"
                                name="keterangan"
                                placeholder="Alasan izin..."
                                @disabled($disableIzin)
                                class="border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400
                                       {{ $disableIzin ? 'bg-gray-100 text-gray-500 cursor-not-allowed' : '' }}"
                            >
                            <button type="submit"
                                @disabled($disableIzin)
                                class="inline-flex items-center justify-center rounded-lg px-4 py-2.5 text-sm font-semibold
                                       {{ $disableIzin ? 'bg-gray-300 text-gray-600 cursor-not-allowed' : 'bg-yellow-500 text-white hover:bg-yellow-600' }}
                                       transition">
                                Izin
                            </button>

                            @if ($disableIzin)
                                <p class="text-xs text-gray-500">
                                    Anda sudah memiliki status hari ini, sehingga izin tidak tersedia.
                                </p>
                            @endif
                        </div>
                    </form>

                    {{-- Turun Lapangan: hanya dari standby_kantor dan belum checkout --}}
                    <form action="{{ route('peserta.turunlapangan') }}" method="POST" class="flex flex-col gap-2">
                        @csrf
                        <p class="text-sm text-gray-600">
                            Gunakan saat Anda <span class="font-medium">ditugaskan turun lapangan</span> oleh kantor.
                        </p>

                        @php
                            $disableLapangan = !$todayAttendance
                                || $isCheckout
                                || $todayStatus !== 'standby_kantor';
                        @endphp

                        <div class="flex flex-col gap-2">
                            <input
                                type="text"
                                name="keterangan"
                                placeholder="Lokasi / kegiatan lapangan..."
                                @disabled($disableLapangan)
                                class="border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400
                                       {{ $disableLapangan ? 'bg-gray-100 text-gray-500 cursor-not-allowed' : '' }}"
                            >
                            <button type="submit"
                                @disabled($disableLapangan)
                                class="inline-flex items-center justify-center rounded-lg px-4 py-2.5 text-sm font-semibold
                                       {{ $disableLapangan ? 'bg-gray-300 text-gray-600 cursor-not-allowed' : 'bg-blue-500 text-white hover:bg-blue-600' }}
                                       transition">
                                Turun Lapangan
                            </button>

                            @if ($disableLapangan)
                                <p class="text-xs text-gray-500">
                                    Turun lapangan hanya tersedia jika Anda sudah check-in dan status Anda standby kantor.
                                </p>
                            @endif
                        </div>
                    </form>

                    {{-- Kembali ke Kantor: hanya jika sedang turun_lapangan --}}
                    <form action="{{ route('peserta.kembaliKantor') }}" method="POST" class="flex flex-col gap-2">
                        @csrf
                        <p class="text-sm text-gray-600">
                            Gunakan saat Anda <span class="font-medium">kembali ke kantor</span> setelah turun lapangan.
                        </p>

                        @php
                            $disableKembali = !$todayAttendance
                                || $isCheckout
                                || $todayStatus !== 'turun_lapangan';
                        @endphp

                        <button type="submit"
                            @disabled($disableKembali)
                            class="inline-flex items-center justify-center rounded-lg px-4 py-2.5 text-sm font-semibold
                                   {{ $disableKembali ? 'bg-gray-300 text-gray-600 cursor-not-allowed' : 'bg-indigo-600 text-white hover:bg-indigo-700' }}
                                   transition">
                            Kembali ke Kantor
                        </button>

                        @if ($disableKembali)
                            <p class="text-xs text-gray-500">
                                Tombol ini hanya tersedia saat status Anda turun lapangan dan belum checkout.
                            </p>
                        @endif
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
                                            'standby_kantor' => 'text-emerald-700',
                                            'izin' => 'text-yellow-700',
                                            'turun_lapangan' => 'text-blue-700',
                                            'checkout' => 'text-slate-700',
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
    </div>
</x-app-layout>
