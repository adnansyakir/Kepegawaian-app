@extends('admin.layouts.app')

@section('title', 'Edit Dosen')
@section('header', 'Edit Dosen')

@section('content')
<div class="bg-white rounded-lg shadow-md">
    <div class="p-6 border-b border-gray-200">
        <h3 class="text-lg font-semibold mb-4">Form Edit Dosen | <span id="progressText" class="text-green-600">0% Selesai</span></h3>
        <!-- Progress Bar -->
        <div class="w-full bg-gray-200 rounded-full h-2.5">
            <div id="progressBar" class="bg-green-600 h-2.5 rounded-full transition-all duration-300" style="width: 0%"></div>
        </div>
    </div>

    <form action="{{ route('admin.dosen.update', $dosen) }}" method="POST" enctype="multipart/form-data" class="p-6">
        @csrf
        @method('PUT')
        
        <!-- Card Data Pribadi -->
        <div class="bg-gray-50 rounded-lg p-6 mb-6 border border-gray-200">
            <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-user mr-2 text-green-600"></i>Data Pribadi
            </h4>
            
            <div class="flex gap-6">
                <!-- Foto Profile with Preview - Left Side -->
                <div class="flex-shrink-0">
                    <div id="fotoPreview" class="{{ $dosen->foto ? '' : 'hidden' }} mb-4">
                        <img id="previewImage" src="{{ $dosen->foto ? asset('storage/' . $dosen->foto) : '' }}" alt="Preview" class="w-48 h-48 object-cover rounded-lg shadow-md">
                    </div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Foto Profile</label>
                    <input type="file" name="foto" id="fotoInput" accept="image/jpeg,image/jpg,image/png" onchange="previewFoto(event)" data-section="pribadi"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('foto') border-red-500 @enderror">
                    <p class="text-xs text-gray-500 mt-1">Format: JPG, JPEG, PNG. Max 2MB</p>
                    @error('foto')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Form Fields - Right Side -->
                <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Dosen -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Dosen <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_dosen" value="{{ old('nama_dosen', $dosen->nama_dosen) }}" required data-section="pribadi"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('nama_dosen') border-red-500 @enderror">
                        @error('nama_dosen')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- NIP -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            NIP <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nip" value="{{ old('nip', $dosen->nip) }}" required data-section="pribadi"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('nip') border-red-500 @enderror">
                        @error('nip')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jenis Kelamin -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Jenis Kelamin <span class="text-red-500">*</span>
                        </label>
                        <select name="jenis_kelamin" required data-section="pribadi"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('jenis_kelamin') border-red-500 @enderror">
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="Laki-laki" {{ old('jenis_kelamin', $dosen->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('jenis_kelamin', $dosen->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tempat Lahir -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $dosen->tempat_lahir) }}" data-section="pribadi"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>

                    <!-- Tanggal Lahir -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $dosen->tanggal_lahir?->format('Y-m-d')) }}" data-section="pribadi"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Data Pekerjaan -->
        <div class="bg-gray-50 rounded-lg p-6 mb-6 border border-gray-200">
            <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-briefcase mr-2 text-blue-600"></i>Data Pekerjaan
            </h4>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Jurusan -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Jurusan</label>
                <select name="jurusan" data-section="pekerjaan"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('jurusan') border-red-500 @enderror">
                    <option value="">Pilih Jurusan</option>
                    @foreach($jurusans as $jurusan)
                        <option value="{{ $jurusan->nama }}" {{ old('jurusan', $dosen->jurusan) == $jurusan->nama ? 'selected' : '' }}>
                            {{ $jurusan->nama }}
                        </option>
                    @endforeach
                </select>
                @error('jurusan')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Prodi -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Prodi</label>
                <select name="prodi" data-section="pekerjaan"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('prodi') border-red-500 @enderror">
                    <option value="">Pilih Prodi</option>
                    @foreach($prodis as $prodi)
                        <option value="{{ $prodi->nama }}" {{ old('prodi', $dosen->prodi) == $prodi->nama ? 'selected' : '' }}>
                            {{ $prodi->nama }}
                        </option>
                    @endforeach
                </select>
                @error('prodi')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- TMT CPNS/PPK -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">TMT CPNS/PPK</label>
                <input type="date" name="tmt_cpns_ppk" value="{{ old('tmt_cpns_ppk', $dosen->tmt_cpns_ppk) }}" data-section="pekerjaan"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('tmt_cpns_ppk') border-red-500 @enderror">
                @error('tmt_cpns_ppk')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status Kepegawaian -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status Kepegawaian</label>
                <select name="status_kepegawaian" data-section="pekerjaan"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('status_kepegawaian') border-red-500 @enderror">
                    <option value="">Pilih Status Kepegawaian</option>
                    <option value="CPNS" {{ old('status_kepegawaian', $dosen->status_kepegawaian) == 'CPNS' ? 'selected' : '' }}>CPNS</option>
                    <option value="PNS" {{ old('status_kepegawaian', $dosen->status_kepegawaian) == 'PNS' ? 'selected' : '' }}>PNS</option>
                    <option value="PPPK" {{ old('status_kepegawaian', $dosen->status_kepegawaian) == 'PPPK' ? 'selected' : '' }}>PPPK</option>
                    <option value="PPPK New" {{ old('status_kepegawaian', $dosen->status_kepegawaian) == 'PPPK New' ? 'selected' : '' }}>PPPK New</option>
                    <option value="PPPK Paruh Waktu" {{ old('status_kepegawaian', $dosen->status_kepegawaian) == 'PPPK Paruh Waktu' ? 'selected' : '' }}>PPPK Paruh Waktu</option>
                    <option value="Honorer" {{ old('status_kepegawaian', $dosen->status_kepegawaian) == 'Honorer' ? 'selected' : '' }}>Honorer</option>
                    <option value="THL" {{ old('status_kepegawaian', $dosen->status_kepegawaian) == 'THL' ? 'selected' : '' }}>THL</option>
                </select>
                @error('status_kepegawaian')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Pendidikan -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Pendidikan</label>
                <select name="pendidikan" data-section="pekerjaan"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('pendidikan') border-red-500 @enderror">
                    <option value="">Pilih Pendidikan</option>
                    <option value="S2" {{ old('pendidikan', $dosen->pendidikan) == 'S2' ? 'selected' : '' }}>S2</option>
                    <option value="S3" {{ old('pendidikan', $dosen->pendidikan) == 'S3' ? 'selected' : '' }}>S3</option>
                </select>
                @error('pendidikan')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tahun Lulus -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tahun Lulus</label>
                <input type="text" name="tahun_lulus" value="{{ old('tahun_lulus', $dosen->tahun_lulus) }}" data-section="pekerjaan"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
            </div>

            <!-- Pangkat -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Pangkat</label>
                <select name="pangkat" data-section="pekerjaan"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('pangkat') border-red-500 @enderror">
                    <option value="">Pilih Pangkat</option>
                    @foreach($pangkats as $pangkat)
                        <option value="{{ $pangkat->nama }}" {{ old('pangkat', $dosen->pangkat) == $pangkat->nama ? 'selected' : '' }}>
                            {{ $pangkat->nama }}
                        </option>
                    @endforeach
                </select>
                @error('pangkat')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Golongan -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Golongan</label>
                <select name="golongan" data-section="pekerjaan"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('golongan') border-red-500 @enderror">
                    <option value="">Pilih Golongan</option>
                    @foreach($golongans as $golongan)
                        <option value="{{ $golongan->nama }}" {{ old('golongan', $dosen->golongan) == $golongan->nama ? 'selected' : '' }}>
                            {{ $golongan->nama }}
                        </option>
                    @endforeach
                </select>
                @error('golongan')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- TMT -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">TMT</label>
                <input type="date" name="tmt" value="{{ old('tmt', $dosen->tmt) }}" placeholder="-" data-section="pekerjaan"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('tmt') border-red-500 @enderror">
                @error('tmt')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Jabatan Fungsional -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Jabatan Fungsional</label>
                <select name="jf" data-section="pekerjaan"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('jf') border-red-500 @enderror">
                    <option value="">Pilih Jabatan Fungsional</option>
                    <option value="TP" {{ old('jf', $dosen->jf) == 'TP' ? 'selected' : '' }}>TP (Tenaga Pengajar)</option>
                    <option value="L" {{ old('jf', $dosen->jf) == 'L' ? 'selected' : '' }}>L (Lektor)</option>
                    <option value="AA" {{ old('jf', $dosen->jf) == 'AA' ? 'selected' : '' }}>AA (Asisten Ahli)</option>
                </select>
                @error('jf')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- TMT JF -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">TMT JF</label>
                <input type="date" name="tmt_jf" value="{{ old('tmt_jf', $dosen->tmt_jf) }}" placeholder="-" data-section="pekerjaan"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('tmt_jf') border-red-500 @enderror">
                @error('tmt_jf')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- NUPTK -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">NUPTK</label>
                <input type="text" name="nuptk" value="{{ old('nuptk', $dosen->nuptk) }}" data-section="pekerjaan"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
            </div>

            <!-- Tahun Purna Tugas -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Purna Tugas</label>
                <input type="date" name="tahun_purna_tugas" value="{{ old('tahun_purna_tugas', $dosen->tahun_purna_tugas) }}" placeholder="-" data-section="pekerjaan"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('tahun_purna_tugas') border-red-500 @enderror">
                @error('tahun_purna_tugas')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
        </div>

        <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-200">
            <a href="{{ route('admin.dosen.index') }}" 
               class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition">
                <i class="fas fa-times mr-2"></i>Batal
            </a>
            <button type="submit" 
                    class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                <i class="fas fa-save mr-2"></i>Update
            </button>
        </div>
    </form>
