@php
    $hideNavigation = true;
@endphp
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-yellow-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div class="text-center">
            <div class="mx-auto h-20 w-20 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mb-6 shadow-lg">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Lost & Found</h2>
            <p class="text-gray-600">Sistem Pelaporan Barang Hilang & Temuan</p>
        </div>

        <div class="bg-white rounded-2xl shadow-xl p-8">
            <div class="mb-6">
                <div class="flex bg-gray-100 rounded-xl p-1">
                    <button type="button" id="userTab" class="flex-1 py-2 px-4 rounded-lg text-sm font-medium transition-all duration-200 bg-blue-500 text-white">
                        Pengguna
                    </button>
                    <button type="button" id="adminTab" class="flex-1 py-2 px-4 rounded-lg text-sm font-medium transition-all duration-200 text-gray-600 hover:text-gray-900">
                        Admin / Petugas
                    </button>
                </div>
            </div>

            <form method="POST" action="{{ route('login.attempt') }}" class="space-y-6">
                @csrf
                <input type="hidden" name="login_type" id="loginType" value="pengguna">

                <div id="userForm">
                    <div>
                        <label for="nomor_induk" class="block text-sm font-medium text-gray-700 mb-2">Nomor Induk (NIM/NIDN)</label>
                        <input id="nomor_induk" name="nomor_induk" type="text" class="form-input" placeholder="Masukkan Nomor Induk Anda" value="{{ old('nomor_induk') }}">
                        @error('nomor_induk')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

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

                @error('login')
                    <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    </div>
                @enderror

                <button type="submit" class="btn-primary w-full">
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

    userTab.addEventListener('click', function() {
        // Tampilkan form user, sembunyikan form admin
        userTab.classList.add('bg-blue-500', 'text-white');
        adminTab.classList.remove('bg-blue-500', 'text-white');
        
        userForm.classList.remove('hidden');
        adminForm.classList.add('hidden');
        
        // Aktifkan input user, nonaktifkan dan KOSONGKAN input admin
        userInput.disabled = false;
        adminInput.disabled = true;
        adminInput.value = ''; // <-- TAMBAHAN PENTING

        loginType.value = 'pengguna';
    });

    adminTab.addEventListener('click', function() {
        // Tampilkan form admin, sembunyikan form user
        adminTab.classList.add('bg-blue-500', 'text-white');
        userTab.classList.remove('bg-blue-500', 'text-white');
        
        adminForm.classList.remove('hidden');
        userForm.classList.add('hidden');

        // Aktifkan input admin, nonaktifkan dan KOSONGKAN input user
        adminInput.disabled = false;
        userInput.disabled = true;
        userInput.value = ''; // <-- TAMBAHAN PENTING
        
        loginType.value = 'staf';
    });

    // Inisialisasi: nonaktifkan dan kosongkan input admin di awal
    adminInput.disabled = true;
    adminInput.value = '';
});
</script>
@endsection