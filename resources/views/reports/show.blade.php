@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-8">
            <h1 class="text-2xl font-bold text-white">Detail Laporan</h1>
            <p class="text-blue-100 mt-2">{{ $report->item_name }}</p>
        </div>

        <div class="p-6">
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                    <p class="text-green-700">{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    <p class="text-red-700">{{ session('error') }}</p>
                </div>
            @endif

            <!-- Report Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <div>
                    @if($report->photo)
                        <a href="{{ Storage::url($report->photo) }}" target="_blank" title="Lihat gambar penuh">
                            <img src="{{ Storage::url($report->photo) }}" alt="{{ $report->item_name }}" 
                                 class="w-full h-auto object-contain rounded-lg border cursor-pointer">
                        </a>
                    @else
                        <div class="w-full h-64 bg-gray-200 rounded-lg flex items-center justify-center">
                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    @endif
                </div>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Barang</label>
                        <p class="text-xl font-semibold text-gray-900">{{ $report->item_name }}</p>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full 
                            {{ $report->type === 'lost' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                            {{ $report->type === 'lost' ? 'Barang Hilang' : 'Barang Temuan' }}
                        </span>
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full 
                            @switch($report->status)
                                @case('pending') bg-yellow-100 text-yellow-800 @break
                                @case('approved') bg-green-100 text-green-800 @break
                                @case('rejected') bg-red-100 text-red-800 @break
                                @case('returned') bg-blue-100 text-blue-800 @break
                            @endswitch">
                            @switch($report->status)
                                @case('pending') Pending @break
                                @case('approved') Disetujui @break
                                @case('rejected') Ditolak @break
                                @case('returned') Dikembalikan @break
                            @endswitch
                        </span>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Kategori</label>
                        <p class="text-gray-900">{{ $report->category->name }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Lokasi</label>
                        <p class="text-gray-900">{{ $report->room->name }}, {{ $report->room->building->name }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal Kejadian</label>
                        <p class="text-gray-900">{{ $report->event_date->format('d/m/Y') }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Pelapor</label>
                        <p class="text-gray-900">{{ $report->user->name }}</p>
                    </div>
                </div>
            </div>
            
            <div class="mb-8">
                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                <p class="text-gray-900">{{ $report->description }}</p>
            </div>

            <!-- Claims List for Report Owner -->
            @if(auth()->id() === $report->user_id && $report->claims->count() > 0)
                <div class="bg-gray-50 rounded-xl p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Klaim untuk Barang Ini</h3>
                    
                    <div class="space-y-4">
                        @foreach($report->claims as $claim)
                            <div class="bg-white rounded-lg p-4 border">
                                <div class="flex items-center justify-between mb-2">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $claim->user->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $claim->user->nim }}</p>
                                    </div>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                        @switch($claim->status)
                                            @case('pending') bg-yellow-100 text-yellow-800 @break
                                            @case('approved') bg-green-100 text-green-800 @break
                                            @case('rejected') bg-red-100 text-red-800 @break
                                        @endswitch">
                                        @switch($claim->status)
                                            @case('pending') Pending @break
                                            @case('approved') Disetujui @break
                                            @case('rejected') Ditolak @break
                                        @endswitch
                                    </span>
                                </div>
                                <p class="text-sm text-gray-700">{{ Str::limit($claim->description, 100) }}</p>
                                <p class="text-xs text-gray-500 mt-2">{{ $claim->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="flex justify-end space-x-4 mt-8 pt-6 border-t">
                {{-- PERBAIKAN TOMBOL "KEMBALI" --}}
                <a href="{{ url()->previous() }}" class="btn-outline">Kembali</a>
                @if(auth()->id() === $report->user_id && $report->status === 'pending')
                    <a href="{{ route('reports.edit', $report) }}" class="btn-secondary-sm">Edit Laporan</a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
