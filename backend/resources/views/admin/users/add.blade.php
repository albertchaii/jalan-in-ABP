@extends('layouts.admin')

@section('content')
{{-- Flash Messages --}}
@if (session('success'))
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
    x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-2"
    style="position:fixed;top:20px;right:24px;z-index:999;background:#ecfdf5;border:1px solid rgba(34,197,94,0.25);color:#15803d;padding:14px 24px;border-radius:14px;font-size:0.85rem;font-weight:600;box-shadow:0 8px 24px rgba(0,0,0,0.1);display:flex;align-items:center;gap:10px;max-width:480px;">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="18" height="18">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
    </svg>
    {{ session('success') }}
</div>
@endif
@if (session('error'))
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
    x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
    style="position:fixed;top:20px;right:24px;z-index:999;background:#fef2f2;border:1px solid rgba(220,38,38,0.25);color:#dc2626;padding:14px 24px;border-radius:14px;font-size:0.85rem;font-weight:600;box-shadow:0 8px 24px rgba(0,0,0,0.1);display:flex;align-items:center;gap:10px;max-width:480px;">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="18" height="18">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
    </svg>
    {{ session('error') }}
</div>
@endif

{{-- Page Header --}}
<div style="margin-bottom: 8px;">
    <a href="{{ route('admin.users.index') }}" style="display:inline-flex;align-items:center;gap:6px;font-size:0.8rem;color:#9ca3af;text-decoration:none;margin-bottom:16px;transition:color 0.2s ease;" onmouseover="this.style.color='var(--maroon, #6B1D2A)'" onmouseout="this.style.color='#9ca3af'">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="14" height="14">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
        </svg>
        Kembali ke Pengelolaan Pengguna
    </a>
</div>

<div style="margin-bottom: 32px;">
    <h1 class="page-title">Menambahkan Operator</h1>
    <p class="page-subtitle">Cari akun masyarakat yang sudah terdaftar, lalu promosikan menjadi Operator.</p>
</div>

{{-- Info Banner --}}
<div style="background:#eff6ff;border:1px solid rgba(37,99,235,0.12);border-radius:14px;padding:16px 20px;margin-bottom:24px;display:flex;align-items:flex-start;gap:12px;">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2563eb" width="20" height="20" style="flex-shrink:0;margin-top:1px;">
        <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
    </svg>
    <div>
        <p style="font-size:0.85rem;font-weight:600;color:#1e40af;margin:0;">Cara Kerja</p>
        <p style="font-size:0.8rem;color:#3b82f6;margin:4px 0 0 0;">Calon operator harus membuat akun terlebih dahulu melalui registrasi di aplikasi mobile atau website. Setelah terdaftar, Admin dapat mencari akun tersebut di halaman ini dan mengubah rolenya menjadi Operator.</p>
    </div>
</div>

