@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        {{-- Header dengan Warna Baru --}}
        <div class="bg-gradient-to-r from-[#073763] to-[#04223b] px-6 py-8">
            <h1 class="text-2xl font-bold text-white">Profil Saya</h1>
            <p class="text-gray-200 mt-2">Kelola informasi profil dan keamanan akun Anda</p>
        </div>

        <div class="p-6 space-y-8">
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <p class="text-green-700">{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Profil</h2>
                
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="form-input">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="nim" class="block text-sm font-medium text-gray-700 mb-2">NIM</label>
                            <input type="text" value="{{ $user->nim }}" class="form-input bg-gray-200 cursor-not-allowed" readonly>
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" value="{{ $user->email }}" class="form-input bg-gray-200 cursor-not-allowed" readonly>
                        </div>
                        
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">No. Telepon</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" class="form-input" placeholder="Belum diisi">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="btn-primary">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Ubah Password</h2>
                
                <form method="POST" action="{{ route('profile.password') }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-4">
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Password Lama</label>
                            <input type="password" name="current_password" id="current_password" class="form-input">
                            @error('current_password', 'updatePassword')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                            <input type="password" name="password" id="password" class="form-input">
                             @error('password', 'updatePassword')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-input">
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="btn-primary">
                            Ubah Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection