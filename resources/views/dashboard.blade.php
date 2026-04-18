<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="flex h-screen bg-gray-100">
        <!-- Sidebar -->
        <div class="hidden md:flex md:flex-col md:w-64 md:fixed md:inset-y-0 bg-gray-900 text-white">
            <div class="flex items-center justify-center h-16 bg-gray-800">
                <span class="text-2xl font-bold">GEO</span>
            </div>
            
            <nav class="flex-1 px-2 py-4 space-y-2">
                <a href="#" class="flex items-center px-4 py-2 text-gray-100 bg-gray-800 rounded-lg">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                    </svg>
                    Dashboard
                </a>
                <a href="#" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-800 rounded-lg transition">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 6H6.28l-.31-1.243A1 1 0 005 4H3z"></path>
                    </svg>
                    Produk
                </a>
                <a href="#" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-800 rounded-lg transition">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v4h8v-4zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>
                    </svg>
                    Pengguna
                </a>
                <a href="#" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-800 rounded-lg transition">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path>
                    </svg>
                    Laporan
                </a>
            </nav>

            <div class="px-4 py-4 border-t border-gray-800">
                <div class="flex items-center">
                    <img class="w-10 h-10 rounded-full" src="https://ui-avatars.com/api/?name=Admin+User&background=random" alt="Profile">
                    <div class="ml-3">
                        <p class="text-sm font-medium">Admin User</p>
                        <p class="text-xs text-gray-400">admin@example.com</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex flex-col flex-1 md:ml-64">
            <!-- Header -->
            <header class="bg-white border-b border-gray-200 shadow-sm">
                <div class="flex items-center justify-between px-4 py-4 md:px-6">
                    <h1 class="text-2xl font-semibold text-gray-900">Dashboard</h1>
                    <div class="flex items-center space-x-4">
                        <button class="p-2 text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                        </button>
                        <button class="p-2 text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-auto p-4 md:p-6">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <!-- Total Users -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="flex-1">
                                <p class="text-gray-600 text-sm font-medium">Total Pengguna</p>
                                <p class="text-3xl font-bold text-gray-900 mt-2">1,234</p>
                                <p class="text-green-600 text-sm mt-2">
                                    <span class="font-semibold">+12%</span> dari bulan lalu
                                </p>
                            </div>
                            <div class="bg-blue-100 rounded-full p-3">
                                <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v4h8v-4zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Total Revenue -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="flex-1">
                                <p class="text-gray-600 text-sm font-medium">Total Pendapatan</p>
                                <p class="text-3xl font-bold text-gray-900 mt-2">Rp 45,3M</p>
                                <p class="text-green-600 text-sm mt-2">
                                    <span class="font-semibold">+8%</span> dari bulan lalu
                                </p>
                            </div>
                            <div class="bg-green-100 rounded-full p-3">
                                <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M8.16 2.97a.75.75 0 01.68.36l5.5 9.25h7.04a.75.75 0 010 1.5h-7.58a.75.75 0 01-.68-.36L10.02 6.5H2.75a.75.75 0 010-1.5h7.41zM2.75 15.5a.75.75 0 000 1.5h14.5a.75.75 0 000-1.5H2.75z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Active Orders -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="flex-1">
                                <p class="text-gray-600 text-sm font-medium">Pesanan Aktif</p>
                                <p class="text-3xl font-bold text-gray-900 mt-2">567</p>
                                <p class="text-red-600 text-sm mt-2">
                                    <span class="font-semibold">-3%</span> dari bulan lalu
                                </p>
                            </div>
                            <div class="bg-orange-100 rounded-full p-3">
                                <svg class="w-8 h-8 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 6H6.28l-.31-1.243A1 1 0 005 4H3z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Conversion Rate -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="flex-1">
                                <p class="text-gray-600 text-sm font-medium">Tingkat Konversi</p>
                                <p class="text-3xl font-bold text-gray-900 mt-2">3.24%</p>
                                <p class="text-green-600 text-sm mt-2">
                                    <span class="font-semibold">+2.1%</span> dari bulan lalu
                                </p>
                            </div>
                            <div class="bg-purple-100 rounded-full p-3">
                                <svg class="w-8 h-8 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                    <!-- Revenue Chart -->
                    <div class="lg:col-span-2 bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-semibold text-gray-900">Penjualan Bulanan</h2>
                            <select class="text-sm border border-gray-300 rounded px-3 py-1">
                                <option>6 Bulan</option>
                                <option>12 Bulan</option>
                            </select>
                        </div>
                        <div class="h-64 flex items-end space-x-2">
                            <div class="flex-1 bg-gradient-to-t from-blue-500 to-blue-400 rounded-t h-1/4"></div>
                            <div class="flex-1 bg-gradient-to-t from-blue-500 to-blue-400 rounded-t h-1/3"></div>
                            <div class="flex-1 bg-gradient-to-t from-blue-500 to-blue-400 rounded-t h-2/4"></div>
                            <div class="flex-1 bg-gradient-to-t from-blue-500 to-blue-400 rounded-t h-3/5"></div>
                            <div class="flex-1 bg-gradient-to-t from-blue-500 to-blue-400 rounded-t h-4/5"></div>
                            <div class="flex-1 bg-gradient-to-t from-blue-500 to-blue-400 rounded-t h-3/4"></div>
                        </div>
                        <div class="flex justify-between text-xs text-gray-600 mt-2">
                            <span>Jan</span>
                            <span>Feb</span>
                            <span>Mar</span>
                            <span>Apr</span>
                            <span>May</span>
                            <span>Jun</span>
                        </div>
                    </div>

                    <!-- Top Products -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Produk Terlaris</h2>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">Produk A</p>
                                    <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                        <div class="bg-blue-500 h-2 rounded-full" style="width: 85%"></div>
                                    </div>
                                </div>
                                <span class="text-sm font-semibold text-gray-900 ml-2">85%</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">Produk B</p>
                                    <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                        <div class="bg-green-500 h-2 rounded-full" style="width: 72%"></div>
                                    </div>
                                </div>
                                <span class="text-sm font-semibold text-gray-900 ml-2">72%</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">Produk C</p>
                                    <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                        <div class="bg-orange-500 h-2 rounded-full" style="width: 60%"></div>
                                    </div>
                                </div>
                                <span class="text-sm font-semibold text-gray-900 ml-2">60%</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">Produk D</p>
                                    <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                        <div class="bg-purple-500 h-2 rounded-full" style="width: 48%"></div>
                                    </div>
                                </div>
                                <span class="text-sm font-semibold text-gray-900 ml-2">48%</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders Table -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Pesanan Terbaru</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-200">
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">ID Pesanan</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">Pelanggan</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">Total</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b border-gray-200 hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm text-gray-900">#12345</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">John Doe</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">18 Apr 2026</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 font-medium">Rp 1,234,000</td>
                                    <td class="px-6 py-4 text-sm">
                                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">Selesai</span>
                                    </td>
                                </tr>
                                <tr class="border-b border-gray-200 hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm text-gray-900">#12346</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">Jane Smith</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">17 Apr 2026</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 font-medium">Rp 2,456,000</td>
                                    <td class="px-6 py-4 text-sm">
                                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">Diproses</span>
                                    </td>
                                </tr>
                                <tr class="border-b border-gray-200 hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm text-gray-900">#12347</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">Mike Johnson</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">16 Apr 2026</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 font-medium">Rp 892,000</td>
                                    <td class="px-6 py-4 text-sm">
                                        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium">Menunggu</span>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm text-gray-900">#12348</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">Sarah Williams</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">15 Apr 2026</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 font-medium">Rp 3,124,000</td>
                                    <td class="px-6 py-4 text-sm">
                                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">Selesai</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
