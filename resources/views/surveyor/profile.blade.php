<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya | Surveyor SINFRA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-gray-50 flex h-screen overflow-hidden text-gray-800 text-left">

    @include('surveyor.partials.sidebar')

    <main class="flex-1 flex flex-col h-screen overflow-y-auto">
        <header class="bg-white border-b border-gray-100 px-8 py-5 flex justify-between items-center z-10">
            <div>
                <p class="text-[10px] font-extrabold text-emerald-600 uppercase tracking-[0.2em] mb-1">Pengaturan Akun</p>
                <h2 class="text-xl font-black text-[#1e1b4b]">Profil Saya</h2>
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
                @if(session('success'))
                <div class="mb-6 px-6 py-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center gap-3 shadow-sm">
                    <i class="fas fa-check-circle"></i>
                    <p class="text-xs font-bold">{{ session('success') }}</p>
                </div>
                @endif

                @if($errors->any())
                <div class="mb-6 px-6 py-4 bg-red-50 border border-red-100 text-red-700 rounded-2xl shadow-sm">
                    <ul class="list-disc list-inside text-xs font-bold">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('surveyor.profile.update') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    @csrf

                    <!-- Foto Profil -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm text-center">
                            <div class="relative w-32 h-32 mx-auto mb-6">
                                <div class="w-full h-full rounded-full bg-emerald-50 border-4 border-white shadow-md overflow-hidden flex items-center justify-center">
                                    @if(auth()->user()->profile_photo)
                                        <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" id="preview-photo" class="w-full h-full object-cover">
                                    @else
                                        <i class="fas fa-camera text-4xl text-emerald-300" id="placeholder-icon"></i>
                                        <img id="preview-photo" class="w-full h-full object-cover hidden">
                                    @endif
                                </div>
                                <label for="profile_photo" class="absolute bottom-0 right-0 w-10 h-10 bg-emerald-600 text-white rounded-full flex items-center justify-center shadow-lg cursor-pointer hover:bg-emerald-700 transition-all border-4 border-white">
                                    <i class="fas fa-camera text-xs"></i>
                                    <input type="file" name="profile_photo" id="profile_photo" class="hidden" accept="image/*" onchange="previewImage(this)">
                                </label>
                            </div>
                            <h4 class="font-black text-[#1e1b4b]">{{ auth()->user()->name }}</h4>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">{{ auth()->user()->role }}</p>
                            
                            <!-- Info Wilayah (Khusus Surveyor) -->
                            <div class="mt-6 pt-6 border-t border-gray-50 space-y-2">
                                <p class="text-[8px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2 text-center">Wilayah Tugas</p>
                                @forelse(auth()->user()->kecamatans as $assignedKec)
                                <div class="flex items-center justify-center gap-2 text-emerald-600 bg-emerald-50 py-1 px-3 rounded-lg">
                                    <i class="fas fa-map-marker-alt text-[10px]"></i>
                                    <span class="text-[9px] font-black uppercase tracking-widest">{{ $assignedKec->nama_kecamatan }}</span>
                                </div>
                                @empty
                                <span class="text-[9px] font-black uppercase tracking-widest text-gray-400">Belum Ditugaskan</span>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Detail Akun -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-[2.5rem] p-10 border border-gray-100 shadow-sm">
                            <div class="mb-8 border-b border-gray-50 pb-5">
                                <h3 class="text-lg font-black text-[#1e1b4b] tracking-tight">Informasi Pribadi</h3>
                                <p class="text-xs text-gray-400 font-medium">Perbarui informasi dasar dan kredensial akun lapangan Anda</p>
                            </div>

                            <div class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest mb-2">Nama Lengkap</label>
                                        <input type="text" name="name" value="{{ auth()->user()->name }}" class="w-full px-5 py-3 bg-gray-50 border border-gray-300 rounded-2xl text-sm font-semibold outline-none focus:border-emerald-500 transition-all" required>
                                    </div>

                                    <div>
                                        <label class="block text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest mb-2">Alamat Email</label>
                                        <input type="email" name="email" value="{{ auth()->user()->email }}" class="w-full px-5 py-3 bg-gray-50 border border-gray-300 rounded-2xl text-sm font-semibold outline-none focus:border-emerald-500 transition-all" required>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest mb-4">Wilayah Tugas (Kecamatan)</label>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 bg-gray-50 p-6 rounded-3xl border border-gray-100 shadow-inner">
                                        @php
                                            $assignedKecamatans = auth()->user()->kecamatans->pluck('id_kecamatan')->toArray();
                                        @endphp
                                        @foreach($semuaKecamatan as $kec)
                                        <label class="relative flex items-center gap-3 p-3 bg-white rounded-xl border border-gray-200 cursor-pointer hover:border-emerald-500 transition-all group">
                                            <input type="checkbox" name="id_kecamatan[]" value="{{ $kec->id_kecamatan }}" 
                                                {{ in_array($kec->id_kecamatan, $assignedKecamatans) ? 'checked' : '' }}
                                                class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500 border-gray-300 transition-all">
                                            <span class="text-xs font-bold text-gray-600 group-hover:text-[#1e1b4b]">{{ $kec->nama_kecamatan }}</span>
                                        </label>
                                        @endforeach
                                    </div>
                                    <p class="text-[9px] text-gray-400 mt-3 italic font-medium px-2">* Anda dapat memilih lebih dari satu kecamatan yang menjadi wilayah tanggung jawab Anda.</p>
                                </div>

                                <div class="pt-4 border-t border-gray-50">
                                    <p class="text-xs font-bold text-gray-400 mb-4 italic">Kosongkan jika tidak ingin mengubah kata sandi</p>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest mb-2">Kata Sandi Baru</label>
                                            <input type="password" name="password" class="w-full px-5 py-3 bg-gray-50 border border-gray-300 rounded-2xl text-sm font-semibold outline-none focus:border-emerald-500 transition-all">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest mb-2">Konfirmasi Sandi</label>
                                            <input type="password" name="password_confirmation" class="w-full px-5 py-3 bg-gray-50 border border-gray-300 rounded-2xl text-sm font-semibold outline-none focus:border-emerald-500 transition-all">
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-6">
                                    <button type="submit" class="w-full bg-[#1e1b4b] text-white py-4 rounded-2xl font-black shadow-xl shadow-indigo-100 hover:bg-emerald-600 transition-all tracking-widest text-xs uppercase">
                                        Simpan Perubahan
                                    </button>
                                </div>
                            </div>
                        </div>
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

        function previewImage(input) {
            const preview = document.getElementById('preview-photo');
            const placeholder = document.getElementById('placeholder-icon');
            
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
