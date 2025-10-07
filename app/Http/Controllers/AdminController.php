<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Claim;
use App\Models\User;
use App\Models\Category;
use App\Models\Building;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // == METHOD YANG DIPERBARUI ==
    public function dashboard()
    {
        // Enhanced statistics
        $stats = [
            'total_reports' => Report::count(),
            'pending_reports' => Report::where('status', 'pending')->count(),
            'approved_reports' => Report::where('status', 'approved')->count(),
            'total_claims' => Claim::count(),
            'pending_claims' => Claim::where('status', 'pending')->count(),
            'approved_claims' => Claim::where('status', 'approved')->count(),
            'total_users' => User::count(),
            'total_students' => User::where('role', 'mahasiswa')->count(),
            'total_staff' => User::where('role', 'petugas')->count(),
        ];

        // Reports by category (for chart)
        $reportsByCategory = Report::select('categories.name', DB::raw('count(*) as total'))
            ->join('categories', 'reports.category_id', '=', 'categories.id')
            ->groupBy('categories.name')
            ->get();

        // Monthly reports (for chart)
        $monthlyReports = Report::select(DB::raw('MONTH(created_at) as month'), DB::raw('count(*) as total'))
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Recent activities
        $recentReports = Report::with(['user', 'category', 'room.building'])
            ->latest()
            ->take(5)
            ->get();

        $recentClaims = Claim::with(['user', 'report'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats', 
            'reportsByCategory', 
            'monthlyReports', 
            'recentReports', 
            'recentClaims'
        ));
    }
}