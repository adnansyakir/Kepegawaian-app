@extends('admin.layouts.app')

@section('title', 'Data Teknisi')
@section('header', 'Data Teknisi')

@section('content')
<!-- Success/Error Messages -->
@if(session('success'))
    <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded" role="alert">
        <p class="font-bold">Sukses!</p>
        <p>{{ session('success') }}</p>
        
        @if(session('failed_imports') && count(session('failed_imports')) > 0)
            <div class="mt-4">
                <p class="font-semibold mb-2">Detail data yang gagal diimport:</p>
                <div class="bg-white rounded p-3 max-h-60 overflow-y-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="border-b">
                                <th class="text-left py-2 px-2">Nama</th>
                                <th class="text-left py-2 px-2">NIP</th>
                                <th class="text-left py-2 px-2">Unit Kerja</th>
                                <th class="text-left py-2 px-2">Error</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(session('failed_imports') as $failed)
                            <tr class="border-b">
                                <td class="py-2 px-2">{{ $failed['nama'] }}</td>
                                <td class="py-2 px-2">{{ $failed['nip'] }}</td>
                                <td class="py-2 px-2">{{ $failed['unit_kerja'] ?? '-' }}</td>
                                <td class="py-2 px-2 text-red-600 text-xs">{{ $failed['error'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
@endif

@if(session('error'))
    <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded" role="alert">
        <p class="font-bold">Error!</p>
        <p>{{ session('error') }}</p>
    </div>
@endif

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <!-- Total Teknisi Card -->
    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm font-medium mb-1">Total Teknisi</p>
                <h3 class="text-3xl font-bold">{{ $teknisi->total() }}</h3>
                <p class="text-purple-100 text-xs mt-1">Seluruh teknisi</p>
            </div>
            <div class="bg-purple-400 bg-opacity-30 rounded-full p-4">
                <i class="fas fa-tools text-3xl"></i>
            </div>
        </div>
    </div>

    <!-- Total by NIP Card -->
    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm font-medium mb-1">Memiliki NIP</p>
                <h3 class="text-3xl font-bold">{{ App\Models\Teknisi::whereNotNull('nip')->where('nip', '!=', '')->count() }}</h3>
                <p class="text-green-100 text-xs mt-1">Teknisi dengan NIP</p>
            </div>
            <div class="bg-green-400 bg-opacity-30 rounded-full p-4">
                <i class="fas fa-id-card text-3xl"></i>
            </div>
        </div>
    </div>

    <!-- Total Unit Kerja Card -->
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm font-medium mb-1">Unit Kerja Aktif</p>
                <h3 class="text-3xl font-bold">{{ App\Models\Teknisi::whereNotNull('unit_kerja')->distinct('unit_kerja')->count('unit_kerja') }}</h3>
                <p class="text-blue-100 text-xs mt-1">Total unit kerja</p>
            </div>
            <div class="bg-blue-400 bg-opacity-30 rounded-full p-4">
                <i class="fas fa-building text-3xl"></i>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow-md">
    <div class="p-6 border-b border-gray-200 flex justify-between items-center">
        <h3 class="text-lg font-semibold text-gray-800">Daftar Teknisi</h3>
        <div class="flex gap-2">
            <!-- Search Box -->
            <form action="{{ route('admin.teknisi.index') }}" method="GET" class="flex gap-2">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" 
                        placeholder="Cari nama, NIP, unit kerja..." 
                        class="w-64 px-4 py-2 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
                @if(request('search'))
                <a href="{{ route('admin.teknisi.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition" title="Clear Search">
                    <i class="fas fa-times"></i>
                </a>
                @endif
            </form>
            
            <button type="button" onclick="openImportModal()" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                <i class="fas fa-file-excel mr-2"></i>Import Excel
            </button>
            <a href="{{ route('admin.teknisi.create') }}" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition">
                <i class="fas fa-plus mr-2"></i>Tambah Teknisi
            </a>
        </div>
    </div>

    <div class="p-6 overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIP</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Kelamin</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Kerja</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($teknisi as $index => $t)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ ($teknisi->currentPage() - 1) * $teknisi->perPage() + $index + 1 }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $t->nama }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $t->nip ?? '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <span class="px-2 py-1 text-xs rounded-full {{ $t->jenis_kelamin == 'Laki-laki' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                            {{ $t->jenis_kelamin }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $t->unit_kerja ?? '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                        <a href="{{ route('admin.teknisi.show', $t) }}" class="text-blue-600 hover:text-blue-900" title="Lihat Detail">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.teknisi.edit', $t) }}" class="text-yellow-600 hover:text-yellow-900" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.teknisi.destroy', $t) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus data {{ $t->nama }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                        <i class="fas fa-inbox text-4xl text-gray-300 mb-2"></i>
                        <p class="text-lg font-medium">Tidak ada data teknisi</p>
                        <p class="text-sm">Silakan tambahkan data teknisi baru</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($teknisi->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $teknisi->links() }}
    </div>
    @endif
</div>

<!-- Modal Import Excel -->
<div id="importModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl transform transition-all duration-300 scale-95 opacity-0" id="importModalContent">
        <!-- Header -->
        <div class="bg-green-600 rounded-t-2xl px-6 py-5">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <div class="bg-white bg-opacity-20 rounded-lg p-2 mr-3">
                        <i class="fas fa-file-excel text-2xl text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white">Import Data Teknisi</h3>
                        <p class="text-green-100 text-sm">Upload file Excel untuk menambahkan data teknisi</p>
                    </div>
                </div>
                <button onclick="closeImportModal()" class="text-white hover:bg-white hover:bg-opacity-20 rounded-lg p-2 transition">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
        
        <form action="{{ route('admin.teknisi.import') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            
            <!-- File Upload Area -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-800 mb-3">
                    <i class="fas fa-cloud-upload-alt text-green-600 mr-2"></i>Pilih File Excel
                </label>
                <div class="relative">
                    <input type="file" name="file" id="fileInput" accept=".xlsx,.xls,.csv" required
                        class="w-full px-4 py-3 border-2 border-dashed border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 transition cursor-pointer">
                </div>
                <div class="flex items-center justify-between mt-2">
                    <p class="text-xs text-gray-500">
                        <i class="fas fa-info-circle text-blue-500 mr-1"></i>Format: .xlsx, .xls, .csv
                    </p>
                    <p class="text-xs text-gray-500">
                        <i class="fas fa-hdd text-orange-500 mr-1"></i>Maks: 50MB
                    </p>
                </div>
            </div>
            
            <!-- Instructions Card -->
            <div class="bg-blue-50 border-l-4 border-blue-500 rounded-xl p-5 mb-6 shadow-sm">
                <div class="flex items-start mb-3">
                    <div class="bg-blue-500 rounded-lg p-2 mr-3">
                        <i class="fas fa-lightbulb text-white"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-blue-900 mb-1">Panduan Format Excel</h4>
                        <p class="text-xs text-blue-700">Pastikan format file Excel sesuai dengan urutan kolom berikut</p>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg p-4 max-h-64 overflow-y-auto">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div class="flex items-center bg-blue-50 rounded-lg p-2">
                            <span class="bg-blue-500 text-white text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center mr-2">B</span>
                            <span class="text-xs text-gray-800 font-medium">Nama</span>
                        </div>
                        <div class="flex items-center bg-blue-50 rounded-lg p-2">
                            <span class="bg-blue-500 text-white text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center mr-2">C</span>
                            <span class="text-xs text-gray-800 font-medium">Jenis Kelamin <span class="text-gray-500">(L/P)</span></span>
                        </div>
                        <div class="flex items-center bg-blue-50 rounded-lg p-2">
                            <span class="bg-blue-500 text-white text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center mr-2">D</span>
                            <span class="text-xs text-gray-800 font-medium">NIP</span>
                        </div>
                        <div class="flex items-center bg-blue-50 rounded-lg p-2">
                            <span class="bg-blue-500 text-white text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center mr-2">E</span>
                            <span class="text-xs text-gray-800 font-medium">Tempat Lahir</span>
                        </div>
                        <div class="flex items-center bg-blue-50 rounded-lg p-2">
                            <span class="bg-blue-500 text-white text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center mr-2">F</span>
                            <span class="text-xs text-gray-800 font-medium">Tanggal Lahir</span>
                        </div>
                        <div class="flex items-center bg-blue-50 rounded-lg p-2">
                            <span class="bg-blue-500 text-white text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center mr-2">G</span>
                            <span class="text-xs text-gray-800 font-medium">TMT CPNS PPPK</span>
                        </div>
                        <div class="flex items-center bg-blue-50 rounded-lg p-2">
                            <span class="bg-blue-500 text-white text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center mr-2">H</span>
                            <span class="text-xs text-gray-800 font-medium">Pendidikan terakhir</span>
                        </div>
                        <div class="flex items-center bg-blue-50 rounded-lg p-2">
                            <span class="bg-blue-500 text-white text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center mr-2">I</span>
                            <span class="text-xs text-gray-800 font-medium">Tahun lulus</span>
                        </div>
                        <div class="flex items-center bg-blue-50 rounded-lg p-2">
                            <span class="bg-blue-500 text-white text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center mr-2">J</span>
                            <span class="text-xs text-gray-800 font-medium">GOL</span>
                        </div>
                        <div class="flex items-center bg-blue-50 rounded-lg p-2">
                            <span class="bg-blue-500 text-white text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center mr-2">K</span>
                            <span class="text-xs text-gray-800 font-medium">TMT GOL</span>
                        </div>
                        <div class="flex items-center bg-blue-50 rounded-lg p-2">
                            <span class="bg-blue-500 text-white text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center mr-2">L</span>
                            <span class="text-xs text-gray-800 font-medium">Pangkat</span>
                        </div>
                        <div class="flex items-center bg-blue-50 rounded-lg p-2">
                            <span class="bg-blue-500 text-white text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center mr-2">M</span>
                            <span class="text-xs text-gray-800 font-medium">Kelas Jabatan</span>
                        </div>
                        <div class="flex items-center bg-blue-50 rounded-lg p-2">
                            <span class="bg-blue-500 text-white text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center mr-2">N</span>
                            <span class="text-xs text-gray-800 font-medium">TUN PURNA TUGAS</span>
                        </div>
                        <div class="flex items-center bg-blue-50 rounded-lg p-2">
                            <span class="bg-blue-500 text-white text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center mr-2">O</span>
                            <span class="text-xs text-gray-800 font-medium">Status <span class="text-gray-500">(PNS/PPPK/Honorer/THL)</span></span>
                        </div>
                        <div class="flex items-center bg-blue-50 rounded-lg p-2">
                            <span class="bg-blue-500 text-white text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center mr-2">P</span>
                            <span class="text-xs text-gray-800 font-medium">Unit Kerja</span>
                        </div>
                    </div>
                    
                    <div class="mt-4 bg-yellow-50 border-l-4 border-yellow-500 rounded p-3">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-triangle text-yellow-600 mt-0.5 mr-2"></i>
                            <div>
                                <p class="text-xs font-semibold text-yellow-800 mb-1">Catatan Penting:</p>
                                <ul class="text-xs text-yellow-700 space-y-1">
                                    <li>• Kolom A berisi nomor urut</li>
                                    <li>• Baris 1 adalah header kolom</li>
                                    <li>• Data dimulai dari baris ke-2</li>
                                    <li>• Format tanggal: DD/MM/YYYY (contoh: 15/08/2023)</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeImportModal()" 
                    class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-medium">
                    <i class="fas fa-times mr-2"></i>Batal
                </button>
                <button type="submit" 
                    class="px-6 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium shadow-lg hover:shadow-xl">
                    <i class="fas fa-upload mr-2"></i>Import Sekarang
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openImportModal() {
    const modal = document.getElementById('importModal');
    const modalContent = document.getElementById('importModalContent');
    
    modal.classList.remove('hidden');
    
    // Trigger animation
    setTimeout(() => {
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeImportModal() {
    const modal = document.getElementById('importModal');
    const modalContent = document.getElementById('importModalContent');
    
    // Animate out
    modalContent.classList.remove('scale-100', 'opacity-100');
    modalContent.classList.add('scale-95', 'opacity-0');
    
    // Hide after animation
    setTimeout(() => {
        modal.classList.add('hidden');
        // Reset file input
        document.getElementById('fileInput').value = '';
    }, 300);
}

// Close modal when clicking outside
document.getElementById('importModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImportModal();
    }
});

// Close modal with ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('importModal');
        if (!modal.classList.contains('hidden')) {
            closeImportModal();
        }
    }
});
</script>
@endsection