{{-- Search --}}
<div class="admin-card" style="max-width: 920px;">
    <div style="margin-bottom: 24px;">
        <form method="GET" action="{{ route('admin.users.add') }}" style="position: relative; max-width: 400px;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18" height="18" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #b8a0a5;">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
            </svg>
            <input type="text" name="search" placeholder="Cari berdasarkan nama atau email..." class="admin-input" style="padding-left: 42px;" value="{{ $search ?? '' }}" autofocus>
        </form>
    </div>

    {{-- User List --}}
    <table class="admin-table">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Bergabung</th>
                <th style="text-align:right;">Aksi</th>
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
                            <div style="font-size: 0.7rem; color: #9ca3af;">
                                <span class="badge badge-neutral">USER</span>
                            </div>
                        </div>
                    </div>
                </td>
                <td style="color: #6b7280;">{{ $user->email }}</td>
                <td style="font-size: 0.8rem; color: #9ca3af;">{{ $user->created_at?->format('d M Y') }}</td>
                <td style="text-align:right;">
                    {{-- Promote Button --}}
                    <button type="button"
                        onclick="openPromoteModal('{{ $user->id }}', '{{ addslashes($user->name) }}', '{{ $user->email }}')"
                        class="btn-admin-primary" style="padding:8px 16px;font-size:0.78rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="14" height="14">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5 7.5 3m0 0L12 7.5M7.5 3v13.5m13.5 0L16.5 21m0 0L12 16.5m4.5 4.5V7.5" />
                        </svg>
                        Jadikan Operator
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align: center; padding: 48px; color: #9ca3af;">
                    <div style="display:flex;flex-direction:column;align-items:center;gap:12px;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" width="40" height="40" style="color:#d1d5db;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                        </svg>
                        @if($search)
                        <p style="font-size:0.9rem;font-weight:500;">Tidak ditemukan akun user dengan kata kunci "{{ $search }}"</p>
                        <p style="font-size:0.8rem;">Pastikan nama atau email yang dimasukkan sudah benar.</p>
                        @else
                        <p style="font-size:0.9rem;font-weight:500;">Belum ada akun masyarakat yang terdaftar.</p>
                        <p style="font-size:0.8rem;">Calon operator perlu mendaftar melalui aplikasi mobile terlebih dahulu.</p>
                        @endif
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    @if($users->hasPages())
    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px; padding-top: 16px; border-top: 1px solid rgba(107, 29, 42, 0.04);">
        <p style="font-size: 0.8rem; color: #9ca3af;">
            Menampilkan {{ $users->firstItem() }}–{{ $users->lastItem() }} dari {{ $users->total() }} user
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

{{-- ===== Promote Confirmation Modal ===== --}}
<div id="promoteModal"
    style="display:none; position:fixed; inset:0; z-index:999; background:rgba(0,0,0,0.35); backdrop-filter:blur(4px); align-items:center; justify-content:center;"
    onclick="if(event.target===this) this.style.display='none'">
    <div style="background:white; border-radius:20px; padding:36px; width:100%; max-width:440px; box-shadow:0 24px 60px rgba(0,0,0,0.15);">
        {{-- Icon --}}
        <div style="width:48px;height:48px;border-radius:12px;background:rgba(107,29,42,0.06);display:flex;align-items:center;justify-content:center;margin-bottom:16px;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="var(--maroon, #6B1D2A)" width="22" height="22">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5 7.5 3m0 0L12 7.5M7.5 3v13.5m13.5 0L16.5 21m0 0L12 16.5m4.5 4.5V7.5" />
            </svg>
        </div>

        <h3 style="font-size:1.1rem; font-weight:700; color:#1a1a1a; margin-bottom:6px;">Promosikan ke Operator?</h3>
        <p style="font-size:0.85rem; color:#6b7280; margin-bottom:8px;">Anda akan mengubah role akun berikut menjadi <strong style="color:var(--maroon, #6B1D2A);">Operator</strong>:</p>

        <div style="background:#faf7f7;border-radius:12px;padding:16px;margin-bottom:24px;">
            <p style="font-size:0.9rem;font-weight:600;color:#1a1a1a;margin:0;" id="promoteUserName">-</p>
            <p style="font-size:0.8rem;color:#9ca3af;margin:4px 0 0 0;" id="promoteUserEmail">-</p>
        </div>

        <p style="font-size:0.78rem;color:#9ca3af;margin-bottom:24px;">
            Setelah dipromosikan, akun ini akan mendapat akses ke dasbor Operator termasuk peta analitik dan pengelolaan laporan.
        </p>

        <form id="promoteForm" method="POST" action="">
            @csrf
            <div style="display:flex; gap:12px; justify-content:flex-end;">
                <button type="button" onclick="document.getElementById('promoteModal').style.display='none'" class="btn-admin-secondary">
                    Batal
                </button>
                <button type="submit" class="btn-admin-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="15" height="15">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>
                    Ya, Promosikan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openPromoteModal(userId, userName, userEmail) {
        document.getElementById('promoteUserName').textContent = userName;
        document.getElementById('promoteUserEmail').textContent = userEmail;
        document.getElementById('promoteForm').action = '/admin/users/' + userId + '/promote';
        document.getElementById('promoteModal').style.display = 'flex';
    }
</script>
@endsection