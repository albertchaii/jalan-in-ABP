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

    {{-- Search Bar --}}
    <div style="margin-bottom: 24px;">
        <form method="GET" action="{{ route('admin.users.index') }}" style="position: relative; max-width: 360px;">
            {{-- Preserve current filters --}}
            <input type="hidden" name="role" value="{{ $roleFilter }}">
            <input type="hidden" name="sort" value="{{ $sort }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18" height="18" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #b8a0a5;">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
            </svg>
            <input type="text" name="search" placeholder="Cari anggota..." class="admin-input" style="padding-left: 42px;" value="{{ $search ?? '' }}">
        </form>
    </div>

    {{-- Page Header --}}
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 32px;">
        <div>
            <h1 class="page-title">Pengelolaan Pengguna</h1>
            <p class="page-subtitle">Mengelola hak akses administratif, akun operator, dan tingkat akses sistem.</p>
        </div>
        <a href="{{ route('admin.users.add') }}" class="btn-admin-primary" style="white-space: nowrap;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="16" height="16">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Operator Baru
        </a>
    </div>

    {{-- Stats Cards --}}
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 32px;">
        {{-- Total Pengguna Aktif --}}
        <div class="stat-card">
            <div>
                <p class="stat-label">Total Pengguna Aktif</p>
                <p class="stat-value">{{ $totalUsers }}</p>
                <p class="stat-footer">Seluruh akun terdaftar</p>
            </div>
            <div class="stat-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18" height="18">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                </svg>
            </div>
        </div>

        {{-- Super Admin --}}
        <div class="stat-card">
            <div>
                <p class="stat-label">Super Admin</p>
                <p class="stat-value">{{ $totalAdmins }}</p>
                <p class="stat-footer">Pengawas sistem</p>
            </div>
            <div class="stat-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18" height="18">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                </svg>
            </div>
        </div>

        {{-- Operator --}}
        <div class="stat-card">
            <div>
                <p class="stat-label">Operator</p>
                <p class="stat-value">{{ $totalOperators }}</p>
                <p class="stat-footer">Staf Lapangan</p>
            </div>
            <div class="stat-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18" height="18">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                </svg>
            </div>
        </div>
    </div>

    {{-- Users Table --}}
    <div class="admin-card" style="margin-bottom: 32px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2 class="section-title">Pengguna</h2>
            <div style="display: flex; gap: 8px;">
                {{-- Role Filter Dropdown --}}
                <div style="position:relative;" x-data="{ open: false }">
                    <button @click="open = !open" @click.away="open = false" class="filter-pill {{ $roleFilter !== 'all' ? 'active' : '' }}" style="display:flex;align-items:center;gap:6px;cursor:pointer;border:none;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" /></svg>
                        Filter: {{ $roleFilter === 'all' ? 'Semua' : ucfirst($roleFilter) }}
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="12" height="12"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" /></svg>
                    </button>
                    <div x-show="open" x-transition style="position:absolute;top:calc(100% + 6px);right:0;background:white;border:1px solid rgba(107,29,42,0.08);border-radius:12px;box-shadow:0 10px 25px rgba(107,29,42,0.08);padding:6px;z-index:20;min-width:160px;">
                        @foreach(['all' => 'Semua Peran', 'admin' => 'Admin', 'operator' => 'Operator', 'user' => 'User'] as $value => $label)
                            <a href="{{ route('admin.users.index', array_merge(request()->query(), ['role' => $value, 'page' => 1])) }}"
                               style="display:block;padding:8px 12px;font-size:0.8rem;font-weight:{{ $roleFilter === $value ? '600' : '400' }};color:{{ $roleFilter === $value ? 'var(--maroon, #6B1D2A)' : '#4b5563' }};text-decoration:none;border-radius:8px;transition:background 0.15s ease;"
                               onmouseover="this.style.background='rgba(107,29,42,0.04)'" onmouseout="this.style.background='transparent'">
                                {{ $label }}
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- Sort Dropdown --}}
                <div style="position:relative;" x-data="{ open: false }">
                    <button @click="open = !open" @click.away="open = false" class="filter-pill" style="display:flex;align-items:center;gap:6px;cursor:pointer;border:none;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5 7.5 3m0 0L12 7.5M7.5 3v13.5m13.5 0L16.5 21m0 0L12 16.5m4.5 4.5V7.5" /></svg>
                        Urutkan: {{ ['terbaru' => 'Terbaru', 'terlama' => 'Terlama', 'nama_asc' => 'Nama A-Z', 'nama_desc' => 'Nama Z-A'][$sort] ?? 'Terbaru' }}
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="12" height="12"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" /></svg>
                    </button>
                    <div x-show="open" x-transition style="position:absolute;top:calc(100% + 6px);right:0;background:white;border:1px solid rgba(107,29,42,0.08);border-radius:12px;box-shadow:0 10px 25px rgba(107,29,42,0.08);padding:6px;z-index:20;min-width:150px;">
                        @foreach(['terbaru' => 'Terbaru', 'terlama' => 'Terlama', 'nama_asc' => 'Nama A-Z', 'nama_desc' => 'Nama Z-A'] as $value => $label)
                            <a href="{{ route('admin.users.index', array_merge(request()->query(), ['sort' => $value, 'page' => 1])) }}"
                               style="display:block;padding:8px 12px;font-size:0.8rem;font-weight:{{ $sort === $value ? '600' : '400' }};color:{{ $sort === $value ? 'var(--maroon, #6B1D2A)' : '#4b5563' }};text-decoration:none;border-radius:8px;transition:background 0.15s ease;"
                               onmouseover="this.style.background='rgba(107,29,42,0.04)'" onmouseout="this.style.background='transparent'">
                                {{ $label }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <table class="admin-table" id="usersTable">
            <thead>
                <tr>
                    <th>Avatar dan Nama</th>
                    <th>Alamat Email</th>
                    <th>Peran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div class="user-avatar">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                                <div>
                                    <div style="font-weight: 600; color: #1a1a1a;">{{ $user->name }}</div>
                                    <div style="font-size: 0.7rem; color: #9ca3af;">Joined {{ $user->created_at?->format('d M Y') }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="color: #6b7280;">{{ $user->email }}</td>
                        <td>
                            @if($user->role === 'admin')
                                <span class="badge badge-maroon">ADMIN</span>
                            @elseif($user->role === 'operator')
                                <span class="badge badge-info">OPERATOR</span>
                            @else
                                <span class="badge badge-neutral">USER</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.users.show', $user) }}" class="action-btn" title="Lihat detail pengguna">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="16" height="16">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 40px; color: #9ca3af;">
                            @if($search)
                                Tidak ditemukan pengguna dengan kata kunci "{{ $search }}".
                            @else
                                Belum ada pengguna terdaftar.
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        @if($users->hasPages())
            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px; padding-top: 16px; border-top: 1px solid rgba(107, 29, 42, 0.04);">
                <p style="font-size: 0.8rem; color: #9ca3af;">
                    Menampilkan {{ $users->firstItem() }}–{{ $users->lastItem() }} dari {{ $users->total() }} pengguna
                </p>
                <div style="display: flex; gap: 8px;">
                    @if ($users->previousPageUrl())
                        <a href="{{ $users->previousPageUrl() }}" class="pagination-btn prev" style="text-decoration:none;">Sebelumnya</a>
                    @endif
                    @if ($users->nextPageUrl())
                        <a href="{{ $users->nextPageUrl() }}" class="pagination-btn next" style="text-decoration:none;">Selanjutnya</a>
                    @endif
                </div>
            </div>
        @endif
    </div>

    {{-- Role Hierarchy Info --}}
    <div class="info-panel">
        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="20" height="20" style="color: var(--maroon, #6B1D2A);">
                <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
            </svg>
            <h3 style="font-size: 0.95rem; font-weight: 700; color: var(--maroon, #6B1D2A);">Hierarki Peran</h3>
        </div>

        <div style="display: flex; flex-direction: column; gap: 16px;">
            <div style="display: flex; align-items: flex-start; gap: 12px;">
                <div style="width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, var(--maroon, #6B1D2A) 0%, #8B2E3B 100%); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="white" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" /></svg>
                </div>
                <div>
                    <h4 style="font-weight: 600; font-size: 0.875rem; color: #1a1a1a;">Super Admin</h4>
                    <p style="font-size: 0.8rem; color: #9ca3af; margin-top: 2px;">Akses penuh ke pengaturan sistem dan manajemen pengguna. Tidak memiliki akses ke antarmuka peta visual.</p>
                </div>
            </div>

            <div style="display: flex; align-items: flex-start; gap: 12px;">
                <div style="width: 32px; height: 32px; border-radius: 50%; background: #eff6ff; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2563eb" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 1 1-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 0 0 4.486-6.336l-3.276 3.277a3.004 3.004 0 0 1-2.25-2.25l3.276-3.276a4.5 4.5 0 0 0-6.336 4.486c.049.58.025 1.193-.14 1.743" /></svg>
                </div>
                <div>
                    <h4 style="font-weight: 600; font-size: 0.875rem; color: #1a1a1a;">Operator</h4>
                    <p style="font-size: 0.8rem; color: #9ca3af; margin-top: 2px;">Memvalidasi laporan, memperbarui status perbaikan, dan menganalisis data spasial melalui peta.</p>
                </div>
            </div>

            <div style="display: flex; align-items: flex-start; gap: 12px;">
                <div style="width: 32px; height: 32px; border-radius: 50%; background: #f3f4f6; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#6b7280" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>
                </div>
                <div>
                    <h4 style="font-weight: 600; font-size: 0.875rem; color: #1a1a1a;">User (Masyarakat)</h4>
                    <p style="font-size: 0.8rem; color: #9ca3af; margin-top: 2px;">Melaporkan kerusakan jalan melalui aplikasi mobile dan melihat status perbaikan di sekitar mereka.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
