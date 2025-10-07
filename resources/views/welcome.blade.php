@php
    $hideNavigation = true;
@endphp
@extends('layouts.app')

@section('content')
<div>
    <section class="min-h-screen h-screen bg-cover bg-center relative flex items-center justify-center text-white" style="background-image: url('/images/GKB.jpg');">
        <div class="absolute inset-0 bg-black bg-opacity-60"></div>
        <div class="z-10 text-center px-4">
            <h1 class="text-4xl md:text-6xl font-bold">FTMM Lost & Found</h1>
            <p class="mt-4 text-lg text-gray-100">Sistem FTMM untuk Laporan dan Pencarian Barang Hilang / Temuan</p>
            <a href="{{ route('login') }}"
               class="mt-6 inline-block bg-gradient-to-r from-blue-500 to-blue-700 text-white font-semibold py-3 px-8 rounded-lg shadow-lg transform hover:scale-105 transition">
                Login
            </a>
        </div>
        <div class="absolute bottom-10 animate-bounce z-10">
            <a href="#temuan" aria-label="Scroll to found items">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                </svg>
            </a>
        </div>
    </section>

    <section class="min-h-screen bg-white flex flex-col justify-center py-16">
        <div class="max-w-4xl mx-auto px-4 text-center mb-12">
            <h2 class="text-3xl font-bold text-blue-800 mb-4">Kenapa FTMM Lost & Found?</h2>
            <p class="text-gray-700 text-lg">
                Aplikasi ini memudahkan mahasiswa dan civitas FTMM untuk melaporkan kehilangan dan menemukan barang di lingkungan fakultas.
                Dengan verifikasi petugas dan sistem yang transparan, Anda bisa lebih tenang saat kehilangan barang.
            </p>
        </div>
        <div class="max-w-5xl mx-auto px-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
            <div class="bg-blue-50 rounded-lg p-6 shadow hover:shadow-md transition">
                <h3 class="text-xl font-semibold text-blue-700">Lapor Kehilangan</h3>
                <p class="text-gray-600 mt-2">Buat laporan barang hilang lengkap dengan detail dan foto.</p>
            </div>
            <div class="bg-blue-50 rounded-lg p-6 shadow hover:shadow-md transition">
                <h3 class="text-xl font-semibold text-blue-700">Lapor Penemuan</h3>
                <p class="text-gray-600 mt-2">Laporkan barang yang Anda temukan agar pemilik bisa mengklaimnya.</p>
            </div>
            <div class="bg-blue-50 rounded-lg p-6 shadow hover:shadow-md transition">
                <h3 class="text-xl font-semibold text-blue-700">Cari Barang Hilang</h3>
                <p class="text-gray-600 mt-2">Gunakan filter kategori & lokasi untuk menemukan barang hilang.</p>
            </div>
            <div class="bg-blue-50 rounded-lg p-6 shadow hover:shadow-md transition">
                <h3 class="text-xl font-semibold text-blue-700">Cek Status Laporan</h3>
                <p class="text-gray-600 mt-2">Ikuti progres laporan dari pending hingga disetujui.</p>
            </div>
            <div class="bg-blue-50 rounded-lg p-6 shadow hover:shadow-md transition">
                <h3 class="text-xl font-semibold text-blue-700">Klaim Barang</h3>
                <p class="text-gray-600 mt-2">Ajukan klaim jika Anda yakin barang adalah milik Anda.</p>
            </div>
            <div class="bg-blue-50 rounded-lg p-6 shadow hover:shadow-md transition">
                <h3 class="text-xl font-semibold text-blue-700">Validasi Petugas</h3>
                <p class="text-gray-600 mt-2">Semua laporan & klaim diverifikasi agar akurat dan terpercaya.</p>
            </div>
        </div>
    </section>

    <section class="min-h-screen bg-gray-100 flex items-center py-16">
        <div class="max-w-5xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-gray-900 text-center mb-10">Pertanyaan Umum (FAQ)</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                <div class="flex flex-col space-y-6">
                    <div class="bg-white rounded-xl shadow p-5 h-full flex flex-col">
                        <h3 class="text-lg font-semibold text-gray-800">Bagaimana cara membuat laporan?</h3>
                        <p class="text-gray-600 mt-2">Login, pilih menu “Lapor Kehilangan / Penemuan”, isi detail dan foto.</p>
                    </div>
                    <div class="bg-white rounded-xl shadow p-5 h-full flex flex-col">
                        <h3 class="text-lg font-semibold text-gray-800">Bagaimana cara mengklaim barang?</h3>
                        <p class="text-gray-600 mt-2">Pada detail barang, klik “Klaim Barang” setelah login dan isi deskripsi.</p>
                    </div>
                    <div class="bg-white rounded-xl shadow p-5 h-full flex flex-col">
                        <h3 class="text-lg font-semibold text-gray-800">Siapa yang memverifikasi?</h3>
                        <p class="text-gray-600 mt-2">Petugas Fakultas akan memvalidasi setiap laporan & klaim.</p>
                    </div>
                </div>
                <div class="flex flex-col space-y-6">
                    <div class="bg-white rounded-xl shadow p-5 h-full flex flex-col">
                        <h3 class="text-lg font-semibold text-gray-800">Berapa lama proses verifikasi?</h3>
                        <p class="text-gray-600 mt-2">Biasanya dalam 1–3 hari kerja tergantung volume laporan.</p>
                    </div>
                    <div class="bg-white rounded-xl shadow p-5 h-full flex flex-col">
                        <h3 class="text-lg font-semibold text-gray-800">Apa yang harus saya bawa ketika ambil barang?</h3>
                        <p class="text-gray-600 mt-2">Siapkan identitas (KTM/KTP) dan bukti klaim untuk verifikasi petugas.</p>
                    </div>
                    <div class="bg-white rounded-xl shadow p-5 h-full flex flex-col">
                        <h3 class="text-lg font-semibold text-gray-800">Apa terjadi konflik klaim ganda?</h3>
                        <p class="text-gray-600 mt-2">Jika ada klaim ganda, petugas akan memverifikasi dan memutuskan satu yang sah.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

@include('reports._public_reports_list')

</div>
@endsection