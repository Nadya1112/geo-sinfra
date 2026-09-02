<div>
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
        <div>
            <h4 class="font-extrabold text-lg text-navy-900 dark:text-white">Data Manajemen Infrastruktur</h4>
            <p class="text-xs text-slate-400 dark:text-slate-300 font-semibold mt-0.5">Kelola seluruh aset infrastruktur permukiman</p>
        </div>

        <div class="flex flex-col lg:flex-row items-center gap-3 w-full lg:w-auto">
            {{-- Filter & Search --}}
            <div class="flex items-center flex-1 min-w-0 w-full lg:w-[280px] xl:w-[400px]">
                <select wire:model.live="show" class="pl-3 pr-7 py-2.5 bg-white dark:bg-navy-900/90 dark:backdrop-blur-xl border border-slate-100 dark:border-white/10 border-r-0 rounded-l-2xl text-[10px] md:text-xs font-bold text-navy-900 dark:text-white focus:outline-none focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 transition-all shadow-sm shrink-0">
                    <option value="10">10 Data</option>
                    <option value="all">Semua Data</option>
                </select>
                <div class="relative flex-1 min-w-[80px]">
                    <input type="text" 
                        wire:model.live.debounce.300ms="search"
                        placeholder="Cari infrastruktur..." 
                        class="w-full pl-3 pr-10 py-2.5 bg-white dark:bg-navy-900/90 dark:backdrop-blur-xl border border-slate-100 dark:border-white/10 text-[10px] md:text-xs font-semibold focus:outline-none focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 transition-all shadow-sm">
                    <div wire:loading wire:target="search" class="absolute right-3 top-1/2 -translate-y-1/2">
                        <i class="fas fa-circle-notch fa-spin text-gold-500 text-xs"></i>
                    </div>
                </div>
                <button type="button" class="bg-white dark:bg-navy-900/90 dark:backdrop-blur-xl border-y border-r border-slate-100 dark:border-white/10 px-4 md:px-5 py-2.5 rounded-r-2xl hover:bg-slate-50 dark:hover:bg-white/5 dark:bg-navy-950/50 transition-all shadow-sm group shrink-0 relative">
                    <i class="fas fa-search text-slate-400 dark:text-slate-300 group-hover:text-gold-500 transition-colors text-xs" wire:loading.remove wire:target="search"></i>
                    <i class="fas fa-circle-notch fa-spin text-gold-500 text-xs hidden" wire:loading.inline-block wire:target="search"></i>
                </button>
            </div>

            {{-- Tambah --}}
            <a href="{{ route('admin.infrastruktur.create') }}"
                class="bg-gold-500 hover:bg-gold-600 text-white text-xs px-5 py-2.5 rounded-2xl font-bold shadow-lg shadow-gold-500/10 hover:shadow-gold-500/20 transition flex items-center justify-center gap-2 whitespace-nowrap w-full lg:w-auto">
                <i class="fas fa-plus text-xs"></i> Tambah Data
            </a>
            
            {{-- Ekspor Excel --}}
            <a href="{{ route('admin.infrastruktur.export') }}"
                class="px-5 py-2.5 bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-500 hover:bg-emerald-500 hover:text-white border border-emerald-100 dark:border-emerald-500/20 hover:border-emerald-500 rounded-2xl text-xs font-bold transition-all shadow-sm flex items-center justify-center gap-2 w-full lg:w-auto">
                <i class="fas fa-file-excel"></i> Excel
            </a>
        </div>
    </div>

    {{-- Alert --}}
    @if(session('success'))
    <div class="mb-6 px-5 py-3 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-100 dark:border-emerald-500/20 text-emerald-700 dark:text-emerald-500 rounded-2xl flex items-center gap-3 text-sm font-bold">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="mb-6 px-5 py-3 bg-red-50 dark:bg-red-500/10 border border-red-100 dark:border-red-500/20 text-red-700 dark:text-red-500 rounded-2xl flex items-center gap-3 text-sm font-bold">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
    </div>
    @endif

    {{-- ── Tabel ── --}}
    <div class="bg-white dark:bg-navy-900/90 dark:backdrop-blur-xl rounded-3xl border border-slate-100 dark:border-white/10 shadow-sm overflow-hidden mb-10 relative">

        <div class="overflow-x-auto custom-scrollbar">
            <!-- Table Layout (Desktop & Tablet) -->
            <table class="w-full text-left border-collapse hidden md:table">
                <thead>
                    <tr class="bg-gradient-to-r from-gold-500 to-gold-600 dark:from-navy-900 dark:to-navy-800 border-b border-gold-600 dark:border-navy-800 shadow-sm dark:shadow-md">
                        <th class="px-4 py-3 text-xs font-black text-white dark:text-gold-500 tracking-widest text-center w-12">NO</th>
                        <th class="hidden md:table-cell px-4 py-3 text-xs font-black text-white dark:text-gold-500 tracking-widest text-center w-20">FOTO</th>
                        <th class="px-4 py-3 text-xs font-black text-white dark:text-gold-500 tracking-widest">INFRASTRUKTUR</th>
                        <th class="px-4 py-3 text-xs font-black text-white dark:text-gold-500 tracking-widest">WILAYAH</th>
                        <th class="px-4 py-3 text-xs font-black text-white dark:text-gold-500 tracking-widest text-center">ANALISIS AI</th>
                        <th class="px-4 py-3 text-xs font-black text-white dark:text-gold-500 tracking-widest text-center">KONDISI</th>
                        <th class="px-4 py-3 text-xs font-black text-white dark:text-gold-500 tracking-widest text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black dark:divide-white/5">
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
                            'kondisi baik'         => 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-500 border border-emerald-200 dark:border-emerald-500/20',
                            'kondisi rusak ringan' => 'bg-yellow-50 dark:bg-yellow-500/10  text-yellow-600 dark:text-yellow-500  border border-yellow-200 dark:border-yellow-500/20',
                            'kondisi rusak sedang' => 'bg-orange-50 dark:bg-orange-500/10  text-orange-600 dark:text-orange-500  border border-orange-200 dark:border-orange-500/20',
                            'kondisi rusak berat'  => 'bg-red-50 dark:bg-red-500/10     text-red-600 dark:text-red-500     border border-red-200 dark:border-red-500/20',
                        ];
                        $labelColor = $kondisiMap[strtolower($labelAkhir ?? '')] ?? 'bg-slate-50 dark:bg-navy-950/50 text-slate-500 dark:text-slate-400 border border-slate-200';

                        $nomor = $show == 'all'
                            ? $index + 1
                            : ($infrastruktur->currentPage() - 1) * $infrastruktur->perPage() + $index + 1;
                    @endphp

                    <tr class="hover:bg-slate-50/60 dark:hover:bg-white/5 transition-colors">

                        {{-- No --}}
                        <td class="px-4 py-3 text-center">
                            <span class="text-xs font-black text-slate-400 dark:text-slate-300">{{ $nomor }}</span>
                        </td>

                        {{-- Foto --}}
                        <td class="hidden md:table-cell px-4 py-3 text-center">
                            <div class="w-14 h-14 rounded-2xl overflow-hidden border-2 border-slate-100 dark:border-white/10 shadow-sm mx-auto bg-slate-100 dark:bg-navy-950/50 flex items-center justify-center">
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
                        <td class="px-4 py-3 max-w-[200px]">
                            <p class="text-sm font-black text-navy-900 dark:text-white leading-snug truncate">{{ $inf->nama_objek ?? $inf->nama_infrastruktur }}</p>
                            <span class="inline-block mt-1 px-2 py-0.5 bg-gold-500/10 text-gold-600 dark:text-gold-500 text-xs font-black rounded-md tracking-wider uppercase">
                                {{ ucfirst($inf->jenis) }}
                            </span>
                            <p class="text-xs text-slate-400 dark:text-slate-300 mt-1 font-bold">ID: INF-{{ $inf->id_infrastruktur }}</p>
                        </td>

                        {{-- Wilayah --}}
                        <td class="px-4 py-3">
                            <div class="flex items-start gap-2">
                                <i class="fas fa-map-marker-alt text-gold-500 text-xs mt-0.5 shrink-0"></i>
                                <div>
                                    <p class="text-xs font-black text-navy-900 dark:text-white leading-snug">{{ $inf->nama_kecamatan ?? '-' }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 font-semibold mt-0.5">Kel. {{ $inf->nama_kelurahan ?? '-' }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- Analisis AI --}}
                        <td class="px-4 py-3">
                            <div class="flex flex-col gap-1.5 items-center min-w-[130px]">
                                <div class="flex items-center gap-2 w-full">
                                    <span class="shrink-0 px-1.5 py-0.5 bg-navy-900 text-white rounded text-[7px] font-black tracking-wider">CNN</span>
                                    <span class="text-xs font-bold {{ $cnnLabel ? $cnnColor : 'text-slate-400' }} leading-none">
                                        {{ $cnn ? round($cnn->skor_cnn * 100).'%' : '—' }}
                                        <span class="text-slate-400 dark:text-slate-300">({{ $cnn->label_kondisi ?? 'Scanning' }})</span>
                                    </span>
                                </div>
                                <div class="flex items-center gap-2 w-full">
                                    <span class="shrink-0 px-1.5 py-0.5 bg-gold-500 text-white rounded text-[7px] font-black tracking-wider">DT</span>
                                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400 leading-none">
                                        Skor: <span class="text-navy-900 dark:text-white font-black">{{ $dt->skor_dt ?? '0' }}</span>/100
                                    </span>
                                </div>
                            </div>
                        </td>

                        {{-- Kondisi --}}
                        <td class="px-4 py-3 text-center">
                            <span class="inline-block px-3 py-1.5 rounded-xl text-xs font-black tracking-wider uppercase {{ $labelColor }}">
                                {{ $labelAkhir }}
                            </span>
                        </td>

                        {{-- Aksi --}}
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center gap-1.5 w-max mx-auto">

                                {{-- Verifikasi --}}
                                @if(($inf->status_verifikasi ?? 'Pending') == 'Verified')
                                    <span title="Terverifikasi" class="w-8 h-8 flex items-center justify-center bg-slate-100 dark:bg-navy-950/50 text-slate-400 dark:text-slate-300 rounded-lg text-xs font-black border border-black dark:border-white/10 cursor-not-allowed">
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
                                <a href="{{ route('admin.infrastruktur.edit', $inf->id_infrastruktur) }}" title="Ubah Data"
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
                            <p class="text-slate-400 dark:text-slate-300 font-bold text-sm">Belum Ada Data Infrastruktur.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Card Layout (Mobile) -->
            <div class="flex flex-col md:hidden divide-y divide-black dark:divide-white/10">
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
                        'kondisi baik'         => 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-500 border border-emerald-200 dark:border-emerald-500/20',
                        'kondisi rusak ringan' => 'bg-yellow-50 dark:bg-yellow-500/10  text-yellow-600 dark:text-yellow-500  border border-yellow-200 dark:border-yellow-500/20',
                        'kondisi rusak sedang' => 'bg-orange-50 dark:bg-orange-500/10  text-orange-600 dark:text-orange-500  border border-orange-200 dark:border-orange-500/20',
                        'kondisi rusak berat'  => 'bg-red-50 dark:bg-red-500/10     text-red-600 dark:text-red-500     border border-red-200 dark:border-red-500/20',
                    ];
                    $labelColor = $kondisiMap[strtolower($labelAkhir ?? '')] ?? 'bg-slate-50 dark:bg-navy-950/50 text-slate-500 dark:text-slate-400 border border-slate-200';
                @endphp

                <div class="p-4 hover:bg-slate-50/60 dark:hover:bg-white/5 transition-colors">
                    <div class="flex gap-3 mb-3">
                        {{-- Foto --}}
                        <div class="w-20 h-20 rounded-2xl overflow-hidden border-2 border-slate-100 dark:border-white/10 shadow-sm bg-slate-100 dark:bg-navy-950/50 flex items-center justify-center shrink-0">
                            @if($inf->foto_terbaru)
                                @php $cleanPath = str_replace('\\', '/', $inf->foto_terbaru); @endphp
                                <img src="{{ asset('storage/' . (str_contains($cleanPath, 'infrastruktur/') ? $cleanPath : 'infrastruktur/' . $cleanPath)) }}"
                                     class="w-full h-full object-cover">
                            @else
                                <i class="fas fa-image text-slate-300 text-2xl"></i>
                            @endif
                        </div>
                        
                        {{-- Info Utama --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-start gap-2 mb-1">
                                <p class="text-sm font-black text-navy-900 dark:text-white leading-snug truncate">{{ $inf->nama_objek ?? $inf->nama_infrastruktur }}</p>
                                <span class="inline-block px-2 py-0.5 rounded-xl text-[9px] font-black tracking-wider uppercase whitespace-nowrap {{ $labelColor }}">
                                    {{ $labelAkhir }}
                                </span>
                            </div>
                            <span class="inline-block mb-1 px-2 py-0.5 bg-gold-500/10 text-gold-600 dark:text-gold-500 text-[10px] font-black rounded-md tracking-wider uppercase">
                                {{ ucfirst($inf->jenis) }}
                            </span>
                            <div class="flex items-center gap-1.5 text-[10px] text-slate-500 dark:text-slate-400 mt-1 truncate">
                                <i class="fas fa-map-marker-alt text-gold-500"></i>
                                <span class="truncate">{{ $inf->nama_kecamatan ?? '-' }}, Kel. {{ $inf->nama_kelurahan ?? '-' }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Analisis AI --}}
                    <div class="flex gap-2 mb-3 bg-slate-50 dark:bg-navy-950/50 rounded-xl p-2 border border-slate-100 dark:border-white/10">
                        <div class="flex-1 flex items-center gap-2 border-r border-black dark:border-white/10 pr-2">
                            <span class="shrink-0 px-1.5 py-0.5 bg-navy-900 text-white rounded text-[7px] font-black tracking-wider">CNN</span>
                            <span class="text-[10px] font-bold {{ $cnnLabel ? $cnnColor : 'text-slate-400' }} leading-none truncate">
                                {{ $cnn ? round($cnn->skor_cnn * 100).'%' : '—' }}
                                <span class="text-slate-400 dark:text-slate-300">({{ $cnn->label_kondisi ?? 'Scanning' }})</span>
                            </span>
                        </div>
                        <div class="flex-1 flex items-center gap-2 pl-1">
                            <span class="shrink-0 px-1.5 py-0.5 bg-gold-500 text-white rounded text-[7px] font-black tracking-wider">DT</span>
                            <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400 leading-none truncate">
                                Skor: <span class="text-navy-900 dark:text-white font-black">{{ $dt->skor_dt ?? '0' }}</span>/100
                            </span>
                        </div>
                    </div>

                    {{-- Aksi --}}
                    <div class="flex items-center gap-2 pt-2 border-t border-slate-100 dark:border-white/10">
                        {{-- Verifikasi --}}
                        @if(($inf->status_verifikasi ?? 'Pending') == 'Verified')
                            <span title="Terverifikasi" class="flex-1 h-8 flex items-center justify-center bg-slate-100 dark:bg-navy-950/50 text-slate-400 dark:text-slate-300 rounded-lg text-[10px] uppercase font-black border border-black dark:border-white/10 cursor-not-allowed">
                                <i class="fas fa-check-double mr-1.5"></i> Terverifikasi
                            </span>
                        @else
                            <form action="{{ route('admin.infrastruktur.verifikasi', $inf->id_infrastruktur) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" onclick="return confirm('Verifikasi aset ini?')"
                                    class="w-full h-8 flex items-center justify-center bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg text-[10px] uppercase font-black transition shadow-sm active:scale-95">
                                    <i class="fas fa-check-double mr-1.5"></i> Verifikasi
                                </button>
                            </form>
                        @endif

                        {{-- Action Icons --}}
                        <div class="flex items-center gap-1.5">
                            <a href="{{ route('admin.infrastruktur.show', $inf->id_infrastruktur) }}" title="Lihat Detail"
                                class="w-8 h-8 flex items-center justify-center bg-navy-900 hover:bg-navy-950 text-white rounded-lg text-xs font-black transition shadow-sm active:scale-95">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.infrastruktur.edit', $inf->id_infrastruktur) }}" title="Ubah Data"
                                class="w-8 h-8 flex items-center justify-center bg-gold-500 hover:bg-gold-600 text-white rounded-lg text-xs font-black transition shadow-sm active:scale-95">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="{{ route('admin.infrastruktur.pdf', $inf->id_infrastruktur) }}" target="_blank" title="Cetak PDF"
                                class="w-8 h-8 flex items-center justify-center bg-red-600 hover:bg-red-700 text-white rounded-lg text-xs font-black transition shadow-sm active:scale-95">
                                <i class="fas fa-file-pdf"></i>
                            </a>
                            <form action="{{ route('admin.infrastruktur.destroy', $inf->id_infrastruktur) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus infrastruktur ini secara permanen? Seluruh data riwayat, foto, dan hasil AI terkait akan ikut terhapus.')" title="Hapus Data"
                                    class="w-8 h-8 flex items-center justify-center bg-rose-500 hover:bg-rose-600 text-white rounded-lg text-xs font-black transition shadow-sm active:scale-95">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <div class="px-8 py-16 text-center">
                    <i class="fas fa-database text-4xl text-slate-200 mb-4 block"></i>
                    <p class="text-slate-400 dark:text-slate-300 font-bold text-sm">Belum Ada Data Infrastruktur.</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Pagination --}}
        @if($show != 'all' && isset($infrastruktur) && $infrastruktur instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="px-4 py-3 border-t border-slate-50">
                {{ $infrastruktur->links() }}
            </div>
        @endif
    </div>
</div>
