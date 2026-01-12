<x-app-layout>
    <div class="max-w-5xl mx-auto px-4 py-6 space-y-6">

        <div class="flex items-center justify-between">
            <div>
                    <h1 class="text-2xl font-semibold text-gray-900">Riwayat Aktivitas</h1>
                    <p class="text-sm text-gray-600 mt-1">Semua aktivitas yang pernah kamu catat.</p>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow p-5">
                <form method="GET" action="{{ route('peserta.worklog.index') }}" class="flex gap-2 items-end">
        <div>
            <label class="block text-xs text-gray-600 mb-1">Tanggal</label>
            <input type="date" name="tanggal" value="{{ $tanggal }}" class="border rounded-lg px-3 py-2 text-sm">
        </div>
        <button class="px-4 py-2 rounded-lg bg-lintasarta-blue text-white text-sm font-semibold">
            Tampilkan
        </button>
    </form>

        </div>


        <div class="bg-white rounded-xl shadow p-5">
            @if($logs->isEmpty())
                <p class="text-sm text-gray-500">Belum ada aktivitas yang tercatat.</p>
            @else
                <div class="space-y-3">
                    @foreach($logs as $log)
                        <div class="border rounded-xl p-4">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <div class="text-xs text-gray-500">
                                        Tanggal: <span class="font-mono">{{ $log->attendance->tanggal ?? '-' }}</span>
                                        • Mulai: <span class="font-mono">{{ $log->jam_mulai }}</span>
                                        @if($log->jam_selesai)
                                            • Selesai: <span class="font-mono">{{ $log->jam_selesai }}</span>
                                        @else
                                            • <span class="text-amber-600 font-semibold">Belum selesai</span>
                                        @endif
                                    </div>
                                    <div class="mt-2 text-sm text-gray-900">
                                        {{ $log->aktivitas }}
                                    </div>
                                </div>

                                @if(!$log->jam_selesai)
                                    <form method="POST" action="{{ route('peserta.worklog.finish', $log->id) }}">
                                        @csrf
                                        <button type="submit"
                                                class="text-xs font-semibold px-3 py-1.5 rounded-lg bg-slate-700 text-white hover:bg-slate-800">
                                            Selesaikan
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
