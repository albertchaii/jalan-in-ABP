<x-guest-layout>
    <div class="glass-panel p-6 sm:p-8 lg:p-10 relative">

        {{-- Heading --}}
        <div class="text-center mb-6 sm:mb-8">
            <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-2">
                Verifikasi Email
            </h2>
            <p class="text-sm text-gray-500 leading-relaxed">
                Terima kasih telah mendaftar! Sebelum memulai, silakan verifikasi alamat email Anda dengan mengklik tautan yang baru saja kami kirimkan.
            </p>
        </div>

        {{-- Status --}}
        @if (session('status') == 'verification-link-sent')
            <div class="alert-success mb-5">
                Tautan verifikasi baru telah dikirim ke alamat email yang Anda berikan saat pendaftaran.
            </div>
        @endif

        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            {{-- Resend --}}
            <form method="POST" action="{{ route('verification.send') }}" class="w-full sm:w-auto">
                @csrf
                <button type="submit" class="btn-maroon">
                    Kirim Ulang Email Verifikasi
                </button>
            </form>

            {{-- Logout --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="link-maroon text-sm">
                    Keluar
                </button>
            </form>
        </div>

    </div>
</x-guest-layout>
