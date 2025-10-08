@extends('layouts.app')

@section('title', 'Detail Laporan')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        {{-- Header dengan Warna Baru --}}
        <div class="bg-gradient-to-r from-[#073763] to-[#04223b] px-6 py-8">
            <h1 class="text-2xl font-bold text-white">{{ $report->item_name }}</h1>
            <div class="flex items-center space-x-4 mt-2">
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
                    {{ ucfirst($report->status) }}
                </span>
            </div>
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

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <div>
                    @if($report->photo)
                        <a href="{{ Storage::url($report->photo) }}" target="_blank" title="Lihat gambar penuh">
                            <img src="{{ Storage::url($report->photo) }}" alt="{{ $report->item_name }}" class="w-full h-auto object-cover rounded-lg border cursor-pointer hover:opacity-90 transition-opacity">
                        </a>
                    @else
                        <div class="w-full h-64 bg-gray-100 rounded-lg flex items-center justify-center border">
                            <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                    @endif
                </div>
                
                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Kategori</label>
                        <p class="text-lg text-gray-900">{{ $report->category->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Lokasi</label>
                        <p class="text-lg text-gray-900">{{ $report->room->name }}, {{ $report->room->building->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Tanggal Kejadian</label>
                        <p class="text-lg text-gray-900">{{ $report->event_date->format('d M Y') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Pelapor</label>
                        <p class="text-lg text-gray-900">{{ $report->user->name }}</p>
                    </div>
                </div>
            </div>
            
            <div class="mb-8 border-t pt-6">
                <label class="block text-sm font-medium text-gray-500 mb-2">Deskripsi Lengkap</label>
                <p class="text-gray-800 leading-relaxed">{{ $report->description }}</p>
            </div>

            @if(auth()->id() === $report->user_id && $report->claims->count() > 0)
                <div class="bg-gray-50 rounded-xl p-6 mb-6 border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Daftar Klaim untuk Barang Ini</h3>
                    <div class="space-y-4">
                        @foreach($report->claims as $claim)
                            <div class="bg-white rounded-lg p-4 border">
                                <div class="flex items-center justify-between mb-2">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $claim->user->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $claim->user->nim }}</p>
                                    </div>
                                    {{-- Status Klaim --}}
                                </div>
                                <p class="text-sm text-gray-700 italic">"{{ Str::limit($claim->description, 100) }}"</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="flex justify-between items-center mt-8 pt-6 border-t">
                {{-- Tombol Kembali yang Dinamis --}}
                <a href="{{ url()->previous() }}" class="btn-primary">Kembali</a>

                <div class="flex space-x-4">
                    {{-- Tombol Klaim (jika barang temuan dan bukan milik sendiri) --}}
                    @if($report->type === 'found' && $report->status === 'approved' && auth()->id() !== $report->user_id)
                        <button type="button" class="btn-primary" onclick="showClaimModal()">Klaim Barang Ini</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL UNTUK KLAIM (JIKA DIPERLUKAN) --}}
{{-- Anda bisa menambahkan HTML & JS untuk modal klaim di sini jika belum ada --}}
@endsection