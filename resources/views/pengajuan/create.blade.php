<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pengajuan Magang / PKL</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">

    <div class="min-h-screen flex flex-col">

        {{-- Top Bar --}}
        <header class="bg-white border-b shadow-sm">
            <div class="max-w-3xl mx-auto px-4 py-4 flex items-center justify-between">
                <div>
                    <h1 class="text-lg font-semibold text-gray-900">
                        SIMAGANG - Pengajuan Magang / PKL
                    </h1>
                    <p class="text-xs text-gray-500">
                        Formulir pengajuan untuk siswa, mahasiswa, maupun magang mandiri.
                    </p>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ url('/') }}"
                    class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-gray-900 transition">
                        ← Landing Page
                    </a>

                </div>

            </div>
        </header>

        {{-- Content --}}
        <main class="flex-1">
            <div class="max-w-3xl mx-auto px-4 py-8 space-y-6">

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

                @if ($errors->any())
                    <div class="bg-red-100 border border-red-200 text-red-800 text-sm px-4 py-3 rounded-xl">
                        <p class="font-semibold mb-1">Terjadi kesalahan:</p>
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Intro Card --}}
                <div class="bg-white shadow rounded-xl p-6 space-y-3">
                    <h2 class="text-xl font-semibold text-gray-900">
                        Formulir Pengajuan Magang / PKL
                    </h2>
                    <p class="text-sm text-gray-600">
                        Isi data pengajuan dengan lengkap dan benar. Pihak kantor akan melakukan verifikasi dan memberikan
                        keputusan (diterima / ditolak). Jika disetujui, akun peserta magang akan dibuat dan dapat digunakan
                        untuk mengisi daftar hadir harian.
                    </p>
                    <ul class="text-xs text-gray-500 list-disc list-inside">
                        <li>Pastikan email yang digunakan aktif.</li>
                        <li>Pastikan Nomor WhatsApp yang digunakan aktif.</li>
                        <li>Periode magang akan menjadi dasar pembatasan pengisian absensi.</li>
                        <li>Surat pengantar dapat berupa PDF resmi dari sekolah / kampus / instansi.</li>
                    </ul>
                </div>

                {{-- Form --}}
                <div class="bg-white shadow rounded-xl p-6">
                    <form method="POST"
                          action="{{ route('pengajuan.store') }}"
                          enctype="multipart/form-data"
                          class="space-y-4">
                        @csrf

                        {{-- Nama Pengaju --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">
                                Nama Pengaju <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   name="nama_pengaju"
                                   value="{{ old('nama_pengaju') }}"
                                   class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400"
                                   placeholder="Nama lengkap peserta magang">
                        </div>

                        {{-- Email Pengaju --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">
                                Email Pengaju <span class="text-red-500">*</span>
                            </label>
                            <input type="email"
                                   name="email_pengaju"
                                   value="{{ old('email_pengaju') }}"
                                   class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400"
                                   placeholder="Alamat email aktif">
                        </div>

                        {{-- Nomor WhatsApp --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">
                                Nomor WhatsApp <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                name="no_wa"
                                value="{{ old('no_wa') }}"
                                class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400"
                                placeholder="Contoh: 08xxxxxxxxxx atau +62xxxxxxxxxx">
                            <p class="text-xs text-gray-500 mt-1">
                                Format yang diterima: 08…, 62…, atau +62…
                            </p>
                        </div>

                        {{-- Tipe Pengajuan --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">
                                Tipe Pengajuan <span class="text-red-500">*</span>
                            </label>
                            <select name="tipe"
                                    class="w-full border rounded-lg px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400">
                                <option value="">Pilih tipe pengajuan</option>
                                <option value="sekolah" {{ old('tipe') === 'sekolah' ? 'selected' : '' }}>Siswa Sekolah / PKL</option>
                                <option value="universitas" {{ old('tipe') === 'universitas' ? 'selected' : '' }}>Mahasiswa Universitas</option>
                                <option value="mandiri" {{ old('tipe') === 'mandiri' ? 'selected' : '' }}>Magang Mandiri</option>
                            </select>
                        </div>

                        {{-- Instansi Asal --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">
                                Instansi Asal
                            </label>
                            <input type="text"
                                   name="instansi"
                                   value="{{ old('instansi') }}"
                                   class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400"
                                   placeholder="Nama sekolah / universitas / instansi (jika ada)">
                        </div>

                        {{-- Periode Magang --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">
                                    Tanggal Mulai Magang <span class="text-red-500">*</span>
                                </label>
                                <input type="date"
                                       name="tanggal_mulai"
                                       value="{{ old('tanggal_mulai') }}"
                                       class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">
                                    Tanggal Selesai Magang <span class="text-red-500">*</span>
                                </label>
                                <input type="date"
                                       name="tanggal_selesai"
                                       value="{{ old('tanggal_selesai') }}"
                                       class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400">
                            </div>
                        </div>

                        {{-- Surat Pengantar --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">
                                Surat Pengantar (PDF)
                            </label>
                            <input type="file"
                                   name="surat_pengantar"
                                   accept="application/pdf"
                                   class="w-full text-sm text-gray-700">
                            <p class="text-xs text-gray-500 mt-1">
                                Format file: PDF.
                            </p>
                        </div>

                        {{-- Persetujuan --}}
                        <div class="flex items-start space-x-2">
                            <input id="persetujuan"
                                   type="checkbox"
                                   required
                                   class="mt-1 border-gray-300 rounded text-indigo-600 focus:ring-indigo-500">
                            <label for="persetujuan" class="text-xs text-gray-600">
                                Saya menyatakan bahwa data yang diisi sudah benar dan bersedia mengikuti ketentuan magang yang berlaku di kantor.
                            </label>
                        </div>

                        <div class="pt-4">
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2.5 rounded-lg text-sm font-semibold bg-indigo-600 text-white hover:bg-indigo-700 transition">
                                Kirim Pengajuan
                            </button>
                        </div>

                    </form>
                </div>

            </div>
        </main>

        <footer class="py-4 text-center text-xs text-gray-400">
            &copy; {{ date('Y') }} SIMAGANG - Sistem Daftar Hadir Magang.
        </footer>
    </div>

</body>
</html>
