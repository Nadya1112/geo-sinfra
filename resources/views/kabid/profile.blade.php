<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Kabid | GEO-SINFRA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-gray-50 flex h-screen overflow-hidden text-gray-800 text-left">

    @include('kabid.partials.sidebar')

    <main class="flex-1 flex flex-col h-screen overflow-y-auto">
        <header class="bg-white border-b border-gray-100 px-8 py-5 flex justify-between items-center z-10">
            <div class="flex items-center gap-4">
                <a href="{{ route('kabid.dashboard') }}" class="w-10 h-10 flex items-center justify-center bg-gray-50 text-gray-400 rounded-xl hover:bg-indigo-50 hover:text-indigo-600 transition-all border border-gray-100">
                    <i class="fas fa-arrow-left text-sm"></i>
                </a>
                <div>
                    <p class="text-[10px] font-extrabold text-indigo-600 uppercase tracking-[0.2em] mb-1">Pengaturan Akun</p>
                    <h2 class="text-xl font-black text-[#1e1b4b]">Profil Kepala Bidang</h2>
                </div>
            </div>
        </header>

        <div class="p-8">
            <div class="max-w-4xl mx-auto">
                <form action="{{ route('kabid.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    
                    <!-- Profile Card -->
                    <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm relative overflow-hidden">
                        <div class="absolute right-0 top-0 w-32 h-32 bg-indigo-50 rounded-bl-[5rem] -z-0"></div>
                        
                        <div class="relative z-10 flex flex-col md:flex-row items-center gap-10">
                            <div class="relative group">
                                <div class="w-32 h-32 rounded-[2.5rem] bg-indigo-100 overflow-hidden border-4 border-white shadow-xl relative">
                                    @if($user->profile_photo)
                                        <img src="{{ asset('storage/' . $user->profile_photo) }}" id="preview" class="w-full h-full object-cover">
                                    @else
                                        <div id="placeholder" class="w-full h-full flex items-center justify-center text-indigo-400">
                                            <i class="fas fa-user-tie text-5xl"></i>
                                        </div>
                                        <img id="preview" class="w-full h-full object-cover hidden">
                                    @endif
                                </div>
                                <label for="profile_photo" class="absolute -bottom-2 -right-2 w-10 h-10 bg-[#1e1b4b] text-white rounded-xl flex items-center justify-center cursor-pointer hover:bg-indigo-600 transition-all shadow-lg border-2 border-white">
                                    <i class="fas fa-camera text-xs"></i>
                                    <input type="file" name="profile_photo" id="profile_photo" class="hidden" accept="image/*" onchange="previewImage(this)">
                                </label>
                            </div>

                            <div class="flex-1 text-center md:text-left">
                                <h3 class="text-2xl font-black text-[#1e1b4b] mb-1">{{ $user->name }}</h3>
                                <p class="text-xs font-bold text-indigo-500 uppercase tracking-widest mb-4">Kepala Bidang Infrastruktur</p>
                                <div class="flex flex-wrap justify-center md:justify-start gap-3">
                                    <span class="px-4 py-1.5 bg-gray-100 rounded-full text-[10px] font-black text-gray-500 uppercase tracking-widest">Email: {{ $user->email }}</span>
                                    <span class="px-4 py-1.5 bg-emerald-50 rounded-full text-[10px] font-black text-emerald-600 uppercase tracking-widest border border-emerald-100">Status: Aktif</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Informasi Dasar -->
                        <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm">
                            <h4 class="font-black text-[#1e1b4b] mb-6 border-b border-gray-50 pb-4 italic">Informasi Dasar</h4>
                            <div class="space-y-5">
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Nama Lengkap</label>
                                    <input type="text" name="name" value="{{ $user->name }}" class="w-full px-5 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 outline-none transition-all" required>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Alamat Email</label>
                                    <input type="email" name="email" value="{{ $user->email }}" class="w-full px-5 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 outline-none transition-all" required>
                                </div>
                            </div>
                        </div>

                        <!-- Ganti Password -->
                        <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm">
                            <h4 class="font-black text-[#1e1b4b] mb-6 border-b border-gray-50 pb-4 italic">Keamanan Akun</h4>
                            <div class="space-y-5">
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Password Baru (Kosongkan jika tidak ganti)</label>
                                    <input type="password" name="password" placeholder="••••••••" class="w-full px-5 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 outline-none transition-all">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Konfirmasi Password</label>
                                    <input type="password" name="password_confirmation" placeholder="••••••••" class="w-full px-5 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 outline-none transition-all">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit" class="px-10 py-4 bg-[#1e1b4b] text-white rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] shadow-xl shadow-indigo-900/10 hover:bg-indigo-600 hover:-translate-y-1 transition-all">
                            Simpan Perubahan Profil
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        function previewImage(input) {
            const preview = document.getElementById('preview');
            const placeholder = document.getElementById('placeholder');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    if (placeholder) placeholder.classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>
