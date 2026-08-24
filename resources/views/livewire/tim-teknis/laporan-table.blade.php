<div>
    <!-- Summary Cards (No Print) -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6 mb-8 no-print">
        <!-- Total Laporan -->
        <div class="relative overflow-hidden rounded-[2rem] p-6 shadow-xl shadow-blue-500/20 hover:-translate-y-1 transition-transform bg-gradient-to-br from-blue-500 to-blue-700">
            <i class="fas fa-layer-group absolute -right-4 -bottom-4 text-7xl text-white opacity-10"></i>
            <div class="relative z-10 flex flex-col justify-between h-full">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-[0.8rem] bg-white/20 dark:bg-[#1e1b4b]/20 backdrop-blur-sm flex items-center justify-center text-white border border-white/10 shadow-inner">
                        <i class="fas fa-layer-group text-sm"></i>
                    </div>
                    <p class="text-xs font-black text-white uppercase tracking-widest mt-1">Total Laporan</p>
                </div>
                <div class="flex items-end gap-2">
                    <h3 class="text-4xl font-black text-white leading-none">{{ $totalLaporan }}</h3>
                    <span class="text-xs font-bold text-white/80 uppercase mb-1">Data</span>
                </div>
            </div>
        </div>

        <!-- Kondisi Baik -->
        <div class="relative overflow-hidden rounded-[2rem] p-6 shadow-xl shadow-emerald-500/20 hover:-translate-y-1 transition-transform bg-gradient-to-br from-emerald-400 to-emerald-600">
            <i class="fas fa-check-double absolute -right-4 -bottom-4 text-7xl text-white opacity-10"></i>
            <div class="relative z-10 flex flex-col justify-between h-full">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-[0.8rem] bg-white/20 dark:bg-[#1e1b4b]/20 backdrop-blur-sm flex items-center justify-center text-white border border-white/10 shadow-inner">
                        <i class="fas fa-check text-sm"></i>
                    </div>
                    <p class="text-xs font-black text-white uppercase tracking-widest mt-1">Kondisi Baik</p>
                </div>
                <div class="flex items-end gap-2">
                    <h3 class="text-4xl font-black text-white leading-none">{{ $totalBaik }}</h3>
                    <span class="text-xs font-bold text-white/80 uppercase mb-1">Lokasi</span>
                </div>
            </div>
        </div>

        <!-- Kondisi Sedang -->
        <div class="relative overflow-hidden rounded-[2rem] p-6 shadow-xl shadow-amber-500/20 hover:-translate-y-1 transition-transform bg-gradient-to-br from-amber-400 to-orange-500">
            <i class="fas fa-exclamation-triangle absolute -right-4 -bottom-4 text-7xl text-white opacity-10"></i>
            <div class="relative z-10 flex flex-col justify-between h-full">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-[0.8rem] bg-white/20 dark:bg-[#1e1b4b]/20 backdrop-blur-sm flex items-center justify-center text-white border border-white/10 shadow-inner">
                        <i class="fas fa-exclamation text-sm"></i>
                    </div>
                    <p class="text-xs font-black text-white uppercase tracking-widest mt-1">Kondisi Sedang</p>
                </div>
                <div class="flex items-end gap-2">
                    <h3 class="text-4xl font-black text-white leading-none">{{ $totalSedang }}</h3>
                    <span class="text-xs font-bold text-white/80 uppercase mb-1">Lokasi</span>
                </div>
            </div>
        </div>

        <!-- Kondisi Berat -->
        <div class="relative overflow-hidden rounded-[2rem] p-6 shadow-xl shadow-rose-500/20 hover:-translate-y-1 transition-transform bg-gradient-to-br from-rose-500 to-rose-600">
            <i class="fas fa-times-circle absolute -right-4 -bottom-4 text-7xl text-white opacity-10"></i>
            <div class="relative z-10 flex flex-col justify-between h-full">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-[0.8rem] bg-white/20 dark:bg-[#1e1b4b]/20 backdrop-blur-sm flex items-center justify-center text-white border border-white/10 shadow-inner">
                        <i class="fas fa-times text-sm"></i>
                    </div>
                    <p class="text-xs font-black text-white uppercase tracking-widest mt-1">Kondisi Berat</p>
                </div>
                <div class="flex items-end gap-2">
                    <h3 class="text-4xl font-black text-white leading-none">{{ $totalBerat }}</h3>
                    <span class="text-xs font-bold text-white/80 uppercase mb-1">Lokasi</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section (No Print) -->
    <div class="bg-white dark:bg-[#1e1b4b] rounded-[2rem] p-5 md:p-8 border border-slate-100 dark:border-white/10 shadow-sm mb-4 no-print relative z-20">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">
            <div class="w-full">
                <label class="text-xs font-black text-slate-400 uppercase tracking-widest block mb-2">Cari Nama</label>
                <div class="relative w-full">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Ketik infrastruktur..." class="w-full pl-11 pr-4 py-2.5 bg-slate-50 dark:bg-[#0f0e2c] border border-slate-100 dark:border-white/10 rounded-xl text-xs font-bold text-navy-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs" wire:loading.remove wire:target="search"></i>
                    <div wire:loading wire:target="search" class="absolute left-4 top-1/2 -translate-y-1/2 flex items-center justify-center">
                        <i class="fas fa-circle-notch fa-spin text-gold-500 text-xs"></i>
                    </div>
                </div>
            </div>
            <div class="w-full">
                <label class="text-xs font-black text-slate-400 uppercase tracking-widest block mb-2">Wilayah</label>
                <select wire:model.live="kecamatan" class="w-full bg-slate-50 dark:bg-[#0f0e2c] border border-slate-100 dark:border-white/10 rounded-xl px-4 py-2.5 text-xs font-bold text-navy-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all cursor-pointer">
                    <option value="">Semua Kecamatan</option>
                    @foreach($allKecamatan as $kec)
                        <option value="{{ $kec->id_kecamatan }}">
                            {{ $kec->nama_kecamatan }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="w-full">
                <label class="text-xs font-black text-slate-400 uppercase tracking-widest block mb-2">Kondisi</label>
                <select wire:model.live="kondisi" class="w-full bg-slate-50 dark:bg-[#0f0e2c] border border-slate-100 dark:border-white/10 rounded-xl px-4 py-2.5 text-xs font-bold text-navy-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all cursor-pointer">
                    <option value="">Semua Kondisi</option>
                    <option value="Baik">Baik</option>
                    <option value="Rusak Sedang">Rusak Sedang</option>
                    <option value="Rusak Berat">Rusak Berat</option>
                </select>
            </div>
            <div class="w-full">
                <label class="text-xs font-black text-slate-400 uppercase tracking-widest block mb-2">Infrastruktur</label>
                <select wire:model.live="jenis" class="w-full bg-slate-50 dark:bg-[#0f0e2c] border border-slate-100 dark:border-white/10 rounded-xl px-4 py-2.5 text-xs font-bold text-navy-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all cursor-pointer">
                    <option value="">Semua Infrastruktur</option>
                    <option value="Jalan">Jalan</option>
                    <option value="Titian">Titian</option>
                    <option value="Jembatan">Jembatan</option>
                </select>
            </div>
            <div class="w-full">
                <label class="text-xs font-black text-slate-400 uppercase tracking-widest block mb-2">Mulai Tanggal</label>
                <input type="date" wire:model.live="start_date" class="w-full bg-slate-50 dark:bg-[#0f0e2c] border border-slate-100 dark:border-white/10 rounded-xl px-4 py-2.5 text-xs font-bold text-navy-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all cursor-pointer">
            </div>
            <div class="w-full">
                <label class="text-xs font-black text-slate-400 uppercase tracking-widest block mb-2">Sampai Tanggal</label>
                <input type="date" wire:model.live="end_date" class="w-full bg-slate-50 dark:bg-[#0f0e2c] border border-slate-100 dark:border-white/10 rounded-xl px-4 py-2.5 text-xs font-bold text-navy-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all cursor-pointer">
            </div>
            <div class="w-full flex justify-end">
                @if($search || $kecamatan || $kondisi || $jenis || $start_date || $end_date)
                <button wire:click="resetFilters" class="px-4 py-3 bg-red-50 text-red-600 font-bold text-xs uppercase tracking-widest rounded-xl hover:bg-red-100 transition-all text-center flex items-center justify-center shrink-0" title="Reset Filter">
                    <i class="fas fa-times mr-2"></i> Reset
                </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Print Header (Kop Surat Dinas) -->
    <div id="kopSurat" class="hidden print-only mb-6 pb-4" style="border-bottom: 4px double black;">
        <div class="flex items-center gap-6" style="display: flex; align-items: center; justify-content: center;">
            <div style="text-align: center;">
                <h2 style="font-size: 14pt; font-weight: bold; text-transform: uppercase; margin: 0; line-height: 1.3;">Dinas Perumahan Rakyat dan Kawasan Permukiman Kota Banjarmasin</h2>
                <p style="font-size: 10pt; margin: 0; line-height: 1.5;">Jalan R.E Martadinata No. 1 Blok B Lantai 2 Kec. Banjarmasin Tengah, Kota Banjarmasin Kalimantan Selatan - 70111</p>
                <p style="font-size: 10pt; margin: 0; line-height: 1.5;">Telepon: (0511) 3365592| Email:  ampihkumuh@gmail.com</p>
            </div>
        </div>
    </div>

    <!-- Print Document Title -->
    <div id="docTitle" class="hidden print-only mb-8" style="text-align: center;">
        <h3 style="font-size: 14pt; font-weight: bold; text-transform: uppercase; text-decoration: underline; margin-bottom: 12px;">Laporan Rekapitulasi Kondisi Infrastruktur</h3>
        <div style="font-size: 10pt; display: flex; justify-content: flex-start; gap: 60px; padding-top: 8px; margin-top: 8px;">
            @if($start_date && $end_date)
            <span><strong>Periode:</strong> {{ \Carbon\Carbon::parse($start_date)->translatedFormat('d M Y') }} &ndash; {{ \Carbon\Carbon::parse($end_date)->translatedFormat('d M Y') }}</span>
            @endif
        </div>
    </div>

    <!-- TABLE SECTION -->
    <div class="print-no-style bg-white dark:bg-[#1e1b4b] rounded-[2rem] border border-slate-100 dark:border-white/10 shadow-sm overflow-hidden mt-4 relative">
        <div wire:loading.delay class="absolute inset-0 bg-white/50 dark:bg-navy-950/50 backdrop-blur-sm z-10 flex items-center justify-center no-print">
            <i class="fas fa-circle-notch fa-spin text-4xl text-gold-500"></i>
        </div>
        
        <!-- Header with Tampilan Dropdown -->
        <div class="px-4 md:px-8 py-4 md:py-6 border-b border-slate-50 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-slate-50 dark:bg-[#0f0e2c]/30 no-print">
            <div>
                <h3 class="text-sm font-black text-navy-900 dark:text-white uppercase tracking-widest">Data Laporan</h3>
                <p class="text-xs text-slate-400 font-bold uppercase mt-1">Hasil filter rekapitulasi data</p>
            </div>
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 sm:gap-4 w-full md:w-auto">
                <div class="flex items-center justify-between sm:justify-start gap-2 border-b sm:border-b-0 sm:border-r border-slate-200 dark:border-white/20 pb-3 sm:pb-0 pr-0 sm:pr-4">
                    <button onclick="printAllData()" class="flex-1 sm:flex-none justify-center no-print px-4 py-2 bg-rose-50 text-rose-600 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-rose-100 hover:scale-[1.02] transition-all flex items-center gap-2 border border-rose-100 shadow-sm">
                        <i class="fas fa-file-pdf"></i> Cetak PDF
                    </button>
                    <button onclick="exportAllDataToExcel()" class="flex-1 sm:flex-none justify-center no-print px-4 py-2 bg-emerald-50 text-emerald-600 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-emerald-100 hover:scale-[1.02] transition-all flex items-center gap-2 border border-emerald-100 shadow-sm">
                        <i class="fas fa-file-excel"></i> Ekspor Excel
                    </button>
                </div>
                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <label class="text-xs font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Tampilan:</label>
                    <select wire:model.live="show" class="text-xs font-bold text-navy-900 dark:text-white bg-slate-50 dark:bg-[#0f0e2c] border border-slate-200 dark:border-white/20 rounded-xl px-3 py-2 focus:outline-none focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20 transition-all shadow-sm cursor-pointer">
                        <option value="10">10 Data</option>
                        <option value="all">Semua Data</option>
                    </select>
                </div>
            </div>
        </div>
        
        @if($search || $kecamatan || $kondisi || $jenis)
        <div class="bg-navy-50/50 px-6 py-4 border-b border-navy-100/50 flex flex-wrap items-center gap-3 no-print">
            <span class="text-xs font-black text-navy-400 uppercase tracking-widest mr-2">Penyaringan Aktif:</span>
            @if($search)
                <span class="px-3 py-1 bg-white dark:bg-[#1e1b4b] text-navy-600 rounded-full text-xs font-bold shadow-sm border border-navy-100">
                    <i class="fas fa-search mr-1"></i> "{{ $search }}"
                </span>
            @endif
            @if($kecamatan)
                <span class="px-3 py-1 bg-white dark:bg-[#1e1b4b] text-navy-600 rounded-full text-xs font-bold shadow-sm border border-navy-100">
                    <i class="fas fa-map-marker-alt mr-1"></i> {{ $allKecamatan->where('id_kecamatan', $kecamatan)->first()->nama_kecamatan ?? 'Wilayah' }}
                </span>
            @endif
            @if($kondisi)
                <span class="px-3 py-1 bg-white dark:bg-[#1e1b4b] text-navy-600 rounded-full text-xs font-bold shadow-sm border border-navy-100">
                    <i class="fas fa-clipboard-list mr-1"></i> {{ $kondisi }}
                </span>
            @endif
            @if($jenis)
                <span class="px-3 py-1 bg-white dark:bg-[#1e1b4b] text-navy-600 rounded-full text-xs font-bold shadow-sm border border-navy-100">
                    <i class="fas fa-layer-group mr-1"></i> {{ $jenis }}
                </span>
            @endif
            <button wire:click="resetFilters" class="ml-auto text-xs font-bold text-red-400 hover:text-red-600 transition-all cursor-pointer bg-transparent border-none">
                <i class="fas fa-times mr-1"></i> Hapus Penyaringan
            </button>
        </div>
        @endif
        
        <div class="overflow-x-auto w-full custom-scrollbar">
            <table id="laporanTable" class="w-full text-left min-w-[600px] md:min-w-full">
                <thead>
                <tr class="bg-slate-50 dark:bg-[#0f0e2c]/50 text-xs font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 dark:border-white/10">
                    <th class="px-6 py-4 text-center border-b border-slate-100 dark:border-white/10" style="width: 10%;">No</th>
                    <th class="px-6 py-4 text-left border-b border-slate-100 dark:border-white/10" style="width: 30%;">Infrastruktur</th>
                    <th class="px-6 py-4 text-left border-b border-slate-100 dark:border-white/10" style="width: 20%;">Wilayah</th>
                    <th class="px-6 py-4 text-center border-b border-slate-100 dark:border-white/10" style="width: 20%;">Kondisi</th>
                    <th class="px-6 py-4 text-center border-b border-slate-100 dark:border-white/10" style="width: 20%;">Tanggal Data</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($reports as $index => $item)
                <tr class="group hover:bg-slate-50 dark:bg-[#0f0e2c]/50 transition-all">
                    <td class="px-6 py-3 text-xs font-bold text-slate-400 text-center">{{ $show == 'all' ? $index + 1 : ($reports->currentPage() - 1) * $reports->perPage() + $index + 1 }}</td>
                    <td class="px-6 py-3">
                        <span class="text-xs font-black text-navy-900 dark:text-white uppercase">{{ $item->nama_objek }}</span><br style="mso-data-placement:same-cell;">
                        <span class="text-xs text-slate-400 font-bold uppercase">{{ $item->jenis }}</span>
                    </td>
                    <td class="px-6 py-3">
                        <span class="text-xs font-bold text-navy-900 dark:text-white">{{ $item->kelurahan->nama_kelurahan ?? '-' }}</span><br style="mso-data-placement:same-cell;">
                        <span class="text-xs text-slate-400 font-bold uppercase">{{ $item->kelurahan->kecamatan->nama_kecamatan ?? '-' }}</span>
                    </td>
                    <td class="px-6 py-3">
                        <div class="flex justify-center">
                            @php
                                $aiLabel = $item->analisis->label_prioritas ?? '';
                                $aiLabelLower = strtolower($aiLabel);
                                
                                $condClass = 'bg-slate-50 dark:bg-[#0f0e2c] text-slate-600 border-slate-200 dark:border-white/20';
                                if (str_contains($aiLabelLower, 'berat')) {
                                    $condClass = 'bg-[#be123c]/10 text-[#be123c] border-[#be123c]/30';
                                } elseif (str_contains($aiLabelLower, 'sedang') || str_contains($aiLabelLower, 'ringan')) {
                                    $condClass = 'bg-[#d97706]/10 text-[#d97706] border-[#d97706]/30';
                                } elseif (str_contains($aiLabelLower, 'baik')) {
                                    $condClass = 'bg-[#059669]/10 text-[#059669] border-[#059669]/30';
                                }
                            @endphp
                            <span class="px-2.5 py-1 rounded-md text-xs font-black uppercase border tracking-widest badge-print {{ $condClass }}">
                                {{ $aiLabel ?: 'Belum Dianalisis' }}
                            </span>
                        </div>
                    </td>
                    <td class="px-6 py-3 text-center text-xs font-bold text-slate-400">
                        {{ $item->created_at->format('d/m/Y') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-20 text-center">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-folder-open text-slate-200 text-4xl mb-4"></i>
                            <p class="text-xs text-slate-400 font-bold italic uppercase">Tidak ada data yang ditemukan sesuai filter.</p>
                        </div>
                    </td>
                </tr>
                @endforelse

            </tbody>
            <tfoot class="print-tfoot-only" style="display: none;">
                <tr>
                    <td colspan="5" style="border: none !important; padding-top: 40px !important;">
                        @php
                            $timTeknis = \App\Models\User::where('role', 'tim_teknis')->first();
                        @endphp
                        <div style="float: right; text-align: center; width: 260px; font-size: 10pt; page-break-inside: avoid;">
                            <p style="margin-bottom: 4px;">Banjarmasin, {{ now()->translatedFormat('d F Y') }}</p>
                            <p style="margin-bottom: 60px;">Mengetahui,<br><strong>Koordinator Tim Teknis</strong></p>
                            <p style="margin: 0; font-weight: bold; text-decoration: underline;">{{ strtoupper($timTeknis->name ?? 'HIZBULWATHONI, S.T.') }}</p>
                            <p style="margin: 0;">NIP. {{ $timTeknis->nip ?? '19760814 200604 1 008' }}</p>
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
        </div>
        
        @if($show != 'all' && isset($reports) && $reports instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="px-8 py-4 border-t border-slate-50 bg-slate-50 dark:bg-[#0f0e2c]/10 no-print">
                {{ $reports->links() }}
            </div>
        @endif
    </div>
</div>