</div>

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- Select2 JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>
.select2-container--default .select2-selection--single {
    height: 42px;
    padding: 6px 12px;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 28px;
    color: #374151;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 40px;
}
.select2-container--default.select2-container--focus .select2-selection--single {
    border-color: #10b981;
    outline: 2px solid transparent;
    outline-offset: 2px;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
}
.select2-dropdown {
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
}
.select2-search--dropdown .select2-search__field {
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    padding: 6px 12px;
}
.select2-results__option--highlighted {
    background-color: #10b981 !important;
}
</style>

<script>
$(document).ready(function() {
    // Inisialisasi Select2 untuk Jurusan
    $('select[name="jurusan"]').select2({
        placeholder: 'Pilih Jurusan',
        allowClear: true,
        width: '100%',
        language: {
            noResults: function() {
                return "Tidak ada data yang ditemukan";
            },
            searching: function() {
                return "Mencari...";
            }
        }
    });
    
    // Inisialisasi Select2 untuk Prodi
    $('select[name="prodi"]').select2({
        placeholder: 'Pilih Prodi',
        allowClear: true,
        width: '100%',
        language: {
            noResults: function() {
                return "Tidak ada data yang ditemukan";
            },
            searching: function() {
                return "Mencari...";
            }
        }
    });
    
    // Inisialisasi Select2 untuk Pangkat
    $('select[name="pangkat"]').select2({
        placeholder: 'Pilih Pangkat',
        allowClear: true,
        width: '100%',
        language: {
            noResults: function() {
                return "Tidak ada data yang ditemukan";
            },
            searching: function() {
                return "Mencari...";
            }
        }
    });
    
    // Inisialisasi Select2 untuk Golongan
    $('select[name="golongan"]').select2({
        placeholder: 'Pilih Golongan',
        allowClear: true,
        width: '100%',
        language: {
            noResults: function() {
                return "Tidak ada data yang ditemukan";
            },
            searching: function() {
                return "Mencari...";
            }
        }
    });
    
    // Inisialisasi Select2 untuk Status Kepegawaian
    $('select[name="status_kepegawaian"]').select2({
        placeholder: 'Pilih Status Kepegawaian',
        allowClear: true,
        width: '100%',
        language: {
            noResults: function() {
                return "Tidak ada data yang ditemukan";
            },
            searching: function() {
                return "Mencari...";
            }
        }
    });
});

