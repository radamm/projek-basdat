@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-8">
            <h1 class="text-2xl font-bold text-white">Detail Klaim</h1>
            <p class="text-blue-100 mt-2">Informasi lengkap klaim barang Anda</p>
        </div>

        <div class="p-6">
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                    <p class="text-green-700">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Claim Status -->
            <div class="mb-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900">Status Klaim</h2>
                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full 
                        @switch($claim->status)
                            @case('pending') bg-yellow-100 text-yellow-800 @break
                            @case('approved') bg-green-100 text-green-800 @break
                            @case('rejected') bg-red-100 text-red-800 @break
                        @endswitch">
                        @switch($claim->status)
                            @case('pending') Menunggu Validasi @break
                            @case('approved') Disetujui @break
                            @case('rejected') Ditolak @break
                        @endswitch
                    </span>
                </div>
                
                @if($claim->status === 'approved')
                    <div class="mt-4 bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex">
                            <svg class="w-5 h-5 text-green-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <h3 class="text-sm font-medium text-green-800">Klaim Disetujui!</h3>
                                <p class="text-sm text-green-700 mt-1">
                                    Silakan ambil barang Anda di <strong>Kantor Kemahasiswaan</strong> pada jam kerja (08:00 - 16:00).
                                    Jangan lupa bawa identitas diri (KTM/KTP) sebagai bukti.
                                </p>
                            </div>
                        </div>
                    </div>
                @elseif($claim->status === 'rejected')
                    <div class="mt-4 bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex">
                            <svg class="w-5 h-5 text-red-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <h3 class="text-sm font-medium text-red-800">Klaim Ditolak</h3>
                                <p class="text-sm text-red-700 mt-1">
                                    Klaim Anda tidak dapat disetujui. Silakan hubungi petugas untuk informasi lebih lanjut.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Item Information -->
            <div class="bg-gray-50 rounded-xl p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Barang</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        @if($claim->report->photo)
                            <img src="{{ Storage::url($claim->report->photo) }}" alt="{{ $claim->report->item_name }}" 
                                 class="w-full h-48 object-cover rounded-lg">
                        @else
                            <div class="w-full h-48 bg-gray-200 rounded-lg flex items-center justify-center">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama Barang</label>
                            <p class="text-gray-900">{{ $claim->report->item_name }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kategori</label>
                            <p class="text-gray-900">{{ $claim->report->category->name }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Lokasi Ditemukan</label>
                            <p class="text-gray-900">{{ $claim->report->room->name }}, {{ $claim->report->room->building->name }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Ditemukan</label>
                            <p class="text-gray-900">{{ $claim->report->event_date->format('d/m/Y') }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Pelapor</label>
                            <p class="text-gray-900">{{ $claim->report->user->name }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Barang</label>
                    <p class="text-gray-900">{{ $claim->report->description }}</p>
                </div>
            </div>

            <!-- Claim Information -->
            <div class="bg-gray-50 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Klaim</h2>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bukti Kepemilikan</label>
                        <p class="text-gray-900">{{ $claim->description }}</p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Klaim</label>
                            <p class="text-gray-900">{{ $claim->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        
                        @if($claim->validator)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Divalidasi Oleh</label>
                                <p class="text-gray-900">{{ $claim->validator->name }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t flex justify-end">
                @auth
                    @if(in_array(Auth::user()->role, ['petugas', 'admin']))
                        {{-- Jika petugas/admin, kembali ke daftar validasi klaim --}}
                        <a href="{{ route('petugas.claims') }}" class="btn-outline">Kembali</a>
                    @else
                        {{-- Jika pengguna biasa, kembali ke daftar klaim saya --}}
                        <a href="{{ route('claims.index') }}" class="btn-outline">Kembali</a>
                    @endif
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection
