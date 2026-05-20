@extends('layouts.operator')

@section('content')
    {{-- Flash --}}
    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             style="position:fixed;top:20px;right:24px;z-index:999;background:#ecfdf5;border:1px solid rgba(34,197,94,0.25);color:#15803d;padding:14px 24px;border-radius:14px;font-size:0.85rem;font-weight:600;box-shadow:0 8px 24px rgba(0,0,0,0.1);display:flex;align-items:center;gap:10px;max-width:480px;">
            ✓ {{ session('success') }}
        </div>
    @endif

    {{-- Header --}}
    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:32px;flex-wrap:wrap;gap:16px;">
        <div>
            <h1 class="page-title">Pengelolaan Laporan</h1>
            <p class="page-subtitle">Memantau, memvalidasi, dan menetapkan laporan kerusakan infrastruktur jalan dari warga dan sensor otomatis.</p>
        </div>
        <form method="GET" action="{{ route('operator.reports.index') }}" id="filterForm" style="display:flex;gap:16px;align-items:center;flex-wrap:wrap;">
            @if($search)
                <input type="hidden" name="search" value="{{ $search }}">
            @endif
            
            <div style="display:flex;align-items:center;gap:8px;">
                <label for="status" style="font-size:0.8rem;color:#6b7280;font-weight:600;">Status:</label>
                <select name="status" id="status" class="admin-input" style="padding:8px 12px;min-height:36px;width:auto;" onchange="document.getElementById('filterForm').submit()">
                    <option value="all" {{ ($statusFilter ?? 'all') === 'all' ? 'selected' : '' }}>Semua Status</option>
                    <option value="Dilaporkan" {{ ($statusFilter ?? '') === 'Dilaporkan' ? 'selected' : '' }}>Dilaporkan</option>
                    <option value="Disurvey" {{ ($statusFilter ?? '') === 'Disurvey' ? 'selected' : '' }}>Disurvey (Valid)</option>
                    <option value="Diproses" {{ ($statusFilter ?? '') === 'Diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="Selesai" {{ ($statusFilter ?? '') === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="Tidak Valid" {{ ($statusFilter ?? '') === 'Tidak Valid' ? 'selected' : '' }}>Tidak Valid</option>
                </select>
            </div>

            <div style="display:flex;align-items:center;gap:8px;">
                <label for="sort" style="font-size:0.8rem;color:#6b7280;font-weight:600;">Urutkan:</label>
                <select name="sort" id="sort" class="admin-input" style="padding:8px 12px;min-height:36px;width:auto;" onchange="document.getElementById('filterForm').submit()">
                    <option value="terbaru" {{ ($sort ?? 'terbaru') === 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                    <option value="terlama" {{ ($sort ?? 'terbaru') === 'terlama' ? 'selected' : '' }}>Terlama</option>
                </select>
            </div>
        </form>
    </div>

    {{-- Stats --}}
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:20px;margin-bottom:32px;">
        <div class="stat-card">
            <div>
                <p class="stat-label">Total Laporan</p>
                <p class="stat-value">{{ number_format($totalReports) }}</p>
            </div>
        </div>
        <div class="stat-card">
            <div>
                <p class="stat-label">Menunggu Validasi</p>
                <p class="stat-value" style="color:#3B82F6;">{{ number_format($pendingValidation) }}</p>
            </div>
        </div>
        <div class="stat-card">
            <div>
                <p class="stat-label">Selesai (24J)</p>
                <p class="stat-value">{{ $completedToday }}</p>
            </div>
        </div>
    </div>

    {{-- Report Cards --}}
    <div style="display:flex;flex-direction:column;gap:16px;margin-bottom:32px;">
        @forelse($reports as $report)
            <div class="admin-card" style="padding:0;overflow:hidden;">
                <div style="display:flex;align-items:stretch;gap:0;">
                    {{-- Photo --}}
                    <div style="width:200px;flex-shrink:0;background:#f5f0f0;">
                        @if($report->photo_path)
                            <img src="{{ asset('storage/' . $report->photo_path) }}" alt="Foto" style="width:100%;height:100%;object-fit:cover;display:block;">
                        @else
                            <div style="width:100%;height:100%;min-height:140px;display:flex;align-items:center;justify-content:center;">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="#b8a0a5" width="40" height="40"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" /></svg>
                            </div>
                        @endif
                    </div>

                    {{-- Info --}}
                    <div style="flex:1;padding:20px 24px;display:flex;align-items:center;justify-content:space-between;">
                        <div>
                            <p style="font-size:0.65rem;color:#9ca3af;font-weight:600;letter-spacing:0.05em;">REP-{{ str_pad($report->id, 5, '0', STR_PAD_LEFT) }}</p>
                            <h3 style="font-size:1rem;font-weight:700;color:#1a1a1a;margin:4px 0;">
                                {{ Str::limit($report->description ?? ($report->damage_type ?? 'Kerusakan Jalan'), 40) }}
                            </h3>
                            <div style="display:flex;align-items:center;gap:16px;margin-top:6px;">
                                <span style="font-size:0.75rem;color:#9ca3af;display:flex;align-items:center;gap:4px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="12" height="12"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" /></svg>
                                    {{ number_format($report->latitude, 4) }}, {{ number_format($report->longitude, 4) }}
                                </span>
                                <span style="font-size:0.75rem;color:#9ca3af;display:flex;align-items:center;gap:4px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="12" height="12"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" /></svg>
                                    {{ $report->created_at->format('M d, Y • H:i') }}
                                </span>
                            </div>
                        </div>

                        {{-- Status Badge + Actions --}}
                        <div style="display:flex;align-items:center;gap:16px;">
                            @php
                                $badgeClass = match($report->status) {
                                    'Dilaporkan'  => 'badge-dilaporkan',
                                    'Disurvey'    => 'badge-disurvey',
                                    'Tidak Valid' => 'badge-tidakvalid',
                                    'Diproses'    => 'badge-diproses',
                                    'Selesai'     => 'badge-selesai',
                                    default       => 'badge-tidakvalid',
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }}" style="padding:6px 14px;">
                                <span class="status-dot {{ strtolower(str_replace(' ', '', $report->status)) }}" style="margin-right:6px;"></span>
                                {{ strtoupper($report->status) }}
                            </span>

                            @if($report->status === 'Selesai')
                                <span class="badge" style="background:#f3f4f6;color:#6b7280;padding:6px 12px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="12" height="12" style="margin-right:4px;"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" /></svg>
                                    LOCKED
                                </span>
                            @else
                                {{-- Validate --}}
                                @if($report->status === 'Dilaporkan')
                                    <form method="POST" action="{{ route('operator.reports.updateStatus', $report) }}" style="display:inline;">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="Disurvey">
                                        <button type="submit" class="action-btn" title="Validasi (Disurvey)">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                                        </button>
                                    </form>
                                @endif

                                {{-- Reject --}}
                                @if(in_array($report->status, ['Dilaporkan', 'Disurvey']))
                                    <form method="POST" action="{{ route('operator.reports.updateStatus', $report) }}" style="display:inline;">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="Tidak Valid">
                                        <button type="submit" class="action-btn" title="Tolak (Tidak Valid)">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                                        </button>
                                    </form>
                                @endif

                                {{-- Detail --}}
                                <a href="{{ route('operator.reports.show', $report) }}" class="action-btn {{ $report->status === 'Diproses' ? 'active' : '' }}" title="Lihat Detail">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="admin-card" style="text-align:center;padding:60px;color:#9ca3af;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" width="48" height="48" style="margin:0 auto 16px;display:block;color:#d1d5db;"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                <p style="font-size:1rem;font-weight:600;">Belum ada laporan.</p>
                <p style="font-size:0.85rem;">Laporan baru akan muncul ketika masyarakat mengirim melalui aplikasi mobile.</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($reports->hasPages())
        <div style="display:flex;justify-content:space-between;align-items:center;">
            <p style="font-size:0.8rem;color:#9ca3af;">Showing {{ $reports->firstItem() }}-{{ $reports->lastItem() }} of {{ $reports->total() }} reports</p>
            <div style="display:flex;gap:8px;align-items:center;">
                @if($reports->previousPageUrl())
                    <a href="{{ $reports->previousPageUrl() }}" class="pagination-btn prev">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg>
                    </a>
                @endif
                @foreach($reports->getUrlRange(1, $reports->lastPage()) as $page => $url)
                    <a href="{{ $url }}" class="page-num {{ $page == $reports->currentPage() ? 'active' : '' }}">{{ $page }}</a>
                @endforeach
                @if($reports->nextPageUrl())
                    <a href="{{ $reports->nextPageUrl() }}" class="pagination-btn next">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
                    </a>
                @endif
            </div>
        </div>
    @endif
@endsection
