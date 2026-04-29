<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Profil | GEO-SINFRA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-gray-50 flex h-screen overflow-hidden text-gray-800 text-left">

    @include('surveyor.partials.sidebar')

    <main class="flex-1 flex flex-col h-screen overflow-y-auto">
        <header class="bg-white border-b border-gray-100 px-8 py-5 flex justify-between items-center z-10">
            <div class="flex items-center gap-4">
                <a href="{{ route('surveyor.dashboard') }}" class="w-10 h-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center text-gray-400 hover:text-emerald-600 transition-all">
                    <i class="fas fa-arrow-left text-xs"></i>
                </a>
                <div>
                    <p class="text-[10px] font-extrabold text-emerald-600 uppercase tracking-[0.2em] mb-1">Akun Surveyor</p>
                    <h2 class="text-xl font-black text-[#1e1b4b]">Pengaturan Profil</h2>
                </div>
            </div>
            
            <div class="flex items-center gap-6">
                <div class="text-right hidden sm:block">
                    <p class="text-[11px] font-black text-[#1e1b4b]" id="mini-clock">00:00 WITA</p>
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
            </div>
        </header>

        <div class="p-8">
            <div class="max-w-4xl mx-auto">
                <form action="{{ route('surveyor.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <!-- Foto Profil -->
                        <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm flex flex-col items-center">
                            <h4 class="font-black text-[#1e1b4b] mb-8 text-center uppercase text-[10px] tracking-widest italic">Foto Profil</h4>
                            
                            <div class="relative group">
                                <div class="w-32 h-32 rounded-[2rem] bg-gray-100 border-4 border-white shadow-xl overflow-hidden relative">
                                    <img id="profile-preview" src="{{ $user->profile_photo ? asset('storage/' . $user->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=10b981&color=fff&size=128' }}" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                        <i class="fas fa-camera text-white text-xl"></i>
                                    </div>
                                </div>
                                <input type="file" name="profile_photo" id="profile_photo" class="absolute inset-0 opacity-0 cursor-pointer" accept="image/*" onchange="previewProfile(this)">
                            </div>
                            
                            <p class="text-[10px] text-gray-400 mt-6 text-center leading-relaxed">Gunakan foto wajah yang jelas.<br>Format: JPG, PNG (Max 2MB)</p>
                        </div>

                        <!-- Data Diri -->
                        <div class="lg:col-span-2 bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm">
                            <h4 class="font-black text-[#1e1b4b] mb-8 border-b border-gray-50 pb-4 uppercase text-[10px] tracking-widest italic">Informasi Personal</h4>
                            
                            <div class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Nama Lengkap</label>
                                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-semibold outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/5 transition-all">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Alamat Email</label>
                                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-semibold outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/5 transition-all">
                                    </div>
                                </div>

                                <div class="p-6 bg-emerald-50/50 rounded-3xl border border-emerald-100/50">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-600">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </div>
                                        <div>
                                            <p class="text-[9px] font-bold text-emerald-600 uppercase tracking-widest">Wilayah Tugas Anda</p>
                                            <p class="text-sm font-black text-[#1e1b4b]">{{ $user->kecamatan->nama_kecamatan ?? 'Seluruh Wilayah' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Keamanan -->
                    <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm">
                        <h4 class="font-black text-[#1e1b4b] mb-8 border-b border-gray-50 pb-4 uppercase text-[10px] tracking-widest italic">Keamanan Akun</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Kata Sandi Baru (Kosongkan jika tidak diubah)</label>
                                <input type="password" name="password" class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-semibold outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/5 transition-all">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Konfirmasi Kata Sandi Baru</label>
                                <input type="password" name="password_confirmation" class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-semibold outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/5 transition-all">
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-4">
                        <button type="submit" class="px-10 py-4 bg-[#1e1b4b] text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-indigo-100 hover:bg-emerald-600 transition-all">
                            Simpan Perubahan Profil
                        </button>
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

        function previewProfile(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profile-preview').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>
