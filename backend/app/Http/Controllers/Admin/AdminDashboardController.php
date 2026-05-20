<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index(Request $request)
    {
        $totalUsers = User::count();
        $totalOperators = User::where('role', 'operator')->count();
        $operators = User::where('role', 'operator')->latest()->take(5)->get();

        // Chart Filtering Logic
        $period = $request->query('period', 'weekly');
        $selectedYear = $request->query('year', \Carbon\Carbon::now()->year);
        $selectedMonth = $request->query('month', \Carbon\Carbon::now()->month);

        $weeklyTrend = [];
        $days = [];

        if ($period === 'yearly') {
            // Data 12 bulan untuk tahun yang dipilih
            for ($i = 1; $i <= 12; $i++) {
                $monthName = \Carbon\Carbon::create()->month($i)->locale('id')->isoFormat('MMM');
                $days[] = strtoupper($monthName);
                
                $count = \App\Models\Report::whereYear('created_at', $selectedYear)
                                           ->whereMonth('created_at', $i)
                                           ->count();
                $weeklyTrend[] = $count;
            }
        } elseif ($period === 'monthly') {
            // Data per hari untuk bulan & tahun yang dipilih
            $daysInMonth = \Carbon\Carbon::create($selectedYear, $selectedMonth)->daysInMonth;
            for ($i = 1; $i <= $daysInMonth; $i++) {
                $days[] = (string)$i;
                $count = \App\Models\Report::whereYear('created_at', $selectedYear)
                                           ->whereMonth('created_at', $selectedMonth)
                                           ->whereDay('created_at', $i)
                                           ->count();
                $weeklyTrend[] = $count;
            }
        } else {
            // Default: Weekly (Rolling 7 Days)
            $today = \Carbon\Carbon::now();
            for ($i = 6; $i >= 0; $i--) {
                $date = $today->copy()->subDays($i);
                
                $dayMap = [
                    'Mon' => 'SEN', 'Tue' => 'SEL', 'Wed' => 'RAB',
                    'Thu' => 'KAM', 'Fri' => 'JUM', 'Sat' => 'SAB', 'Sun' => 'MIN'
                ];
                $dayName = $dayMap[$date->format('D')];
                $dateString = $date->format('d/m');
                
                $days[] = [$dayName, $dateString];
                $weeklyTrend[] = \App\Models\Report::whereDate('created_at', $date)->count();
            }
        }

        return view('admin.dashboard', compact(
            'totalUsers', 'totalOperators', 'operators', 
            'days', 'weeklyTrend', 'period', 'selectedYear', 'selectedMonth'
        ));
    }
}
