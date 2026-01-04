<x-app-layout>
<div class="max-w-5xl mx-auto px-4 py-6 space-y-4">

    <div class="bg-white rounded-xl shadow p-5">
        <div class="flex items-start justify-between">
            <div>
                <h1 class="text-lg font-semibold text-gray-900">Detail Aktivitas</h1>
                <p class="text-sm text-gray-600">
                    {{ $attendance->user->name }} ({{ $attendance->user->email }})
                </p>
            </div>
            <div class="text-right text-xs text-gray-500">
                Tanggal<br>
                <span class="font-semibold text-gray-700">{{ $attendance->tanggal }}</span>
            </div>
        </div>

        <div class="mt-3 text-xs text-gray-600">
            Status: <span class="font-semibold">{{ strtoupper($attendance->status) }}</span> •
            Masuk: <span class="font-mono">{{ $attendance->jam_masuk ?? '-' }}</span> •
            Keluar: <span class="font-mono">{{ $attendance->jam_keluar ?? '-' }}</span>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow p-5">
        <h2 class="text-sm font-semibold text-gray-900 mb-3">Log Aktivitas</h2>

        @if($attendance->workLogs->isEmpty())
            <p class="text-sm text-gray-500">Belum ada aktivitas dicatat.</p>
        @else
            <div class="space-y-2">
                @foreach($attendance->workLogs as $log)
                    <div class="border rounded-lg p-3">
                        <div class="text-[11px] text-gray-500 font-mono">
                            {{ $log->jam_mulai }}{{ $log->jam_selesai ? ' - '.$log->jam_selesai : '' }}
                        </div>
                        <div class="mt-1 text-sm text-gray-900">
                            {{ $log->aktivitas }}
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="mt-4">
            <a href="{{ route('admin.absensi.index', ['tanggal' => $attendance->tanggal]) }}"
               class="text-xs text-gray-600 hover:underline">
                ← Kembali ke Log Absensi
            </a>
        </div>
    </div>

</div>
</x-app-layout>
