@extends('admin.layouts.app')

@section('title', 'Detail Dosen')
@section('header', 'Detail Dosen')

@section('content')
<div class="bg-white rounded-lg shadow-md">
    <div class="p-6 border-b border-gray-200">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-800">Detail Dosen | 
                <span class="text-green-600">
                    @php
                        $fields = [
                            $dosen->foto, $dosen->nama_dosen, $dosen->nip, $dosen->jenis_kelamin,
                            $dosen->tempat_lahir, $dosen->tanggal_lahir, $dosen->jurusan,
                            $dosen->prodi, $dosen->tmt_cpns_ppk, $dosen->status_kepegawaian,
                            $dosen->pendidikan, $dosen->tahun_lulus, $dosen->pangkat,
                            $dosen->golongan, $dosen->tmt, $dosen->jf, $dosen->tmt_jf,
                            $dosen->nuptk, $dosen->tahun_purna_tugas
                        ];
                        $filledCount = count(array_filter($fields, fn($f) => !empty($f)));
                        $totalCount = count($fields);
                        $percentage = round(($filledCount / $totalCount) * 100);
                    @endphp
                    {{ $percentage }}% Lengkap
                </span>
            </h3>
            <div class="space-x-2">
            <a href="{{ route('admin.dosen.edit', $dosen) }}" 
               class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 transition">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
            <a href="{{ route('admin.dosen.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
            </div>
        </div>
        <!-- Progress Bar -->
        <div class="w-full bg-gray-200 rounded-full h-2.5">
            <div class="bg-green-600 h-2.5 rounded-full transition-all duration-300" style="width: {{ $percentage }}%"></div>
        </div>
    </div>

    <div class="p-6">
        <!-- Card Data Pribadi -->
        <div class="bg-gray-50 rounded-lg p-6 mb-6 border border-gray-200">
            <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-user mr-2 text-green-600"></i>Data Pribadi
            </h4>
            
            <div class="flex gap-6">
                <!-- Foto Profile - Left Side -->
                <div class="flex-shrink-0">
                    @if($dosen->foto)
                        <img src="{{ asset('storage/' . $dosen->foto) }}" alt="Foto {{ $dosen->nama_dosen }}" 
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
                        <label class="block text-sm font-medium text-gray-600 mb-1">Nama Dosen</label>
                        <p class="text-base text-gray-900 font-semibold">{{ $dosen->nama_dosen }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">NIP</label>
                        <p class="text-base text-gray-900">{{ $dosen->nip }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Jenis Kelamin</label>
                        <span class="inline-block px-3 py-1 text-sm rounded-full {{ $dosen->jenis_kelamin == 'Laki-laki' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                            {{ $dosen->jenis_kelamin }}
                        </span>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Tempat Lahir</label>
                        <p class="text-base text-gray-900">{{ $dosen->tempat_lahir ?? '-' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Tanggal Lahir</label>
                        <p class="text-base text-gray-900">
                            {{ $dosen->tanggal_lahir ? $dosen->tanggal_lahir->locale('id')->isoFormat('DD MMMM YYYY') : '-' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Data Pekerjaan -->
        <div class="bg-gray-50 rounded-lg p-6 mb-6 border border-gray-200">
            <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-briefcase mr-2 text-blue-600"></i>Data Pekerjaan
            </h4>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Jurusan</label>
                    <p class="text-base text-gray-900">{{ $dosen->jurusan ?? '-' }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Prodi</label>
                    <p class="text-base text-gray-900">{{ $dosen->prodi ?? '-' }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">TMT CPNS/PPK</label>
                    <p class="text-base text-gray-900">{{ $dosen->tmt_cpns_ppk ? \Carbon\Carbon::parse($dosen->tmt_cpns_ppk)->locale('id')->isoFormat('DD MMMM YYYY') : '-' }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Status Kepegawaian</label>
                    <span class="inline-block px-3 py-1 text-sm rounded-full bg-green-100 text-green-800">
                        {{ $dosen->status_kepegawaian ?? '-' }}
                    </span>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Pendidikan</label>
                    <p class="text-base text-gray-900">{{ $dosen->pendidikan ?? '-' }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Tahun Lulus</label>
                    <p class="text-base text-gray-900">{{ $dosen->tahun_lulus ?? '-' }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Pangkat</label>
                    <p class="text-base text-gray-900">{{ $dosen->pangkat ?? '-' }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Golongan</label>
                    <p class="text-base text-gray-900">{{ $dosen->golongan ?? '-' }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">TMT</label>
                    <p class="text-base text-gray-900">{{ $dosen->tmt ? \Carbon\Carbon::parse($dosen->tmt)->locale('id')->isoFormat('DD MMMM YYYY') : '-' }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Jabatan Fungsional</label>
                    <p class="text-base text-gray-900">{{ $dosen->jf ?? '-' }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">TMT JF</label>
                    <p class="text-base text-gray-900">{{ $dosen->tmt_jf ? \Carbon\Carbon::parse($dosen->tmt_jf)->locale('id')->isoFormat('DD MMMM YYYY') : '-' }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">NUPTK</label>
                    <p class="text-base text-gray-900">{{ $dosen->nuptk ?? '-' }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Tanggal Purna Tugas</label>
                    <p class="text-base text-gray-900">{{ $dosen->tahun_purna_tugas ? \Carbon\Carbon::parse($dosen->tahun_purna_tugas)->locale('id')->isoFormat('DD MMMM YYYY') : '-' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
