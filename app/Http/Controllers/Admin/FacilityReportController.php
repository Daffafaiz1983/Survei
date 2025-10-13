<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FacilityReport;
use Illuminate\Http\Request;

class FacilityReportController extends Controller
{
    public function index()
    {
        $reports = FacilityReport::with('user')->latest()->paginate(15);
        return view('admin.reports.index', compact('reports'));
    }

    public function show(int $id)
    {
        $report = FacilityReport::with('user')->findOrFail($id);
        return view('admin.reports.show', compact('report'));
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'status' => 'required|in:submitted,in_review,resolved',
        ]);

        $report = FacilityReport::findOrFail($id);
        $report->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status laporan diperbarui.');
    }
}


