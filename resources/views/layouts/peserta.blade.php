{{-- resources/views/layouts/peserta.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>SIMAGANG</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
<div class="min-h-screen">
    <main class="py-6">
        <div class="max-w-5xl mx-auto px-4">
            @yield('content')
        </div>
    </main>
</div>
</body>
</html>
