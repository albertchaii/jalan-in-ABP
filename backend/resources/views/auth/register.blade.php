<x-guest-layout>
    <div class="glass-panel p-6 sm:p-8 lg:p-10 relative" x-data="{
        submitting: false,
        showPassword: false,
        showPasswordConfirm: false,
    }">

        {{-- Logo inside card --}}
        <div class="text-center mb-4 sm:mb-5">
            <a href="/" class="inline-block">
                <span class="text-2xl sm:text-3xl font-black tracking-tight italic" style="color: var(--maroon);">
                    jalan.in
                </span>
            </a>
        </div>

        {{-- Heading --}}
        <div class="text-center mb-2 sm:mb-3">
            <h2 class="text-xl sm:text-2xl font-bold text-gray-800">
                Daftar Akun Baru
            </h2>
        </div>

        {{-- Subtitle --}}
        <div class="text-center mb-6 sm:mb-8">
            <p class="text-xs sm:text-sm font-semibold tracking-widest uppercase" style="color: var(--maroon); opacity: 0.7;">
                Internal Infrastructure Use Only
            </p>
        </div>

        <form method="POST" action="{{ route('register') }}" x-on:submit="submitting = true">
            @csrf

            {{-- Name Field --}}
            <div class="mb-5 sm:mb-6">
                <label for="name" class="label-antigravity block mb-2">
                    NAMA LENGKAP
                </label>
                <div class="input-group">
                    <div class="input-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                    </div>
                    <input
                        id="name"
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="Masukkan nama lengkap"
                        required
                        autofocus
                        autocomplete="name"
                        class="input-antigravity w-full"
                    >
                </div>
                @error('name')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

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
                        placeholder="contoh@jalan.in"
                        required
                        autocomplete="username"
                        class="input-antigravity w-full"
                    >
                </div>
                @error('email')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password & Confirm Password Side by Side --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5 mb-5 sm:mb-6">
                {{-- Password Field --}}
                <div>
                    <label for="password" class="label-antigravity block mb-2">
                        PASSWORD
                    </label>
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
                            placeholder="••••••••"
                            required
                            autocomplete="new-password"
                            class="input-antigravity w-full"
                        >
                        <button
                            type="button"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors z-10"
                            x-on:click="showPassword = !showPassword"
                            tabindex="-1"
                        >
                            <svg x-show="!showPassword" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            <svg x-show="showPassword" x-cloak width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/>
                                <line x1="1" y1="1" x2="23" y2="23"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirm Password Field --}}
                <div>
                    <label for="password_confirmation" class="label-antigravity block mb-2">
                        KONFIRMASI PASSWORD
                    </label>
                    <div class="input-group">
                        <div class="input-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                            </svg>
                        </div>
                        <input
                            id="password_confirmation"
                            :type="showPasswordConfirm ? 'text' : 'password'"
                            name="password_confirmation"
                            placeholder="••••••••"
                            required
                            autocomplete="new-password"
                            class="input-antigravity w-full"
                        >
                        <button
                            type="button"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors z-10"
                            x-on:click="showPasswordConfirm = !showPasswordConfirm"
                            tabindex="-1"
                        >
                            <svg x-show="!showPasswordConfirm" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            <svg x-show="showPasswordConfirm" x-cloak width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/>
                                <line x1="1" y1="1" x2="23" y2="23"/>
                            </svg>
                        </button>
                    </div>
                    @error('password_confirmation')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Info Box --}}
            <div class="info-box mb-6 sm:mb-8">
                <div class="flex items-start gap-3">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0 mt-0.5 text-gray-400">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="16" x2="12" y2="12"/>
                        <line x1="12" y1="8" x2="12.01" y2="8"/>
                    </svg>
                    <p class="text-xs sm:text-sm text-gray-500 leading-relaxed">
                        Akun admin hanya dapat dibuat oleh sistem. Pastikan data diri sesuai dengan kredensial departemen.
                    </p>
                </div>
            </div>

            {{-- Submit Button --}}
            <button
                type="submit"
                class="btn-maroon"
                :disabled="submitting"
            >
                <span x-show="!submitting">Daftar</span>
                <span x-show="submitting" x-cloak>Mendaftar...</span>

                <svg x-show="submitting" x-cloak class="animate-spin" width="20" height="20" viewBox="0 0 24 24" fill="none">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                </svg>
            </button>
        </form>

        {{-- Login Link --}}
        <div class="text-center mt-6 sm:mt-8">
            <p class="text-sm text-gray-500">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="link-maroon ml-1 font-bold">
                    Masuk
                </a>
            </p>
        </div>

    </div>
</x-guest-layout>
