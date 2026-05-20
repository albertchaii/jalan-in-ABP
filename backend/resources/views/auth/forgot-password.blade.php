<x-guest-layout>
    <div class="glass-panel p-6 sm:p-8 lg:p-10 relative" x-data="{ submitting: false }">

        {{-- Heading --}}
        <div class="text-center mb-6 sm:mb-8">
            <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-2">
                Lupa Kata Sandi?
            </h2>
            <p class="text-sm text-gray-500 leading-relaxed">
                Tidak masalah. Masukkan email Anda dan kami akan mengirimkan tautan untuk mengatur ulang kata sandi.
            </p>
        </div>

        {{-- Session Status --}}
        @if (session('status'))
            <div class="alert-success mb-5">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" x-on:submit="submitting = true">
            @csrf

            {{-- Email Field --}}
            <div class="mb-6 sm:mb-8">
                <label for="email" class="label-antigravity block mb-2">
                    EMAIL
                </label>
                <div class="input-group">
                    <div class="input-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="4" width="20" height="16" rx="2"/>
                            <path d="M22 7l-10 6L2 7"/>
                        </svg>
                    </div>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="pengguna@jalan.in"
                        required
                        autofocus
                        class="input-antigravity w-full"
                    >
                </div>
                @error('email')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            {{-- Submit Button --}}
            <button
                type="submit"
                class="btn-maroon"
                :disabled="submitting"
            >
                <span x-show="!submitting">Kirim Tautan Reset</span>
                <span x-show="submitting" x-cloak>Mengirim...</span>

                <svg x-show="submitting" x-cloak class="animate-spin" width="20" height="20" viewBox="0 0 24 24" fill="none">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                </svg>
            </button>
        </form>

        {{-- Back to Login --}}
        <div class="text-center mt-6 sm:mt-8">
            <p class="text-sm text-gray-500">
                Ingat kata sandi?
                <a href="{{ route('login') }}" class="link-maroon ml-1 font-bold">
                    Kembali ke Login
                </a>
            </p>
        </div>

    </div>
</x-guest-layout>
