<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pengguna | GEO-SINFRA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-gray-50 flex h-screen overflow-hidden text-gray-800">

    <aside class="w-64 bg-[#1e1b4b] text-white flex flex-col hidden md:flex shadow-2xl z-20">
        <div class="p-6 flex-1">
            <div class="flex items-center gap-3 mb-10">
                <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center"><i class="fas fa-city text-xs"></i></div>
                <span class="font-extrabold text-xl tracking-tighter">GEO-SINFRA</span>
            </div>
            <nav class="space-y-1">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:text-white hover:bg-white/5 rounded-xl text-sm font-semibold transition"><i class="fas fa-th-large"></i> Dashboard</a>
                <a href="{{ route('admin.peta') }}" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:text-white hover:bg-white/5 rounded-xl text-sm font-semibold transition"><i class="fas fa-map-marked-alt"></i> Peta Spasial</a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:text-white hover:bg-white/5 rounded-xl text-sm font-semibold transition"><i class="fas fa-database"></i> Data Infrastruktur</a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 bg-blue-600 rounded-xl text-sm font-bold transition shadow-lg shadow-blue-900/20"><i class="fas fa-users-cog"></i> Manajemen Pengguna</a>
            </nav>
        </div>
        <div class="p-6 border-t border-white/5">
            <form action="{{ route('logout') }}" method="POST">@csrf
                <button class="flex items-center gap-3 text-red-400 hover:text-red-300 text-sm font-bold transition"><i class="fas fa-sign-out-alt"></i> Keluar Sistem</button>
            </form>
        </div>
    </aside>

    <main class="flex-1 flex flex-col h-screen overflow-hidden">
        <header class="bg-white border-b border-gray-100 px-8 py-5 flex justify-between items-center z-10">
            <div>
                <h2 class="text-xl font-black text-[#1e1b4b]">Manajemen Pengguna</h2>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Daftar Surveyor & Kabid</p>
            </div>
            <button class="bg-blue-600 text-white text-xs px-6 py-2.5 rounded-xl font-bold shadow-lg shadow-blue-200 hover:bg-blue-700 transition">+ Tambah User</button>
        </header>

        <div class="flex-1 p-8 overflow-y-auto custom-scrollbar">
            <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-100">
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Nama Lengkap</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Email</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Role</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($users as $user)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-indigo-50 text-indigo-600 rounded-full flex items-center justify-center font-bold text-xs">{{ substr($user->name, 0, 1) }}</div>
                                    <span class="text-xs font-bold text-[#1e1b4b]">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-500">{{ $user->email }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase {{ $user->role == 'kabid' ? 'bg-purple-100 text-purple-600' : 'bg-orange-100 text-orange-600' }}">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <button class="p-2 bg-gray-50 text-gray-400 hover:text-blue-600 rounded-lg transition"><i class="fas fa-edit text-xs"></i></button>
                                    <button class="p-2 bg-gray-50 text-gray-400 hover:text-red-600 rounded-lg transition"><i class="fas fa-trash text-xs"></i></button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>