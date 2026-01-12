<x-app-layout>

<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">
                    Daftar Pengajuan Magang / PKL
                </h1>
                <p class="text-sm text-gray-600 mt-1">
                    Kelola pengajuan magang dari peserta (pending, disetujui, dan ditolak).
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

        {{-- Tabel Pengajuan --}}
        <div class="bg-white shadow rounded-xl p-6">
            @if ($items->isEmpty())
                <p class="text-sm text-gray-600">
                    Belum ada pengajuan yang masuk.
                </p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm border border-gray-200 rounded-lg overflow-hidden">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-2 text-left font-semibold text-gray-700 border-b text-xs">ID</th>
                                <th class="px-3 py-2 text-left font-semibold text-gray-700 border-b text-xs">Nama Pengaju</th>
                                <th class="px-3 py-2 text-left font-semibold text-gray-700 border-b text-xs">Email</th>
                                <th class="px-3 py-2 text-left font-semibold text-gray-700 border-b text-xs">No. WA</th>
                                <th class="px-3 py-2 text-left font-semibold text-gray-700 border-b text-xs">Tipe</th>
                                <th class="px-3 py-2 text-left font-semibold text-gray-700 border-b text-xs">Instansi</th>
                                <th class="px-3 py-2 text-left font-semibold text-gray-700 border-b text-xs">Periode Magang</th>
                                <th class="px-3 py-2 text-left font-semibold text-gray-700 border-b text-xs">Surat Pengantar</th>
                                <th class="px-3 py-2 text-left font-semibold text-gray-700 border-b text-xs">Status</th>
                                <th class="px-3 py-2 text-left font-semibold text-gray-700 border-b text-xs">Alasan Penolakan</th>
                                <th class="px-3 py-2 text-left font-semibold text-gray-700 border-b text-xs">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @foreach ($items as $item)
                                @php
                                    $statusText = strtoupper($item->status);
                                    $statusClass = match($item->status) {
                                        'pending' => 'bg-amber-100 text-amber-800',
                                        'approved' => 'bg-emerald-100 text-emerald-800',
                                        'rejected' => 'bg-red-100 text-red-800',
                                        default => 'bg-gray-100 text-gray-800',
                                    };
                                @endphp
                                <tr class="border-t border-gray-200">
                                    <td class="px-3 py-2 text-xs text-gray-700 font-mono">
                                        {{ $item->id }}
                                    </td>
                                    <td class="px-3 py-2 text-sm text-gray-900">
                                        {{ $item->nama_pengaju }}
                                    </td>
                                    <td class="px-3 py-2 text-xs text-gray-700">
                                        {{ $item->email_pengaju }}
                                    </td>
                                    <td class="px-3 py-2 text-xs text-gray-700">
                                        <a href="https://wa.me/{{ $item->no_wa }}"
                                        target="_blank"
                                        class="text-emerald-700 hover:underline font-semibold">
                                            {{ $item->no_wa }}
                                        </a>
                                    </td>
                                    <td class="px-3 py-2 text-xs text-gray-700">
                                        {{ $item->tipe }}
                                    </td>
                                    <td class="px-3 py-2 text-xs text-gray-700">
                                        {{ $item->instansi ?? '-' }}
                                    </td>
                                    <td class="px-3 py-2 text-xs text-gray-700">
                                        @if ($item->tanggal_mulai && $item->tanggal_selesai)
                                            {{ $item->tanggal_mulai }} s/d {{ $item->tanggal_selesai }}
                                        @elseif ($item->tanggal_mulai)
                                            Mulai: {{ $item->tanggal_mulai }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-3 py-2 text-xs">
                                        @if ($item->surat_pengantar)
                                            <a href="{{ asset('storage/' . $item->surat_pengantar) }}"
                                               target="_blank"
                                               class="text-blue-600 hover:underline">
                                                Lihat Surat
                                            </a>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-2 text-xs">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full font-semibold {{ $statusClass }}">
                                            {{ $statusText }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-2 text-xs text-gray-700">
                                        {{ $item->alasan_penolakan ?? '-' }}
                                    </td>
                                    <td class="px-3 py-2 text-xs text-gray-700">
                                        <div class="flex flex-col gap-2">

                                            {{-- AKSI PENDING --}}
                                            @if ($item->status === 'pending')

                                                {{-- Approve --}}
                                                <form action="{{ route('admin.pengajuan.updateStatus', $item->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    <input type="hidden" name="status" value="approved">
                                                    <button type="submit"
                                                            class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-600 text-white hover:bg-emerald-700">
                                                        Setujui
                                                    </button>
                                                </form>

                                                {{-- Reject --}}
                                                <form action="{{ route('admin.pengajuan.updateStatus', $item->id) }}"
                                                    method="POST"
                                                    class="flex flex-col gap-1">
                                                    @csrf
                                                    <input type="hidden" name="status" value="rejected">
                                                    <input
                                                        type="text"
                                                        name="alasan_penolakan"
                                                        placeholder="Alasan..."
                                                        class="border rounded px-2 py-1 text-xs">
                                                    <button type="submit"
                                                            class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-red-600 text-white hover:bg-red-700">
                                                        Tolak
                                                    </button>
                                                </form>

                                                {{-- Hapus --}}
                                                <form action="{{ route('admin.pengajuan.destroy', $item->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Hapus pengajuan ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-gray-600 text-white hover:bg-gray-700">
                                                        Hapus
                                                    </button>
                                                </form>

                                            {{-- AKSI REJECTED --}}
                                            @elseif ($item->status === 'rejected')

                                                <form action="{{ route('admin.pengajuan.destroy', $item->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Hapus pengajuan ditolak ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-gray-600 text-white hover:bg-gray-700">
                                                        Hapus
                                                    </button>
                                                </form>

                                            {{-- APPROVED --}}
                                            @else
                                                <span class="italic text-gray-500">
                                                    Final.
                                                </span>
                                            @endif
                                        </div>
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
