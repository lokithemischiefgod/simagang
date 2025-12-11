@extends('layouts.admin')

@section('content')
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
                <div class="md:col-span-3">
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        Cari Peserta (Nama / Email)
                    </label>
                    <input type="text" name="q" value="{{ $q }}" placeholder="Nama atau email"
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400">
                </div>
                <div>
                    <button type="submit"
                            class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-lg text-sm font-semibold bg-indigo-600 text-white hover:bg-indigo-700 transition">
                        Cari
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
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm border border-gray-200 rounded-lg overflow-hidden">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-2 text-left font-semibold text-gray-700 border-b text-xs">Nama</th>
                                <th class="px-3 py-2 text-left font-semibold text-gray-700 border-b text-xs">Email</th>
                                <th class="px-3 py-2 text-left font-semibold text-gray-700 border-b text-xs">Instansi Asal</th>
                                <th class="px-3 py-2 text-left font-semibold text-gray-700 border-b text-xs">Periode Magang</th>
                                <th class="px-3 py-2 text-left font-semibold text-gray-700 border-b text-xs">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @foreach ($peserta as $p)
                                @php
                                    $req = $pengajuanPerEmail[$p->email] ?? null;
                                @endphp
                                <tr class="border-t border-gray-200">
                                    <td class="px-3 py-2 text-sm text-gray-900">
                                        {{ $p->name }}
                                    </td>
                                    <td class="px-3 py-2 text-xs text-gray-700">
                                        {{ $p->email }}
                                    </td>
                                    <td class="px-3 py-2 text-xs text-gray-700">
                                        @if ($req)
                                            {{ $req->instansi ?? '-' }}
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
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
            @endif
        </div>

    </div>
@endsection
