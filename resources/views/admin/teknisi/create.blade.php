@extends('admin.layouts.app')

@section('title', 'Tambah Teknisi')
@section('header', 'Tambah Teknisi')

@section('content')
<div class="bg-white rounded-lg shadow-md">
    <div class="p-6 border-b border-gray-200">
        <h3 class="text-lg font-semibold mb-4">Form Tambah Teknisi | <span id="progressText" class="text-purple-600">0% Selesai</span></h3>
        <!-- Progress Bar -->
        <div class="w-full bg-gray-200 rounded-full h-2.5">
            <div id="progressBar" class="bg-purple-600 h-2.5 rounded-full transition-all duration-300" style="width: 0%"></div>
        </div>
    </div>

    <form action="{{ route('admin.teknisi.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
        @csrf
        
        <!-- Card Data Pribadi -->
        <div class="bg-gray-50 rounded-lg p-6 mb-6 border border-gray-200">
            <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-user mr-2 text-purple-600"></i>Data Pribadi
            </h4>
            
            <div class="flex gap-6">
                <!-- Foto Profile with Preview - Left Side -->
                <div class="flex-shrink-0">
                    <div id="fotoPreview" class="hidden mb-4">
                        <img id="previewImage" src="" alt="Preview" class="w-48 h-48 object-cover rounded-lg shadow-md">
                    </div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Foto Profile</label>
                    <input type="file" name="foto" id="fotoInput" accept="image/jpeg,image/jpg,image/png" onchange="previewFoto(event)" data-section="pribadi"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('foto') border-red-500 @enderror">
                    <p class="text-xs text-gray-500 mt-1">Format: JPG, JPEG, PNG. Max 2MB</p>
                    @error('foto')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Form Fields - Right Side -->
                <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama -->
                    <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nama <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama" value="{{ old('nama') }}" required data-section="pribadi"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('nama') border-red-500 @enderror">
                    @error('nama')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- NIP -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">NIP</label>
                    <input type="text" name="nip" value="{{ old('nip') }}" data-section="pribadi"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('nip') border-red-500 @enderror">
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
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('jenis_kelamin') border-red-500 @enderror">
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tempat Lahir -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" data-section="pribadi"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                </div>

                <!-- Tanggal Lahir -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" data-section="pribadi"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                </div>
                </div>
            </div>
        </div>

        <!-- Card Data Pekerjaan -->
        <div class="bg-gray-50 rounded-lg p-6 mb-6 border border-gray-200">
            <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-briefcase mr-2 text-green-600"></i>Data Pekerjaan
            </h4>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Unit Kerja -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Unit Kerja</label>
                    <input type="text" name="unit_kerja" value="{{ old('unit_kerja') }}" data-section="pekerjaan"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('unit_kerja') border-red-500 @enderror">
                    @error('unit_kerja')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- TMT CPNS/PPK -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">TMT CPNS/PPK</label>
                    <input type="date" name="tmt_cpns_ppk" value="{{ old('tmt_cpns_ppk') }}" data-section="pekerjaan"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('tmt_cpns_ppk') border-red-500 @enderror">
                    @error('tmt_cpns_ppk')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status Kepegawaian -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status Kepegawaian</label>
                    <select name="status_kepegawaian" data-section="pekerjaan"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('status_kepegawaian') border-red-500 @enderror">
                        <option value="">Pilih Status Kepegawaian</option>
                        <option value="CPNS" {{ old('status_kepegawaian') == 'CPNS' ? 'selected' : '' }}>CPNS</option>
                        <option value="PNS" {{ old('status_kepegawaian') == 'PNS' ? 'selected' : '' }}>PNS</option>
                        <option value="PPPK" {{ old('status_kepegawaian') == 'PPPK' ? 'selected' : '' }}>PPPK</option>
                        <option value="PPPK New" {{ old('status_kepegawaian') == 'PPPK New' ? 'selected' : '' }}>PPPK New</option>
                        <option value="PPPK Paruh Waktu" {{ old('status_kepegawaian') == 'PPPK Paruh Waktu' ? 'selected' : '' }}>PPPK Paruh Waktu</option>
                        <option value="Honorer" {{ old('status_kepegawaian') == 'Honorer' ? 'selected' : '' }}>Honorer</option>
                        <option value="THL" {{ old('status_kepegawaian') == 'THL' ? 'selected' : '' }}>THL</option>
                    </select>
                    @error('status_kepegawaian')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Pendidikan Terakhir -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pendidikan Terakhir</label>
                    <input type="text" name="pendidikan_terakhir" value="{{ old('pendidikan_terakhir') }}" data-section="pekerjaan"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                </div>

                <!-- Tahun Lulus -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tahun Lulus</label>
                    <input type="text" name="tahun_lulus" value="{{ old('tahun_lulus') }}" data-section="pekerjaan"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                </div>

                <!-- Pangkat -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pangkat</label>
                    <select name="pangkat" data-section="pekerjaan"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('pangkat') border-red-500 @enderror">
                        <option value="">Pilih Pangkat</option>
                        @foreach($pangkats as $pangkat)
                            <option value="{{ $pangkat->nama }}" {{ old('pangkat') == $pangkat->nama ? 'selected' : '' }}>
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
                    <select name="gol" data-section="pekerjaan"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('gol') border-red-500 @enderror">
                        <option value="">Pilih Golongan</option>
                        @foreach($golongans as $golongan)
                            <option value="{{ $golongan->nama }}" {{ old('gol') == $golongan->nama ? 'selected' : '' }}>
                                {{ $golongan->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('gol')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- TMT Golongan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">TMT Golongan</label>
                    <input type="date" name="tmt_gol" value="{{ old('tmt_gol') }}" placeholder="-" data-section="pekerjaan"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('tmt_gol') border-red-500 @enderror">
                    @error('tmt_gol')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kelas Jabatan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kelas Jabatan</label>
                    <select name="kelas_jabatan" data-section="pekerjaan"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('kelas_jabatan') border-red-500 @enderror">
                        <option value="">-</option>
                        <option value="1" {{ old('kelas_jabatan') == '1' ? 'selected' : '' }}>1</option>
                        <option value="3" {{ old('kelas_jabatan') == '3' ? 'selected' : '' }}>3</option>
                        <option value="5" {{ old('kelas_jabatan') == '5' ? 'selected' : '' }}>5</option>
                        <option value="6" {{ old('kelas_jabatan') == '6' ? 'selected' : '' }}>6</option>
                        <option value="7" {{ old('kelas_jabatan') == '7' ? 'selected' : '' }}>7</option>
                        <option value="8" {{ old('kelas_jabatan') == '8' ? 'selected' : '' }}>8</option>
                        <option value="9" {{ old('kelas_jabatan') == '9' ? 'selected' : '' }}>9</option>
                        <option value="12" {{ old('kelas_jabatan') == '12' ? 'selected' : '' }}>12</option>
                    </select>
                    @error('kelas_jabatan')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tahun Purna Tugas -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Purna Tugas</label>
                    <input type="date" name="tahun_purna_tugas" value="{{ old('tahun_purna_tugas') }}" placeholder="-" data-section="pekerjaan"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('tahun_purna_tugas') border-red-500 @enderror">
                    @error('tahun_purna_tugas')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-200">
            <a href="{{ route('admin.teknisi.index') }}" 
               class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition">
                <i class="fas fa-times mr-2"></i>Batal
            </a>
            <button type="submit" 
                    class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition">
                <i class="fas fa-save mr-2"></i>Simpan
            </button>
        </div>
    </form>
</div>

<script>
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
            field.addEventListener('change', updateProgress);
        } else {
            field.addEventListener('input', updateProgress);
            field.addEventListener('change', updateProgress);
        }
    });
    
    // Initial progress calculation
    updateProgress();
});
</script>

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
    border-color: #9333ea;
    outline: 2px solid transparent;
    outline-offset: 2px;
    box-shadow: 0 0 0 3px rgba(147, 51, 234, 0.1);
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
    background-color: #9333ea !important;
}
</style>

<script>
$(document).ready(function() {
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
    $('select[name="gol"]').select2({
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
    
    // Inisialisasi Select2 untuk Kelas Jabatan
    $('select[name="kelas_jabatan"]').select2({
        placeholder: 'Pilih Kelas Jabatan',
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
    
    // Update progress when Select2 changes
    $('select[data-section]').on('select2:select select2:clear', function() {
        updateProgress();
    });
});
</script>
@endsection
