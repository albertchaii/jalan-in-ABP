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
    @if (session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             style="position:fixed;top:20px;right:24px;z-index:999;background:#fef2f2;border:1px solid rgba(220,38,38,0.25);color:#dc2626;padding:14px 24px;border-radius:14px;font-size:0.85rem;font-weight:600;box-shadow:0 8px 24px rgba(0,0,0,0.1);display:flex;align-items:center;gap:10px;max-width:480px;">
            ✕ {{ session('error') }}
        </div>
    @endif

    {{-- Header --}}
    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:32px;">
        <div>
            <a href="{{ route('operator.reports.index') }}" style="display:inline-flex;align-items:center;gap:6px;font-size:0.8rem;color:#9ca3af;text-decoration:none;margin-bottom:12px;" onmouseover="this.style.color='var(--maroon)'" onmouseout="this.style.color='#9ca3af'">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
                Kembali
            </a>
            <h1 class="page-title">{{ $report->description ?? ($report->damage_type ?? 'Analisis Kerusakan Jalan') }}</h1>
            <p class="page-subtitle" style="display:flex;align-items:center;gap:6px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" /></svg>
                Dilaporkan pada {{ $report->created_at->format('F d, Y • H:i') }} WIB
            </p>
        </div>
        <div style="display:flex;gap:12px;">
            <button class="btn-admin-secondary" style="font-size:0.8rem;padding:10px 18px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 1 0 0 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186 9.566-5.314m-9.566 7.5 9.566 5.314m0 0a2.25 2.25 0 1 0 3.935 2.186 2.25 2.25 0 0 0-3.935-2.186Zm0-12.814a2.25 2.25 0 1 0 3.935-2.186 2.25 2.25 0 0 0-3.935 2.186Z" /></svg>
                Laporan Ekspor
            </button>
            <button class="btn-admin-primary" style="font-size:0.8rem;padding:10px 18px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m0 0a48.773 48.773 0 0 1 10.5 0" /></svg>
                Cetak PDF
            </button>
        </div>
    </div>

    {{-- Main Content: 2 columns --}}
    <div style="display:grid;grid-template-columns:1fr 380px;gap:24px;">

        {{-- ===== LEFT COLUMN ===== --}}
        <div style="display:flex;flex-direction:column;gap:24px;">
            {{-- Photo --}}
            <div class="admin-card" style="padding:0;overflow:hidden;position:relative;">
                @if($report->ai_photo_path)
                    <div style="position:absolute;top:16px;left:16px;z-index:2;background:rgba(107,29,42,0.85);color:white;padding:6px 14px;border-radius:8px;font-size:0.7rem;font-weight:700;letter-spacing:0.04em;">
                        LAPISAN DETEKSI AI
                    </div>
                    <img src="{{ asset('storage/' . $report->ai_photo_path) }}" alt="AI Detection" style="width:100%;max-height:360px;object-fit:cover;display:block;">
                @elseif($report->photo_path)
                    <img src="{{ asset('storage/' . $report->photo_path) }}" alt="Foto Laporan" style="width:100%;max-height:360px;object-fit:cover;display:block;">
                @else
                    <div style="height:240px;display:flex;align-items:center;justify-content:center;background:#f5f0f0;color:#b8a0a5;">
                        <p>Tidak ada foto</p>
                    </div>
                @endif
            </div>

            {{-- Validasi Kerusakan --}}
            <div class="admin-card">
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:16px;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="var(--maroon, #6B1D2A)" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                    <h3 style="font-size:1rem;font-weight:700;color:#1a1a1a;">Validasi Kerusakan</h3>
                </div>

                @if($report->damage_type)
                    <div style="background:#ecfdf5;border:1px solid rgba(34,197,94,0.15);border-radius:12px;padding:16px;">
                        <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#15803d" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                            <span style="font-size:0.85rem;font-weight:600;color:#15803d;">Terverifikasi sebagai {{ $report->damage_type }}</span>
                        </div>
                        <p style="font-size:0.8rem;color:#6b7280;margin:0;">Telah divalidasi oleh operator melalui bukti visual.</p>
                    </div>
                @else
                    <div style="background:#faf7f7;border-radius:12px;padding:16px;">
                        <p style="font-size:0.85rem;color:#9ca3af;margin:0;">Belum divalidasi — menunggu deteksi AI atau validasi manual.</p>
                    </div>
                @endif
            </div>

            {{-- Deskripsi --}}
            <div class="admin-card">
                <h3 style="font-size:1rem;font-weight:700;color:#1a1a1a;margin-bottom:12px;">Deskripsi Laporan</h3>
                <p style="font-size:0.875rem;color:#4b5563;line-height:1.7;">
                    {{ $report->description ?? 'Tidak ada deskripsi dari pelapor.' }}
                </p>
                @if($report->user)
                    <p style="font-size:0.75rem;color:#9ca3af;margin-top:12px;">Dilaporkan oleh: <strong style="color:#6b7280;">{{ $report->user->name }}</strong></p>
                @endif
            </div>
        </div>

        {{-- ===== RIGHT COLUMN ===== --}}
        <div style="display:flex;flex-direction:column;gap:24px;">
            {{-- Map --}}
            <div class="admin-card" style="padding:0;overflow:hidden;">
                <div id="detailMap" style="height:200px;"></div>
                <div style="padding:16px;">
                    <p style="font-size:0.9rem;font-weight:600;color:#1a1a1a;margin:0;">Lokasi Laporan</p>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-top:12px;">
                        <div>
                            <p style="font-size:0.6rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:#9ca3af;">LATITUDE</p>
                            <p style="font-size:0.8rem;font-weight:600;color:#1a1a1a;font-family:monospace;">{{ $report->latitude }}</p>
                        </div>
                        <div>
                            <p style="font-size:0.6rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:#9ca3af;">LONGITUDE</p>
                            <p style="font-size:0.8rem;font-weight:600;color:#1a1a1a;font-family:monospace;">{{ $report->longitude }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Timeline Proses Pengerjaan --}}
            <div class="admin-card">
                <h3 style="font-size:1rem;font-weight:700;color:#1a1a1a;margin-bottom:20px;">Proses Pengerjaan</h3>

                @php
                    $statusOrder = ['Dilaporkan', 'Disurvey', 'Diproses', 'Selesai'];
                    $currentIndex = array_search($report->status, $statusOrder);
                    if ($currentIndex === false && $report->status === 'Tidak Valid') $currentIndex = 0;
                @endphp

                <div style="display:flex;flex-direction:column;gap:0;">
                    @foreach($statusOrder as $i => $status)
                        @php
                            $isCompleted = $i < $currentIndex || ($i === $currentIndex && $report->status === 'Selesai');
                            $isCurrent = $i === $currentIndex && $report->status !== 'Selesai' && $report->status !== 'Tidak Valid';
                            $isPending = $i > $currentIndex;
                            $isLast = $i === count($statusOrder) - 1;

                            $timeLabel = match($status) {
                                'Dilaporkan' => $report->created_at->format('M d, Y • H:i') . ' WIB',
                                'Disurvey'   => $report->verified_at ? $report->verified_at->format('M d, Y • H:i') . ' WIB' : ($isCompleted || $isCurrent ? 'Telah disurvey' : 'Belum disurvey'),
                                'Diproses'   => $isCurrent ? 'Sedang diperbaiki' : ($isCompleted ? 'Telah diperbaiki' : 'Belum diproses'),
                                'Selesai'    => $report->completed_at ? $report->completed_at->format('M d, Y • H:i') . ' WIB' : 'Belum selesai',
                                default      => '',
                            };
                        @endphp
                        <div style="display:flex;gap:16px;position:relative;">
                            {{-- Line + Dot --}}
                            <div style="display:flex;flex-direction:column;align-items:center;width:24px;">
                                <div style="width:24px;height:24px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;
                                    {{ $isCompleted ? 'background:var(--maroon,#6B1D2A);' : ($isCurrent ? 'background:var(--maroon,#6B1D2A);' : 'background:#e5e7eb;') }}">
                                    @if($isCompleted)
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="white" width="12" height="12"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                                    @elseif($isCurrent)
                                        <div style="width:8px;height:8px;border-radius:50%;background:white;"></div>
                                    @else
                                        <div style="width:8px;height:8px;border-radius:50%;background:#d1d5db;"></div>
                                    @endif
                                </div>
                                @if(!$isLast)
                                    <div style="width:2px;flex:1;min-height:32px;{{ $isCompleted ? 'background:var(--maroon,#6B1D2A);' : 'background:#e5e7eb;' }}"></div>
                                @endif
                            </div>

                            {{-- Content --}}
                            <div style="padding-bottom:{{ $isLast ? '0' : '20px' }};">
                                <p style="font-size:0.875rem;font-weight:{{ $isCompleted || $isCurrent ? '700' : '500' }};color:{{ $isPending ? '#9ca3af' : '#1a1a1a' }};margin:0;">
                                    {{ $status }}
                                </p>
                                <p style="font-size:0.75rem;color:#9ca3af;margin:2px 0 0 0;">{{ $timeLabel }}</p>
                            </div>
                        </div>
                    @endforeach

                    @if($report->status === 'Tidak Valid')
                        <div style="margin-top:12px;background:#fef2f2;border-radius:10px;padding:12px;">
                            <p style="font-size:0.8rem;font-weight:600;color:#dc2626;margin:0;">Laporan Tidak Valid</p>
                            <p style="font-size:0.75rem;color:#9ca3af;margin:4px 0 0 0;">{{ $report->rejection_note ?? 'Foto bukan kerusakan jalan.' }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Update Status --}}
            @if($report->status !== 'Selesai' && $report->status !== 'Tidak Valid')
                <div class="admin-card" style="background:#faf7f7;">
                    <p style="font-size:0.65rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:#9ca3af;margin-bottom:12px;">PERBARUI STATUS PEKERJAAN</p>
                    <form method="POST" action="{{ route('operator.reports.updateStatus', $report) }}">
                        @csrf @method('PATCH')
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:16px;">
                            @foreach(['Dilaporkan', 'Disurvey', 'Diproses', 'Selesai'] as $s)
                                <label style="cursor:pointer;">
                                    <input type="radio" name="status" value="{{ $s }}" {{ $report->status === $s ? 'checked' : '' }} style="display:none;" class="status-radio">
                                    <div class="status-option {{ $report->status === $s ? 'active' : '' }}" style="padding:10px;border-radius:10px;text-align:center;font-size:0.8rem;font-weight:600;border:1.5px solid {{ $report->status === $s ? 'var(--maroon,#6B1D2A)' : '#e5e7eb' }};color:{{ $report->status === $s ? 'var(--maroon,#6B1D2A)' : '#6b7280' }};background:{{ $report->status === $s ? 'rgba(107,29,42,0.04)' : 'white' }};transition:all 0.15s ease;">
                                        {{ $s }}
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        <button type="submit" class="btn-admin-primary" style="width:100%;justify-content:center;">Simpan Status</button>
                    </form>
                </div>
            @endif

            {{-- Upload Bukti Perbaikan --}}
            @if($report->status !== 'Tidak Valid')
                <div style="margin-top:0;">
                    @if($report->evidence_photo_path)
                        <div class="admin-card" style="padding:0;overflow:hidden;">
                            <img src="{{ asset('storage/' . $report->evidence_photo_path) }}" alt="Bukti" style="width:100%;height:160px;object-fit:cover;">
                            <div style="padding:12px 16px;">
                                <p style="font-size:0.8rem;font-weight:600;color:#15803d;margin:0;">✓ Bukti perbaikan telah diunggah</p>
                            </div>
                        </div>
                    @else
                        <form method="POST" action="{{ route('operator.reports.uploadEvidence', $report) }}" enctype="multipart/form-data">
                            @csrf
                            <label for="evidence_photo" class="btn-admin-primary" style="width:100%;justify-content:center;cursor:pointer;">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18" height="18"><path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" /></svg>
                                Unggah Bukti Perbaikan
                            </label>
                            <input type="file" id="evidence_photo" name="evidence_photo" accept="image/*" style="display:none;" onchange="this.form.submit()">
                            @error('evidence_photo')
                                <p style="font-size:0.8rem;color:#dc2626;margin-top:8px;">{{ $message }}</p>
                            @enderror
                        </form>
                    @endif
                </div>
            @endif
        </div>
    </div>

    {{-- Scripts --}}
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Detail Map
        const lat = {{ $report->latitude }};
        const lng = {{ $report->longitude }};
        const map = L.map('detailMap', { zoomControl: false, attributionControl: false }).setView([lat, lng], 15);
        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png').addTo(map);
        L.circleMarker([lat, lng], { radius: 10, fillColor: '{{ $report->statusColor() }}', color: '#ffffff', weight: 3, fillOpacity: 0.9 }).addTo(map);

        // Radio styling
        document.querySelectorAll('.status-radio').forEach(radio => {
            radio.addEventListener('change', function() {
                document.querySelectorAll('.status-option').forEach(opt => {
                    opt.style.borderColor = '#e5e7eb';
                    opt.style.color = '#6b7280';
                    opt.style.background = 'white';
                });
                const div = this.parentElement.querySelector('.status-option');
                div.style.borderColor = 'var(--maroon,#6B1D2A)';
                div.style.color = 'var(--maroon,#6B1D2A)';
                div.style.background = 'rgba(107,29,42,0.04)';
            });
        });
    });
    </script>
@endsection
