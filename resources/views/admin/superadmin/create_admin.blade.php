<x-app-layout>

<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="space-y-6 max-w-xl">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">
                    Tambah Admin Baru
                </h1>
                <p class="text-sm text-gray-600 mt-1">
                    Buat akun admin yang dapat mengelola pengajuan, absensi, dan peserta.
                </p>
            </div>
            <div>
                <a href="{{ route('superadmin.admins.index') }}"
                   class="inline-flex items-center px-3 py-2 rounded-lg text-xs font-semibold border border-gray-300 text-gray-700 hover:bg-gray-50 transition">
                    &larr; Kembali ke Manajemen Admin
                </a>
            </div>
        </div>

        {{-- Error Validasi --}}
        @if ($errors->any())
            <div class="bg-red-100 border border-red-200 text-red-800 text-sm px-4 py-3 rounded-xl mb-2">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form --}}
        <div class="bg-white shadow rounded-xl p-6">
            <form method="POST" action="{{ route('superadmin.admins.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        Nama
                    </label>
                    <input type="text"
                           name="name"
                           value="{{ old('name') }}"
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        Email
                    </label>
                    <input type="email"
                           name="email"
                           value="{{ old('email') }}"
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        Password
                    </label>
                    <input type="password"
                           name="password"
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400">
                    <p class="text-xs text-gray-500 mt-1">
                        Sampaikan password ini ke admin terkait dan anjurkan untuk segera menggantinya.
                    </p>
                </div>

                <div class="pt-2">
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2.5 rounded-lg text-sm font-semibold bg-indigo-600 text-white hover:bg-indigo-700 transition">
                        Simpan Admin
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
</x-app-layout>
