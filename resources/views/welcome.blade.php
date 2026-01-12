<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMAGANG</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white-50 text-gray-900">
    <div class="min-h-screen flex flex-col">

        {{-- Main --}}
        <main class="flex-1 flex items-center">
            <div class="max-w-5xl mx-auto px-4 w-full">
                <div class="max-w-2xl space-y-6">

                    <div class="flex mb-6">
                        <img src="{{ asset('images/lintasarta.png') }}"
                            alt="Lintasarta"
                            class="h-20">
                    </div>

                    {{-- Judul utama --}}
                    <h1 class="text-3xl md:text-4xl font-extrabold leading-tight">
                        Sistem pengajuan dan absensi magang.
                    </h1>

                    <div class="text-sm text-gray-600 leading-relaxed space-y-2">
                        <p>
                            Silakan gunakan sistem ini untuk mengajukan magang atau PKL secara resmi.
                            Pengajuan akan diverifikasi oleh pihak kantor sebelum peserta dapat
                            melakukan absensi harian.
                        </p>

                    </div>

                    {{-- Aksi utama --}}
                    <div class="flex flex-wrap gap-3 pt-2">
                        <a href="{{ url('/pengajuan') }}"
                           class="inline-flex items-center px-4 py-2.5 rounded-lg bg-lintasarta-blue text-white font-semibold hover:bg-lintasarta-navy transition">
                            Ajukan Magang
                        </a>

                        <a href="{{ route('login') }}"
                           class="inline-flex items-center px-4 py-2.5 rounded-lg border border-gray-300 text-gray-800 font-semibold hover:bg-white transition">
                            Login
                        </a>
                    </div>

                </div>
            </div>
        </main>

        {{-- Footer ringan --}}
        <footer class="py-4 text-center text-xs text-gray-400">
            &copy; {{ date('Y') }} SIMAGANG
        </footer>

    </div>
</body>
</html>