// Preview foto saat dipilih
function previewFoto(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImage').src = e.target.result;
            document.getElementById('fotoPreview').classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    }
    updateProgress();
}

// Update progress bar based on filled fields
function updateProgress() {
    const pribadiFields = document.querySelectorAll('[data-section="pribadi"]');
    const pekerjaanFields = document.querySelectorAll('[data-section="pekerjaan"]');
    
    let pribadiCount = 0;
    let pribadiTotal = pribadiFields.length;
    
    pribadiFields.forEach(field => {
        if (field.type === 'file') {
            if (field.files.length > 0) pribadiCount++;
        } else if (field.value && field.value !== '') {
            pribadiCount++;
        }
    });
    
    let pekerjaanCount = 0;
    let pekerjaanTotal = pekerjaanFields.length;
    
    pekerjaanFields.forEach(field => {
        if (field.value && field.value !== '') {
            pekerjaanCount++;
        }
    });
    
    const totalFields = pribadiTotal + pekerjaanTotal;
    const filledFields = pribadiCount + pekerjaanCount;
    const percentage = Math.round((filledFields / totalFields) * 100);
    
    document.getElementById('progressBar').style.width = percentage + '%';
    document.getElementById('progressText').textContent = percentage + '% Selesai';
}

// Add event listeners to all form fields
document.addEventListener('DOMContentLoaded', function() {
    const allFields = document.querySelectorAll('[data-section]');
    allFields.forEach(field => {
        if (field.tagName === 'SELECT') {
            // Regular change event
            field.addEventListener('change', updateProgress);
            // Select2 specific event
            $(field).on('select2:select select2:clear', function() {
                updateProgress();
            });
        } else if (field.type === 'file') {
            // File input - also handle when cleared
            field.addEventListener('change', updateProgress);
        } else {
            // Text, date, and other inputs
            field.addEventListener('input', updateProgress);
            field.addEventListener('change', updateProgress);
        }
    });
    
    // Initial progress calculation
    updateProgress();
});
</script>
@endsection
