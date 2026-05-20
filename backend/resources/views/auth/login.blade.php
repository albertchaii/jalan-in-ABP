<x-guest-layout>
    <div class="glass-panel p-6 sm:p-8 lg:p-10 relative" x-data="{
        submitting: false,
        showPassword: false,
    }">

        {{-- Welcome Text --}}
        <div class="text-center mb-6 sm:mb-8">
            <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-2">
                Selamat Datang Kembali
            </h2>
            <p class="text-sm text-gray-500 leading-relaxed">
                Masuk untuk mengelola infrastruktur sipil Anda.
            </p>
        </div>

        {{-- Session Status --}}
        @if (session('status'))
            <div class="alert-success mb-5">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" x-on:submit="submitting = true">
            @csrf

            {{-- Email Field --}}
            <div class="mb-5 sm:mb-6">
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
                        autocomplete="username"
                        class="input-antigravity w-full"
                    >
                </div>
                @error('email')
                    @if ($message !== __('auth.failed'))
                        <p class="error-message">{{ $message }}</p>
                    @endif
                @enderror
            </div>

            {{-- Password Field --}}
            <div class="mb-5 sm:mb-6">
                <div class="flex items-center justify-between mb-2">
                    <label for="password" class="label-antigravity">
                        PASSWORD
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="link-maroon text-xs">
                            Lupa Password
                        </a>
                    @endif
                </div>
                <div class="input-group">
                    <div class="input-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                            <path d="M7 11V7a5 5 0 0110 0v4"/>
                        </svg>
                    </div>
                    <input
                        id="password"
                        :type="showPassword ? 'text' : 'password'"
                        name="password"
                        placeholder="password123"
                        required
                        autocomplete="current-password"
                        class="input-antigravity w-full"
                    >
                    {{-- Toggle Password Visibility --}}
                    <button
                        type="button"
                        class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors z-10"
                        x-on:click="showPassword = !showPassword"
                        tabindex="-1"
                    >
                        <svg x-show="!showPassword" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                        <svg x-show="showPassword" x-cloak width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/>
                            <line x1="1" y1="1" x2="23" y2="23"/>
                        </svg>
                    </button>
                </div>
                @error('password')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            {{-- Auth Failed Error --}}
            @if ($errors->has('email') && $errors->first('email') === __('auth.failed'))
                <div class="alert-error-inline mb-5 flex items-center gap-2">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="8" x2="12" y2="12"/>
                        <line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                    <span>Email atau password salah</span>
                </div>
            @endif

            {{-- Submit Button --}}
            <button
                type="submit"
                class="btn-maroon"
                :disabled="submitting"
            >
                <span x-show="!submitting">Masuk</span>
                <span x-show="submitting" x-cloak>Masuk...</span>

                <svg x-show="submitting" x-cloak class="animate-spin" width="20" height="20" viewBox="0 0 24 24" fill="none">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                </svg>
            </button>
        </form>

        {{-- Register Link --}}
        <div class="text-center mt-6 sm:mt-8">
            <p class="text-sm text-gray-500">
                Belum punya akun?
                <a href="{{ route('register') }}" class="link-maroon ml-1 font-bold">
                    Daftar
                </a>
            </p>
        </div>

    </div>
</x-guest-layout>
