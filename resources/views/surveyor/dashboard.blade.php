<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surveyor Dashboard | GEO-SINFRA</title>
    <link rel="icon" href="{{ asset('logo_geo-sinfra.png') }}" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
                    colors: {
                        navy: { 50:'#f4f4fa', 100:'#e9e9f3', 500:'#6366f1', 800:'#1e1b4b', 900:'#0f0e2c', 950:'#070617' },
                        gold: { 50:'#fdfbf7', 100:'#fbf7ed', 500:'#c5a059', 600:'#b38f4a', 700:'#9d7c3d' }
                    }
                }
            }
        }
    </script>
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <style> 
        body { font-family: 'Plus Jakarta Sans', sans-serif; } 
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>

<style>
    @media (min-width: 768px) { html { font-size: 14px; } }
    @media (max-width: 767px) { html { font-size: 12px; } }
</style>
</head>
<body class="bg-slate-50  flex h-screen overflow-hidden text-slate-800 font-sans   transition-colors duration-300">

    @include('surveyor.partials.sidebar')

    <main class="flex-1 flex flex-col h-screen overflow-hidden">
        
        {{-- ── Header ── --}}
        <header class="bg-white/80  backdrop-blur-xl border-b border-slate-100  sticky top-0 px-4 pl-16 md:px-8 py-4 flex justify-between items-center z-40 shrink-0">
            <div>
                <p class="text-xs font-black text-gold-500 uppercase tracking-[0.2em] mb-1">Surveyor Portal</p>
                <h2 class="text-xl font-black text-navy-900  leading-none">Dashboard Utama</h2>
            </div>
            
            <div class="flex items-center gap-6">
                <div class="text-right">
                    <p class="text-sm font-black text-navy-900" id="mini-clock">00:00 WITA</p>
                    <p class="text-[10px] font-bold text-emerald-500 uppercase mt-0.5 sm:hidden">ONLINE</p>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter hidden sm:block">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
                <div class="h-8 w-[1px] bg-slate-100"></div>
                <a href="{{ route('surveyor.profile') }}" class="flex items-center gap-3 group">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-black text-navy-900 leading-none uppercase group-hover:text-gold-500 transition-colors">{{ auth()->user()->name }}</p>
                        <p class="text-xs font-bold text-emerald-500 uppercase mt-1">ONLINE</p>
                    </div>
                    <div class="w-10 h-10 bg-navy-900 rounded-xl flex items-center justify-center text-gold-500 shadow-md group-hover:shadow-lg transition-all overflow-hidden">
                        @if(auth()->user()->profile_photo)
                            <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-user-circle text-xl"></i>
                        @endif
                    </div>
                </a>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto custom-scrollbar p-4 md:p-8 pb-16">

            @if($kecamatans->isEmpty())
            {{-- Warning: Kecamatan Belum Dipilih --}}
            <div class="bg-orange-50 border border-orange-100 p-6 rounded-3xl mb-8 flex flex-col md:flex-row items-center justify-between shadow-sm">
                <div class="flex items-center gap-5 mb-4 md:mb-0">
                    <div class="w-14 h-14 bg-white  rounded-2xl flex-shrink-0 flex items-center justify-center text-orange-500 shadow-sm border border-orange-100 text-2xl">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-black text-orange-900 uppercase tracking-tight">Wilayah Tugas Belum Ditentukan!</h4>
                        <p class="text-sm text-orange-700 font-medium mt-1">
                            Anda belum memilih wilayah tugas. Harap tentukan wilayah kerja Anda agar laporan dapat diproses.
                        </p>
                    </div>
                </div>
                <button onclick="toggleModal('territoryModal')" class="px-6 py-3 bg-orange-500 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-orange-600 transition-all shadow-md flex items-center gap-2">
                    <i class="fas fa-map-marked-alt"></i> Pilih Wilayah Sekarang
                </button>
            </div>
            @endif

            {{-- Welcome Card --}}
            <div class="relative bg-navy-900 rounded-[2.5rem] p-10 mb-8 overflow-hidden shadow-xl shadow-navy-900/10">
                <div class="absolute -right-10 -top-10 w-64 h-64 bg-gold-500/20 rounded-full blur-3xl pointer-events-none"></div>
                
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                    <div>
                        <h3 class="text-3xl font-black text-white mb-3 leading-tight">Selamat Datang, <span class="text-gold-500">{{ auth()->user()->name }}</span>!</h3>
                        <p class="text-slate-300 text-sm font-medium max-w-xl leading-relaxed">
                            Siap untuk mendata infrastruktur hari ini? Pastikan GPS aktif dan foto yang diambil jelas untuk hasil pemantauan status kondisi yang akurat di lapangan.
                        </p>
                    </div>
                    <a href="{{ route('surveyor.input') }}" class="shrink-0 px-8 py-4 bg-gold-500 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-gold-600 transition-all shadow-lg shadow-gold-500/20 flex items-center gap-3 group">
                        Mulai Survey <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </div>

            {{-- Stats Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-blue-50  p-6 rounded-3xl border border-blue-100  shadow-sm hover:-translate-y-1 transition-transform">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-11 h-11 bg-white  rounded-2xl flex items-center justify-center shadow-sm">
                            <i class="fas fa-file-alt text-blue-500 "></i>
                        </div>
                    </div>
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Total Survey Saya</p>
                    <h3 class="text-3xl font-black text-blue-600 ">{{ $totalSurvey }} <span class="text-xs font-bold text-slate-400">Laporan</span></h3>
                </div>
                
                <div class="bg-orange-50  p-6 rounded-3xl border border-orange-100  shadow-sm hover:-translate-y-1 transition-transform">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-11 h-11 bg-white  text-orange-500  rounded-2xl flex items-center justify-center shadow-sm">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Menunggu Validasi</p>
                    <h3 class="text-3xl font-black text-orange-600 ">{{ $waitingValidation }} <span class="text-xs font-bold text-slate-400">Objek</span></h3>
                </div>
                
                <div class="bg-emerald-50  p-6 rounded-3xl border border-emerald-100  shadow-sm hover:-translate-y-1 transition-transform">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-11 h-11 bg-white  text-emerald-500  rounded-2xl flex items-center justify-center shadow-sm">
                            <i class="fas fa-check-double"></i>
                        </div>
                    </div>
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Terverifikasi AI</p>
                    <h3 class="text-3xl font-black text-emerald-600 ">{{ $verifiedAI }} <span class="text-xs font-bold text-slate-400">Selesai</span></h3>
                </div>
                
                <div class="bg-red-50  p-6 rounded-3xl border border-red-100  shadow-sm hover:-translate-y-1 transition-transform">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-11 h-11 bg-white  text-red-500  rounded-2xl flex items-center justify-center shadow-sm">
                            <i class="fas fa-rotate-left"></i>
                        </div>
                    </div>
                    <p class="text-xs font-black text-red-400 uppercase tracking-widest mb-1">Ditolak / Revisi</p>
                    <h3 class="text-3xl font-black text-red-600 ">{{ $totalRejected }} <span class="text-xs font-bold text-red-400/50">Tindakan</span></h3>
                </div>
            </div>

            {{-- Stats Grid Laporan Warga --}}
            <h4 class="font-black text-lg text-navy-900  mb-4 flex items-center gap-2"><i class="fas fa-clipboard-list text-gold-500"></i> Penugasan Laporan Warga</h4>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
                <a href="{{ route('surveyor.laporan') }}" class="block bg-indigo-50  p-6 rounded-3xl border border-indigo-100  shadow-sm hover:border-indigo-500/50 transition-all group hover:-translate-y-1">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-white  rounded-2xl flex items-center justify-center text-indigo-500 group-hover:bg-indigo-500 group-hover:text-white transition-colors shadow-sm">
                            <i class="fas fa-tasks text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Total Tugas</p>
                            <h3 class="text-2xl font-black text-indigo-600 ">{{ $totalTugas }}</h3>
                        </div>
                    </div>
                </a>
                
                <a href="{{ route('surveyor.laporan', ['status' => 'Menunggu']) }}" class="block bg-orange-50  p-6 rounded-3xl border border-orange-100  shadow-sm hover:border-orange-500/50 transition-all group hover:-translate-y-1">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-white  rounded-2xl flex items-center justify-center text-orange-500 group-hover:bg-orange-500 group-hover:text-white transition-colors shadow-sm">
                            <i class="fas fa-clock text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Tugas Menunggu</p>
                            <h3 class="text-2xl font-black text-orange-600 ">{{ $tugasMenunggu }}</h3>
                        </div>
                    </div>
                </a>
                
                <a href="{{ route('surveyor.laporan', ['status' => 'Selesai']) }}" class="block bg-emerald-50  p-6 rounded-3xl border border-emerald-100  shadow-sm hover:border-emerald-500/50 transition-all group hover:-translate-y-1">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-white  rounded-2xl flex items-center justify-center text-emerald-500 group-hover:bg-emerald-500 group-hover:text-white transition-colors shadow-sm">
                            <i class="fas fa-check-circle text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Tugas Selesai</p>
                            <h3 class="text-2xl font-black text-emerald-600 ">{{ $tugasSelesai }}</h3>
                        </div>
                    </div>
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                {{-- Panduan Survey --}}
                <div class="bg-white  rounded-[2.5rem] p-8 border border-slate-100  shadow-sm">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-8 h-8 bg-navy-900 rounded-xl flex items-center justify-center text-gold-500 shrink-0">
                            <i class="fas fa-book-open text-xs"></i>
                        </div>
                        <h4 class="font-black text-lg text-navy-900 ">Panduan Survey Cepat</h4>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-start gap-4 p-4 bg-slate-50  rounded-2xl border border-slate-100  group hover:border-gold-500/30 transition-colors">
                            <div class="w-8 h-8 bg-white  rounded-lg flex items-center justify-center text-gold-500 font-black shadow-sm shrink-0 group-hover:bg-gold-500 group-hover:text-white transition-colors">1</div>
                            <div>
                                <p class="text-xs font-black text-navy-900  uppercase">Pilih Detail Infrastruktur</p>
                                <p class="text-xs text-slate-500 mt-1 font-medium">Pastikan seluruh form mulai dari Jenis hingga Material Utama sesuai kondisi lapangan.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4 p-4 bg-slate-50  rounded-2xl border border-slate-100  group hover:border-gold-500/30 transition-colors">
                            <div class="w-8 h-8 bg-white  rounded-lg flex items-center justify-center text-gold-500 font-black shadow-sm shrink-0 group-hover:bg-gold-500 group-hover:text-white transition-colors">2</div>
                            <div>
                                <p class="text-xs font-black text-navy-900  uppercase">Ambil Foto Fokus</p>
                                <p class="text-xs text-slate-500 mt-1 font-medium">AI membutuhkan foto yang jelas dan terpusat pada area yang rusak untuk akurasi.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4 p-4 bg-slate-50  rounded-2xl border border-slate-100  group hover:border-gold-500/30 transition-colors">
                            <div class="w-8 h-8 bg-white  rounded-lg flex items-center justify-center text-gold-500 font-black shadow-sm shrink-0 group-hover:bg-gold-500 group-hover:text-white transition-colors">3</div>
                            <div>
                                <p class="text-xs font-black text-navy-900  uppercase">Aktifkan GPS</p>
                                <p class="text-xs text-slate-500 mt-1 font-medium">Koordinat akan terisi otomatis jika GPS HP Anda aktif saat form dibuka.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    {{-- Kondisi Cuaca Lapangan --}}
                    <div class="bg-gradient-to-br from-[#0f0e2c] to-navy-900 rounded-[2.5rem] p-8 text-white relative overflow-hidden shadow-lg shadow-navy-900/10 border border-white/5">
                        <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
                        <div class="relative z-10 flex items-center justify-between gap-4">
                            <div>
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="px-2 py-0.5 bg-red-500/20 text-red-400 border border-red-500/30 rounded text-xs font-black uppercase tracking-widest">Waspada Banjir</span>
                                </div>
                                <h4 class="font-black text-xl leading-none mb-1">Hujan Lebat</h4>
                                <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">Banjarmasin, 28°C</p>
                            </div>
                            <div class="w-16 h-16 bg-white/10  backdrop-blur-md rounded-2xl flex items-center justify-center text-blue-400 shadow-inner border border-white/10 shrink-0">
                                <i class="fas fa-cloud-showers-heavy text-3xl"></i>
                            </div>
                        </div>
                        <div class="relative z-10 mt-6 pt-4 border-t border-white/10">
                            <p class="text-xs text-slate-300 font-medium leading-relaxed">
                                <strong class="text-white">Peringatan Lapangan:</strong> Hati-hati saat mengambil foto di area genangan. Pastikan kamera fokus pada titik kerusakan drainase atau aspal yang terendam.
                            </p>
                        </div>
                    </div>

                    {{-- Info Wilayah Tugas --}}
                    <div class="bg-navy-900 rounded-[2.5rem] p-8 text-white relative overflow-hidden shadow-lg shadow-navy-900/10">
                        <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-gold-500/10 rounded-full blur-3xl"></div>
                        <div class="relative z-10">
                            <div class="flex justify-between items-start mb-8">
                                <div>
                                    <h4 class="font-black text-lg mb-1">Wilayah Tugas Anda</h4>
                                    <p class="text-slate-400 text-xs uppercase tracking-widest font-bold">Kecamatan Tanggung Jawab</p>
                                </div>
                                <button onclick="toggleModal('territoryModal')" class="px-4 py-2 bg-white/5  hover:bg-white/10  text-gold-500 border border-white/10 rounded-xl text-xs font-black uppercase tracking-widest transition-all backdrop-blur-md">
                                    <i class="fas fa-edit mr-2"></i> Kelola
                                </button>
                            </div>
                            
                            <div class="grid grid-cols-1 gap-3">
                                @forelse($kecamatans as $assignedKec)
                                <div class="bg-white/5  border border-white/10 rounded-2xl p-4 backdrop-blur-md flex items-center gap-4 transition-transform hover:-translate-y-1 cursor-default">
                                    <div class="w-10 h-10 bg-gold-500 rounded-xl flex items-center justify-center text-white shadow-md shadow-gold-500/20">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <h5 class="text-sm font-black text-gold-500 uppercase tracking-wider">{{ $assignedKec->nama_kecamatan }}</h5>
                                </div>
                                @empty
                                <div class="bg-white/5  border border-white/10 rounded-2xl p-6 backdrop-blur-md text-center">
                                    <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">Belum ada wilayah tugas.</p>
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    {{-- Upload Terbaru --}}
                    <div class="bg-white  rounded-[2.5rem] p-8 border border-slate-100  shadow-sm">
                        <div class="flex justify-between items-center mb-6">
                            <h4 class="font-black text-lg text-navy-900 ">Upload Terbaru</h4>
                            <a href="{{ route('surveyor.history') }}" class="text-xs font-black text-gold-500 uppercase tracking-widest hover:text-gold-600 transition-colors">Semua Riwayat</a>
                        </div>
                        <div class="space-y-4">
                            @forelse($recentUploads as $upload)
                            <div class="flex items-center gap-4 p-3 hover:bg-slate-50   rounded-2xl border border-transparent hover:border-slate-100  transition-all group cursor-pointer">
                                <div class="w-12 h-12 rounded-xl bg-slate-100 overflow-hidden flex-shrink-0 relative">
                                    <img src="{{ asset('storage/' . $upload->foto_terbaru) }}" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-navy-900/10 group-hover:bg-transparent transition-colors"></div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-black text-navy-900  truncate uppercase">{{ $upload->nama_infrastruktur }}</p>
                                    <p class="text-xs text-slate-400 font-bold uppercase">{{ $upload->created_at->diffForHumans() }}</p>
                                </div>
                                @if($upload->status_verifikasi == 'Verified')
                                    <div class="px-2 py-1 bg-emerald-50  text-emerald-600  border border-emerald-100  rounded-lg text-xs font-black uppercase tracking-wider">Verified</div>
                                @else
                                    <div class="px-2 py-1 bg-orange-50  text-orange-600  border border-orange-100  rounded-lg text-xs font-black uppercase tracking-wider">Pending</div>
                                @endif
                            </div>
                            @empty
                            <p class="text-xs text-slate-400 font-bold text-center py-6 uppercase tracking-wider">Belum ada data diunggah.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    {{-- Modal Kelola Wilayah --}}
    <div id="territoryModal" class="fixed inset-0 z-[60] hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-navy-900/60 backdrop-blur-sm transition-opacity" onclick="toggleModal('territoryModal')"></div>
            
            <div class="relative bg-white  rounded-[2.5rem] w-full max-w-2xl p-8 shadow-2xl transition-all scale-95 opacity-0 duration-300" id="modalContent">
                <div class="flex justify-between items-center mb-8 pb-4 border-b border-slate-100 ">
                    <div>
                        <h3 class="text-xl font-black text-navy-900 ">Kelola Wilayah Tugas</h3>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-1">Pilih kecamatan yang menjadi tanggung jawab Anda</p>
                    </div>
                    <button onclick="toggleModal('territoryModal')" class="w-10 h-10 flex items-center justify-center bg-slate-50  text-slate-400 rounded-xl hover:bg-red-50 hover:text-red-500 transition-all">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form action="{{ route('surveyor.territory.update') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                        @php $assignedIds = $kecamatans->pluck('id_kecamatan')->toArray(); @endphp
                        @foreach($semuaKecamatan as $kec)
                        <label class="relative flex items-center gap-3 p-4 bg-slate-50  rounded-2xl border border-slate-100  cursor-pointer hover:bg-gold-50 hover:border-gold-200 transition-all group">
                            <input type="checkbox" name="id_kecamatan[]" value="{{ $kec->id_kecamatan }}" 
                                {{ in_array($kec->id_kecamatan, $assignedIds) ? 'checked' : '' }}
                                class="w-5 h-5 rounded-lg text-gold-500 focus:ring-gold-500 border-slate-300 transition-all cursor-pointer">
                            <span class="text-xs font-bold text-slate-600  group-hover:text-navy-900  uppercase">{{ $kec->nama_kecamatan }}</span>
                        </label>
                        @endforeach
                    </div>

                    <div class="flex gap-3">
                        <button type="button" onclick="toggleModal('territoryModal')" class="flex-1 py-4 bg-slate-100 text-slate-500 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-slate-200 transition-all">
                            Batal
                        </button>
                        <button type="submit" class="flex-[2] py-4 bg-navy-900 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-gold-500 transition-all shadow-xl shadow-navy-900/10">
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
            const options = { timeZone: 'Asia/Makassar', hour: '2-digit', minute: '2-digit', hour12: false };
            const timeString = new Intl.DateTimeFormat('id-ID', options).format(now);
            const el = document.getElementById('mini-clock');
            if (el) el.textContent = timeString.replace('.', ':') + ' WITA';
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
