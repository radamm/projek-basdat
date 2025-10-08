@php
    // Variabel ini akan memberitahu layout untuk tidak menampilkan navigasi di halaman ini
    $hideNavigation = true;
@endphp

@extends('layouts.app')

@section('title', 'Login')

@section('content')
{{-- Latar Belakang Utama dengan Gambar dan Overlay --}}
<div class="min-h-screen bg-cover bg-center flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8" style="background-image: url('/images/GKB.jpg');">
    <div class="absolute inset-0 bg-gradient-to-br from-[#073763]/80 via-[#073763]/90 to-[#741B47]/80 backdrop-blur-sm"></div>

    <div class="max-w-md w-full space-y-8 z-10">
        <div class="text-center">
            <div class="mx-auto h-20 w-20 bg-white/20 rounded-2xl flex items-center justify-center mb-6 shadow-lg backdrop-blur-md">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <h2 class="text-3xl font-bold text-white mb-2 drop-shadow-md">Lost & Found</h2>
            <p class="text-gray-200">Sistem Pelaporan Barang Hilang & Temuan</p>
        </div>

        <div class="bg-white/90 backdrop-blur-lg rounded-2xl shadow-xl p-8">
            <div class="mb-6">
                <div class="flex bg-gray-100 rounded-xl p-1">
                    <button type="button" id="userTab" class="flex-1 py-2 px-4 rounded-lg text-sm font-medium transition-all duration-300 bg-[#073763] text-white shadow">
                        Pengguna
                    </button>
                    <button type="button" id="adminTab" class="flex-1 py-2 px-4 rounded-lg text-sm font-medium transition-all duration-300 text-gray-600 hover:text-gray-900">
                        Admin / Petugas
                    </button>
                </div>
            </div>

            <form method="POST" action="{{ route('login.attempt') }}" class="space-y-6">
                @csrf
                <input type="hidden" name="login_type" id="loginType" value="pengguna">

                {{-- Form untuk Pengguna --}}
                <div id="userForm">
                    <div>
                        <label for="nomor_induk" class="block text-sm font-medium text-gray-700 mb-2">Nomor Induk (NIM)</label>
                        <input id="nomor_induk" name="nomor_induk" type="text" class="form-input" placeholder="Masukkan Nomor Induk Anda" value="{{ old('nomor_induk') }}">
                        @error('nomor_induk')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Form untuk Admin/Petugas --}}
                <div id="adminForm" class="hidden">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input id="email" name="email" type="email" class="form-input" placeholder="Masukkan email Anda" value="{{ old('email') }}">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input id="password" name="password" type="password" class="form-input" placeholder="Masukkan password">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                @if($errors->has('login'))
                    <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                        <p class="text-sm text-red-600">{{ $errors->first('login') }}</p>
                    </div>
                @endif

                <button type="submit" class="btn-primary w-full bg-[#073763] hover:bg-opacity-90">
                    Masuk
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const userTab = document.getElementById('userTab');
    const adminTab = document.getElementById('adminTab');
    const userForm = document.getElementById('userForm');
    const adminForm = document.getElementById('adminForm');
    const loginType = document.getElementById('loginType');
    const userInput = document.getElementById('nomor_induk');
    const adminInput = document.getElementById('email');

    function switchTab(activeTab, inactiveTab, activeForm, inactiveForm, type) {
        activeTab.classList.add('bg-[#073763]', 'text-white', 'shadow');
        inactiveTab.classList.remove('bg-[#073763]', 'text-white', 'shadow');
        
        activeForm.classList.remove('hidden');
        inactiveForm.classList.add('hidden');
        
        // Nonaktifkan input yang tidak aktif dan aktifkan yang aktif
        if (type === 'pengguna') {
            userInput.disabled = false;
            adminInput.disabled = true;
            adminInput.value = '';
        } else {
            userInput.disabled = true;
            adminInput.disabled = false;
            userInput.value = '';
        }
        
        loginType.value = type;
    }

    userTab.addEventListener('click', () => switchTab(userTab, adminTab, userForm, adminForm, 'pengguna'));
    adminTab.addEventListener('click', () => switchTab(adminTab, userTab, adminForm, userForm, 'staf'));

    // Inisialisasi awal
    adminInput.disabled = true;
    adminInput.value = '';
});
</script>
@endsection