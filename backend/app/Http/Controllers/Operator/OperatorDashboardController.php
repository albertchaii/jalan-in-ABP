<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Carbon\Carbon;

use Illuminate\Http\Request;

class OperatorDashboardController extends Controller
{
    public function index(Request $request)
    {
        $counts = [
            'dilaporkan'  => Report::where('status', 'Dilaporkan')->count(),
            'disurvey'    => Report::where('status', 'Disurvey')->count(),
            'tidak_valid' => Report::where('status', 'Tidak Valid')->count(),
            'diproses'    => Report::where('status', 'Diproses')->count(),
            'selesai'     => Report::where('status', 'Selesai')->count(),
        ];

        // Chart Filtering Logic
        $period = $request->query('period', 'weekly');
        $selectedYear = $request->query('year', Carbon::now()->year);
        $selectedMonth = $request->query('month', Carbon::now()->month);

        $weeklyTrend = [];
        $days = [];

        if ($period === 'yearly') {
            for ($i = 1; $i <= 12; $i++) {
                $monthName = Carbon::create()->month($i)->locale('id')->isoFormat('MMM');
                $days[] = strtoupper($monthName);
                
                $count = Report::whereYear('created_at', $selectedYear)
                               ->whereMonth('created_at', $i)
                               ->count();
                $weeklyTrend[] = $count;
            }
        } elseif ($period === 'monthly') {
            $daysInMonth = Carbon::create($selectedYear, $selectedMonth)->daysInMonth;
            for ($i = 1; $i <= $daysInMonth; $i++) {
                $days[] = (string)$i;
                $count = Report::whereYear('created_at', $selectedYear)
                               ->whereMonth('created_at', $selectedMonth)
                               ->whereDay('created_at', $i)
                               ->count();
                $weeklyTrend[] = $count;
            }
        } else {
            // Default: Weekly (Rolling 7 Days)
            $today = Carbon::now();
            for ($i = 6; $i >= 0; $i--) {
                $date = $today->copy()->subDays($i);
                
                $dayMap = [
                    'Mon' => 'SEN', 'Tue' => 'SEL', 'Wed' => 'RAB',
                    'Thu' => 'KAM', 'Fri' => 'JUM', 'Sat' => 'SAB', 'Sun' => 'MIN'
                ];
                $dayName = $dayMap[$date->format('D')];
                $dateString = $date->format('d/m');
                
                $days[] = [$dayName, $dateString];
                $weeklyTrend[] = Report::whereDate('created_at', $date)->count();
            }
        }

        // 3 laporan terbaru
        $latestReports = Report::with('user')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        // Semua laporan untuk mini-map (hanya koordinat + status)
        $mapReports = Report::select('id', 'latitude', 'longitude', 'status', 'description')
            ->whereIn('status', ['Dilaporkan', 'Disurvey', 'Diproses'])
            ->get();

        return view('operator.dashboard', compact(
            'counts', 'weeklyTrend', 'days', 'latestReports', 'mapReports',
            'period', 'selectedYear', 'selectedMonth'
        ));
    }
}
