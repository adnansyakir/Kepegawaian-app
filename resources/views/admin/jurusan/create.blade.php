@extends('admin.layouts.app')

@section('title', 'Tambah Jurusan')

@section('header', 'Tambah Jurusan')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <form action="{{ route('admin.jurusan.store') }}" method="POST">
        @csrf
        
        <div id="input-container">
            <div class="input-group mb-4">
                <div class="flex items-center gap-2">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Jurusan</label>
                        <input type="text" name="nama[]" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500" 
                               required>
                    </div>
                    <button type="button" onclick="removeInput(this)" class="mt-7 px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 hidden remove-btn">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
        </div>

        <button type="button" onclick="addInput()" class="mb-4 px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">
            <i class="fas fa-plus"></i> Tambah Input
        </button>

        @error('nama')
            <p class="mb-4 text-sm text-red-500">{{ $message }}</p>
        @enderror

        <div class="flex justify-end space-x-2">
            <a href="{{ route('admin.jurusan.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                Batal
            </a>
            <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                Simpan
            </button>
        </div>
    </form>
</div>

<script>
function addInput() {
    const container = document.getElementById('input-container');
    const inputGroup = document.createElement('div');
    inputGroup.className = 'input-group mb-4';
    inputGroup.innerHTML = `
        <div class="flex items-center gap-2">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Jurusan</label>
                <input type="text" name="nama[]" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500" 
                       required>
            </div>
            <button type="button" onclick="removeInput(this)" class="mt-7 px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 remove-btn">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    `;
    container.appendChild(inputGroup);
    updateRemoveButtons();
}

function removeInput(btn) {
    btn.closest('.input-group').remove();
    updateRemoveButtons();
}

function updateRemoveButtons() {
    const groups = document.querySelectorAll('.input-group');
    groups.forEach((group, index) => {
        const removeBtn = group.querySelector('.remove-btn');
        if (groups.length === 1) {
            removeBtn.classList.add('hidden');
        } else {
            removeBtn.classList.remove('hidden');
        }
    });
}
</script>
@endsection
