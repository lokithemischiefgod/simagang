<x-app-layout>

<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">
                    Daftar Peserta Magang
                </h1>
                <p class="text-sm text-gray-600 mt-1">
                    Data peserta yang telah memiliki akun dan terdaftar dalam sistem.
                </p>
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
        <div class="bg-white shadow rounded-xl p-6">
            <form method="GET" action="{{ route('admin.peserta.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        Cari Peserta (Nama / Email)
                    </label>
                    <input type="text" name="q" value="{{ $q }}" placeholder="Nama atau email"
                        class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-lintasarta-navy focus:border-lintasarta-navy">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        Status Peserta
                    </label>
                    <select name="status"
                            class="w-full border rounded-lg px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-lintasarta-navy focus:border-lintasarta-navy">
                        <option value="all" {{ ($statusFilter ?? 'all') === 'all' ? 'selected' : '' }}>Semua</option>
                        <option value="active" {{ ($statusFilter ?? 'all') === 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ ($statusFilter ?? 'all') === 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                </div>

                <div>
                    <button type="submit"
                            class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-lg text-sm font-semibold bg-lintasarta-blue text-white hover:bg-lintasarta-navy transition">
                        Terapkan
                    </button>
                </div>
            </form>
        </div>


        {{-- Tabel Peserta --}}
        <div class="bg-white shadow rounded-xl p-6">
            @if ($peserta->isEmpty())
                <p class="text-sm text-gray-600">
                    Belum ada peserta magang yang terdaftar.
                </p>
            @else

                {{-- Bulk Delete Form --}}
                <form method="POST" action="{{ route('admin.peserta.bulkDestroy') }}" id="bulkDeleteForm">
                    @csrf
                    @method('DELETE')

                    {{-- biar setelah delete, filter tetap sama (optional) --}}
                    <input type="hidden" name="q" value="{{ $q }}">
                    <input type="hidden" name="status" value="{{ $statusFilter ?? 'all' }}">

                    <div class="flex items-center justify-between mb-3">
                        <div class="text-xs text-gray-500">
                            Centang peserta untuk dihapus (akun, absensi, dan pengajuan akan ikut terhapus).
                        </div>

                        <button type="submit"
                                onclick="return confirm('Yakin hapus peserta yang dipilih? Data absensi & aktivitas ikut terhapus.')"
                                class="inline-flex items-center px-3 py-2 rounded-lg text-xs font-semibold bg-rose-600 text-white hover:bg-rose-700 transition disabled:opacity-50"
                                id="bulkDeleteBtn"
                                disabled>
                            Hapus Terpilih
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm border border-gray-200 rounded-lg overflow-hidden">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 py-2 border-b text-xs">
                                        <input type="checkbox" id="checkAll" class="rounded border-gray-300">
                                    </th>
                                    <th class="px-3 py-2 text-left font-semibold text-gray-700 border-b text-xs">Nama</th>
                                    <th class="px-3 py-2 text-left font-semibold text-gray-700 border-b text-xs">Email</th>
                                    <th class="px-3 py-2 text-left font-semibold text-gray-700 border-b text-xs">Instansi Asal</th>
                                    <th class="px-3 py-2 text-left font-semibold text-gray-700 border-b text-xs">Periode Magang</th>
                                    <th class="px-3 py-2 text-left font-semibold text-gray-700 border-b text-xs">Status</th>
                                    <th class="px-3 py-2 text-left font-semibold text-gray-700 border-b text-xs">Aksi</th>
                                </tr>
                            </thead>

                            <tbody class="bg-white">
                                @foreach ($peserta as $p)
                                    @php
                                        $req = $pengajuanPerEmail[$p->email] ?? null;
                                        $today = now()->toDateString();
                                        $isInactive = ($req && $req->tanggal_selesai) ? ($req->tanggal_selesai < $today) : null;
                                    @endphp

                                    <tr class="border-t border-gray-200">
                                        <td class="px-3 py-2 text-xs">
                                        <input type="checkbox"
                                        name="ids[]"
                                        value="{{ $p->id }}"
                                        class="rowCheck rounded border-gray-300">
                                        </td>

                                        <td class="px-3 py-2 text-sm text-gray-900">
                                            {{ $p->name }}
                                        </td>

                                        <td class="px-3 py-2 text-xs text-gray-700">
                                            {{ $p->email }}
                                        </td>

                                        <td class="px-3 py-2 text-xs text-gray-700">
                                            {{ $req->instansi ?? '-' }}
                                        </td>

                                        <td class="px-3 py-2 text-xs text-gray-700">
                                            @if ($req && $req->tanggal_mulai && $req->tanggal_selesai)
                                                {{ $req->tanggal_mulai }} s/d {{ $req->tanggal_selesai }}
                                            @elseif ($req && $req->tanggal_mulai)
                                                Mulai: {{ $req->tanggal_mulai }}
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>

                                        <td class="px-3 py-2 text-xs">
                                            @if($isInactive === null)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-gray-100 text-gray-700 font-semibold">-</span>
                                            @elseif($isInactive)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-rose-100 text-rose-800 font-semibold">Tidak Aktif</span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 font-semibold">Aktif</span>
                                            @endif
                                        </td>

                                        <td class="px-3 py-2 text-xs">
                                            <a href="{{ route('admin.peserta.show', $p->id) }}"
                                            class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-700 text-white hover:bg-slate-800 transition">
                                                Lihat Detail
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </form>

                {{-- JS kecil untuk check all + enable tombol --}}
                <script>
                    const checkAll = document.getElementById('checkAll');
                    const btn = document.getElementById('bulkDeleteBtn');

                    function refreshBtn() {
                        const checked = document.querySelectorAll('.rowCheck:checked').length;
                        btn.disabled = checked === 0;
                    }

                    checkAll?.addEventListener('change', (e) => {
                        document.querySelectorAll('.rowCheck').forEach(cb => cb.checked = e.target.checked);
                        refreshBtn();
                    });

                    document.querySelectorAll('.rowCheck').forEach(cb => {
                        cb.addEventListener('change', refreshBtn);
                    });

                    refreshBtn();
                </script>

            @endif
        </div>

    </div>
</div>
</x-app-layout>
