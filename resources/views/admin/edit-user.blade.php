<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pengguna | Admin SINFRA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-gray-50 flex h-screen overflow-hidden text-gray-800 text-left">

    <aside class="w-64 bg-[#1e1b4b] text-white flex flex-col hidden md:flex shadow-2xl z-20 text-left">
        <div class="p-6 flex-1">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 mb-10 hover:opacity-80 transition">
                <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center shadow-lg shadow-blue-500/20">
                    <i class="fas fa-city text-xs text-white"></i>
                </div>
                <span class="font-extrabold text-xl tracking-tighter uppercase">GEO-SINFRA</span>
            </a>
            <nav class="space-y-1">
                <a href="{{ route('admin.users') }}" class="flex items-center gap-3 px-4 py-3 bg-blue-600 rounded-xl text-sm font-bold transition shadow-lg shadow-blue-900/20"><i class="fas fa-users-cog"></i> Manajemen Pengguna</a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:text-white hover:bg-white/5 rounded-xl text-sm font-semibold transition"><i class="fas fa-database"></i> Manajemen Infrastruktur</a>
                <a href="{{ route('admin.peta') }}" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:text-white hover:bg-white/5 rounded-xl text-sm font-semibold transition"><i class="fas fa-map-marked-alt"></i> Peta Spasial</a>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:text-white hover:bg-white/5 rounded-xl text-sm font-semibold transition"><i class="fas fa-chart-bar"></i> Statistik dan Laporan</a>
            </nav>
        </div>
        <div class="p-6 border-t border-white/5 text-left">
            <form action="{{ route('logout') }}" method="POST">@csrf
                <button class="flex items-center gap-3 text-red-400 hover:text-red-300 text-sm font-bold transition"><i class="fas fa-sign-out-alt"></i> Keluar Sistem</button>
            </form>
        </div>
    </aside>

    <main class="flex-1 flex flex-col h-screen overflow-hidden text-left">
        <header class="bg-white border-b border-gray-100 px-8 py-5 flex justify-between items-center z-10 text-left">
            <div>
                <p class="text-[10px] font-extrabold text-blue-600 uppercase tracking-[0.2em] mb-1">Administrator Portal</p>
                <h2 class="text-xl font-black text-[#1e1b4b]">Edit Data Pengguna</h2>
            </div>
            <div class="flex items-center gap-6">
                <div class="text-right hidden sm:block">
                    <p class="text-[11px] font-black text-[#1e1b4b]" id="mini-clock">00:00 WITA</p>
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
                <div class="h-8 w-[1px] bg-gray-100"></div>
                <div class="flex items-center gap-3 text-left">
                    <div class="text-right">
                        <p class="text-[11px] font-black text-[#1e1b4b] leading-none uppercase">Admin SINFRA</p>
                        <p class="text-[9px] font-bold text-green-500 uppercase mt-1">Online</p>
                    </div>
                    <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 border border-indigo-100"><i class="fas fa-user-circle text-xl"></i></div>
                </div>
            </div>
        </header>

        <div class="flex-1 p-8 overflow-y-auto text-left">
            <div class="max-w-2xl bg-white rounded-[2.5rem] p-10 border border-gray-100 shadow-sm text-left">
                <div class="mb-8 text-left">
                    <h4 class="font-extrabold text-lg text-[#1e1b4b]">Informasi Akun</h4>
                    <p class="text-xs text-gray-400 font-medium">Perbarui detail profil dan hak akses pengguna ini.</p>
                </div>

                <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="space-y-6 text-left">
                    @csrf
                    @method('PUT')
                    
                    <div class="text-left">
                        <label class="block text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest mb-2">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ $user->name }}" class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-semibold focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none">
                    </div>

                    <div class="text-left">
                        <label class="block text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest mb-2">Alamat Email</label>
                        <input type="email" name="email" value="{{ $user->email }}" class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-semibold focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none">
                    </div>

                    <div class="text-left">
                        <label class="block text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest mb-2">Peran / Role</label>
                        <select name="role" class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-semibold focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none appearance-none">
                            <option value="surveyor" {{ $user->role == 'surveyor' ? 'selected' : '' }}>SURVEYOR</option>
                            <option value="kabid" {{ $user->role == 'kabid' ? 'selected' : '' }}>KABID</option>
                        </select>
                    </div>

                    <div class="pt-6 flex gap-3 text-left">
                        <button type="submit" class="bg-blue-600 text-white text-xs px-8 py-3.5 rounded-xl font-bold shadow-lg shadow-blue-200 hover:bg-blue-700 transition">Simpan Perubahan</button>
                        <a href="{{ route('admin.users') }}" class="bg-gray-100 text-gray-500 text-xs px-8 py-3.5 rounded-xl font-bold hover:bg-gray-200 transition">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        function updateClock() {
            const now = new Date();
            document.getElementById('mini-clock').textContent = `${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')} WITA`;
        }
        setInterval(updateClock, 1000); updateClock();
    </script>
</body>
</html>