<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Claim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'petugas':
                return redirect()->route('petugas.dashboard');
            case 'mahasiswa':
            default:
                // Continue with mahasiswa dashboard
                break;
        }
        
        $stats = [
            'total_reports' => $user->reports()->count(),
            'pending_reports' => $user->reports()->where('status', 'pending')->count(),
            'approved_reports' => $user->reports()->where('status', 'approved')->count(),
            'total_claims' => $user->claims()->count(),
            'pending_claims' => $user->claims()->where('status', 'pending')->count(),
        ];

        $recent_reports = $user->reports()
            ->with(['category', 'room.building'])
            ->latest()
            ->take(5)
            ->get();

        $recent_claims = $user->claims()
            ->with(['report'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact('stats', 'recent_reports', 'recent_claims'));
    }
    public function userDashboard()
    {
        $userId = Auth::id();

        // Statistik yang sudah lengkap
        $stats = [
            'total_reports'    => Report::where('user_id', $userId)->count(),
            'pending_reports'  => Report::where('user_id', $userId)->where('status', 'pending')->count(),
            'approved_reports' => Report::where('user_id', $userId)->where('status', 'approved')->count(),
            'total_claims'     => Claim::where('user_id', $userId)->count(),
        ];
        
        // Data laporan terbaru yang sudah ada
        $recentReports = Report::with('category')
            ->where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();
            
        // =======================================================
        // == 1. TAMBAHKAN BLOK LOGIKA BARU INI UNTUK KLAIM      ==
        // =======================================================
        $recentClaims = Claim::with('report') // Ambil juga data laporannya
            ->where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        // ===================================================================
        // == 2. TAMBAHKAN 'recentClaims' KE DALAM compact()                 ==
        // ===================================================================
        return view('dashboard', compact('stats', 'recentReports', 'recentClaims'));
    }
}
