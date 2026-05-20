<x-guest-layout>
    <div class="glass-panel p-6 sm:p-8 lg:p-10 relative" x-data="{ submitting: false }">

        {{-- Heading --}}
        <div class="text-center mb-6 sm:mb-8">
            <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-2">
                Konfirmasi Password
            </h2>
            <p class="text-sm text-gray-500 leading-relaxed">
                Ini adalah area aman. Silakan konfirmasi kata sandi Anda sebelum melanjutkan.
            </p>
        </div>

        <form method="POST" action="{{ route('password.confirm') }}" x-on:submit="submitting = true">
            @csrf

            {{-- Password Field --}}
            <div class="mb-6 sm:mb-8">
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
                        type="password"
                        name="password"
                        placeholder="••••••••"
                        required
                        autocomplete="current-password"
                        class="input-antigravity w-full"
                    >
                </div>
                @error('password')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            {{-- Submit Button --}}
            <button
                type="submit"
                class="btn-maroon"
                :disabled="submitting"
            >
                <span x-show="!submitting">Konfirmasi</span>
                <span x-show="submitting" x-cloak>Memproses...</span>

                <svg x-show="submitting" x-cloak class="animate-spin" width="20" height="20" viewBox="0 0 24 24" fill="none">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                </svg>
            </button>
        </form>

    </div>
</x-guest-layout>
