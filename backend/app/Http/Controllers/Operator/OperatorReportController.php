<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class OperatorReportController extends Controller
{
    public function index(Request $request)
    {
        $totalReports = Report::count();
        $pendingValidation = Report::where('status', 'Dilaporkan')->count();
        $completedToday = Report::where('status', 'Selesai')
            ->where('completed_at', '>=', Carbon::now()->subDay())
            ->count();

        $query = Report::with('user');

        // Filter by status
        $statusFilter = $request->get('status');
        if ($statusFilter && $statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        // Search
        $search = $request->get('search');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'LIKE', "%{$search}%")
                  ->orWhere('id', $search);
            });
        }

        // Sort
        $sort = $request->get('sort', 'terbaru');
        if ($sort === 'terlama') {
            $query->orderBy('created_at', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $reports = $query->paginate(10)
            ->appends($request->query());

        return view('operator.reports.index', compact(
            'totalReports', 'pendingValidation', 'completedToday',
            'reports', 'statusFilter', 'search', 'sort'
        ));
    }

    public function show(Report $report)
    {
        $report->load('user', 'operator');

        return view('operator.reports.show', compact('report'));
    }

    public function updateStatus(Request $request, Report $report)
    {
        $request->validate([
            'status' => ['required', 'in:Dilaporkan,Disurvey,Tidak Valid,Diproses,Selesai'],
        ]);

        $newStatus = $request->status;

        // Selesai butuh bukti perbaikan
        if ($newStatus === 'Selesai' && !$report->evidence_photo_path) {
            return redirect()->route('operator.reports.show', $report)
                ->with('error', 'Wajib mengunggah foto bukti perbaikan sebelum menandai Selesai.');
        }

        $updateData = [
            'status' => $newStatus,
            'operator_id' => Auth::id(),
        ];

        if ($newStatus === 'Disurvey' && !$report->verified_at) {
            $updateData['verified_at'] = now();
        }

        if ($newStatus === 'Selesai') {
            $updateData['completed_at'] = now();
        }

        $report->update($updateData);

        return redirect()->route('operator.reports.show', $report)
            ->with('success', "Status laporan berhasil diubah menjadi \"{$newStatus}\".");
    }

    public function uploadEvidence(Request $request, Report $report)
    {
        $request->validate([
            'evidence_photo' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $path = $request->file('evidence_photo')->store('evidence', 'public');

        $report->update([
            'evidence_photo_path' => $path,
        ]);

        return redirect()->route('operator.reports.show', $report)
            ->with('success', 'Foto bukti perbaikan berhasil diunggah.');
    }
}
