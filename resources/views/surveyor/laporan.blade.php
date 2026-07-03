<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penugasan Lapangan | Surveyor SINFRA</title>
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
                        navy: { 50:'#f4f4fa', 100:'#e9e9f3', 200:'#c7c8e3', 500:'#6366f1', 800:'#1e1b4b', 900:'#0f0e2c', 950:'#070617' },
                        gold: { 50:'#fdfbf7', 100:'#fbf7ed', 500:'#c5a059', 600:'#b38f4a', 700:'#9d7c3d' }
                    }
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; height: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>

<style>
    @media (min-width: 768px) { html { font-size: 14px; } }
    @media (max-width: 767px) { html { font-size: 12px; } }
</style>
</head>
<body class="bg-slate-50  flex h-screen overflow-hidden text-slate-800 text-left font-sans   transition-colors duration-300">

    @include('surveyor.partials.sidebar')

    <main class="flex-1 flex flex-col h-screen overflow-hidden text-left font-sans relative">
        <header class="bg-white/85  backdrop-blur-xl border-b border-slate-100  sticky top-0 px-4 pl-16 md:px-8 py-4 flex justify-between items-center z-40 text-left">
            <div class="flex items-center gap-4">
                <a href="{{ route('surveyor.dashboard') }}"
                   class="hidden md:flex w-10 h-10 bg-white  border border-slate-100  rounded-xl  items-center justify-center text-slate-400 hover:text-gold-500 hover:border-gold-500/20 hover:shadow-lg hover:shadow-gold-500/5 transition-all group"
                   title="Kembali ke Dashboard Utama">
                    <i class="fas fa-arrow-left text-xs group-hover:-translate-x-1 transition-transform"></i>
                </a>
                <div class="text-left">
                    <p class="text-xs font-black text-gold-500 uppercase tracking-[0.2em] mb-1">Surveyor Portal</p>
                    <h2 class="text-xl font-black text-navy-900  leading-none">Penugasan Laporan Warga</h2>
                </div>
            </div>

            <div class="flex items-center gap-3 md:gap-6">
                <div class="text-right">
                    <p class="text-[10px] md:text-sm font-black text-navy-900 mt-1" id="mini-clock">00:00 WITA</p>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter hidden md:block">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
                <div class="h-6 md:h-8 w-[1px] bg-slate-200"></div>
                <div class="flex items-center gap-2 md:gap-3">
                    <a href="{{ route('surveyor.profile') }}" class="text-right group">
                        <p class="text-xs md:text-sm font-black text-navy-900 leading-none uppercase group-hover:text-gold-500 transition-all max-w-[200px] truncate hidden md:block">{{ auth()->user()->name }}</p>
                        <p class="text-[8px] md:text-xs font-bold text-emerald-500 uppercase md:mt-0.5">Online</p>
                    </a>
                    <a href="{{ route('surveyor.profile') }}" class="w-8 h-8 md:w-10 md:h-10 bg-navy-900 rounded-xl flex items-center justify-center text-gold-500 border border-white/10 overflow-hidden hover:shadow-lg hover:shadow-navy-950/20 transition-all shadow-md shrink-0">
                        @if(auth()->user()->profile_photo)
                            <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-user-circle text-lg md:text-xl"></i>
                        @endif
                    </a>
                </div>
            </div>
        </header>

        <!-- Content -->
        <div class="flex-1 overflow-y-auto custom-scrollbar p-6 md:p-8 bg-slate-50 ">
            <div class="max-w-7xl mx-auto space-y-6">

                @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-6 py-4 rounded-2xl mb-6 flex items-center gap-4 shadow-sm animate-fade-in">
                    <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center shrink-0">
                        <i class="fas fa-check text-emerald-600"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-bold text-sm">{{ session('success') }}</p>
                    </div>
                </div>
                @endif

                {{-- ── Toolbar: Judul + Filter + Ekspor ── --}}
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-2">
                    <div>
                        <h4 class="font-extrabold text-lg text-navy-900 ">Daftar Penugasan Laporan Warga</h4>
                        <p class="text-xs text-slate-400 font-semibold mt-0.5">Tinjau lokasi laporan di lapangan dan ubah status laporan</p>
                    </div>
                </div>

                <!-- Filters & Search -->
                <div class="bg-white  p-5 rounded-[2rem] shadow-sm border border-slate-100  flex flex-col md:flex-row gap-4 items-center justify-between mb-4">
                    <form method="GET" action="{{ route('surveyor.laporan') }}" class="flex flex-col md:flex-row gap-3 w-full">
                        <div class="flex-1 relative">
                            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama pelapor, deskripsi, atau no HP..." 
                                   class="w-full pl-11 pr-4 py-2.5 bg-slate-50  border border-slate-200  rounded-xl text-sm focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all font-medium">
                        </div>
                        <div class="w-full md:w-48 relative">
                            <select name="status" onchange="this.form.submit()" class="w-full pl-4 pr-10 py-2.5 bg-slate-50  border border-slate-200  rounded-xl text-sm font-bold text-navy-900  focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 appearance-none">
                                <option value="all" {{ ($status ?? 'all') == 'all' ? 'selected' : '' }}>Semua Status</option>
                                <option value="Menunggu" {{ ($status ?? '') == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                                <option value="Ditinjau" {{ ($status ?? '') == 'Ditinjau' ? 'selected' : '' }}>Ditinjau</option>
                                <option value="Diproses" {{ ($status ?? '') == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                                <option value="Selesai" {{ ($status ?? '') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="Ditolak" {{ ($status ?? '') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                        </div>
                        @if($search || ($status && $status !== 'all'))
                        <a href="{{ route('surveyor.laporan') }}" class="px-5 py-2.5 bg-red-50 text-red-600 font-bold text-xs uppercase tracking-widest rounded-xl hover:bg-red-100 transition-all text-center flex items-center justify-center shrink-0">
                            Reset
                        </a>
                        @endif
                        <button type="submit" class="px-6 py-2.5 bg-navy-900 text-white font-bold text-xs uppercase tracking-widest rounded-xl hover:bg-navy-800 transition-all shadow-md flex items-center justify-center shrink-0">
                            Cari
                        </button>
                    </form>
                </div>

                <!-- Table Container -->
                <div class="bg-white  rounded-[2rem] shadow-xl shadow-slate-200/40 border border-slate-100  overflow-hidden">
                    <div class="overflow-x-auto custom-scrollbar">
                        <table class="w-full text-left text-sm whitespace-nowrap md:whitespace-normal">
                            <thead class="bg-gradient-to-r from-navy-900 to-navy-800 border-b border-navy-800 shadow-md">
                                <tr>
                                    <th class="px-6 py-4 font-extrabold uppercase tracking-widest text-xs text-gold-500">Waktu Lapor</th>
                                    <th class="px-6 py-4 font-extrabold uppercase tracking-widest text-xs text-gold-500">Pelapor</th>
                                    <th class="px-6 py-4 font-extrabold uppercase tracking-widest text-xs text-gold-500">Laporan Kerusakan</th>
                                    <th class="px-5 py-4 font-extrabold uppercase tracking-widest text-xs text-gold-500 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($laporanWarga as $laporan)
                                <tr class="hover:bg-slate-50   transition-colors group">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <i class="far fa-clock text-slate-400"></i>
                                            <div>
                                                <p class="font-bold text-navy-900 ">{{ \Carbon\Carbon::parse($laporan->created_at)->format('d M Y') }}</p>
                                                <p class="text-xs text-slate-500 font-medium">{{ \Carbon\Carbon::parse($laporan->created_at)->format('H:i') }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="font-bold text-navy-900 ">{{ $laporan->nama_pelapor }}</p>
                                        <p class="text-xs font-semibold text-slate-500 mt-0.5"><i class="fas fa-phone-alt text-xs text-slate-400 mr-1"></i> {{ $laporan->no_hp }}</p>
                                    </td>
                                    <td class="px-6 py-4 min-w-[250px]">
                                        <p class="text-sm font-medium text-slate-700  line-clamp-2 leading-relaxed mb-2">{{ $laporan->deskripsi }}</p>
                                        
                                        @if($laporan->label_ai)
                                            @php
                                                $aiColor = 'bg-slate-100 text-slate-600   border-slate-200 ';
                                                $aiIcon = 'fa-robot';
                                                if(str_contains(strtolower($laporan->label_ai), 'berat')) {
                                                    $aiColor = 'bg-red-50 text-red-600   border-red-200 ';
                                                    $aiIcon = 'fa-exclamation-triangle';
                                                } elseif(str_contains(strtolower($laporan->label_ai), 'sedang')) {
                                                    $aiColor = 'bg-yellow-50 text-yellow-600   border-yellow-200 ';
                                                    $aiIcon = 'fa-exclamation-circle';
                                                } elseif(str_contains(strtolower($laporan->label_ai), 'baik')) {
                                                    $aiColor = 'bg-emerald-50 text-emerald-600   border-emerald-200 ';
                                                    $aiIcon = 'fa-check-circle';
                                                }
                                                $skorPercent = $laporan->skor_ai ? round($laporan->skor_ai * 100) . '%' : '';
                                            @endphp
                                            <div class="inline-flex items-center gap-1.5 px-2 py-1 mb-3 rounded border {{ $aiColor }} text-xs font-bold tracking-wider">
                                                <i class="fas {{ $aiIcon }}"></i> AI: {{ $laporan->label_ai }} {{ $skorPercent ? "($skorPercent)" : '' }}
                                            </div>
                                        @endif
                                        
                                        <div class="flex gap-2">
                                            @if($laporan->foto)
                                                <button onclick="showPhotoModal('{{ asset('storage/' . $laporan->foto) }}')" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg text-xs font-bold uppercase tracking-wider transition-colors">
                                                    <i class="fas fa-image"></i> Lihat Foto
                                                </button>
                                            @endif
                                            
                                            <a href="https://www.google.com/maps/search/?api=1&query={{ $laporan->latitude }},{{ $laporan->longitude }}" target="_blank" 
                                               class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 text-emerald-600 hover:bg-emerald-100 rounded-lg text-xs font-bold uppercase tracking-wider transition-colors">
                                                <i class="fas fa-map-marker-alt"></i> Cek Lokasi
                                            </a>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 text-center">
                                        <form action="{{ route('surveyor.laporan.status', $laporan->id) }}" method="POST" class="inline-block relative w-36 text-left">
                                            @csrf
                                            @method('PUT')
                                            
                                            @php
                                                $statusColor = 'bg-slate-100 text-slate-700   border-slate-200 ';
                                                if($laporan->status == 'Menunggu') $statusColor = 'bg-yellow-50 text-yellow-700   border-yellow-200 ';
                                                if($laporan->status == 'Ditinjau') $statusColor = 'bg-blue-50 text-blue-700   border-blue-200 ';
                                                if($laporan->status == 'Diproses') $statusColor = 'bg-indigo-50 text-indigo-700   border-indigo-200 ';
                                                if($laporan->status == 'Selesai') $statusColor = 'bg-emerald-50 text-emerald-700   border-emerald-200 ';
                                                if($laporan->status == 'Ditolak') $statusColor = 'bg-red-50 text-red-700   border-red-200 ';
                                            @endphp
                                            
                                            <select name="status" onchange="this.form.submit()" class="w-full appearance-none pl-3 pr-8 py-1.5 rounded-lg text-xs font-bold border {{ $statusColor }} focus:outline-none focus:ring-2 focus:ring-navy-500 cursor-pointer shadow-sm">
                                                <option value="Menunggu" {{ $laporan->status == 'Menunggu' ? 'selected' : '' }}>⏳ Menunggu</option>
                                                <option value="Ditinjau" {{ $laporan->status == 'Ditinjau' ? 'selected' : '' }}>👀 Ditinjau</option>
                                                <option value="Diproses" {{ $laporan->status == 'Diproses' ? 'selected' : '' }}>⚙️ Diproses</option>
                                                <option value="Selesai" {{ $laporan->status == 'Selesai' ? 'selected' : '' }}>✅ Selesai</option>
                                                <option value="Ditolak" {{ $laporan->status == 'Ditolak' ? 'selected' : '' }}>❌ Ditolak</option>
                                            </select>
                                            <i class="fas fa-chevron-down absolute right-2.5 top-1/2 -translate-y-1/2 text-xs opacity-60 pointer-events-none"></i>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-8 py-20 text-center">
                                        <i class="fas fa-file-alt text-4xl text-slate-200 mb-4 block"></i>
                                        <p class="text-slate-400 font-bold text-sm">Belum Ada Laporan Warga.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($laporanWarga->hasPages())
                    <div class="p-6 border-t border-slate-100  bg-slate-50 ">
                        {{ $laporanWarga->links() }}
                    </div>
                    @endif
                </div>

            </div>
        </div>
    </main>

    <!-- Modal Foto -->
    <div id="photoModal" class="fixed inset-0 bg-navy-950/90 backdrop-blur-sm z-[9999] hidden opacity-0 transition-opacity duration-300 flex items-center justify-center p-4">
        <button onclick="closePhotoModal()" class="absolute top-6 right-6 w-12 h-12 bg-white/10  hover:bg-white/20  text-white rounded-full flex items-center justify-center transition-colors">
            <i class="fas fa-times text-xl"></i>
        </button>
        <div class="max-w-4xl w-full max-h-[90vh] relative transform scale-95 transition-transform duration-300" id="photoModalContent">
            <img id="modalImage" src="" alt="Foto Laporan" class="w-full h-full object-contain rounded-xl shadow-2xl">
        </div>
    </div>

    <script>
        function showPhotoModal(src) {
            const modal = document.getElementById('photoModal');
            const modalContent = document.getElementById('photoModalContent');
            const img = document.getElementById('modalImage');
            
            img.src = src;
            modal.classList.remove('hidden');
            
            // Trigger animation
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modalContent.classList.remove('scale-95');
                modalContent.classList.add('scale-100');
            }, 10);
            
            document.body.style.overflow = 'hidden';
        }

        function closePhotoModal() {
            const modal = document.getElementById('photoModal');
            const modalContent = document.getElementById('photoModalContent');
            
            modal.classList.add('opacity-0');
            modalContent.classList.remove('scale-100');
            modalContent.classList.add('scale-95');
            
            setTimeout(() => {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }, 300);
        }

        // Close on background click
        document.getElementById('photoModal').addEventListener('click', function(e) {
            if (e.target === this) closePhotoModal();
        });

        // Real-time Clock function
        function updateClock() {
            const now = new Date();
            const options = { timeZone: 'Asia/Makassar', hour: '2-digit', minute: '2-digit', hour12: false };
            const timeString = new Intl.DateTimeFormat('id-ID', options).format(now);
            const el = document.getElementById('mini-clock');
            if (el) el.textContent = timeString.replace('.', ':') + ' WITA';
        }
        setInterval(updateClock, 1000); updateClock();
    </script>

</body>
</html>
