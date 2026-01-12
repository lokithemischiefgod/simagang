<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-50">
        <div class="w-full max-w-md space-y-6">

            {{-- Header --}}
            <div class="text-center space-y-1">
                <h1 class="text-2xl font-semibold text-gray-900">
                    Masuk ke SIMAGANG
                </h1>
                <p class="text-xs text-gray-500">
                    Gunakan akun admin, superadmin, atau peserta magang.
                </p>
            </div>

            {{-- Session Status --}}
            <x-auth-session-status class="mb-4" :status="session('status')" />

            {{-- Card --}}
            <div class="bg-white shadow rounded-xl px-6 py-6">
                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    {{-- Email Address --}}
                    <div>
                        <x-input-label for="email" :value="__('Email')" class="text-xs" />
                        <x-text-input id="email"
                                      class="block mt-1 w-full"
                                      type="email"
                                      name="email"
                                      :value="old('email')"
                                      required autofocus autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>

                    {{-- Password --}}
                    <div>
                        <x-input-label for="password" :value="__('Password')" class="text-xs" />
                        <x-text-input id="password"
                                      class="block mt-1 w-full"
                                      type="password"
                                      name="password"
                                      required autocomplete="current-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-1" />
                    </div>

                    {{-- Remember Me --}}
                    <div class="flex items-center justify-between mt-2">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me"
                                   type="checkbox"
                                   class="rounded border-gray-300 text-lintasarta-blue shadow-sm focus:ring-lintasarta-blue"
                                   name="remember">
                            <span class="ml-2 text-xs text-gray-600">{{ __('Remember me') }}</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a class="text-xs text-lintasarta-blue hover:text-lintasarta-navy"
                               href="{{ route('password.request') }}">
                                {{ __('Forgot your password?') }}
                            </a>
                        @endif
                    </div>

                    <div class="flex items-center justify-between mt-4">
                        <a href="{{ url('/') }}" class="text-xs text-gray-500 hover:text-gray-700">
                            &larr; Kembali ke landing
                        </a>

                        <x-primary-button class="ml-3">
                            {{ __('Log in') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>

            <div class="text-center text-xs text-gray-500">
                Belum punya akun? Akun peserta dibuat setelah pengajuan disetujui oleh admin.
            </div>
        </div>
    </div>
</x-guest-layout>
