@component('mail::message')
# Reset Password SIMAGANG

Halo **{{ $user->name }}**,

Kami menerima permintaan untuk mereset password akun Anda.

@component('mail::button', ['url' => $url])
Reset Password
@endcomponent

Link ini berlaku selama **60 menit**.

Jika Anda tidak merasa melakukan permintaan ini, silakan abaikan email ini.

Terima kasih,<br>
**SIMAGANG**
@endcomponent
