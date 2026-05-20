@extends('layouts.admin')

@section('content')
    {{-- Page Header --}}
    <div style="margin-bottom: 32px;">
        <h1 class="page-title">Dasbor</h1>
        <p class="page-subtitle">Memantau infrastruktur jaringan dan aktivitas pengguna di seluruh platform.</p>
    </div>

    {{-- Stats Cards --}}
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 32px;">
        {{-- Total Pengguna Aktif --}}
        <div class="stat-card">
            <div>
                <p class="stat-label">Total Pengguna Aktif</p>
                <p class="stat-value">{{ number_format($totalUsers ?? 0) }}</p>
                <p class="stat-footer">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="14" height="14">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    Diperbarui {{ now()->diffForHumans() }}
                </p>
            </div>
            <div style="display: flex; align-items: flex-start; gap: 8px;">
                <div class="stat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                    </svg>
                </div>
                <span class="badge badge-success" style="font-size: 0.6rem;">+12%</span>
            </div>
        </div>

        {{-- Total Operator --}}
        <div class="stat-card">
            <div>
                <p class="stat-label">Operator</p>
                <p class="stat-value">{{ $totalOperators ?? 0 }}</p>
                <p class="stat-footer">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="14" height="14">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    Semua sertifikasi berlaku
                </p>
            </div>
            <div style="display: flex; align-items: flex-start; gap: 8px;">
                <div class="stat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                    </svg>
                </div>
                <span class="badge badge-info" style="font-size: 0.6rem;">Batch Baru</span>
            </div>
        </div>

        {{-- System Uptime --}}
        <div class="stat-card accent">
            <div>
                <p class="stat-label">System Uptime</p>
                <p class="stat-value">99.98<span style="font-size: 1rem;">%</span></p>
                <p class="stat-footer">Load Balancer: Optimal</p>
            </div>
            <div style="display: flex; align-items: flex-start; gap: 8px;">
                <div class="stat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
                <span class="badge" style="font-size: 0.6rem; background: rgba(255,255,255,0.2); color: white;">Healthy</span>
            </div>
        </div>
    </div>

    {{-- Activity Chart --}}
    <div class="admin-card" style="margin-bottom: 32px;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px;">
            <div>
                <h2 class="section-title">Aktivitas Sistem</h2>
                <p class="section-subtitle">Volume permintaan dan laporan baru</p>
            </div>
            <form method="GET" action="{{ route('admin.dashboard') }}" id="chartFilterForm" style="display: flex; gap: 12px; align-items: center;">
                <input type="hidden" name="period" id="periodInput" value="{{ $period ?? 'weekly' }}">
                
                <select name="year" class="admin-input" style="padding: 6px 12px; min-height: 36px;" onchange="document.getElementById('chartFilterForm').submit()">
                    <option value="2026" {{ ($selectedYear ?? 2026) == 2026 ? 'selected' : '' }}>2026</option>
                    <option value="2025" {{ ($selectedYear ?? 2026) == 2025 ? 'selected' : '' }}>2025</option>
                </select>

                <select name="month" class="admin-input" style="padding: 6px 12px; min-height: 36px;" onchange="document.getElementById('chartFilterForm').submit()">
                    @foreach(['01'=>'Jan', '02'=>'Feb', '03'=>'Mar', '04'=>'Apr', '05'=>'Mei', '06'=>'Jun', '07'=>'Jul', '08'=>'Agt', '09'=>'Sep', '10'=>'Okt', '11'=>'Nov', '12'=>'Des'] as $num => $name)
                        <option value="{{ $num }}" {{ str_pad($selectedMonth ?? date('m'), 2, '0', STR_PAD_LEFT) == $num ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>

                <div class="chart-toggle" style="margin-left: 8px;">
                    <button type="button" class="{{ ($period ?? 'weekly') === 'weekly' ? 'active' : '' }}" onclick="document.getElementById('periodInput').value='weekly'; document.getElementById('chartFilterForm').submit()">Mingguan</button>
                    <button type="button" class="{{ ($period ?? 'weekly') === 'monthly' ? 'active' : '' }}" onclick="document.getElementById('periodInput').value='monthly'; document.getElementById('chartFilterForm').submit()">Bulanan</button>
                    <button type="button" class="{{ ($period ?? 'weekly') === 'yearly' ? 'active' : '' }}" onclick="document.getElementById('periodInput').value='yearly'; document.getElementById('chartFilterForm').submit()">Tahunan</button>
                </div>
            </form>
        </div>
        <div style="height: 240px;">
            <canvas id="activityChart"></canvas>
        </div>
    </div>

    {{-- Operator Management Table --}}
    <div class="admin-card">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px;">
            <div>
                <h2 class="section-title">Pengelolaan Operator</h2>
                <p class="section-subtitle">Gambaran singkat mengenai tenaga kerja operasional saat ini.</p>
            </div>
            <a href="{{ route('admin.users.add') }}" class="btn-admin-primary">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="16" height="16">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Operator Baru
            </a>
        </div>

        <table class="admin-table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Sinkronisasi Terakhir</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($operators ?? [] as $operator)
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div class="user-avatar">
                                    {{ strtoupper(substr($operator->name, 0, 1)) }}
                                </div>
                                <span style="font-weight: 600; color: #1a1a1a;">{{ $operator->name }}</span>
                            </div>
                        </td>
                        </td>
                        <td style="font-size: 0.8rem; color: #9ca3af;">
                            {{ $operator->updated_at?->diffForHumans() ?? '-' }}
                        </td>
                        <td>
                            <button class="action-btn" title="Opsi lainnya">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18" height="18">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 12.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 18.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
                                </svg>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="text-align: center; padding: 40px; color: #9ca3af;">
                            Belum ada operator terdaftar.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Chart Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('activityChart').getContext('2d');

            const gradient1 = ctx.createLinearGradient(0, 0, 0, 240);
            gradient1.addColorStop(0, 'rgba(107, 29, 42, 0.6)');
            gradient1.addColorStop(1, 'rgba(107, 29, 42, 0.15)');

            const gradient2 = ctx.createLinearGradient(0, 0, 0, 240);
            gradient2.addColorStop(0, 'rgba(139, 46, 59, 0.45)');
            gradient2.addColorStop(1, 'rgba(139, 46, 59, 0.1)');

            const dataValues = @json($weeklyTrend);
            
            // Beri warna khusus (gradient) untuk hari terakhir (hari ini)
            const bgColors = dataValues.map((val, index) => {
                return index === dataValues.length - 1 ? gradient1 : 'rgba(139, 46, 59, 0.35)';
            });

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($days),
                    datasets: [{
                        label: 'Laporan Masuk',
                        data: dataValues,
                        backgroundColor: bgColors,
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
                        tooltip: {
                            backgroundColor: '#6B1D2A',
                            titleFont: { family: 'Manrope', size: 12 },
                            bodyFont: { family: 'Manrope', size: 11 },
                            cornerRadius: 8,
                            padding: 10,
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: {
                                font: { family: 'Manrope', size: 11, weight: '500' },
                                color: '#9ca3af'
                            }
                        },
                        y: {
                            display: false,
                            grid: { display: false },
                        }
                    }
                }
            });
        });
    </script>
@endsection
