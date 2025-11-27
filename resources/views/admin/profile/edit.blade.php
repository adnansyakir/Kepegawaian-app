@extends('admin.layouts.app')

@section('title', 'Edit Profil')
@section('header', 'Edit Profil')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Profil Card -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold">Update Informasi Profil</h3>
            </div>

            <form action="{{ route('admin.profile.update') }}" method="POST" class="p-6">
                @csrf
                @method('PUT')
                
                <div class="space-y-6">
                    <!-- Nama -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nama <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('name') border-red-500 @enderror">
                        @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('email') border-red-500 @enderror">
                        @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex gap-3 mt-8 pt-6 border-t border-gray-200">
                    <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition">
                        <i class="fas fa-save mr-2"></i>Simpan Profil
                    </button>
                </div>
            </form>

            @if (session('status') === 'profile-updated')
            <div class="p-6 bg-green-50 border-l-4 border-green-500 rounded">
                <p class="text-green-700">Profil berhasil diperbarui.</p>
            </div>
            @endif
        </div>

        <!-- Password Change Card -->
        <div class="bg-white rounded-lg shadow-md mt-6">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold">Ubah Password</h3>
            </div>

            <form action="{{ route('admin.profile.password.update') }}" method="POST" class="p-6">
                @csrf
                @method('PUT')
                
                <div class="space-y-6">
                    <!-- Password Lama -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Password Saat Ini <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="current_password" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('current_password') border-red-500 @enderror">
                        @error('current_password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Baru -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Password Baru <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('password') border-red-500 @enderror">
                        @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Konfirmasi Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Konfirmasi Password Baru <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password_confirmation" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>
                </div>

                <div class="flex gap-3 mt-8 pt-6 border-t border-gray-200">
                    <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition">
                        <i class="fas fa-lock mr-2"></i>Ubah Password
                    </button>
                </div>
            </form>

            @if (session('status') === 'password-updated')
            <div class="p-6 bg-green-50 border-l-4 border-green-500 rounded">
                <p class="text-green-700">Password berhasil diubah.</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Info Card -->
    <div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="text-center mb-6">
                <div class="w-16 h-16 rounded-full bg-green-500 text-white flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-user text-2xl"></i>
                </div>
                <h4 class="text-lg font-semibold text-gray-900">{{ auth()->user()->name }}</h4>
                <p class="text-sm text-gray-500">{{ auth()->user()->email }}</p>
            </div>

            <div class="space-y-4 pt-4 border-t border-gray-200">
                <div>
                    <span class="text-sm font-medium text-gray-600">Role</span>
                    <p class="text-gray-900 font-semibold capitalize">{{ auth()->user()->role }}</p>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-600">Email Status</span>
                    <p class="text-sm">
                        @if(auth()->user()->email_verified_at)
                            <span class="text-green-600 font-medium">✓ Terverifikasi</span><br>
                            <span class="text-xs text-gray-500">{{ auth()->user()->email_verified_at->format('d M Y H:i') }}</span>
                        @else
                            <span class="text-yellow-600 font-medium">⚠ Belum Terverifikasi</span>
                        @endif
                    </p>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-600">Bergabung Sejak</span>
                    <p class="text-gray-900">{{ auth()->user()->created_at?->format('d M Y') ?? '-' }}</p>
                </div>
            </div>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-600 text-lg"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700">
                        Untuk keamanan akun, pastikan Anda menggunakan password yang kuat dan sering memperbarui password secara berkala.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
