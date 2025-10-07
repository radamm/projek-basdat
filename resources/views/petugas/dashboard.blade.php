@extends('layouts.app')

@section('title', 'Dashboard Petugas')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Dashboard Petugas</h1>
        <p class="text-gray-600">Kelola validasi laporan dan klaim barang hilang & temuan</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Laporan Pending</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['pending_reports'] }}</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-full">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Klaim Pending</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['pending_claims'] }}</p>
                </div>
                <div class="p-3 bg-yellow-100 rounded-full">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Total Kategori</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_categories'] }}</p>
                </div>
                <div class="p-3 bg-green-100 rounded-full">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.99 1.99 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Total Gedung</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_buildings'] }}</p>
                </div>
                <div class="p-3 bg-purple-100 rounded-full">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-indigo-500">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Total Ruangan</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_rooms'] }}</p>
                </div>
                <div class="p-3 bg-indigo-100 rounded-full">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Laporan per Kategori</h3>
            <canvas id="categoryChart" width="400" height="200"></canvas>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Laporan Bulanan</h3>
            <canvas id="monthlyChart" width="400" height="200"></canvas>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <a href="{{ route('petugas.reports.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white rounded-lg p-6 text-center transition-colors">
            <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            <h4 class="font-semibold">Tambah Laporan</h4>
            <p class="text-sm opacity-90">Langsung upload barang temuan</p>
        </a>

        <a href="{{ route('petugas.reports') }}" class="bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg p-6 text-center transition-colors">
            <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            <h4 class="font-semibold">Validasi Laporan</h4>
            <p class="text-sm opacity-90">{{ $stats['pending_reports'] }} pending</p>
        </a>

        <a href="{{ route('petugas.claims') }}" class="bg-green-600 hover:bg-green-700 text-white rounded-lg p-6 text-center transition-colors">
            <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <h4 class="font-semibold">Validasi Klaim</h4>
            <p class="text-sm opacity-90">{{ $stats['pending_claims'] }} pending</p>
        </a>
        {{--
        <a href="{{ route('admin.categories.index') }}" class="bg-purple-600 hover:bg-purple-700 text-white rounded-lg p-6 text-center transition-colors">
            <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            <h4 class="font-semibold">Kelola Data</h4>
            <p class="text-sm opacity-90">Kategori, Gedung, Ruangan</p>
        </a>
        --}}
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Laporan Terbaru (Pending)</h3>
            <div class="space-y-4">
                @forelse($recentReports as $report)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex-1">
                        <h4 class="font-medium text-gray-900">{{ $report->item_name }}</h4>
                        <p class="text-sm text-gray-600">{{ $report->category->name }} - {{ $report->room->building->name }}</p>
                        <p class="text-xs text-gray-500">{{ $report->created_at->diffForHumans() }}</p>
                    </div>
                    <a href="{{ route('petugas.reports') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Validasi</a>
                </div>
                @empty
                <p class="text-gray-500 text-center py-4">Tidak ada laporan pending</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Klaim Terbaru (Pending)</h3>
            <div class="space-y-4">
                @forelse($recentClaims as $claim)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex-1">
                        <h4 class="font-medium text-gray-900">{{ $claim->report->item_name }}</h4>
                        <p class="text-sm text-gray-600">Oleh: {{ $claim->user->name }}</p>
                        <p class="text-xs text-gray-500">{{ $claim->created_at->diffForHumans() }}</p>
                    </div>
                    <a href="{{ route('petugas.claims') }}" class="text-green-600 hover:text-green-800 text-sm font-medium">Validasi</a>
                </div>
                @empty
                <p class="text-gray-500 text-center py-4">Tidak ada klaim pending</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

{{-- == BAGIAN SCRIPT YANG DIPERBARUI == --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Monthly Chart Data Preparation
const monthlyLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
const monthlyDataFromServer = @json($monthlyReports->mapWithKeys(function ($item) { return [$item->month => $item->total]; }));
const monthlyData = monthlyLabels.map((label, index) => monthlyDataFromServer[index + 1] || 0);

// Monthly Chart
const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
new Chart(monthlyCtx, {
    type: 'bar',
    data: {
        labels: monthlyLabels,
        datasets: [{
            label: 'Jumlah Laporan',
            data: monthlyData,
            backgroundColor: '#3B82F6'
        }]
    }
});

// Category Chart
const categoryCtx = document.getElementById('categoryChart').getContext('2d');
new Chart(categoryCtx, {
    type: 'doughnut',
    data: {
        labels: @json($reportsByCategory->pluck('name')),
        datasets: [{
            data: @json($reportsByCategory->pluck('total')),
            backgroundColor: ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#EC4899', '#6B7280']
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
            }
        }
    }
});
</script>
@endpush