<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin - @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        [x-cloak] { display: none !important; }
    </style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        function updateTime() {
            const now = new Date();
            const dateOptions = { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric'
            };
            const timeOptions = {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            };
            const dateString = now.toLocaleDateString('id-ID', dateOptions);
            const timeString = now.toLocaleTimeString('id-ID', timeOptions);
            const timeElement = document.getElementById('current-time');
            if (timeElement) {
                timeElement.textContent = dateString + ' | ' + timeString + ' WIB ';
            }
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            updateTime();
            setInterval(updateTime, 1000);
        });
    </script>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 bg-purple-800 text-white shrink-0">
            <div class="p-4 border-b border-purple-700 text-center">
                <div class="w-20 h-20 bg-purple-900 rounded-full flex items-center justify-center text-white font-bold text-3xl mx-auto mb-3">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <h1 class="text-xl font-bold">KEPEGAWAIAN-APP</h1>
                <p class="text-sm text-purple-200">Administrator</p>
            </div>
            
            <nav class="p-4">
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('admin.dashboard') }}" 
                           class="flex items-center p-3 rounded hover:bg-purple-700 {{ request()->routeIs('admin.dashboard') ? 'bg-purple-700' : '' }}">
                            <i class="fas fa-home mr-3"></i>
                            Dashboard
                        </a>
                    </li>
                    
                    <li>
                        <a href="{{ route('admin.profile.edit') }}" 
                           class="flex items-center p-3 rounded hover:bg-purple-700 {{ request()->routeIs('admin.profile.*') ? 'bg-purple-700' : '' }}">
                            <i class="fas fa-user mr-3"></i>
                            Profile
                        </a>
                    </li>
                    
                    <li x-data="{ open: {{ request()->routeIs('admin.unit-kerja.*') || request()->routeIs('admin.jurusan.*') || request()->routeIs('admin.prodi.*') || request()->routeIs('admin.pangkat.*') || request()->routeIs('admin.golongan.*') ? 'true' : 'false' }} }">
                        <button @click="open = !open" class="flex items-center justify-between w-full p-3 rounded hover:bg-purple-700 {{ request()->routeIs('admin.unit-kerja.*') || request()->routeIs('admin.jurusan.*') || request()->routeIs('admin.prodi.*') || request()->routeIs('admin.pangkat.*') || request()->routeIs('admin.golongan.*') ? 'bg-purple-700' : '' }}">
                            <div class="flex items-center">
                                <i class="fas fa-database mr-3"></i>
                                Data Master
                            </div>
                            <i class="fas fa-chevron-down transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
                        </button>
                        <ul x-show="open" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-2" class="mt-2 space-y-1">
                            <li>
                                <a href="{{ route('admin.unit-kerja.index') }}" 
                                   class="flex items-center pl-12 p-2 rounded hover:bg-purple-700 text-sm {{ request()->routeIs('admin.unit-kerja.*') ? 'bg-purple-700' : '' }}">
                                    <i class="fas fa-building mr-3"></i>
                                    Unit Kerja
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.jurusan.index') }}" 
                                   class="flex items-center pl-12 p-2 rounded hover:bg-purple-700 text-sm {{ request()->routeIs('admin.jurusan.*') ? 'bg-purple-700' : '' }}">
                                    <i class="fas fa-graduation-cap mr-3"></i>
                                    Jurusan
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.prodi.index') }}" 
                                   class="flex items-center pl-12 p-2 rounded hover:bg-purple-700 text-sm {{ request()->routeIs('admin.prodi.*') ? 'bg-purple-700' : '' }}">
                                    <i class="fas fa-book mr-3"></i>
                                    Program Studi
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.pangkat.index') }}" 
                                   class="flex items-center pl-12 p-2 rounded hover:bg-purple-700 text-sm {{ request()->routeIs('admin.pangkat.*') ? 'bg-purple-700' : '' }}">
                                    <i class="fas fa-star mr-3"></i>
                                    Pangkat
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.golongan.index') }}" 
                                   class="flex items-center pl-12 p-2 rounded hover:bg-purple-700 text-sm {{ request()->routeIs('admin.golongan.*') ? 'bg-purple-700' : '' }}">
                                    <i class="fas fa-layer-group mr-3"></i>
                                    Golongan
                                </a>
                            </li>
                        </ul>
                    </li>
                    
                    <li>
                        <a href="{{ route('admin.karyawan.index') }}" 
                           class="flex items-center p-3 rounded hover:bg-purple-700 {{ request()->routeIs('admin.karyawan.*') ? 'bg-purple-700' : '' }}">
                            <i class="fas fa-users mr-3"></i>
                            Karyawan
                        </a>
                    </li>
                    
                    <li>
                        <a href="{{ route('admin.dosen.index') }}" 
                           class="flex items-center p-3 rounded hover:bg-purple-700 {{ request()->routeIs('admin.dosen.*') ? 'bg-purple-700' : '' }}">
                            <i class="fas fa-chalkboard-teacher mr-3"></i>
                            Dosen
                        </a>
                    </li>
                    
                    <li>
                        <a href="{{ route('admin.teknisi.index') }}" 
                           class="flex items-center p-3 rounded hover:bg-purple-700 {{ request()->routeIs('admin.teknisi.*') ? 'bg-purple-700' : '' }}">
                            <i class="fas fa-tools mr-3"></i>
                            Teknisi
                        </a>
                    </li>
                    
                    <li>
                        <a href="{{ route('admin.users.index') }}" 
                           class="flex items-center p-3 rounded hover:bg-purple-700 {{ request()->routeIs('admin.users.*') ? 'bg-purple-700' : '' }}">
                            <i class="fas fa-user-cog mr-3"></i>
                            User
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-purple-600 shadow-sm z-10">
                <div class="px-6 py-4 flex items-center justify-between">
                    <h2 class="text-2xl font-semibold text-white">Administrator | @yield('header')</h2>
                    <div class="flex items-center space-x-4">
                        <div class="text-white text-sm">
                            <i class="fas fa-clock mr-2"></i>
                            <span id="current-time"></span>
                        </div>
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">
                                <div class="w-10 h-10 bg-purple-800 rounded-full flex items-center justify-center text-white font-semibold hover:bg-purple-900 transition">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                            </button>
                            <div x-show="open" x-cloak @click.away="open = false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                <div class="px-4 py-2 text-sm text-gray-700 border-b">
                                    {{ auth()->user()->name }}
                                </div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 overflow-y-auto p-6">
                @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
                @endif

                @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
                @endif

                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t border-gray-200 py-4 px-6">
                <div class="flex flex-col md:flex-row justify-between items-center text-sm text-gray-600">
                    <div class="mb-2 md:mb-0">
                        <p>&copy; {{ date('Y') }} <span class="font-semibold text-purple-600">KEPEGAWAIAN-APP</span>. All rights reserved.</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span>Version 1.0.0</span>
                        <span>|</span>
                        <span>Developed by <span class="font-semibold text-purple-600">IT Team</span></span>
                    </div>
                </div>
            </footer>
        </div>
    </div>
</body>
</html>