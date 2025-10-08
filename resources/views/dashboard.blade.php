@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
        <p class="text-gray-600 mt-2">Selamat datang kembali, {{ auth()->user()->name }}!</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Total Laporan --}}
        <div class="bg-[#073763] rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm opacity-80">Total Laporan</p>
                    <p class="text-3xl font-bold">{{ $stats['total_reports'] }}</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-lg p-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
            </div>
        </div>

        {{-- Laporan Pending --}}
        <div class="bg-[#741B47] rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-pink-100 text-sm opacity-80">Laporan Pending</p>
                    <p class="text-3xl font-bold">{{ $stats['pending_reports'] }}</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-lg p-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>

        {{-- Laporan Disetujui --}}
        <div class="bg-white border border-gray-200 rounded-xl p-6 text-gray-800">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Laporan Disetujui</p>
                    <p class="text-3xl font-bold">{{ $stats['approved_reports'] }}</p>
                </div>
                <div class="bg-gray-100 rounded-lg p-3">
                    <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>

        {{-- Total Klaim --}}
        <div class="bg-white border border-gray-200 rounded-xl p-6 text-gray-800">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Klaim</p>
                    <p class="text-3xl font-bold">{{ $stats['total_claims'] }}</p>
                </div>
                <div class="bg-gray-100 rounded-lg p-3">
                    <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-900">Laporan Terbaru Anda</h2>
                <a href="{{ route('reports.index') }}" class="text-[#073763] hover:underline text-sm font-semibold">
                    Lihat Semua →
                </a>
            </div>
            
            <div class="space-y-4">
                @forelse($recentReports as $report)
                    <div class="flex items-center space-x-4 p-3 hover:bg-gray-50 rounded-lg transition-colors">
                        <div class="flex-shrink-0">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                {{ $report->type === 'lost' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                {{ $report->type === 'lost' ? 'Hilang' : 'Temuan' }}
                            </span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $report->item_name }}</p>
                            <p class="text-sm text-gray-500">{{ $report->created_at->diffForHumans() }}</p>
                        </div>
                        <div>
                            <a href="{{ route('reports.show', $report) }}" class="text-sm text-[#073763] hover:underline font-semibold">Detail</a>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4">
                        <p class="text-sm text-gray-500">Anda belum membuat laporan apapun.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-900">Klaim Terbaru Anda</h2>
                <a href="{{ route('claims.index') }}" class="text-[#073763] hover:underline text-sm font-semibold">
                    Lihat Semua →
                </a>
            </div>
            
            <div class="space-y-4">
                @forelse($recentClaims as $claim)
                    <div class="flex items-center space-x-4 p-3 hover:bg-gray-50 rounded-lg transition-colors">
                        <div class="flex-shrink-0">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                @switch($claim->status)
                                    @case('pending') bg-yellow-100 text-yellow-800 @break
                                    @case('approved') bg-green-100 text-green-800 @break
                                    @case('rejected') bg-red-100 text-red-800 @break
                                @endswitch">
                                {{ ucfirst($claim->status) }}
                            </span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $claim->report->item_name }}</p>
                            <p class="text-sm text-gray-500">Klaim dibuat {{ $claim->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4">
                        <p class="text-sm text-gray-500">Anda belum membuat klaim apapun.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="mt-8 bg-white rounded-xl shadow-sm p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Aksi Cepat</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('reports.create') }}" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors border border-gray-200">
                <div class="bg-[#073763] rounded-lg p-2 mr-4">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                </div>
                <div>
                    <p class="font-medium text-gray-900">Buat Laporan</p>
                    <p class="text-sm text-gray-500">Laporkan barang hilang/temuan</p>
                </div>
            </a>

            <a href="{{ route('reports.public_index') }}" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors border border-gray-200">
                <div class="bg-[#073763] rounded-lg p-2 mr-4">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <div>
                    <p class="font-medium text-gray-900">Cari Barang</p>
                    <p class="text-sm text-gray-500">Lihat daftar barang temuan</p>
                </div>
            </a>

            <a href="{{ route('profile.show') }}" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors border border-gray-200">
                <div class="bg-[#741B47] rounded-lg p-2 mr-4">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
                <div>
                    <p class="font-medium text-gray-900">Profil Saya</p>
                    <p class="text-sm text-gray-500">Kelola akun dan ganti password</p>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection