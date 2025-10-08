@extends('layouts.app')

@section('title', 'Dashboard Petugas')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard Petugas</h1>
        <p class="text-gray-600 mt-2">Kelola validasi laporan dan klaim barang hilang & temuan</p>
    </div>

    {{-- Stats Cards dengan Warna Baru --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
        <div class="bg-[#073763] rounded-xl p-6 text-white shadow-lg">
            <p class="text-blue-100 text-sm opacity-80">Laporan Pending</p>
            <p class="text-3xl font-bold mt-2">{{ $stats['pending_reports'] }}</p>
        </div>
        <div class="bg-[#741B47] rounded-xl p-6 text-white shadow-lg">
            <p class="text-pink-100 text-sm opacity-80">Klaim Pending</p>
            <p class="text-3xl font-bold mt-2">{{ $stats['pending_claims'] }}</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-6">
            <p class="text-gray-500 text-sm">Total Kategori</p>
            <p class="text-3xl font-bold mt-2 text-gray-800">{{ $stats['total_categories'] }}</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-6">
            <p class="text-gray-500 text-sm">Total Gedung</p>
            <p class="text-3xl font-bold mt-2 text-gray-800">{{ $stats['total_buildings'] }}</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-6">
            <p class="text-gray-500 text-sm">Total Ruangan</p>
            <p class="text-3xl font-bold mt-2 text-gray-800">{{ $stats['total_rooms'] }}</p>
        </div>
    </div>

    {{-- Charts dengan Ukuran yang Dibatasi --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Laporan per Kategori</h3>
            {{-- Wrapper untuk membatasi ukuran chart --}}
            <div class="relative h-64">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Laporan Bulanan Tahun Ini</h3>
            <div class="relative h-64">
                <canvas id="monthlyChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Aksi Cepat</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('petugas.reports.create') }}" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors border border-gray-200">
                <div class="bg-[#073763] rounded-lg p-3 mr-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                </div>
                <div>
                    <p class="font-medium text-gray-900">Tambah Laporan Temuan</p>
                </div>
            </a>
            <a href="{{ route('petugas.reports') }}" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors border border-gray-200">
                <div class="bg-[#741B47] rounded-lg p-3 mr-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <div>
                    <p class="font-medium text-gray-900">Validasi Laporan</p>
                    <p class="text-sm text-gray-500">{{ $stats['pending_reports'] }} item menunggu</p>
                </div>
            </a>
            <a href="{{ route('petugas.claims') }}" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors border border-gray-200">
                <div class="bg-[#C0C0C0] rounded-lg p-3 mr-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="font-medium text-gray-900">Validasi Klaim</p>
                    <p class="text-sm text-gray-500">{{ $stats['pending_claims'] }} item menunggu</p>
                </div>
            </a>
        </div>
    </div>
    
    {{-- Recent Activities (Opsional, bisa dihapus jika terlalu ramai) --}}
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Data untuk Chart Bulanan
    const monthlyLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    const monthlyDataFromServer = @json($monthlyReports->mapWithKeys(fn($item) => [$item->month => $item->total]));
    const monthlyData = monthlyLabels.map((label, index) => monthlyDataFromServer[index + 1] || 0);

    // Chart Bulanan (Bar Chart)
    const monthlyCtx = document.getElementById('monthlyChart');
    if (monthlyCtx) {
        new Chart(monthlyCtx, {
            type: 'bar',
            data: {
                labels: monthlyLabels,
                datasets: [{
                    label: 'Jumlah Laporan',
                    data: monthlyData,
                    backgroundColor: '#073763', // Prussian Blue
                    borderRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // Penting agar chart mengikuti tinggi wrapper
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }

    // Data untuk Chart Kategori
    const categoryData = @json($reportsByCategory->pluck('total'));
    const categoryLabels = @json($reportsByCategory->pluck('name'));
    
    // Chart Kategori (Doughnut Chart)
    const categoryCtx = document.getElementById('categoryChart');
    if (categoryCtx) {
        new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: categoryLabels,
                datasets: [{
                    data: categoryData,
                    backgroundColor: [
                        '#073763', // Prussian Blue
                        '#741B47', // Pompadour
                        '#C0C0C0', // Silver
                        '#60a5fa', // Varian Biru Muda
                        '#a14c71', // Varian Pompadour Muda
                        '#e5e7eb', // Varian Silver Muda
                    ],
                    borderColor: '#fff',
                    borderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // Penting agar chart mengikuti tinggi wrapper
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });
    }
});
</script>
@endpush