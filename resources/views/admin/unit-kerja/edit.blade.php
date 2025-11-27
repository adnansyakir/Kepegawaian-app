@extends('admin.layouts.app')

@section('title', 'Edit Unit Kerja')

@section('header', 'Edit Unit Kerja')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <form action="{{ route('admin.unit-kerja.update', $unitKerja->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="mb-4">
            <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">Nama Unit Kerja</label>
            <input type="text" name="nama" id="nama" value="{{ old('nama', $unitKerja->nama) }}" 
                   class="w-full px-4 py-2 border rounded-lg focus:ring-purple-500 focus:border-purple-500 @error('nama') border-red-500 @else border-gray-300 @enderror" 
                   required>
            @error('nama')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end space-x-2">
            <a href="{{ route('admin.unit-kerja.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                Batal
            </a>
            <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                Update
            </button>
        </div>
    </form>
</div>
@endsection
