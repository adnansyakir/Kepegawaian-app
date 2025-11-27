~@extends('admin.layouts.app')

@section('title', 'Detail Teknisi')
@section('header', 'Detail Teknisi')

@section('content')
<div class="bg-white rounded-lg shadow-md">
    <div class="p-6 border-b border-gray-200">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-800">Detail Teknisi | 
                <span class="text-purple-600">
                    @php
                        $fields = [
                            $teknisi->foto, $teknisi->nama, $teknisi->nip, $teknisi->jenis_kelamin,
                            $teknisi->tempat_lahir, $teknisi->tanggal_lahir, $teknisi->unit_kerja,
                            $teknisi->tmt_cpns_ppk, $teknisi->status_kepegawaian,
                            $teknisi->pendidikan_terakhir, $teknisi->tahun_lulus, $teknisi->pangkat,
                            $teknisi->gol, $teknisi->tmt_gol, $teknisi->kelas_jabatan, $teknisi->tahun_purna_tugas
                        ];
                        $filledCount = count(array_filter($fields, fn($f) => !empty($f)));
                        $totalCount = count($fields);
                        $percentage = round(($filledCount / $totalCount) * 100);
                    @endphp
                    {{ $percentage }}% Lengkap
                </span>
            </h3>
            <div class="space-x-2">
            <a href="{{ route('admin.teknisi.edit', $teknisi) }}" 
               class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 transition">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
            <a href="{{ route('admin.teknisi.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
            </div>
        </div>
        <!-- Progress Bar -->
        <div class="w-full bg-gray-200 rounded-full h-2.5">
            <div class="bg-purple-600 h-2.5 rounded-full transition-all duration-300" style="width: {{ $percentage }}%"></div>
        </div>
    </div>

    <div class="p-6">
        <!-- Card Data Pribadi -->
        <div class="bg-gray-50 rounded-lg p-6 mb-6 border border-gray-200">
            <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-user mr-2 text-purple-600"></i>Data Pribadi
            </h4>
            
            <div class="flex gap-6">
                <!-- Foto Profile - Left Side -->
                <div class="flex-shrink-0">
                    @if($teknisi->foto)
                        <img src="{{ asset('storage/' . $teknisi->foto) }}" alt="Foto {{ $teknisi->nama }}" 
                            class="w-48 h-48 object-cover rounded-lg shadow-md">
                    @else
                        <div class="w-48 h-48 bg-gray-200 rounded-lg shadow-md flex items-center justify-center">
                            <i class="fas fa-user text-6xl text-gray-400"></i>
                        </div>
                    @endif
                </div>
                
                <!-- Data Fields - Right Side -->
                <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-600 mb-1">Nama</label>
                        <p class="text-base text-gray-900 font-semibold">{{ $teknisi->nama }}</p>
                    </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">NIP</label>
                    <p class="text-base text-gray-900">{{ $teknisi->nip ?? '-' }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Jenis Kelamin</label>
                    <span class="inline-block px-3 py-1 text-sm rounded-full {{ $teknisi->jenis_kelamin == 'Laki-laki' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                        {{ $teknisi->jenis_kelamin }}
                    </span>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Tempat Lahir</label>
                    <p class="text-base text-gray-900">{{ $teknisi->tempat_lahir ?? '-' }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Tanggal Lahir</label>
                    <p class="text-base text-gray-900">
                        {{ $teknisi->tanggal_lahir ? $teknisi->tanggal_lahir->locale('id')->isoFormat('DD MMMM YYYY') : '-' }}
                    </p>
                </div>
                </div>
            </div>
        </div>

        <!-- Card Data Pekerjaan -->
        <div class="bg-gray-50 rounded-lg p-6 mb-6 border border-gray-200">
            <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-briefcase mr-2 text-green-600"></i>Data Pekerjaan
            </h4>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Unit Kerja</label>
                    <p class="text-base text-gray-900">{{ $teknisi->unit_kerja ?? '-' }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">TMT CPNS/PPK</label>
                    <p class="text-base text-gray-900">{{ $teknisi->tmt_cpns_ppk ? \Carbon\Carbon::parse($teknisi->tmt_cpns_ppk)->locale('id')->isoFormat('DD MMMM YYYY') : '-' }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Status Kepegawaian</label>
                    <span class="inline-block px-3 py-1 text-sm rounded-full bg-green-100 text-green-800">
                        {{ $teknisi->status_kepegawaian ?? '-' }}
                    </span>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Pendidikan Terakhir</label>
                    <p class="text-base text-gray-900">{{ $teknisi->pendidikan_terakhir ?? '-' }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Tahun Lulus</label>
                    <p class="text-base text-gray-900">{{ $teknisi->tahun_lulus ?? '-' }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Pangkat</label>
                    <p class="text-base text-gray-900">{{ $teknisi->pangkat ?? '-' }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Golongan</label>
                    <p class="text-base text-gray-900">{{ $teknisi->gol ?? '-' }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">TMT Golongan</label>
                    <p class="text-base text-gray-900">{{ $teknisi->tmt_gol ? \Carbon\Carbon::parse($teknisi->tmt_gol)->locale('id')->isoFormat('DD MMMM YYYY') : '-' }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Kelas Jabatan</label>
                    <p class="text-base text-gray-900">{{ $teknisi->kelas_jabatan ?? '-' }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Tanggal Purna Tugas</label>
                    <p class="text-base text-gray-900">{{ $teknisi->tahun_purna_tugas ? \Carbon\Carbon::parse($teknisi->tahun_purna_tugas)->locale('id')->isoFormat('DD MMMM YYYY') : '-' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
