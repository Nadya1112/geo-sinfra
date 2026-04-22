<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GEO SINFRA - Sistem Informasi Pemetaan Infrastruktur Permukiman Kota Banjarmasin</title>
    
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style>
            @tailwind base;
            @tailwind components;
            @tailwind utilities;
        </style>
    @endif
</head>
<body class="bg-white">
    <!-- Top Bar -->
    <div class="bg-blue-900 text-white py-2 px-4">
        <div class="max-w-7xl mx-auto flex justify-between items-center text-sm">
            <div>Pemerintah Kota Banjarmasin</div>
            <div class="flex gap-4">
                <a href="#" class="hover:underline">Hubungi Kami</a>
                <a href="#" class="hover:underline">Peta Situs</a>
            </div>
        </div>
    </div>

    <!-- Header -->
    <header class="bg-white border-b-4 border-blue-600 py-6 px-4">
        <div class="max-w-7xl mx-auto">
            <div class="flex items-center justify-between gap-6">
                <!-- Logo dan Judul -->
                <div class="flex items-center gap-4">
                    <div class="w-20 h-20 bg-blue-600 rounded-lg flex items-center justify-center flex-shrink-0">
                        <span class="text-white font-bold text-2xl">GEO</span>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">GEO-SINFRA</h1>
                        <p class="text-sm text-gray-600">Sistem Informasi Pemetaan Infrastruktur Permukiman</p>
                        <p class="text-sm text-gray-600">Kota Banjarmasin</p>
                    </div>
                </div>

                <!-- Login Button -->
                <a href="#login" class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition font-semibold">
                    Login
                </a>
            </div>
        </div>
    </header>

    <!-- Navigation Menu -->
    <nav class="bg-blue-600 text-white shadow-md">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-center gap-8 py-4">
                <a href="#beranda" class="hover:bg-blue-700 px-4 py-2 rounded transition">Beranda</a>
                <a href="#tentang" class="hover:bg-blue-700 px-4 py-2 rounded transition">Tentang</a>
                <a href="#peta" class="hover:bg-blue-700 px-4 py-2 rounded transition">Peta Sebaran</a>
                <a href="#statistik" class="hover:bg-blue-700 px-4 py-2 rounded transition">Data Statistik</a>
                <a href="#informasi" class="hover:bg-blue-700 px-4 py-2 rounded transition">Informasi</a>
            </div>
        </div>
    </nav>

    <!-- Hero/Welcome Section -->
    <section id="beranda" class="bg-gradient-to-r from-blue-50 to-blue-100 py-20 px-4">
        <div class="max-w-7xl mx-auto">
            <div class="text-center">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                    Selamat Datang di GEO-SINFRA
                </h2>
                <p class="text-xl text-gray-700 mb-4">
                    Sistem Informasi Pemetaan Infrastruktur Permukiman Kota Banjarmasin
                </p>
                <p class="text-gray-600 mb-8 max-w-2xl mx-auto">
                    Platform terpadu untuk pemetaan, monitoring, dan analisis infrastruktur permukiman di Kota Banjarmasin. 
                    Kami menyediakan data akurat dan real-time untuk mendukung pengambilan keputusan yang lebih baik.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="#peta" class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition font-semibold">
                        Lihat Peta Sebaran
                    </a>
                    <a href="#statistik" class="bg-white text-blue-600 border-2 border-blue-600 px-8 py-3 rounded-lg hover:bg-blue-50 transition font-semibold">
                        Data Statistik
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Tentang Section -->
    <section id="tentang" class="py-16 px-4 bg-white">
        <div class="max-w-7xl mx-auto">
            <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Tentang GEO-SINFRA</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-gray-50 p-8 rounded-lg">
                    <h3 class="text-xl font-bold text-blue-600 mb-4">Visi</h3>
                    <p class="text-gray-700">
                        Mewujudkan sistem informasi pemetaan infrastruktur permukiman yang terintegrasi, 
                        akurat, dan mudah diakses untuk mendukung pembangunan berkelanjutan Kota Banjarmasin.
                    </p>
                </div>
                <div class="bg-gray-50 p-8 rounded-lg">
                    <h3 class="text-xl font-bold text-blue-600 mb-4">Misi</h3>
                    <p class="text-gray-700">
                        Menyediakan informasi spasial infrastruktur permukiman yang komprehensif dan real-time 
                        untuk mendukung perencanaan, monitoring, dan pengambilan kebijakan pembangunan infrastruktur.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Fitur Section -->
    <section class="py-16 px-4 bg-gray-50">
        <div class="max-w-7xl mx-auto">
            <h2 class="text-3xl font-bold text-gray-900 mb-12 text-center">Fitur Utama</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Fitur 1 -->
                <div class="bg-white p-8 rounded-lg shadow-md hover:shadow-lg transition text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.553-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-1.553-.894L15 9"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Peta Sebaran</h3>
                    <p class="text-gray-600">Visualisasi data infrastruktur permukiman dalam bentuk peta interaktif yang mudah dipahami.</p>
                </div>

                <!-- Fitur 2 -->
                <div class="bg-white p-8 rounded-lg shadow-md hover:shadow-lg transition text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Data Statistik</h3>
                    <p class="text-gray-600">Laporan statistik lengkap dan analisis data infrastruktur untuk pengambilan keputusan strategis.</p>
                </div>

                <!-- Fitur 3 -->
                <div class="bg-white p-8 rounded-lg shadow-md hover:shadow-lg transition text-center">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Monitoring Real-time</h3>
                    <p class="text-gray-600">Pantau status infrastruktur secara real-time dengan sistem notifikasi otomatis untuk perubahan.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-blue-600 text-white py-16 px-4">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-3xl font-bold mb-6">Mulai Gunakan Sistem Sekarang</h2>
            <p class="text-xl mb-8">Akses peta dan data infrastruktur permukiman Kota Banjarmasin</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="#peta" class="bg-white text-blue-600 px-8 py-3 rounded-lg hover:bg-gray-100 transition font-semibold">
                    Lihat Peta
                </a>
                <a href="#login" class="border-2 border-white text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition font-semibold">
                    Login Pengguna
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 py-12 px-4">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                <div>
                    <h4 class="text-white font-bold mb-4">GEO-SINFRA</h4>
                    <p>Sistem Informasi Pemetaan Infrastruktur Permukiman Kota Banjarmasin</p>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-4">Menu</h4>
                    <ul class="space-y-2">
                        <li><a href="#beranda" class="hover:text-white transition">Beranda</a></li>
                        <li><a href="#peta" class="hover:text-white transition">Peta Sebaran</a></li>
                        <li><a href="#statistik" class="hover:text-white transition">Data Statistik</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-4">Kontak</h4>
                    <p>Email: info@geo-sinfra.banjarmasin.go.id</p>
                    <p>Telepon: (0511) XXXX-XXXX</p>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-8 text-center">
                <p>&copy; 2024 GEO-SINFRA - Kota Banjarmasin. Hak Cipta Dilindungi Undang-Undang.</p>
            </div>
        </div>
    </footer>
</body>
</html>
