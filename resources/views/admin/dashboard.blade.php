@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('header', 'Dashboard Admin')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <!-- Card Karyawan -->
    <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Total Karyawan</p>
                <h3 class="text-3xl font-bold text-blue-600 mt-2">{{ $totalKaryawan }}</h3>
            </div>
            <div class="bg-blue-100 rounded-full p-4">
                <i class="fas fa-users text-blue-600 text-3xl"></i>
            </div>
        </div>
        <a href="{{ route('admin.karyawan.index') }}" class="text-blue-600 text-sm mt-4 inline-block hover:underline font-medium">
            Lihat Detail →
        </a>
    </div>

    <!-- Card Dosen -->
    <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Total Dosen</p>
                <h3 class="text-3xl font-bold text-green-600 mt-2">{{ $totalDosen }}</h3>
            </div>
            <div class="bg-green-100 rounded-full p-4">
                <i class="fas fa-chalkboard-teacher text-green-600 text-3xl"></i>
            </div>
        </div>
        <a href="{{ route('admin.dosen.index') }}" class="text-green-600 text-sm mt-4 inline-block hover:underline font-medium">
            Lihat Detail →
        </a>
    </div>

    <!-- Card Teknisi -->
    <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Total Teknisi</p>
                <h3 class="text-3xl font-bold text-purple-600 mt-2">{{ $totalTeknisi }}</h3>
            </div>
            <div class="bg-purple-100 rounded-full p-4">
                <i class="fas fa-tools text-purple-600 text-3xl"></i>
            </div>
        </div>
        <a href="{{ route('admin.teknisi.index') }}" class="text-purple-600 text-sm mt-4 inline-block hover:underline font-medium">
            Lihat Detail →
        </a>
    </div>

    <!-- Card User -->
    <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Total User</p>
                <h3 class="text-3xl font-bold text-orange-600 mt-2">{{ $totalUser }}</h3>
            </div>
            <div class="bg-orange-100 rounded-full p-4">
                <i class="fas fa-user-cog text-orange-600 text-3xl"></i>
            </div>
        </div>
        <a href="{{ route('admin.users.index') }}" class="text-orange-600 text-sm mt-4 inline-block hover:underline font-medium">
            Lihat Detail →
        </a>
    </div>
</div>

<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex items-start">
        <div class="bg-blue-100 rounded-full p-3 mr-4">
            <i class="fas fa-info-circle text-blue-600 text-2xl"></i>
        </div>
        <div>
            <h3 class="text-xl font-semibold mb-2 text-gray-800">Selamat Datang, {{ auth()->user()->name }}!</h3>
            <p class="text-gray-600 leading-relaxed">
                Anda login sebagai <span class="font-semibold text-blue-600">Administrator</span> pada Sistem Informasi Manajemen Pegawai Politeknik Negeri Lampung.
                Anda memiliki hak akses penuh untuk mengelola semua data pegawai termasuk Karyawan, Dosen, Teknisi, dan User.
            </p>
        </div>
    </div>
</div>
@endsection