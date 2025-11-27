<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Direktur - @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
                timeElement.textContent = dateString + ' | ' + timeString;
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
        <aside class="w-64 bg-blue-800 text-white flex-shrink-0">
            <div class="p-4 border-b border-blue-700 text-center">
                <div class="w-20 h-20 bg-blue-900 rounded-full flex items-center justify-center text-white font-bold text-3xl mx-auto mb-3">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <h1 class="text-xl font-bold">KEPEGAWAIAN-APP</h1>
                <p class="text-sm text-blue-200">Direktur</p>
            </div>
            
            <nav class="p-4">
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('direktur.dashboard') }}" 
                           class="flex items-center p-3 rounded hover:bg-blue-700 {{ request()->routeIs('direktur.dashboard') ? 'bg-blue-700' : '' }}">
                            <i class="fas fa-home mr-3"></i>
                            Dashboard
                        </a>
                    </li>
                    
                    <li>
                        <a href="{{ route('direktur.karyawan.index') }}" 
                           class="flex items-center p-3 rounded hover:bg-blue-700 {{ request()->routeIs('direktur.karyawan.*') ? 'bg-blue-700' : '' }}">
                            <i class="fas fa-users mr-3"></i>
                            Karyawan
                        </a>
                    </li>
                    
                    <li>
                        <a href="{{ route('direktur.dosen.index') }}" 
                           class="flex items-center p-3 rounded hover:bg-blue-700 {{ request()->routeIs('direktur.dosen.*') ? 'bg-blue-700' : '' }}">
                            <i class="fas fa-chalkboard-teacher mr-3"></i>
                            Dosen
                        </a>
                    </li>
                    
                    <li>
                        <a href="{{ route('direktur.teknisi.index') }}" 
                           class="flex items-center p-3 rounded hover:bg-blue-700 {{ request()->routeIs('direktur.teknisi.*') ? 'bg-blue-700' : '' }}">
                            <i class="fas fa-tools mr-3"></i>
                            Teknisi
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-blue-600 shadow-sm z-10">
                <div class="px-6 py-4 flex items-center justify-between">
                    <h2 class="text-2xl font-semibold text-white">@yield('header')</h2>
                    <div class="flex items-center space-x-4">
                        <div class="text-white text-sm">
                            <i class="fas fa-clock mr-2"></i>
                            <span id="current-time"></span>
                        </div>
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">
                                <div class="w-10 h-10 bg-blue-800 rounded-full flex items-center justify-center text-white font-semibold hover:bg-blue-900 transition">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                            </button>
                            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
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
        </div>
    </div>
</body>
</html>