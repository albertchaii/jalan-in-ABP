<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class OperatorMapController extends Controller
{
    public function index(Request $request)
    {
        return view('operator.map');
    }

    /**
     * Return reports as GeoJSON for Leaflet map.
     * Supports filter by status via query param.
     */
    public function geojson(Request $request)
    {
        $query = Report::query();

        // Filter by status (multiple allowed via comma-separated)
        $statusFilter = $request->get('status');
        if ($statusFilter) {
            $statuses = explode(',', $statusFilter);
            $query->whereIn('status', $statuses);
        }

        $reports = $query->with('user:id,name')->get();

        $features = $reports->map(function ($report) {
            return [
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [$report->longitude, $report->latitude],
                ],
                'properties' => [
                    'id'          => $report->id,
                    'description' => $report->description ?? 'Laporan Kerusakan Jalan',
                    'status'      => $report->status,
                    'color'       => $report->statusColor(),
                    'damage_type' => $report->damage_type,
                    'photo_url'   => $report->photo_path ? asset('storage/' . $report->photo_path) : null,
                    'reporter'    => $report->user->name ?? '-',
                    'created_at'  => $report->created_at->format('d M Y, H:i'),
                    'detail_url'  => route('operator.reports.show', $report->id),
                ],
            ];
        });

        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $features,
        ]);
    }
}
