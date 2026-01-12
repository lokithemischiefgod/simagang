<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-50">
        <div class="w-full max-w-md space-y-6">

            {{-- Header --}}
            <div class="text-center space-y-1">
                <h1 class="text-2xl font-semibold text-gray-900">
                    Registrasi Akun
                </h1>
                <p class="text-xs text-gray-500">
                    Disarankan hanya digunakan oleh pihak internal saat setup awal.
                </p>
            </div>

            {{-- Card --}}
            <div class="bg-white shadow rounded-xl px-6 py-6">
                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    {{-- Name --}}
                    <div>
                        <x-input-label for="name" :value="__('Name')" class="text-xs" />
                        <x-text-input id="name"
                                      class="block mt-1 w-full"
                                      type="text"
                                      name="name"
                                      :value="old('name')"
                                      required autofocus autocomplete="name" />
                        <x-input-error :messages="$errors->get('name')" class="mt-1" />
                    </div>

                    {{-- Email --}}
                    <div>
                        <x-input-label for="email" :value="__('Email')" class="text-xs" />
                        <x-text-input id="email"
                                      class="block mt-1 w-full"
                                      type="email"
                                      name="email"
                                      :value="old('email')"
                                      required autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>

                    {{-- Password --}}
                    <div>
                        <x-input-label for="password" :value="__('Password')" class="text-xs" />
                        <x-text-input id="password"
                                      class="block mt-1 w-full"
                                      type="password"
                                      name="password"
                                      required autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-1" />
                    </div>

                    {{-- Confirm Password --}}
                    <div>
                        <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-xs" />
                        <x-text-input id="password_confirmation"
                                      class="block mt-1 w-full"
                                      type="password"
                                      name="password_confirmation"
                                      required autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
                    </div>

                    <div class="flex items-center justify-between mt-4">
                        <a href="{{ url('/') }}" class="text-xs text-gray-500 hover:text-gray-700">
                            &larr; Kembali ke landing
                        </a>

                        <x-primary-button class="ml-3">
                            {{ __('Register') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>

            <div class="text-center text-xs text-gray-500">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-lintasarta-blue hover:text-lintasarta-navy">
                    Login di sini
                </a>
            </div>

        </div>
    </div>
</x-guest-layout>
