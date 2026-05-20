@extends('layouts.admin')

@section('content')
    {{-- Flash Messages --}}
    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-2"
             style="position:fixed;top:20px;right:24px;z-index:999;background:#ecfdf5;border:1px solid rgba(34,197,94,0.25);color:#15803d;padding:14px 24px;border-radius:14px;font-size:0.85rem;font-weight:600;box-shadow:0 8px 24px rgba(0,0,0,0.1);display:flex;align-items:center;gap:10px;max-width:480px;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="18" height="18"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             style="position:fixed;top:20px;right:24px;z-index:999;background:#fef2f2;border:1px solid rgba(220,38,38,0.25);color:#dc2626;padding:14px 24px;border-radius:14px;font-size:0.85rem;font-weight:600;box-shadow:0 8px 24px rgba(0,0,0,0.1);display:flex;align-items:center;gap:10px;max-width:480px;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="18" height="18"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" /></svg>
            {{ session('error') }}
        </div>
    @endif
    @if (session('info'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             style="position:fixed;top:20px;right:24px;z-index:999;background:#eff6ff;border:1px solid rgba(37,99,235,0.25);color:#2563eb;padding:14px 24px;border-radius:14px;font-size:0.85rem;font-weight:600;box-shadow:0 8px 24px rgba(0,0,0,0.1);display:flex;align-items:center;gap:10px;max-width:480px;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="18" height="18"><path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" /></svg>
            {{ session('info') }}
        </div>
    @endif

    {{-- Breadcrumb --}}
    <div style="margin-bottom: 8px;">
        <a href="{{ route('admin.users.index') }}" style="display:inline-flex;align-items:center;gap:6px;font-size:0.8rem;color:#9ca3af;text-decoration:none;margin-bottom:16px;transition:color 0.2s ease;" onmouseover="this.style.color='var(--maroon, #6B1D2A)'" onmouseout="this.style.color='#9ca3af'">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
            Kembali ke Pengelolaan Pengguna
        </a>
    </div>

    {{-- Page Header --}}
    <div style="margin-bottom: 32px;">
        <h1 class="page-title">Detail Pengguna</h1>
        <p class="page-subtitle">Lihat informasi akun dan kelola hak akses pengguna.</p>
    </div>

    {{-- Main Content --}}
    <div style="display: grid; grid-template-columns: 300px 1fr; gap: 24px; max-width: 920px;">

        {{-- ===== LEFT: User Card ===== --}}
        <div class="admin-card" style="display:flex;flex-direction:column;align-items:center;padding:32px 24px;text-align:center;">
            {{-- Avatar --}}
            <div style="width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg, #d4b8b8 0%, #b89898 100%);display:flex;align-items:center;justify-content:center;font-size:1.8rem;font-weight:800;color:var(--maroon, #6B1D2A);border:4px solid white;box-shadow:0 4px 20px rgba(107,29,42,0.12);margin-bottom:16px;">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>

            {{-- Name --}}
            <h2 style="font-size:1.05rem;font-weight:700;color:#1a1a1a;margin-bottom:4px;">{{ $user->name }}</h2>
            <p style="font-size:0.8rem;color:#9ca3af;margin-bottom:16px;">{{ $user->email }}</p>

            {{-- Role Badge --}}
            @if($user->role === 'admin')
                <span class="badge badge-maroon" style="padding:6px 14px;font-size:0.7rem;">SUPER ADMIN</span>
            @elseif($user->role === 'operator')
                <span class="badge badge-info" style="padding:6px 14px;font-size:0.7rem;">OPERATOR</span>
            @else
                <span class="badge badge-neutral" style="padding:6px 14px;font-size:0.7rem;">USER</span>
            @endif

            {{-- Meta Info --}}
            <div style="width:100%;margin-top:24px;padding-top:20px;border-top:1px solid rgba(107,29,42,0.06);">
                <div style="display:flex;justify-content:space-between;margin-bottom:10px;">
                    <span style="font-size:0.75rem;color:#9ca3af;">Bergabung</span>
                    <span style="font-size:0.75rem;font-weight:600;color:#374151;">{{ $user->created_at?->format('d M Y') }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;margin-bottom:10px;">
                    <span style="font-size:0.75rem;color:#9ca3af;">Diperbarui</span>
                    <span style="font-size:0.75rem;font-weight:600;color:#374151;">{{ $user->updated_at?->diffForHumans() }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;">
                    <span style="font-size:0.75rem;color:#9ca3af;">Status</span>
                    <div style="display:flex;align-items:center;gap:5px;">
                        <span class="status-dot {{ $user->email_verified_at ? 'online' : 'offline' }}"></span>
                        <span style="font-size:0.75rem;font-weight:600;color:{{ $user->email_verified_at ? '#15803d' : '#9ca3af' }};">
                            {{ $user->email_verified_at ? 'Terverifikasi' : 'Belum Verifikasi' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== RIGHT: Info & Actions ===== --}}
        <div style="display:flex;flex-direction:column;gap:24px;">

            {{-- Informasi Akun --}}
            <div class="admin-card">
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:24px;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="20" height="20" style="color:var(--maroon, #6B1D2A);">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                    <span style="font-size:0.7rem;font-weight:800;text-transform:uppercase;letter-spacing:0.1em;color:#374151;">Informasi Akun</span>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div>
                        <label class="admin-label" style="font-size:0.65rem;letter-spacing:0.08em;color:#9ca3af;">NAMA LENGKAP</label>
                        <div style="margin-top:6px;padding:13px 16px;background:#f5f0f0;border-radius:12px;font-size:0.875rem;color:#1a1a1a;font-weight:500;">
                            {{ $user->name }}
                        </div>
                    </div>
                    <div>
                        <label class="admin-label" style="font-size:0.65rem;letter-spacing:0.08em;color:#9ca3af;">ALAMAT EMAIL</label>
                        <div style="margin-top:6px;padding:13px 16px;background:#f5f0f0;border-radius:12px;font-size:0.875rem;color:#1a1a1a;font-weight:500;">
                            {{ $user->email }}
                        </div>
                    </div>
                </div>

                <p style="font-size:0.75rem;color:#b8a0a5;margin-top:16px;font-style:italic;">
                    * Informasi nama dan email hanya dapat diubah oleh pemilik akun melalui halaman profil mereka.
                </p>
            </div>

            {{-- Pengaturan Role --}}
            <div class="admin-card">
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:24px;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="20" height="20" style="color:var(--maroon, #6B1D2A);">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                    </svg>
                    <span style="font-size:0.7rem;font-weight:800;text-transform:uppercase;letter-spacing:0.1em;color:#374151;">Pengaturan Hak Akses</span>
                </div>

                @if($user->role === 'admin')
                    {{-- Admin cannot be changed --}}
                    <div style="background:#faf7f7;border:1px solid rgba(107,29,42,0.06);border-radius:14px;padding:20px;display:flex;align-items:center;gap:14px;">
                        <div style="width:40px;height:40px;border-radius:10px;background:linear-gradient(135deg, var(--maroon, #6B1D2A) 0%, #8B2E3B 100%);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="white" width="18" height="18"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" /></svg>
                        </div>
                        <div>
                            <p style="font-size:0.9rem;font-weight:600;color:#1a1a1a;margin:0;">Role Terkunci</p>
                            <p style="font-size:0.8rem;color:#9ca3af;margin:4px 0 0 0;">Akun Super Admin tidak dapat diubah role-nya melalui dashboard.</p>
                        </div>
                    </div>
                @else
                    <form method="POST" action="{{ route('admin.users.updateRole', $user) }}" id="roleForm">
                        @csrf
                        @method('PATCH')

                        <label class="admin-label" style="font-size:0.65rem;letter-spacing:0.08em;color:#9ca3af;margin-bottom:8px;">ROLE SAAT INI</label>

                        {{-- Role Selection --}}
                        <div style="display:flex;gap:12px;margin-bottom:20px;">
                            {{-- User Option --}}
                            <label style="flex:1;cursor:pointer;" id="roleUserLabel">
                                <input type="radio" name="role" value="user" {{ $user->role === 'user' ? 'checked' : '' }} style="display:none;" onchange="updateRoleUI()">
                                <div id="roleUserCard" style="padding:16px;border-radius:14px;border:2px solid {{ $user->role === 'user' ? 'var(--maroon, #6B1D2A)' : 'rgba(107,29,42,0.08)' }};background:{{ $user->role === 'user' ? 'rgba(107,29,42,0.03)' : 'white' }};transition:all 0.2s ease;text-align:center;">
                                    <div style="width:36px;height:36px;border-radius:50%;background:#f3f4f6;display:flex;align-items:center;justify-content:center;margin:0 auto 10px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#6b7280" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>
                                    </div>
                                    <p style="font-size:0.85rem;font-weight:600;color:#374151;margin:0;">User</p>
                                    <p style="font-size:0.7rem;color:#9ca3af;margin:4px 0 0 0;">Masyarakat</p>
                                </div>
                            </label>

                            {{-- Operator Option --}}
                            <label style="flex:1;cursor:pointer;" id="roleOperatorLabel">
                                <input type="radio" name="role" value="operator" {{ $user->role === 'operator' ? 'checked' : '' }} style="display:none;" onchange="updateRoleUI()">
                                <div id="roleOperatorCard" style="padding:16px;border-radius:14px;border:2px solid {{ $user->role === 'operator' ? 'var(--maroon, #6B1D2A)' : 'rgba(107,29,42,0.08)' }};background:{{ $user->role === 'operator' ? 'rgba(107,29,42,0.03)' : 'white' }};transition:all 0.2s ease;text-align:center;">
                                    <div style="width:36px;height:36px;border-radius:50%;background:#eff6ff;display:flex;align-items:center;justify-content:center;margin:0 auto 10px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2563eb" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 1 1-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 0 0 4.486-6.336l-3.276 3.277a3.004 3.004 0 0 1-2.25-2.25l3.276-3.276a4.5 4.5 0 0 0-6.336 4.486c.049.58.025 1.193-.14 1.743" /></svg>
                                    </div>
                                    <p style="font-size:0.85rem;font-weight:600;color:#374151;margin:0;">Operator</p>
                                    <p style="font-size:0.7rem;color:#9ca3af;margin:4px 0 0 0;">Staf Lapangan</p>
                                </div>
                            </label>
                        </div>

                        {{-- Save Button --}}
                        <button type="submit" class="btn-admin-primary" id="saveRoleBtn" style="opacity:0.5;pointer-events:none;" disabled>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="15" height="15"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                            Simpan Perubahan Role
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    @if($user->role !== 'admin')
    <script>
        const originalRole = '{{ $user->role }}';

        function updateRoleUI() {
            const userRadio = document.querySelector('input[name="role"][value="user"]');
            const operatorRadio = document.querySelector('input[name="role"][value="operator"]');
            const userCard = document.getElementById('roleUserCard');
            const operatorCard = document.getElementById('roleOperatorCard');
            const saveBtn = document.getElementById('saveRoleBtn');

            const selectedRole = userRadio.checked ? 'user' : 'operator';

            // Update card styles
            if (userRadio.checked) {
                userCard.style.borderColor = 'var(--maroon, #6B1D2A)';
                userCard.style.background = 'rgba(107,29,42,0.03)';
                operatorCard.style.borderColor = 'rgba(107,29,42,0.08)';
                operatorCard.style.background = 'white';
            } else {
                operatorCard.style.borderColor = 'var(--maroon, #6B1D2A)';
                operatorCard.style.background = 'rgba(107,29,42,0.03)';
                userCard.style.borderColor = 'rgba(107,29,42,0.08)';
                userCard.style.background = 'white';
            }

            // Enable/disable save button
            if (selectedRole !== originalRole) {
                saveBtn.style.opacity = '1';
                saveBtn.style.pointerEvents = 'auto';
                saveBtn.disabled = false;
            } else {
                saveBtn.style.opacity = '0.5';
                saveBtn.style.pointerEvents = 'none';
                saveBtn.disabled = true;
            }
        }
    </script>
    @endif
@endsection
