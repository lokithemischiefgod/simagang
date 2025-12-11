{{-- resources/views/layouts/admin.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>SIMAGANG - Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <nav class="bg-white border-b shadow-sm">
            <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between">
                <div class="space-x-4">
                    <a href="{{ route('admin.pengajuan.index') }}" class="text-sm font-medium">Pengajuan</a>
                    <a href="{{ route('admin.absensi.index') }}" class="text-sm font-medium">Absensi</a>
                    <a href="{{ route('admin.peserta.index') }}" class="text-sm font-medium">Peserta</a>
                    @if(auth()->user()?->role === 'superadmin')
                        <a href="{{ route('superadmin.admins.index') }}" class="text-sm font-medium">Manajemen Admin</a>
                    @endif
                </div>
                <div>
                    <span class="text-sm text-gray-600 mr-3">{{ auth()->user()->name ?? '' }}</span>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button class="text-sm text-red-500">Logout</button>
                    </form>
                </div>
            </div>
        </nav>

        <main class="py-6">
            <div class="max-w-7xl mx-auto px-4">
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
