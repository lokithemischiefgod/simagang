<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SIMAGANG – Sistem Daftar Hadir Magang</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex flex-col">

        {{-- Navbar --}}
        <header class="bg-white border-b shadow-sm">
            <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white text-xs font-bold">
                        SM
                    </div>
                    <div>
                        <h1 class="text-sm font-semibold text-gray-900">
                            SIMAGANG
                        </h1>
                        <p class="text-xs text-gray-500">
                            Sistem Daftar Hadir Magang / PKL
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-3 text-xs">
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-indigo-700">
                        Login
                    </a>
                    <a href="{{ url('/pengajuan') }}"
                       class="inline-flex items-center px-3 py-1.5 rounded-lg bg-indigo-600 text-white font-semibold hover:bg-indigo-700 transition">
                        Ajukan Magang
                    </a>
                </div>
            </div>
        </header>

        {{-- Hero --}}
        <main class="flex-1">
            <div class="max-w-6xl mx-auto px-4 py-12 grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
                <div class="space-y-5">
                    <p class="inline-flex items-center px-2 py-1 rounded-full bg-indigo-50 text-[11px] font-semibold text-indigo-700">
                        Sistem Internal Kantor • Magang & PKL
                    </p>
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 leading-tight">
                        Kelola pengajuan dan<br class="hidden md:block" />
                        <span class="text-indigo-600">daftar hadir magang</span> dalam satu sistem.
                    </h2>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        SIMAGANG membantu kantor memonitor siswa/mahasiswa magang & PKL
                        mulai dari pengajuan, verifikasi, hingga absensi harian. Peserta cukup
                        melakukan satu kali klik untuk check-in, izin, atau turun lapangan.
                    </p>
                    <div class="flex flex-wrap gap-3 text-sm">
                        <a href="{{ url('/pengajuan') }}"
                           class="inline-flex items-center px-4 py-2.5 rounded-lg bg-indigo-600 text-white font-semibold hover:bg-indigo-700 transition">
                            Ajukan Magang / PKL
                        </a>
                        <a href="{{ route('login') }}"
                           class="inline-flex items-center px-4 py-2.5 rounded-lg border border-gray-300 text-gray-700 font-semibold hover:bg-gray-50 transition">
                            Login Admin / Peserta
                        </a>
                    </div>
                    <div class="grid grid-cols-2 gap-4 pt-4 text-xs text-gray-600">
                        <div class="space-y-1">
                            <p class="font-semibold text-gray-800">Untuk Kantor</p>
                            <p>Monitor siapa yang hadir, izin, atau sedang turun lapangan secara real-time.</p>
                        </div>
                        <div class="space-y-1">
                            <p class="font-semibold text-gray-800">Untuk Peserta</p>
                            <p>Absensi harian praktis, sesuai periode magang yang telah disetujui.</p>
                        </div>
                    </div>
                </div>

                {{-- Kartu ringkasan fitur --}}
                <div class="space-y-4">
                    <div class="bg-white shadow-lg rounded-2xl p-5 border border-indigo-50">
                        <h3 class="text-sm font-semibold text-gray-900 mb-3">
                            Ringkasan Fitur Utama
                        </h3>
                        <ul class="space-y-2 text-xs text-gray-600">
                            <li>• Pengajuan magang/PKL dari sekolah, kampus, dan mandiri.</li>
                            <li>• Verifikasi & persetujuan pengajuan oleh admin kantor.</li>
                            <li>• Pembuatan akun peserta otomatis setelah disetujui.</li>
                            <li>• Absensi harian: hadir, izin, turun lapangan, dan check-out.</li>
                            <li>• Monitoring peserta dan export laporan absensi (CSV).</li>
                        </ul>
                    </div>

                    <div class="bg-white shadow rounded-2xl p-5 text-xs text-gray-600 border border-gray-100">
                        <p class="font-semibold text-gray-800 mb-2">Akses Cepat</p>
                        <div class="flex flex-col gap-2">
                            <a href="{{ url('/pengajuan') }}" class="text-indigo-600 hover:underline">
                                • Form Pengajuan Magang / PKL
                            </a>
                            <a href="{{ route('login') }}" class="text-indigo-600 hover:underline">
                                • Login Admin / Peserta
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <footer class="py-4 text-center text-xs text-gray-400 border-t bg-white">
            &copy; {{ date('Y') }} SIMAGANG – Sistem Daftar Hadir Magang / PKL.
        </footer>

    </div>
</body>
</html>
