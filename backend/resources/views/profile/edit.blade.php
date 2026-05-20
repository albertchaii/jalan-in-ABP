@extends(Auth::user()->role === 'operator' ? 'layouts.operator' : 'layouts.admin')

@section('content')

    {{-- Success / Status Flash --}}
    @if (session('status') === 'profile-updated')
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             style="position:fixed;top:20px;right:24px;z-index:999;background:#ecfdf5;border:1px solid rgba(34,197,94,0.25);color:#15803d;padding:12px 20px;border-radius:12px;font-size:0.85rem;font-weight:600;box-shadow:0 4px 16px rgba(0,0,0,0.08);">
            ✓ Profil berhasil diperbarui.
        </div>
    @endif
    @if (session('status') === 'password-updated')
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             style="position:fixed;top:20px;right:24px;z-index:999;background:#ecfdf5;border:1px solid rgba(34,197,94,0.25);color:#15803d;padding:12px 20px;border-radius:12px;font-size:0.85rem;font-weight:600;box-shadow:0 4px 16px rgba(0,0,0,0.08);">
            ✓ Kata sandi berhasil diperbarui.
        </div>
    @endif

    {{-- Page Header --}}
    <div style="margin-bottom: 32px;">
        <h1 class="page-title">Pengaturan Akun</h1>
        <p class="page-subtitle">Kelola kredensial profesional dan preferensi sistem Anda.</p>
    </div>

    {{-- Main Profile Card --}}
    <div class="admin-card" style="max-width: 920px; padding: 0; overflow: hidden;">
        <div style="display: flex; gap: 0; min-height: 500px;">

            {{-- ===== LEFT: Avatar Panel ===== --}}
            <div style="width: 240px; flex-shrink: 0; background: #faf7f7; border-right: 1px solid rgba(107,29,42,0.06); display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 40px 24px; gap: 20px;">
                {{-- Avatar --}}
                <div style="position: relative; display: inline-block;">
                    <div style="width: 96px; height: 96px; border-radius: 50%; background: linear-gradient(135deg, #d4b8b8 0%, #b89898 100%); display: flex; align-items: center; justify-content: center; font-size: 2rem; font-weight: 800; color: var(--maroon, #6B1D2A); border: 4px solid white; box-shadow: 0 4px 20px rgba(107,29,42,0.12);">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    {{-- Edit Avatar Button --}}
                    <button type="button"
                        style="position: absolute; bottom: 4px; right: 0; width: 28px; height: 28px; border-radius: 50%; background: var(--maroon, #6B1D2A); border: 2px solid white; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 2px 8px rgba(107,29,42,0.25);"
                        title="Ganti foto profil"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="white" width="12" height="12">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z" />
                        </svg>
                    </button>
                </div>

                {{-- Name --}}
                <h2 style="font-size: 1rem; font-weight: 700; color: #1a1a1a; text-align: center;">
                    {{ Auth::user()->name }}
                </h2>
            </div>

            {{-- ===== RIGHT: Details Panel ===== --}}
            <div style="flex: 1; padding: 36px 40px; display: flex; flex-direction: column; gap: 0;">

                {{-- ─── RINCIAN PRIBADI ─── --}}
                <div style="padding-bottom: 32px; border-bottom: 1px solid rgba(107,29,42,0.06);">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="20" height="20" style="color: var(--maroon, #6B1D2A);">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                            <span style="font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em; color: #374151;">Rincian Pribadi</span>
                        </div>

                        {{-- Edit Info Toggle Button --}}
                        <button type="button" id="toggleEditBtn"
                            style="font-size: 0.8rem; font-weight: 600; color: var(--maroon, #6B1D2A); background: none; border: none; cursor: pointer; padding: 4px 8px; border-radius: 6px; transition: background 0.2s ease;"
                            onclick="toggleEditMode()"
                        >Edit Info</button>
                    </div>

                    <form id="profileForm" method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('patch')

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                            {{-- Nama Lengkap --}}
                            <div>
                                <label class="admin-label" style="font-size: 0.65rem; letter-spacing: 0.08em; color: #9ca3af;">NAMA LENGKAP</label>
                                <input
                                    id="nameInput"
                                    type="text"
                                    name="name"
                                    value="{{ old('name', Auth::user()->name) }}"
                                    class="admin-input"
                                    style="margin-top: 6px; background: #f5f0f0; border-color: transparent; cursor: default;"
                                    readonly
                                >
                                @error('name')
                                    <p class="error-message">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Alamat Email --}}
                            <div>
                                <label class="admin-label" style="font-size: 0.65rem; letter-spacing: 0.08em; color: #9ca3af;">ALAMAT EMAIL</label>
                                <input
                                    id="emailInput"
                                    type="email"
                                    name="email"
                                    value="{{ old('email', Auth::user()->email) }}"
                                    class="admin-input"
                                    style="margin-top: 6px; background: #f5f0f0; border-color: transparent; cursor: default;"
                                    readonly
                                >
                                @error('email')
                                    <p class="error-message">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Tingkat Izin (Role) --}}
                        <div>
                            <label class="admin-label" style="font-size: 0.65rem; letter-spacing: 0.08em; color: #9ca3af;">TINGKAT IZIN</label>
                            <div style="margin-top: 6px; padding: 13px 16px; background: #f5f0f0; border-radius: 12px; display: flex; align-items: center; gap: 8px;">
                                @if(Auth::user()->role === 'admin')
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="16" height="16" style="color: var(--maroon, #6B1D2A);">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                                    </svg>
                                    <span style="font-size: 0.875rem; font-weight: 600; color: var(--maroon, #6B1D2A);">Super Admin</span>
                                @elseif(Auth::user()->role === 'operator')
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="16" height="16" style="color: #2563eb;">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 1 1-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 0 0 4.486-6.336l-3.276 3.277a3.004 3.004 0 0 1-2.25-2.25l3.276-3.276a4.5 4.5 0 0 0-6.336 4.486c.049.58.025 1.193-.14 1.743" />
                                    </svg>
                                    <span style="font-size: 0.875rem; font-weight: 600; color: #2563eb;">Operator</span>
                                @else
                                    <span style="font-size: 0.875rem; font-weight: 600; color: #6b7280;">User</span>
                                @endif
                            </div>
                        </div>

                        {{-- Save Button (hidden by default, shown in edit mode) --}}
                        <div id="saveProfileBtn" style="display: none; margin-top: 16px;">
                            <button type="submit" class="btn-admin-primary" style="width: auto;">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>

                {{-- ─── KEAMANAN & AKSES ─── --}}
                <div style="padding: 32px 0; border-bottom: 1px solid rgba(107,29,42,0.06);">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="20" height="20" style="color: var(--maroon, #6B1D2A);">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                        </svg>
                        <span style="font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em; color: #374151;">Keamanan & Akses</span>
                    </div>

                    {{-- Password field (display only, opens modal) --}}
                    <div style="margin-bottom: 24px;">
                        <label class="admin-label" style="font-size: 0.65rem; letter-spacing: 0.08em; color: #9ca3af;">KATA SANDI SAAT INI</label>
                        <input
                            type="password"
                            value="placeholder"
                            class="admin-input"
                            style="margin-top: 6px; background: #f5f0f0; border-color: transparent; cursor: default; letter-spacing: 0.2em;"
                            readonly
                            placeholder="••••••••"
                        >
                    </div>

                    <div style="display: flex; align-items: center; gap: 12px;">
                        {{-- Ubah Kata Sandi Button --}}
                        <button type="button" onclick="document.getElementById('passwordModal').style.display='flex'" class="btn-admin-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="16" height="16">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 0 1 21.75 8.25Z" />
                            </svg>
                            Ubah Kata Sandi
                        </button>

                        {{-- Keluar Button --}}
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn-admin-secondary">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="16" height="16">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" />
                                </svg>
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>

                {{-- ─── AKSI BERBAHAYA ─── --}}
                <div style="padding-top: 28px;">
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <div>
                            <p style="font-size: 0.875rem; font-weight: 600; color: #374151;">Aksi Berbahaya</p>
                            <p style="font-size: 0.8rem; color: #9ca3af; margin-top: 2px;">Hapus akses.</p>
                        </div>
                        <button type="button"
                            onclick="document.getElementById('deleteModal').style.display='flex'"
                            style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; border: 1.5px solid rgba(107,29,42,0.2); border-radius: 50px; background: white; font-size: 0.8rem; font-weight: 600; color: var(--maroon, #6B1D2A); cursor: pointer; transition: all 0.2s ease;"
                            onmouseover="this.style.background='rgba(107,29,42,0.04)'"
                            onmouseout="this.style.background='white'"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="15" height="15">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                            </svg>
                            Hapus Akun
                        </button>
                    </div>
                </div>

            </div>{{-- end right panel --}}
        </div>{{-- end main card flex --}}
    </div>{{-- end main card --}}

    {{-- ===================================================
         MODAL: Ubah Kata Sandi
    =================================================== --}}
    <div id="passwordModal"
         style="display:none; position:fixed; inset:0; z-index:999; background:rgba(0,0,0,0.35); backdrop-filter:blur(4px); align-items:center; justify-content:center;"
         onclick="if(event.target===this) this.style.display='none'">
        <div style="background:white; border-radius:20px; padding:36px; width:100%; max-width:440px; box-shadow:0 24px 60px rgba(0,0,0,0.15); animation: slide-up 0.3s ease;">
            <h3 style="font-size:1.1rem; font-weight:700; color:#1a1a1a; margin-bottom:6px;">Ubah Kata Sandi</h3>
            <p style="font-size:0.8rem; color:#9ca3af; margin-bottom:24px;">Pastikan kata sandi baru minimal 8 karakter.</p>

            <form method="POST" action="{{ route('password.update') }}" style="display:flex; flex-direction:column; gap:16px;">
                @csrf
                @method('put')

                <div>
                    <label class="admin-label">Kata Sandi Saat Ini</label>
                    <input type="password" name="current_password" class="admin-input" style="margin-top:6px;" placeholder="••••••••" autocomplete="current-password">
                    @error('current_password', 'updatePassword')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="admin-label">Kata Sandi Baru</label>
                    <input type="password" name="password" class="admin-input" style="margin-top:6px;" placeholder="••••••••" autocomplete="new-password">
                    @error('password', 'updatePassword')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="admin-label">Konfirmasi Kata Sandi</label>
                    <input type="password" name="password_confirmation" class="admin-input" style="margin-top:6px;" placeholder="••••••••" autocomplete="new-password">
                </div>

                <div style="display:flex; gap:12px; justify-content:flex-end; margin-top:8px;">
                    <button type="button" onclick="document.getElementById('passwordModal').style.display='none'" class="btn-admin-secondary">
                        Batal
                    </button>
                    <button type="submit" class="btn-admin-primary">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ===================================================
         MODAL: Hapus Akun
    =================================================== --}}
    <div id="deleteModal"
         style="display:none; position:fixed; inset:0; z-index:999; background:rgba(0,0,0,0.35); backdrop-filter:blur(4px); align-items:center; justify-content:center;"
         onclick="if(event.target===this) this.style.display='none'">
        <div style="background:white; border-radius:20px; padding:36px; width:100%; max-width:420px; box-shadow:0 24px 60px rgba(0,0,0,0.15); animation: slide-up 0.3s ease;">
            <div style="width:48px;height:48px;border-radius:12px;background:#fef2f2;display:flex;align-items:center;justify-content:center;margin-bottom:16px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#dc2626" width="22" height="22">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                </svg>
            </div>
            <h3 style="font-size:1.1rem; font-weight:700; color:#1a1a1a; margin-bottom:6px;">Hapus Akun</h3>
            <p style="font-size:0.8rem; color:#9ca3af; margin-bottom:24px;">Aksi ini tidak dapat dibatalkan. Masukkan kata sandi untuk konfirmasi.</p>

            <form method="POST" action="{{ route('profile.destroy') }}" style="display:flex; flex-direction:column; gap:16px;">
                @csrf
                @method('delete')

                <div>
                    <label class="admin-label">Kata Sandi</label>
                    <input type="password" name="password" class="admin-input" style="margin-top:6px;" placeholder="••••••••">
                    @error('password', 'userDeletion')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div style="display:flex; gap:12px; justify-content:flex-end; margin-top:8px;">
                    <button type="button" onclick="document.getElementById('deleteModal').style.display='none'" class="btn-admin-secondary">
                        Batal
                    </button>
                    <button type="submit" style="display:inline-flex;align-items:center;gap:8px;padding:12px 20px;background:#dc2626;color:white;border:none;border-radius:12px;font-size:0.85rem;font-weight:600;cursor:pointer;">
                        Hapus Akun
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Script: Toggle Edit Mode --}}
    <script>
        let editMode = false;

        function toggleEditMode() {
            editMode = !editMode;
            const nameInput  = document.getElementById('nameInput');
            const emailInput = document.getElementById('emailInput');
            const saveBtn    = document.getElementById('saveProfileBtn');
            const editBtn    = document.getElementById('toggleEditBtn');

            if (editMode) {
                nameInput.removeAttribute('readonly');
                emailInput.removeAttribute('readonly');
                nameInput.style.background  = 'white';
                emailInput.style.background = 'white';
                nameInput.style.borderColor  = 'rgba(107,29,42,0.2)';
                emailInput.style.borderColor = 'rgba(107,29,42,0.2)';
                nameInput.style.cursor  = 'text';
                emailInput.style.cursor = 'text';
                saveBtn.style.display = 'block';
                editBtn.textContent = 'Batal';
            } else {
                nameInput.setAttribute('readonly', true);
                emailInput.setAttribute('readonly', true);
                nameInput.style.background  = '#f5f0f0';
                emailInput.style.background = '#f5f0f0';
                nameInput.style.borderColor  = 'transparent';
                emailInput.style.borderColor = 'transparent';
                nameInput.style.cursor  = 'default';
                emailInput.style.cursor = 'default';
                saveBtn.style.display = 'none';
                editBtn.textContent = 'Edit Info';
            }
        }

        // Auto-open password modal if there are password errors
        @if ($errors->updatePassword->any())
            document.addEventListener('DOMContentLoaded', () => {
                document.getElementById('passwordModal').style.display = 'flex';
            });
        @endif

        // Auto-open delete modal if there are delete errors
        @if ($errors->userDeletion->any())
            document.addEventListener('DOMContentLoaded', () => {
                document.getElementById('deleteModal').style.display = 'flex';
            });
        @endif
    </script>

@endsection
