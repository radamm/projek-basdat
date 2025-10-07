@extends('layouts.app')

@section('title', 'Tambah Ruangan')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-2xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Tambah Ruangan Baru</h1>
                <p class="text-gray-600">Tambahkan ruangan baru ke sistem</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="btn-outline">
                Kembali
            </a>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <form method="POST" action="{{ route('admin.rooms.store') }}">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Room Name -->
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Ruangan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}"
                               placeholder="Contoh: R101, Lab Komputer, Aula, dll"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                               required>
                        @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Building -->
                    <div class="md:col-span-2">
                        <label for="building_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Gedung <span class="text-red-500">*</span>
                        </label>
                        <select id="building_id" 
                                name="building_id" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('building_id') border-red-500 @enderror"
                                required>
                            <option value="">Pilih Gedung</option>
                            @foreach($buildings as $building)
                            <option value="{{ $building->id }}" {{ old('building_id') == $building->id ? 'selected' : '' }}>
                                {{ $building->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('building_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                @if($buildings->isEmpty())
                <div class="mt-6 p-4 bg-yellow-50 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">Perhatian</h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <p>Belum ada gedung yang tersedia. Silakan <a href="{{ route('admin.buildings.create') }}" class="font-medium underline">tambah gedung</a> terlebih dahulu.</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <div class="flex justify-end space-x-4 mt-8">
                    <a href="{{ route('admin.rooms.index') }}" 
                       class="px-6 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md transition-colors">
                        Batal
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md transition-colors"
                            {{ $buildings->isEmpty() ? 'disabled' : '' }}>
                        Tambah Ruangan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
