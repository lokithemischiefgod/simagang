<x-app-layout>
    <div class="min-h-[calc(100vh-4rem)] flex items-center justify-center px-4">
        <div class="w-full max-w-md">

            @if (session('error'))
                <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            <div class="rounded-2xl bg-white shadow-sm border border-gray-200 p-6">
                @include('profile.partials.update-password-form')
            </div>

        </div>
    </div>
</x-app-layout>
