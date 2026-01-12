<x-app-layout>

<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">
                    Manajemen Admin
                </h1>
                <p class="text-sm text-gray-600 mt-1">
                    Kelola akun admin yang memiliki akses ke panel SIMAGANG.
                </p>
            </div>
            <div>
                <a href="{{ route('superadmin.admins.create') }}"
                   class="inline-flex items-center px-4 py-2.5 rounded-lg text-sm font-semibold bg-lintasarta-blue text-white hover:bg-lintasarta-navy transition">
                    + Tambah Admin Baru
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

        {{-- Tabel Admin --}}
        <div class="bg-white shadow rounded-xl p-6">
            @if ($admins->isEmpty())
                <p class="text-sm text-gray-600">
                    Belum ada admin terdaftar.
                </p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm border border-gray-200 rounded-lg overflow-hidden">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-2 text-left font-semibold text-gray-700 border-b text-xs">Nama</th>
                                <th class="px-3 py-2 text-left font-semibold text-gray-700 border-b text-xs">Email</th>
                                <th class="px-3 py-2 text-left font-semibold text-gray-700 border-b text-xs">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @foreach ($admins as $admin)
                                <tr class="border-t border-gray-200">
                                    <td class="px-3 py-2 text-sm text-gray-900">
                                        {{ $admin->name }}
                                    </td>
                                    <td class="px-3 py-2 text-xs text-gray-700">
                                        {{ $admin->email }}
                                    </td>
                                    <td class="px-3 py-2 text-xs">
                                        @if(auth()->user()->role === 'superadmin' && $admin->role === 'admin')
                                        <form action="{{ route('superadmin.admins.promote', $admin->id) }}"
                                            method="POST"
                                            class="inline"
                                            onsubmit="return confirm('Yakin jadikan {{ $admin->name }} sebagai Superadmin?')">
                                            @csrf
                                            @method('PATCH')

                                            <button class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-lintasarta-blue text-white hover:bg-lintasarta-navy transition">
                                                Jadikan Superadmin
                                            </button>
                                        </form>
                                        @endif

                                        <form action="{{ route('superadmin.admins.destroy', $admin->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Yakin ingin menghapus admin ini?');"
                                              class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-red-600 text-white hover:bg-red-700 transition">
                                                Hapus
                                            </button>
                                        </form>
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
