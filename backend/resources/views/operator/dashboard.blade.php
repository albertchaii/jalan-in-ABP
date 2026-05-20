@extends('layouts.operator')

@section('content')
    {{-- Stats Cards (5 statuses) --}}
    <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 16px; margin-bottom: 32px;">
        @foreach([
            ['label' => 'Dilaporkan', 'count' => $counts['dilaporkan'], 'color' => '#EF4444', 'icon' => 'M3 3v1.5M3 21v-6m0 0 2.77-.693a9 9 0 0 1 6.208.682l.108.054a9 9 0 0 0 6.086.71l3.114-.732a48.524 48.524 0 0 1-.005-10.499l-3.11.732a9 9 0 0 1-6.085-.711l-.108-.054a9 9 0 0 0-6.208-.682L3 4.5M3 15V4.5'],
            ['label' => 'Valid', 'count' => $counts['disurvey'], 'color' => '#22C55E', 'icon' => 'M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z'],
            ['label' => 'Tidak Valid', 'count' => $counts['tidak_valid'], 'color' => '#EF4444', 'icon' => 'M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636'],
            ['label' => 'Diproses', 'count' => $counts['diproses'], 'color' => '#3B82F6', 'icon' => 'M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182'],
            ['label' => 'Selesai', 'count' => $counts['selesai'], 'color' => '#22C55E', 'icon' => 'M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z'],
        ] as $stat)
            <div class="stat-card">
                <div>
                    <p class="stat-label">{{ $stat['label'] }}</p>
                    <p class="stat-value" style="color: {{ $stat['color'] }};">{{ number_format($stat['count']) }}</p>
                </div>
                <div class="stat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $stat['icon'] }}" />
                    </svg>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Tren Laporan + Mini Map --}}
    <div style="display: grid; grid-template-columns: 1fr 300px; gap: 24px; margin-bottom: 32px;">
        {{-- Chart --}}
        <div class="admin-card">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px;">
                <h2 class="section-title">Tren Laporan</h2>
                
                <form method="GET" action="{{ route('operator.dashboard') }}" id="chartFilterForm" style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap; justify-content: flex-end;">
                    <input type="hidden" name="period" id="periodInput" value="{{ $period ?? 'weekly' }}">
                    
                    <select name="year" class="admin-input" style="padding: 6px 10px; min-height: 32px; font-size: 0.8rem; width: auto;" onchange="document.getElementById('chartFilterForm').submit()">
                        <option value="2026" {{ ($selectedYear ?? 2026) == 2026 ? 'selected' : '' }}>2026</option>
                        <option value="2025" {{ ($selectedYear ?? 2026) == 2025 ? 'selected' : '' }}>2025</option>
                    </select>

                    <select name="month" class="admin-input" style="padding: 6px 10px; min-height: 32px; font-size: 0.8rem; width: auto;" onchange="document.getElementById('chartFilterForm').submit()">
                        @foreach(['01'=>'Jan', '02'=>'Feb', '03'=>'Mar', '04'=>'Apr', '05'=>'Mei', '06'=>'Jun', '07'=>'Jul', '08'=>'Agt', '09'=>'Sep', '10'=>'Okt', '11'=>'Nov', '12'=>'Des'] as $num => $name)
                            <option value="{{ $num }}" {{ str_pad($selectedMonth ?? date('m'), 2, '0', STR_PAD_LEFT) == $num ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>

                    <div class="chart-toggle" style="margin-left: 4px;">
                        <button type="button" style="padding: 6px 10px; font-size: 0.75rem;" class="{{ ($period ?? 'weekly') === 'weekly' ? 'active' : '' }}" onclick="document.getElementById('periodInput').value='weekly'; document.getElementById('chartFilterForm').submit()">Minggu</button>
                        <button type="button" style="padding: 6px 10px; font-size: 0.75rem;" class="{{ ($period ?? 'weekly') === 'monthly' ? 'active' : '' }}" onclick="document.getElementById('periodInput').value='monthly'; document.getElementById('chartFilterForm').submit()">Bulan</button>
                        <button type="button" style="padding: 6px 10px; font-size: 0.75rem;" class="{{ ($period ?? 'weekly') === 'yearly' ? 'active' : '' }}" onclick="document.getElementById('periodInput').value='yearly'; document.getElementById('chartFilterForm').submit()">Tahun</button>
                    </div>
                </form>
            </div>
            <div style="height: 220px;">
                <canvas id="trendChart"></canvas>
            </div>
        </div>

        {{-- Mini Map --}}
        <div class="admin-card" style="padding: 0; overflow: hidden; position: relative; display: flex; flex-direction: column;">
            <div id="miniMap" style="flex: 1; min-height: 200px;"></div>
            <div style="padding: 16px; background: rgba(107,29,42,0.85); backdrop-filter: blur(8px);">
                <p style="font-size: 0.85rem; font-weight: 700; color: white; margin: 0;">Peta Laporan Aktif</p>
                <p style="font-size: 0.7rem; color: rgba(255,255,255,0.7); margin: 4px 0 12px 0;">Pantau persebaran laporan kerusakan jalan secara real-time.</p>
                <a href="{{ route('operator.map') }}" class="btn-admin-secondary" style="background: white; color: var(--maroon, #6B1D2A); font-size: 0.78rem; padding: 8px 16px; width: 100%; justify-content: center;">
                    Buka Peta
                </a>
            </div>
        </div>
    </div>

    {{-- Laporan Terbaru --}}
    <h2 class="section-title" style="margin-bottom: 20px;">Laporan Terbaru</h2>
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 32px;">
        @forelse($latestReports as $report)
            <div class="admin-card" style="padding: 0; overflow: hidden;">
                {{-- Status Badge + Time --}}
                <div style="padding: 16px 20px 0; display: flex; justify-content: space-between; align-items: center;">
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
                    <span class="badge {{ $badgeClass }}">{{ strtoupper($report->status) }}</span>
                    <span style="font-size: 0.7rem; color: #9ca3af;">{{ $report->created_at->diffForHumans() }}</span>
                </div>

                {{-- Content --}}
                <div style="padding: 16px 20px;">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                        @if($report->photo_path)
                            <img src="{{ asset('storage/' . $report->photo_path) }}" alt="Foto laporan"
                                 style="width: 48px; height: 48px; border-radius: 10px; object-fit: cover; border: 1px solid rgba(107,29,42,0.06);">
                        @else
                            <div style="width:48px;height:48px;border-radius:10px;background:#f5f0f0;display:flex;align-items:center;justify-content:center;">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="#b8a0a5" width="22" height="22"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" /></svg>
                            </div>
                        @endif
                        <div>
                            <p style="font-size: 0.875rem; font-weight: 600; color: #1a1a1a; margin: 0;">
                                {{ $report->damage_type ?? 'Kerusakan Jalan' }}
                            </p>
                            <p style="font-size: 0.7rem; color: #9ca3af; margin: 2px 0 0 0; display: flex; align-items: center; gap: 4px;">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="11" height="11"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" /></svg>
                                {{ number_format($report->latitude, 4) }}, {{ number_format($report->longitude, 4) }}
                            </p>
                        </div>
                    </div>

                    @if($report->description)
                        <p style="font-size: 0.8rem; color: #6b7280; margin-bottom: 12px; line-height: 1.5;">
                            "{{ Str::limit($report->description, 80) }}"
                        </p>
                    @endif
                </div>

                {{-- Footer --}}
                <div style="padding: 12px 20px; border-top: 1px solid rgba(107,29,42,0.04);">
                    <a href="{{ route('operator.reports.show', $report) }}" style="font-size: 0.8rem; font-weight: 600; color: var(--maroon, #6B1D2A); text-decoration: none;">
                        Lihat Detail →
                    </a>
                </div>
            </div>
        @empty
            <div class="admin-card" style="grid-column: span 3; text-align: center; padding: 48px; color: #9ca3af;">
                <p>Belum ada laporan masuk.</p>
            </div>
        @endforelse
    </div>

    {{-- Scripts --}}
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // ===== Trend Chart =====
        const ctx = document.getElementById('trendChart').getContext('2d');
        const data = @json($weeklyTrend);
        const days = @json($days);
        const maxVal = Math.max(...data, 1);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: days,
                datasets: [{
                    data: data,
                    backgroundColor: data.map(v => v === maxVal ? 'rgba(107,29,42,0.85)' : 'rgba(107,29,42,0.2)'),
                    borderRadius: 8,
                    borderSkipped: false,
                    barThickness: 40,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { backgroundColor: '#6B1D2A', cornerRadius: 8, padding: 10, titleFont: { family: 'Manrope' }, bodyFont: { family: 'Manrope' } }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { font: { family: 'Manrope', size: 11, weight: '500' }, color: '#9ca3af' } },
                    y: { display: false }
                }
            }
        });

        // ===== Mini Map =====
        const map = L.map('miniMap', { zoomControl: false, attributionControl: false }).setView([-6.2088, 106.8456], 11);
        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png').addTo(map);

        const mapReports = @json($mapReports);
        mapReports.forEach(r => {
            const colors = { 'Dilaporkan': '#EF4444', 'Disurvey': '#F59E0B', 'Diproses': '#3B82F6' };
            L.circleMarker([r.latitude, r.longitude], {
                radius: 6, fillColor: colors[r.status] || '#9CA3AF', color: 'white', weight: 2, fillOpacity: 0.9
            }).addTo(map);
        });
    });
    </script>
@endsection
