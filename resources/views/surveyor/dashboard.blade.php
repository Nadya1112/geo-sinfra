<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surveyor Dashboard | GEO-SINFRA</title>
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
                <p class="text-[10px] font-extrabold text-emerald-600 uppercase tracking-[0.2em] mb-1">Surveyor Portal</p>
                <h2 class="text-xl font-black text-[#1e1b4b]">Dashboard Utama</h2>
            </div>
            
            <div class="flex items-center gap-6">
                <div class="text-right hidden sm:block">
                    <p class="text-[11px] font-black text-[#1e1b4b]" id="mini-clock">00:00 WITA</p>
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
                <div class="h-8 w-[1px] bg-gray-100"></div>
                <a href="{{ route('surveyor.profile') }}" class="flex items-center gap-3">
                    <div class="text-right">
                        <p class="text-[11px] font-black text-[#1e1b4b] leading-none uppercase">{{ auth()->user()->name }}</p>
                        <p class="text-[9px] font-bold text-emerald-500 uppercase mt-1">ONLINE</p>
                    </div>
                    <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 border border-emerald-100 overflow-hidden">
                        @if(auth()->user()->profile_photo)
                            <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-user-circle text-xl"></i>
                        @endif
                    </div>
                </a>
            </div>
        </header>

            @if(auth()->user()->kecamatans->isEmpty())
            <!-- Warning: Kecamatan Belum Dipilih -->
            <div class="bg-amber-50 border-l-4 border-amber-500 p-6 rounded-[2.5rem] mb-8 flex flex-col md:flex-row items-center justify-between shadow-sm shadow-amber-900/5 border border-amber-100">
                <div class="flex items-center gap-5 mb-4 md:mb-0 text-left">
                    <div class="w-14 h-14 bg-white rounded-2xl flex-shrink-0 flex items-center justify-center text-amber-500 shadow-sm border border-amber-100 text-2xl">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-black text-amber-900 uppercase tracking-tight">Wilayah Tugas Belum Ditentukan!</h4>
                        <p class="text-xs text-amber-700 font-medium mt-1">
                            Anda belum memilih wilayah tugas. Harap tentukan wilayah kerja Anda agar laporan dapat diproses dan disaring dengan tepat.
                        </p>
                    </div>
                </div>
                <button onclick="toggleModal('territoryModal')" class="px-6 py-3 bg-amber-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-amber-700 transition-all shadow-lg shadow-amber-900/10 flex items-center gap-2">
                    <i class="fas fa-map-marked-alt"></i> Pilih Wilayah Sekarang
                </button>
            </div>
            @endif

            <!-- Welcome Card -->
            <div id="v2-welcome-card" class="relative bg-gradient-to-br from-emerald-700 to-teal-900 rounded-[2.5rem] p-10 mb-8 overflow-hidden shadow-lg shadow-emerald-900/10">
                <div class="absolute -right-10 -top-10 w-64 h-64 bg-white opacity-5 rounded-full blur-3xl"></div>
                <div class="relative z-10">
                    <h3 class="text-3xl font-black text-white mb-4 leading-tight">Selamat Datang, {{ auth()->user()->name }}!</h3>
                    <p class="text-white text-sm font-semibold max-w-2xl leading-relaxed mb-8 opacity-95">
                        Mari mulai mendata infrastruktur hari ini untuk membantu pemetaan aset daerah yang lebih akurat. Pastikan GPS aktif dan foto yang diambil jelas untuk hasil analisis AI yang maksimal.
                    </p>
                    <div class="flex gap-4">
                        <a href="{{ route('surveyor.input') }}" class="px-8 py-4 bg-white text-emerald-800 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-emerald-50 transition-all inline-block shadow-2xl">
                            <i class="fas fa-camera mr-2"></i> Mulai Survey Sekarang
                        </a>
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                    <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mb-4"><i class="fas fa-file-alt"></i></div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Survey Saya</p>
                    <h3 class="text-2xl font-black text-[#1e1b4b]">{{ $totalSurvey }} <span class="text-xs font-medium text-gray-400">Laporan</span></h3>
                </div>
                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                    <div class="w-10 h-10 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center mb-4"><i class="fas fa-clock"></i></div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Menunggu Validasi</p>
                    <h3 class="text-2xl font-black text-[#1e1b4b]">{{ $waitingValidation }} <span class="text-xs font-medium text-gray-400">Objek</span></h3>
                </div>
                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                    <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center mb-4"><i class="fas fa-check-double"></i></div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Terverifikasi AI</p>
                    <h3 class="text-2xl font-black text-[#1e1b4b]">{{ $verifiedAI }} <span class="text-xs font-medium text-gray-400">Selesai</span></h3>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Panduan Survey -->
                <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm">
                    <h4 class="font-black text-lg text-[#1e1b4b] mb-6">Panduan Survey Cepat</h4>
                    <div class="space-y-4">
                        <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-2xl border border-gray-100">
                            <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center text-emerald-600 font-bold shadow-sm">1</div>
                            <div>
                                <p class="text-xs font-bold text-[#1e1b4b]">Pilih Jenis Infrastruktur</p>
                                <p class="text-[10px] text-gray-500 mt-1">Pastikan kategori objek sesuai (Jalan, Jembatan, atau Drainase).</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-2xl border border-gray-100">
                            <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center text-emerald-600 font-bold shadow-sm">2</div>
                            <div>
                                <p class="text-xs font-bold text-[#1e1b4b]">Ambil Foto Fokus</p>
                                <p class="text-[10px] text-gray-500 mt-1">AI membutuhkan foto yang jelas dan terpusat pada area yang rusak.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-2xl border border-gray-100">
                            <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center text-emerald-600 font-bold shadow-sm">3</div>
                            <div>
                                <p class="text-xs font-bold text-[#1e1b4b]">Aktifkan GPS</p>
                                <p class="text-[10px] text-gray-500 mt-1">Koordinat akan terisi otomatis jika GPS HP Anda aktif saat memotret.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Info Wilayah Tugas -->
                <div class="space-y-6">
                    <div class="bg-[#1e1b4b] rounded-[2.5rem] p-8 text-white relative overflow-hidden shadow-2xl shadow-indigo-900/40">
                        <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-blue-600 opacity-10 rounded-full blur-3xl"></div>
                        <div class="relative z-10">
                            <div class="flex justify-between items-start mb-8">
                                <div>
                                    <h4 class="font-black text-lg mb-1">Wilayah Tugas Anda</h4>
                                    <p class="text-blue-200 text-[9px] uppercase tracking-widest font-bold">Kecamatan Tanggung Jawab</p>
                                </div>
                                <button onclick="toggleModal('territoryModal')" class="px-4 py-2 bg-white/10 hover:bg-white/20 border border-white/20 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all backdrop-blur-md">
                                    <i class="fas fa-edit mr-2"></i> Kelola
                                </button>
                            </div>
                            
                            <div class="grid grid-cols-1 gap-3">
                                @forelse(auth()->user()->kecamatans as $assignedKec)
                                <div class="bg-white/5 border border-white/10 rounded-2xl p-4 backdrop-blur-md flex items-center gap-4">
                                    <div class="w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center text-lg shadow-lg shadow-emerald-500/20">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <h5 class="text-md font-black">{{ $assignedKec->nama_kecamatan }}</h5>
                                </div>
                                @empty
                                <div class="bg-white/5 border border-white/10 rounded-2xl p-6 backdrop-blur-md text-center">
                                    <p class="text-xs text-blue-200 italic">Belum ada wilayah tugas yang dipilih.</p>
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm">
                        <div class="flex justify-between items-center mb-6">
                            <h4 class="font-black text-lg text-[#1e1b4b]">Upload Terbaru</h4>
                            <a href="{{ route('surveyor.history') }}" class="text-[10px] font-black text-emerald-600 uppercase tracking-widest hover:underline">Semua</a>
                        </div>
                        <div class="space-y-4">
                            @forelse($recentUploads as $upload)
                            <div class="flex items-center gap-4 p-3 hover:bg-gray-50 rounded-2xl transition-all">
                                <div class="w-12 h-12 rounded-xl bg-gray-100 overflow-hidden flex-shrink-0">
                                    <img src="{{ asset('storage/' . $upload->foto_terbaru) }}" class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-black text-[#1e1b4b] truncate uppercase">{{ $upload->nama_infrastruktur }}</p>
                                    <p class="text-[9px] text-gray-400 font-bold uppercase">{{ $upload->created_at->diffForHumans() }}</p>
                                </div>
                                <div class="w-2 h-2 rounded-full {{ $upload->status_verifikasi == 'Verified' ? 'bg-emerald-500' : 'bg-amber-500' }}"></div>
                            </div>
                            @empty
                            <p class="text-xs text-gray-400 italic text-center py-4">Belum ada data diunggah.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal Kelola Wilayah -->
    <div id="territoryModal" class="fixed inset-0 z-[60] hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-[#1e1b4b]/60 backdrop-blur-sm transition-opacity" onclick="toggleModal('territoryModal')"></div>
            
            <div class="relative bg-white rounded-[2.5rem] w-full max-w-2xl p-8 shadow-2xl transition-all scale-95 opacity-0 duration-300" id="modalContent">
                <div class="flex justify-between items-center mb-8 pb-4 border-b border-gray-50">
                    <div>
                        <h3 class="text-xl font-black text-[#1e1b4b]">Kelola Wilayah Tugas</h3>
                        <p class="text-xs text-gray-400 font-medium">Pilih satu atau lebih kecamatan yang menjadi tanggung jawab Anda</p>
                    </div>
                    <button onclick="toggleModal('territoryModal')" class="w-10 h-10 flex items-center justify-center bg-gray-50 text-gray-400 rounded-xl hover:bg-red-50 hover:text-red-500 transition-all">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form action="{{ route('surveyor.territory.update') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                        @php $assignedIds = auth()->user()->kecamatans->pluck('id_kecamatan')->toArray(); @endphp
                        @foreach($semuaKecamatan as $kec)
                        <label class="relative flex items-center gap-3 p-4 bg-gray-50 rounded-2xl border border-transparent cursor-pointer hover:bg-emerald-50 hover:border-emerald-200 transition-all group">
                            <input type="checkbox" name="id_kecamatan[]" value="{{ $kec->id_kecamatan }}" 
                                {{ in_array($kec->id_kecamatan, $assignedIds) ? 'checked' : '' }}
                                class="w-5 h-5 rounded-lg text-emerald-600 focus:ring-emerald-500 border-gray-300 transition-all cursor-pointer">
                            <span class="text-sm font-bold text-gray-700 group-hover:text-[#1e1b4b]">{{ $kec->nama_kecamatan }}</span>
                        </label>
                        @endforeach
                    </div>

                    <div class="flex gap-3">
                        <button type="button" onclick="toggleModal('territoryModal')" class="flex-1 py-4 bg-gray-100 text-gray-600 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-gray-200 transition-all">
                            Batal
                        </button>
                        <button type="submit" class="flex-[2] py-4 bg-[#1e1b4b] text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-emerald-600 transition-all shadow-xl shadow-indigo-100">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function updateClock() {
            const now = new Date();
            document.getElementById('mini-clock').textContent = `${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')} WITA`;
        }
        setInterval(updateClock, 1000); updateClock();

        function toggleModal(id) {
            const modal = document.getElementById(id);
            const content = document.getElementById('modalContent');
            if (modal.classList.contains('hidden')) {
                modal.classList.remove('hidden');
                setTimeout(() => {
                    content.classList.remove('scale-95', 'opacity-0');
                    content.classList.add('scale-100', 'opacity-100');
                }, 10);
            } else {
                content.classList.remove('scale-100', 'opacity-100');
                content.classList.add('scale-95', 'opacity-0');
                setTimeout(() => {
                    modal.classList.add('hidden');
                }, 300);
            }
        }
    </script>
</body>
</html>
</body>
</html>
