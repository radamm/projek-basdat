<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClaimController extends Controller
{
    public function index(Request $request)
    {
        $query = Claim::with(['report.category']) // Eager load relasi yang dibutuhkan
            ->where('user_id', Auth::id());

        if ($request->filled('search')) {
            $query->whereHas('report', function($q) use ($request) {
                $q->where('item_name', 'like', "%{$request->search}%");
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $claims = $query->latest()->paginate(10);
        return view('claims.index', compact('claims'));
    }

    public function show(Claim $claim)
    {
        $claim->load(['report.category', 'report.room.building', 'report.user', 'validator']);
        
        return view('claims.show', compact('claim'));
    }

    public function store(Request $request, Report $report)
    {
        // Check if report is approved and is a found item
        if ($report->status !== 'approved' || $report->type !== 'found') {
            return back()->with('error', 'Barang ini tidak dapat diklaim.');
        }

        // Check if user already has a pending claim for this report
        $existingClaim = Claim::where('report_id', $report->id)
            ->where('user_id', Auth::id())
            ->where('status', 'pending')
            ->first();

        if ($existingClaim) {
            return back()->with('error', 'Anda sudah memiliki klaim yang sedang diproses untuk barang ini.');
        }

        $request->validate([
            'description' => 'required|string|min:20',
        ]);

        $claim = Claim::create([
            'report_id' => $report->id,
            'user_id' => Auth::id(),
            'description' => $request->description,
            'status' => 'pending',
        ]);

        // Create notification for report owner
        $report->user->notifications()->create([
            'title' => 'Klaim Baru untuk Barang Anda',
            'message' => "Ada klaim baru untuk barang '{$report->item_name}' yang Anda laporkan. Silakan koordinasi dengan petugas untuk verifikasi.",
        ]);

        return redirect()->back()
            ->with('success', 'Klaim berhasil diajukan. Menunggu validasi dari petugas.');
    }

    public function validation()
    {
        $query = Claim::with(['report.category', 'report.room.building', 'user'])
            ->where('status', 'pending');

        if (request('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->whereHas('report', function($q2) use ($search) {
                    $q2->where('item_name', 'like', "%{$search}%");
                })->orWhereHas('user', function($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%");
                });
            });
        }

        $claims = $query->latest()->paginate(10);

        return view('shared.claims_validation', compact('claims'));
    }

    public function validate(Request $request, Claim $claim)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'notes' => 'nullable|string',
        ]);

        $claim->update([
            'status' => $request->status,
            'validator_id' => Auth::id(),
        ]);

        // If approved, update report status to returned
        if ($request->status === 'approved') {
            $claim->report->update(['status' => 'returned']);
        }

        // Create notification for claimer
        $notificationTitle = $request->status === 'approved' ? 'Klaim Disetujui!' : 'Klaim Ditolak';
        $notificationMessage = $request->status === 'approved' 
            ? "Klaim Anda untuk barang '{$claim->report->item_name}' telah disetujui. Silakan ambil barang di Kantor Kemahasiswaan pada jam kerja."
            : "Klaim Anda untuk barang '{$claim->report->item_name}' ditolak. " . ($request->notes ? "Alasan: {$request->notes}" : "");

        $claim->user->notifications()->create([
            'title' => $notificationTitle,
            'message' => $notificationMessage,
        ]);

        // Also notify the report owner
        $claim->report->user->notifications()->create([
            'title' => 'Status Klaim Diperbarui',
            'message' => "Klaim untuk barang '{$claim->report->item_name}' telah " . 
                        ($request->status === 'approved' ? 'disetujui' : 'ditolak') . 
                        " oleh petugas.",
        ]);

        return back()->with('success', 'Klaim berhasil divalidasi.');
    }

    public function validated(Request $request)
    {
        // Secara otomatis set status filter jadi 'approved'
        $request->merge(['status' => 'approved']);

        // Pakai ulang index() supaya tampilan & filter sama
        return $this->index();
    }

    public function destroy(Claim $claim)
    {
        // Pastikan hanya admin/petugas yang bisa menghapus
        if (!in_array(Auth::user()->role, ['petugas', 'admin'])) {
            abort(403);
        }
        
        // Jika klaim yang dihapus adalah klaim yang disetujui,
        // kembalikan status laporan menjadi 'approved' agar bisa diklaim lagi.
        if ($claim->status === 'approved') {
            $claim->report->update(['status' => 'approved']);
        }

        $claim->delete();

        return redirect()->back()->with('success', 'Klaim berhasil dihapus.');
    }
}
