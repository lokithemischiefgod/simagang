<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Masukkan alamat email anda agar kami dapat mengirimkan tautan untuk mengatur ulang kata sandi akun anda.') }}
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="mb-4 text-sm font-medium text-[#50C878]">
            Tautan reset password SIMAGANG telah kami kirim ke email Anda.
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            @if ($errors->has('email'))
                <div class="mt-2 text-sm text-red-600">
                    @if (str_contains($errors->first('email'), 'wait'))
                        Mohon tunggu beberapa saat sebelum mencoba kembali mengirim email reset password.
                    @else
                        {{ $errors->first('email') }}
                    @endif
                </div>
            @endif

        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="inline-flex items-center px-4 py-2.5 rounded-lg bg-lintasarta-blue text-white font-semibold hover:bg-lintasarta-navy transition" >
                {{ __('KIRIM EMAIL') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
