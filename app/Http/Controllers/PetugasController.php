<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Claim;
use App\Models\Category;
use App\Models\Building;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PetugasController extends Controller
{
    use AuthorizesRequests; // Pastikan trait ini ada
    public function dashboard()
    {
        $stats = [
            'pending_reports' => Report::where('status', 'pending')->count(),
            'pending_claims' => Claim::where('status', 'pending')->count(),
            'total_categories' => Category::count(),
            'total_buildings' => Building::count(),
            'total_rooms' => Room::count(),
        ];

        $monthlyReports = Report::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as total')
        )
        ->whereYear('created_at', date('Y'))
        ->groupBy('month')
        ->orderBy('month')
        ->get();

        $reportsByCategory = Report::select('categories.name', DB::raw('COUNT(*) as total'))
            ->join('categories', 'reports.category_id', '=', 'categories.id')
            ->groupBy('categories.id', 'categories.name')
            ->get();

        $recentReports = Report::with(['category', 'room.building', 'user']) // Eager load relasi building dari room
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        $recentClaims = Claim::with(['report.category', 'user'])
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        return view('petugas.dashboard', compact('stats', 'monthlyReports', 'reportsByCategory', 'recentReports', 'recentClaims'));
    }

    public function reports(Request $request)
    {
        // PERBAIKAN 1: Hanya ambil laporan yang statusnya 'pending'
        $query = Report::with(['category', 'room.building', 'user'])
            ->whereIn('status', ['pending', 'approved', 'rejected']);

        // Terapkan filter dari request (jika ada)
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $search = $request->search;
                $q->where('item_name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%");
                });
            });
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // PERBAIKAN 2: Siapkan data statistik dengan benar
        $stats = [
            'pending_total' => Report::where('status', 'pending')->count(), 
            'pending' => (clone $query)->where('status', 'pending')->count(),
            'lost' => (clone $query)->where('type', 'lost')->count(),
            'found' => (clone $query)->where('type', 'found')->count(),
        ];
        
        // Ambil data utama dengan pagination
        $reports = $query->latest('updated_at')->paginate(10);

        // Kirim data ke view
        return view('shared.reports_validation', compact('reports', 'stats'));
    }

    public function validateReport(Request $request, Report $report)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $report->update([
            'status' => $request->status,
            'validator_id' => auth()->id(), // Menggunakan validator_id sesuai skema baru
        ]);

        return redirect()->back()->with('success', 'Laporan berhasil divalidasi');
    }

    public function claims(Request $request)
    {
        $query = Claim::with(['user', 'report'])
            ->whereIn('status', ['pending', 'approved', 'rejected']);

        $stats = [
            'total' => (clone $query)->count(),
            'pending' => (clone $query)->where('status', 'pending')->count(),
            'approved' => (clone $query)->where('status', 'approved')->count(),
            'rejected' => (clone $query)->where('status', 'rejected')->count(),
        ];
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('report', fn($q2) => $q2->where('item_name', 'like', "%{$search}%"))
                ->orWhereHas('user', fn($q2) => $q2->where('name', 'like', "%{$search}%"));
            });
        }
        $claims = $query->latest('updated_at')->paginate(10);

        return view('shared.claims_validation', compact('claims'));
    }

    public function validateClaim(Request $request, Claim $claim)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $claim->update([
            'status' => $request->status,
            'validator_id' => auth()->id(), // Menggunakan validator_id
        ]);

        if ($request->status === 'approved') {
            $claim->report->update(['status' => 'returned']); // Status 'returned' lebih deskriptif
        }

        return redirect()->back()->with('success', 'Klaim berhasil divalidasi');
    }

    public function createReport()
    {
        $categories = Category::all();
        $buildings = Building::with('rooms')->get();
        // Rooms akan di-load secara dinamis, jadi tidak perlu dikirim semua
        return view('petugas.reports.create', compact('categories', 'buildings'));
    }

    // == METHOD YANG DIPERBARUI ==
    public function storeReport(Request $request)
    {
        $request->validate([
            'item_name' => 'required|string|max:255', // Sesuai skema DB: item_name
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'room_id' => 'required|exists:rooms,id', // Hanya butuh room_id
            'event_date' => 'required|date', // Sesuai skema DB: event_date
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Sesuai skema DB: photo
        ]);

        $imagePath = null;
        if ($request->hasFile('photo')) {
            $imagePath = $request->file('photo')->store('reports', 'public');
        }

        Report::create([
            'user_id' => auth()->id(),
            'item_name' => $request->item_name,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'room_id' => $request->room_id,

            'event_date' => $request->event_date,
            'photo' => $imagePath,
            'type' => 'found', // Petugas hanya melaporkan barang temuan
            'status' => 'approved', // Otomatis approved untuk petugas
            'validator_id' => auth()->id(), // Langsung set validator
        ]);

        return redirect()->route('petugas.reports') // Arahkan ke index
            ->with('success', 'Laporan berhasil ditambahkan dan langsung disetujui.');
    }
    public function showReport(Report $report)
    {
        // Petugas bisa melihat detail laporan apapun
        $report->load(['user', 'room.building', 'category', 'validator', 'claims.user']);
        
        // Kita bisa gunakan lagi view 'reports.show' yang sudah ada
        return view('reports.show', compact('report'));
    }

    public function edit(Report $report)
    {
        $this->authorize('update', $report); // Gunakan Policy

        $categories = Category::all();
        $buildings = Building::with('rooms')->get();
        
        // Arahkan ke view BARU yang khusus untuk petugas
        return view('petugas.reports.edit', compact('report', 'categories', 'buildings'));
    }

    public function update(Request $request, Report $report)
    {
        // 1. Otorisasi: Pastikan petugas/admin boleh mengedit
        $this->authorize('update', $report);

        // 2. Validasi: Pastikan semua data dari form valid
        $validatedData = $request->validate([
            'item_name' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'room_id' => 'required|exists:rooms,id',
            'event_date' => 'required|date',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:pending,approved,rejected', // <-- ATURAN BARU
        ]);

        // 3. Handle upload foto (jika ada)
        if ($request->hasFile('photo')) {
            if ($report->photo) {
                Storage::disk('public')->delete($report->photo);
            }
            $validatedData['photo'] = $request->file('photo')->store('reports', 'public');
        }
        
        // 4. Update data di database
        $report->update($validatedData);

        // 5. PERBAIKAN REDIRECT: Arahkan ke daftar laporan petugas
        return redirect()->route('petugas.reports')->with('success', 'Laporan berhasil diperbarui.');
    }

}