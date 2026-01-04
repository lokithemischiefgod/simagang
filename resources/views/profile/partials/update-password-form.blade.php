<section>
    <header class="mb-5">
        <h2 class="text-lg font-bold text-gray-900">Ganti Password</h2>
        <p class="mt-1 text-sm text-gray-600">
            Demi keamanan, Anda wajib mengganti password sebelum mengakses dashboard.
        </p>
    </header>

    @if (session('status') === 'password-updated')
        <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            Password berhasil diperbarui. Silakan kembali ke dashboard.
        </div>
    @endif

    <form method="post" action="{{ route('password.update') }}" class="space-y-4">
        @csrf
        @method('put')

        <div>
            <label for="current_password" class="block text-sm font-semibold text-gray-700">
                Password Saat Ini
            </label>
            <input id="current_password" name="current_password" type="password"
                   class="mt-1 block w-full rounded-xl border-gray-300 focus:border-gray-900 focus:ring-gray-900"
                   autocomplete="current-password">

            @if ($errors->updatePassword->get('current_password'))
                <p class="mt-1 text-xs text-red-600">
                    {{ $errors->updatePassword->first('current_password') }}
                </p>
            @endif
        </div>

        <div>
            <label for="password" class="block text-sm font-semibold text-gray-700">
                Password Baru
            </label>
            <input id="password" name="password" type="password"
                   class="mt-1 block w-full rounded-xl border-gray-300 focus:border-gray-900 focus:ring-gray-900"
                   autocomplete="new-password">

            @if ($errors->updatePassword->get('password'))
                <p class="mt-1 text-xs text-red-600">
                    {{ $errors->updatePassword->first('password') }}
                </p>
            @endif
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-semibold text-gray-700">
                Konfirmasi Password Baru
            </label>
            <input id="password_confirmation" name="password_confirmation" type="password"
                   class="mt-1 block w-full rounded-xl border-gray-300 focus:border-gray-900 focus:ring-gray-900"
                   autocomplete="new-password">
        </div>

        <div class="flex items-center justify-end pt-2">
            <button type="submit"
                    class="px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-semibold">
                Simpan Password
            </button>
        </div>
    </form>
</section>
