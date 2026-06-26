<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Infrastruktur | Admin SINFRA</title>
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
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
<style>
    @media (min-width: 768px) { html { font-size: 14px; } }
    @media (max-width: 767px) { html { font-size: 12px; } }
</style>
</head>
<body class="bg-navy-50 dark:bg-navy-950 text-slate-800 dark:text-slate-200 antialiased flex overflow-hidden h-screen transition-colors duration-300">

    @include('admin.partials.sidebar')

    <main class="flex-1 flex flex-col h-screen overflow-hidden text-left font-sans">
        <header class="bg-white/85 dark:bg-navy-950/80 backdrop-blur-xl border-b border-slate-100 dark:border-white/5 px-4 pl-16 md:px-8 py-4 flex justify-between items-center z-40 text-left transition-colors duration-300">
            <div class="flex items-center gap-4 text-left">
                <a href="{{ route('admin.dashboard') }}"
                   class="w-10 h-10 bg-white border border-slate-100 rounded-xl flex items-center justify-center text-slate-400 hover:text-gold-500 hover:border-gold-500/20 hover:shadow-lg hover:shadow-gold-500/5 transition-all group"
                   title="Kembali ke Dashboard Utama">
                    <i class="fas fa-arrow-left text-xs group-hover:-translate-x-1 transition-transform"></i>
                </a>
                <div class="text-left">
                    <p class="text-xs font-black text-gold-500 uppercase tracking-wider mb-1">Administrator Portal</p>
                    <h2 class="text-xl font-black text-navy-900 dark:text-white leading-none">Manajemen Infrastruktur</h2>
                </div>
            </div>

            <div class="flex items-center gap-3 md:gap-6">
                <div class="text-right">
                    <p class="text-sm font-black text-navy-900 dark:text-white" id="mini-clock">00:00 WITA</p>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-tighter">{{ now()->translatedFormat('d M Y') }}</p>
                </div>
                <div class="h-8 w-[1px] bg-slate-100"></div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.profile') }}" class="text-right group hidden md:block">
                        <p class="text-sm font-black text-navy-900 dark:text-white leading-none uppercase group-hover:text-gold-500 transition-all">{{ auth()->user()->name }}</p>
                        <p class="text-xs font-bold text-emerald-500 uppercase mt-1">Online</p>
                    </a>
                    <a href="{{ route('admin.profile') }}" class="w-10 h-10 bg-navy-900 rounded-xl flex items-center justify-center text-gold-500 border border-white/10 overflow-hidden hover:shadow-lg hover:shadow-navy-950/20 transition-all shadow-md">
                        @if(auth()->user()->profile_photo)
                            <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-user-circle text-xl"></i>
                        @endif
                    </a>
                </div>
            </div>
        </header>

        <div class="flex-1 p-8 overflow-y-auto custom-scrollbar text-left">

            {{-- ── Toolbar: Judul + Filter + Tambah ── --}}
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
                <div>
                    <h4 class="font-extrabold text-lg text-navy-900">Data Manajemen Infrastruktur</h4>
                    <p class="text-xs text-slate-400 font-semibold mt-0.5">Kelola seluruh aset infrastruktur permukiman</p>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    {{-- Export Excel --}}
                    <a href="{{ route('admin.infrastruktur.export') }}"
                        class="px-4 py-2 bg-emerald-50 text-emerald-600 hover:bg-emerald-500 hover:text-white border border-emerald-100 hover:border-emerald-500 rounded-xl text-xs font-black tracking-widest uppercase transition-all shadow-sm flex items-center gap-2">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </a>

                    {{-- Filter & Search --}}
                    <form action="{{ route('admin.infrastruktur') }}" method="GET" class="flex items-center gap-2">
                        <select name="show" onchange="this.form.submit()"
                            class="text-xs font-bold text-navy-900 bg-white border border-slate-200 rounded-xl px-3 py-2.5 focus:outline-none focus:border-gold-500 transition shadow-sm">
                            <option value="10" {{ request('show') != 'all' ? 'selected' : '' }}>10 Data</option>
                            <option value="all" {{ request('show') == 'all' ? 'selected' : '' }}>Semua Data</option>
                        </select>
                        <div class="relative">
                            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari infrastruktur..."
                                class="pl-8 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-bold outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition shadow-sm placeholder-slate-400 text-slate-600 w-52">
                        </div>
                    </form>

                    {{-- Tambah --}}
                    <a href="{{ route('admin.infrastruktur.create') }}"
                        class="bg-gold-500 hover:bg-gold-600 text-white text-xs px-5 py-2.5 rounded-xl font-black shadow-md shadow-gold-500/20 hover:shadow-gold-500/30 transition flex items-center gap-2 whitespace-nowrap tracking-wider">
                        <i class="fas fa-plus"></i> Tambah Data
                    </a>
                </div>
            </div>

            {{-- Alert --}}
            @if(session('success'))
            <div class="mb-6 px-5 py-3 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center gap-3 text-sm font-bold">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div class="mb-6 px-5 py-3 bg-red-50 border border-red-100 text-red-700 rounded-2xl flex items-center gap-3 text-sm font-bold">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
            @endif

            {{-- ── Tabel ── --}}
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden mb-10">
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gradient-to-r from-navy-900 to-navy-800 border-b border-navy-800 shadow-md">
                                <th class="px-5 py-4 text-xs font-black text-gold-500 tracking-widest text-center w-12">NO</th>
                                <th class="px-5 py-4 text-xs font-black text-gold-500 tracking-widest text-center w-20">FOTO</th>
                                <th class="px-5 py-4 text-xs font-black text-gold-500 tracking-widest">INFRASTRUKTUR</th>
                                <th class="px-5 py-4 text-xs font-black text-gold-500 tracking-widest">WILAYAH</th>
                                <th class="px-5 py-4 text-xs font-black text-gold-500 tracking-widest text-center">ANALISIS AI</th>
                                <th class="px-5 py-4 text-xs font-black text-gold-500 tracking-widest text-center">KONDISI</th>
                                <th class="px-5 py-4 text-xs font-black text-gold-500 tracking-widest text-center">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($infrastruktur as $index => $inf)
                            @php
                                $dt = (object) [
                                    'label_prioritas' => $inf->dt_label_prioritas,
                                    'skor_dt'         => $inf->dt_skor_dt,
                                    'rekomendasi'     => $inf->dt_rekomendasi
                                ];
                                $cnn = (object) [
                                    'label_kondisi' => $inf->cnn_label_kondisi,
                                    'skor_cnn'      => $inf->cnn_skor_cnn
                                ];
                                $labelAkhir = $dt->label_prioritas ?? $inf->kondisi;

                                $cnnLabel = strtolower($cnn->label_kondisi ?? '');
                                $cnnColor = str_contains($cnnLabel, 'berat')  ? 'text-red-500'
                                          : (str_contains($cnnLabel, 'sedang') ? 'text-orange-500'
                                          : 'text-emerald-500');

                                $kondisiMap = [
                                    'baik'         => 'bg-emerald-50 text-emerald-600 border border-emerald-200',
                                    'rusak sedang' => 'bg-orange-50  text-orange-600  border border-orange-200',
                                    'rusak berat'  => 'bg-red-50     text-red-600     border border-red-200',
                                ];
                                $labelColor = $kondisiMap[strtolower($labelAkhir ?? '')] ?? 'bg-slate-50 text-slate-500 border border-slate-200';

                                $nomor = request('show') == 'all'
                                    ? $index + 1
                                    : ($infrastruktur->currentPage() - 1) * $infrastruktur->perPage() + $index + 1;
                            @endphp

                            <tr class="hover:bg-slate-50/60 transition-colors">

                                {{-- No --}}
                                <td class="px-5 py-4 text-center">
                                    <span class="text-xs font-black text-slate-400">{{ $nomor }}</span>
                                </td>

                                {{-- Foto --}}
                                <td class="px-5 py-4 text-center">
                                    <div class="w-14 h-14 rounded-2xl overflow-hidden border-2 border-slate-100 shadow-sm mx-auto bg-slate-100 flex items-center justify-center">
                                        @if($inf->foto_terbaru)
                                            @php $cleanPath = str_replace('\\', '/', $inf->foto_terbaru); @endphp
                                            <img src="{{ asset('storage/' . (str_contains($cleanPath, 'infrastruktur/') ? $cleanPath : 'infrastruktur/' . $cleanPath)) }}"
                                                 class="w-full h-full object-cover">
                                        @else
                                            <i class="fas fa-image text-slate-300 text-xl"></i>
                                        @endif
                                    </div>
                                </td>

                                {{-- Nama & Jenis --}}
                                <td class="px-5 py-4 max-w-[200px]">
                                    <p class="text-sm font-black text-navy-900 leading-snug truncate">{{ $inf->nama_objek ?? $inf->nama_infrastruktur }}</p>
                                    <span class="inline-block mt-1 px-2 py-0.5 bg-gold-500/10 text-gold-600 text-xs font-black rounded-md tracking-wider uppercase">
                                        {{ ucfirst($inf->jenis) }}
                                    </span>
                                    <p class="text-xs text-slate-400 mt-1 font-bold">ID: INF-{{ $inf->id_infrastruktur }}</p>
                                </td>

                                {{-- Wilayah --}}
                                <td class="px-5 py-4">
                                    <div class="flex items-start gap-2">
                                        <i class="fas fa-map-marker-alt text-gold-500 text-xs mt-0.5 shrink-0"></i>
                                        <div>
                                            <p class="text-xs font-black text-navy-900 leading-snug">{{ $inf->nama_kecamatan ?? '-' }}</p>
                                            <p class="text-xs text-slate-500 font-semibold mt-0.5">Kel. {{ $inf->nama_kelurahan ?? '-' }}</p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Analisis AI --}}
                                <td class="px-5 py-4">
                                    <div class="flex flex-col gap-1.5 items-center min-w-[130px]">
                                        <div class="flex items-center gap-2 w-full">
                                            <span class="shrink-0 px-1.5 py-0.5 bg-navy-900 text-white rounded text-[7px] font-black tracking-wider">CNN</span>
                                            <span class="text-xs font-bold {{ $cnnLabel ? $cnnColor : 'text-slate-400' }} leading-none">
                                                {{ $cnn ? round($cnn->skor_cnn * 100).'%' : '—' }}
                                                <span class="text-slate-400">({{ $cnn->label_kondisi ?? 'Scanning' }})</span>
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-2 w-full">
                                            <span class="shrink-0 px-1.5 py-0.5 bg-gold-500 text-white rounded text-[7px] font-black tracking-wider">DT</span>
                                            <span class="text-xs font-bold text-slate-500 leading-none">
                                                Skor: <span class="text-navy-900 font-black">{{ $dt->skor_dt ?? '0' }}</span>/100
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                {{-- Kondisi --}}
                                <td class="px-5 py-4 text-center">
                                    <span class="inline-block px-3 py-1.5 rounded-xl text-xs font-black tracking-wider uppercase {{ $labelColor }}">
                                        {{ $labelAkhir }}
                                    </span>
                                </td>

                                {{-- Aksi --}}
                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-center gap-1.5 w-max mx-auto">

                                        {{-- Verifikasi --}}
                                        @if(($inf->status_verifikasi ?? 'Pending') == 'Verified')
                                            <span title="Terverifikasi" class="w-8 h-8 flex items-center justify-center bg-slate-100 text-slate-400 rounded-lg text-xs font-black border border-slate-200 cursor-not-allowed">
                                                <i class="fas fa-check-double"></i>
                                            </span>
                                        @else
                                            <form action="{{ route('admin.infrastruktur.verifikasi', $inf->id_infrastruktur) }}" method="POST" class="inline-block">
                                                @csrf
                                                <button type="submit" onclick="return confirm('Verifikasi aset ini?')" title="Verifikasi"
                                                    class="w-8 h-8 flex items-center justify-center bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg text-xs font-black transition shadow-sm hover:scale-105">
                                                    <i class="fas fa-check-double"></i>
                                                </button>
                                            </form>
                                        @endif

                                        {{-- Detail --}}
                                        <a href="{{ route('admin.infrastruktur.show', $inf->id_infrastruktur) }}" title="Lihat Detail"
                                            class="w-8 h-8 flex items-center justify-center bg-navy-900 hover:bg-navy-950 text-white rounded-lg text-xs font-black transition shadow-sm hover:scale-105">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        {{-- Edit --}}
                                        <a href="{{ route('admin.infrastruktur.edit', $inf->id_infrastruktur) }}" title="Edit Data"
                                            class="w-8 h-8 flex items-center justify-center bg-gold-500 hover:bg-gold-600 text-white rounded-lg text-xs font-black transition shadow-sm hover:scale-105">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        {{-- Export PDF --}}
                                        <a href="{{ route('admin.infrastruktur.pdf', $inf->id_infrastruktur) }}" target="_blank" title="Cetak PDF"
                                            class="w-8 h-8 flex items-center justify-center bg-red-600 hover:bg-red-700 text-white rounded-lg text-xs font-black transition shadow-sm hover:scale-105">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>

                                        {{-- Hapus --}}
                                        <form action="{{ route('admin.infrastruktur.destroy', $inf->id_infrastruktur) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus infrastruktur ini secara permanen? Seluruh data riwayat, foto, dan hasil AI terkait akan ikut terhapus.')" title="Hapus Data"
                                                class="w-8 h-8 flex items-center justify-center bg-rose-500 hover:bg-rose-600 text-white rounded-lg text-xs font-black transition shadow-sm hover:scale-105">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-8 py-20 text-center">
                                    <i class="fas fa-database text-4xl text-slate-200 mb-4 block"></i>
                                    <p class="text-slate-400 font-bold text-sm">Belum Ada Data Infrastruktur.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if(request('show') != 'all' && isset($infrastruktur) && $infrastruktur instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    <div class="px-8 py-5 border-t border-slate-50">
                        {{ $infrastruktur->links() }}
                    </div>
                @endif
            </div>
        </div>
    </main>

    <script>
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

