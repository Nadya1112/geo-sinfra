<div>
    <!-- TABLE SECTION -->
    <div class="bg-white dark:bg-[#1e1b4b] rounded-[2rem] md:rounded-[3rem] shadow-sm border border-slate-100 dark:border-white/10 overflow-hidden mb-10 relative">
        <div wire:loading.delay class="absolute inset-0 bg-white/50 dark:bg-navy-950/50 backdrop-blur-sm z-10 flex items-center justify-center no-print">
            <i class="fas fa-circle-notch fa-spin text-4xl text-gold-500"></i>
        </div>

        <div class="px-4 md:px-8 py-4 md:py-5 border-b border-slate-50 flex flex-col md:flex-row justify-between items-center bg-white dark:bg-[#1e1b4b] gap-4">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-gold-50 flex items-center justify-center text-gold-500 border border-gold-100">
                    <i class="fas fa-tasks"></i>
                </div>
                <div>
                    <h3 class="text-sm font-black text-navy-900 dark:text-white uppercase tracking-widest">Antrean Validasi</h3>
                    <p class="text-xs text-slate-400 font-bold uppercase mt-1">Daftar laporan surveyor yang menunggu keputusan</p>
                </div>
            </div>
            <div>
                <div class="flex items-center gap-2 relative z-20">
                    <label class="text-xs font-black text-slate-400 uppercase tracking-widest">Tampilan:</label>
                    <select wire:model.live="show" class="text-xs font-bold text-navy-900 dark:text-white bg-slate-50 dark:bg-[#0f0e2c] border border-slate-200 dark:border-white/20 rounded-xl px-3 py-2 focus:outline-none focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20 transition-all shadow-sm cursor-pointer">
                        <option value="10">10 Data</option>
                        <option value="all">Semua Data</option>
                    </select>
                </div>
            </div>
        </div>
        
        <!-- FILTER TABS & ADVANCED FILTER -->
        <div class="px-4 md:px-8 py-4 bg-white dark:bg-[#1e1b4b] border-b border-slate-100 dark:border-white/10 relative z-20">
            <div class="flex flex-col gap-4">
                <!-- Filter Status -->
                <div class="flex flex-wrap gap-2 mb-2 items-center justify-between w-full">
                    <div class="flex flex-wrap gap-2">
                        <button wire:click="setStatusFilter('All')" class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all cursor-pointer {{ $statusFilter == 'All' ? 'bg-navy-900 text-white shadow-md' : 'bg-white dark:bg-[#1e1b4b] text-slate-400 hover:bg-slate-100 border border-slate-200 dark:border-white/20' }}">Semua Antrean</button>
                        <button wire:click="setStatusFilter('Pending')" class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all cursor-pointer {{ $statusFilter == 'Pending' ? 'bg-amber-500 text-white shadow-md shadow-amber-500/20' : 'bg-white dark:bg-[#1e1b4b] text-slate-400 hover:bg-slate-100 border border-slate-200 dark:border-white/20' }}">Menunggu</button>
                        <button wire:click="setStatusFilter('Validated')" class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all cursor-pointer {{ $statusFilter == 'Validated' ? 'bg-emerald-500 text-white shadow-md shadow-emerald-500/20' : 'bg-white dark:bg-[#1e1b4b] text-slate-400 hover:bg-slate-100 border border-slate-200 dark:border-white/20' }}">Disetujui (Validated)</button>
                        <button wire:click="setStatusFilter('Rejected')" class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all cursor-pointer {{ $statusFilter == 'Rejected' ? 'bg-rose-500 text-white shadow-md shadow-rose-500/20' : 'bg-white dark:bg-[#1e1b4b] text-slate-400 hover:bg-slate-100 border border-slate-200 dark:border-white/20' }}">Ditolak / Perbaikan</button>
                    </div>
                    <div>
                        <a href="{{ route('tim_teknis.laporan.pdf') }}?status={{ $statusFilter }}&kecamatan={{ $kecamatan }}&start={{ $start_date }}&end={{ $end_date }}" target="_blank" class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all cursor-pointer bg-red-600 hover:bg-red-700 text-white shadow-md shadow-red-500/20 flex items-center gap-2">
                            <i class="fas fa-file-pdf"></i> Cetak Rekap (PDF)
                        </a>
                    </div>
                </div>

                <!-- Advanced Filter -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 items-end bg-slate-50 dark:bg-[#0f0e2c]/50 p-4 rounded-2xl border border-slate-100 dark:border-white/10">
                    <div class="w-full">
                        <label class="text-xs font-black text-slate-400 uppercase tracking-widest block mb-2">Pencarian</label>
                        <div class="relative w-full">
                            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari infrastruktur..." class="w-full pl-11 pr-4 py-2.5 bg-white dark:bg-[#1e1b4b] border border-slate-200 dark:border-white/20 rounded-xl text-xs font-bold text-navy-900 dark:text-white shadow-sm focus:outline-none focus:border-gold-500 transition-all">
                            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs" wire:loading.remove wire:target="search"></i>
                            <div wire:loading wire:target="search" class="absolute left-4 top-1/2 -translate-y-1/2 flex items-center justify-center">
                                <i class="fas fa-circle-notch fa-spin text-gold-500 text-xs"></i>
                            </div>
                        </div>
                    </div>
                    <div class="w-full">
                        <label class="text-xs font-black text-slate-400 uppercase tracking-widest block mb-2">Wilayah Kecamatan</label>
                        <select wire:model.live="kecamatan" class="w-full bg-white dark:bg-[#1e1b4b] border border-slate-200 dark:border-white/20 rounded-xl px-4 py-2.5 text-xs font-bold text-navy-900 dark:text-white focus:outline-none focus:border-gold-500 transition-all shadow-sm cursor-pointer">
                            <option value="">Semua Kecamatan</option>
                            @foreach($allKecamatan as $kec)
                                <option value="{{ $kec->id_kecamatan }}">
                                    {{ $kec->nama_kecamatan }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-full">
                        <label class="text-xs font-black text-slate-400 uppercase tracking-widest block mb-2">Mulai Tanggal</label>
                        <input type="date" wire:model.live="start_date" class="w-full bg-white dark:bg-[#1e1b4b] border border-slate-200 dark:border-white/20 rounded-xl px-4 py-2.5 text-xs font-bold text-navy-900 dark:text-white focus:outline-none focus:border-gold-500 transition-all shadow-sm cursor-pointer">
                    </div>
                    <div class="w-full">
                        <label class="text-xs font-black text-slate-400 uppercase tracking-widest block mb-2">Sampai Tanggal</label>
                        <input type="date" wire:model.live="end_date" class="w-full bg-white dark:bg-[#1e1b4b] border border-slate-200 dark:border-white/20 rounded-xl px-4 py-2.5 text-xs font-bold text-navy-900 dark:text-white focus:outline-none focus:border-gold-500 transition-all shadow-sm cursor-pointer">
                    </div>
                </div>
            </div>
        </div>


        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gradient-to-r from-navy-900 to-navy-800 border-b border-navy-800 shadow-md text-xs font-black text-gold-500 uppercase tracking-widest">
                        <th class="px-2 py-4 w-12 border-b border-navy-800">No</th>
                    <th class="px-6 py-4 border-b border-navy-800">Infrastruktur</th>
                    <th class="px-6 py-4 border-b border-navy-800">Wilayah</th>
                    <th class="px-6 py-4 border-b border-navy-800">Surveyor</th>
                    <th class="px-6 py-4 border-b border-navy-800">Status Kondisi</th>
                    <th class="px-6 py-4 border-b border-navy-800">Status Validasi</th>
                    <th class="px-6 py-4 text-center border-b border-navy-800">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($allUsulan as $index => $item)
                <tr class="hover:bg-slate-50 dark:bg-[#0f0e2c]/50 transition-colors group">
                    <td class="px-2 py-5 whitespace-nowrap text-xs font-black text-slate-300">
                        {{ $show == 'all' ? sprintf('%02d', $index + 1) : sprintf('%02d', ($allUsulan->currentPage() - 1) * $allUsulan->perPage() + $index + 1) }}
                    </td>
                    <td class="px-6 py-5 min-w-[280px]">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-slate-100 overflow-hidden shadow-inner border border-white flex-shrink-0 flex items-center justify-center relative">
                                @if($item->foto_terbaru)
                                    <img src="{{ asset('storage/' . $item->foto_terbaru) }}" class="w-full h-full object-cover">
                                @else
                                    <i class="fas fa-image text-slate-300"></i>
                                @endif
                            </div>
                            <div>
                                <h4 class="text-[13px] font-black text-navy-900 dark:text-white leading-tight mb-0.5">{{ $item->nama_objek }}</h4>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ $item->jenis }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-5 min-w-[150px]">
                        <p class="text-xs font-bold text-navy-900 dark:text-white mb-0.5">{{ $item->kelurahan->nama_kelurahan ?? '-' }}</p>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">{{ $item->kelurahan->kecamatan->nama_kecamatan ?? '-' }}</p>
                    </td>
                    <td class="px-6 py-5 min-w-[150px]">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-gold-50 border border-gold-100 flex items-center justify-center text-gold-500 font-bold text-xs uppercase shadow-sm">
                                {{ substr($item->user->name ?? 'A', 0, 1) }}
                            </div>
                            <p class="text-xs font-black text-navy-900 dark:text-white">{{ $item->user->name ?? 'Anonim' }}</p>
                        </div>
                    </td>
                    <td class="px-6 py-5 min-w-[150px]">
                        <div class="flex flex-col gap-2 items-start">
                            @php
                                $aiLabel = $item->analisis->label_prioritas ?? '';
                                $aiLabelLower = strtolower($aiLabel);
                                $aiScore = $item->analisis->skor_dt ?? null;
                                
                                $aiClass = 'bg-slate-50 dark:bg-[#0f0e2c] text-slate-600 border-slate-200 dark:border-white/20';
                                if (str_contains($aiLabelLower, 'berat')) {
                                    $aiClass = 'bg-[#be123c]/10 text-[#be123c] border-[#be123c]/30';
                                } elseif (str_contains($aiLabelLower, 'sedang') || str_contains($aiLabelLower, 'ringan')) {
                                    $aiClass = 'bg-[#d97706]/10 text-[#d97706] border-[#d97706]/30';
                                } elseif (str_contains($aiLabelLower, 'baik')) {
                                    $aiClass = 'bg-[#059669]/10 text-[#059669] border-[#059669]/30';
                                }
                            @endphp
                            <span class="px-2.5 py-1 rounded-md border text-xs font-black uppercase tracking-widest whitespace-nowrap {{ $aiClass }}">
                                {{ $aiLabel ?: 'Belum Dianalisis' }}
                            </span>
                            @if($aiScore !== null)
                                <span class="text-xs font-bold text-slate-400 uppercase flex items-center gap-1.5 tracking-widest mt-1">
                                    <i class="fas fa-chart-bar text-gold-500"></i> Skor Prioritas: {{ number_format($aiScore, 1) }}%
                                </span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-5 min-w-[140px]">
                        <div class="flex flex-col gap-2">
                            @php
                                $statusClass = match($item->status_validasi) {
                                    'Validated' => 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 border-emerald-100 dark:border-emerald-500/20',
                                    'Rejected' => 'bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 border-rose-100 dark:border-rose-500/20',
                                    default => 'bg-slate-50 dark:bg-[#0f0e2c] text-slate-600 border-slate-200 dark:border-white/20'
                                };
                                $statusIcon = match($item->status_validasi) {
                                    'Validated' => 'fa-check-double',
                                    'Rejected' => 'fa-times-circle',
                                    default => 'fa-clock'
                                };
                            @endphp
                            <span class="px-3 py-1.5 rounded-xl text-xs font-black uppercase tracking-widest border flex items-center gap-2 w-fit {{ $statusClass }}">
                                <i class="fas {{ $statusIcon }} text-xs"></i>
                                {{ $item->status_validasi == 'Validated' ? 'Diterima' : ($item->status_validasi == 'Rejected' ? 'Ditolak' : 'Menunggu') }}
                            </span>
                        </div>
                    </td>
                    <td class="px-6 py-5 min-w-[260px]">
                        <div class="flex items-center justify-center gap-2">
                            {{-- Detail --}}
                            <a href="{{ route('tim_teknis.infrastruktur.show', $item->id_infrastruktur) }}" class="flex items-center justify-center gap-2 px-3 py-2.5 bg-navy-50 text-navy-900 dark:text-white rounded-xl hover:bg-gold-500 hover:text-white transition-all border border-navy-100 shadow-sm group" title="Lihat Detail">
                                <i class="fas fa-eye text-xs group-hover:scale-110 transition-transform"></i>
                                <span class="text-xs font-black uppercase tracking-widest hidden 2xl:block">Detail</span>
                            </a>

                            {{-- ACC --}}
                            {{-- ACC --}}
                            @if($item->status_validasi == 'Pending')
                                <button type="button" wire:click="openModal({{ $item->id_infrastruktur }}, 'Validated')" class="flex-1 flex items-center justify-center gap-2 px-3 py-2.5 bg-[#059669] text-white rounded-xl hover:bg-[#047857] transition-all shadow-lg shadow-[#059669]/20 group border border-[#059669]" title="Setujui Validasi">
                                    <i class="fas fa-check text-xs group-hover:scale-110 transition-transform"></i>
                                    <span class="text-xs font-black uppercase tracking-widest">ACC</span>
                                </button>
                                
                                {{-- Tolak --}}
                                <button type="button" wire:click="openModal({{ $item->id_infrastruktur }}, 'Rejected')" class="flex-1 flex items-center justify-center gap-2 px-3 py-2.5 bg-rose-50 text-rose-600 rounded-xl hover:bg-rose-500 hover:text-white transition-all border border-rose-200 shadow-sm group" title="Tolak Validasi">
                                    <i class="fas fa-times text-xs group-hover:scale-110 transition-transform"></i>
                                    <span class="text-xs font-black uppercase tracking-widest">Tolak</span>
                                </button>
                            @else
                                <button disabled class="flex-1 flex items-center justify-center gap-2 px-3 py-2.5 bg-slate-50 dark:bg-[#0f0e2c] text-slate-300 rounded-xl border border-slate-100 dark:border-white/10 cursor-not-allowed">
                                    <i class="fas fa-check text-xs"></i>
                                    <span class="text-xs font-black uppercase tracking-widest">ACC</span>
                                </button>
                                <button disabled class="flex-1 flex items-center justify-center gap-2 px-3 py-2.5 bg-slate-50 dark:bg-[#0f0e2c] text-slate-300 rounded-xl border border-slate-100 dark:border-white/10 cursor-not-allowed">
                                    <i class="fas fa-times text-xs"></i>
                                    <span class="text-xs font-black uppercase tracking-widest">Tolak</span>
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 md:px-8 py-12 md:py-20 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <div class="w-16 h-16 bg-slate-50 dark:bg-[#0f0e2c] rounded-full flex items-center justify-center text-slate-300">
                                <i class="fas fa-clipboard-check text-2xl"></i>
                            </div>
                            <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">Tidak ada data untuk divalidasi</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        </div>

        @if($show != 'all' && isset($allUsulan) && $allUsulan instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="px-8 py-4 border-t border-slate-50 bg-slate-50 dark:bg-[#0f0e2c]/10">
                {{ $allUsulan->links() }}
            </div>
        @endif
    </div>

    <!-- Livewire Validation Modal -->
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-end md:items-center justify-center bg-slate-900/50 backdrop-blur-sm transition-all duration-300 p-0 md:p-4">
        <div class="bg-white dark:bg-[#1e1b4b] rounded-t-[2rem] md:rounded-[2rem] shadow-2xl w-full max-w-md p-6 md:p-8 transform scale-100 opacity-100 transition-all duration-300">
            <div class="flex items-center justify-between mb-6">
                @if($modalAction === 'Rejected')
                    <h3 class="text-xl font-black text-rose-600">Tolak Validasi</h3>
                @else
                    <h3 class="text-xl font-black text-emerald-600">Setujui Validasi</h3>
                @endif
                <button type="button" wire:click="closeModal" class="w-8 h-8 flex items-center justify-center bg-slate-50 dark:bg-[#0f0e2c] text-slate-400 rounded-xl hover:bg-rose-50 hover:text-rose-500 transition-colors border border-slate-100 dark:border-white/10">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <p class="text-sm text-slate-500 mb-6 font-medium">
                @if($modalAction === 'Rejected')
                    Silakan masukkan <strong>alasan penolakan</strong> (Wajib diisi).
                @else
                    Silakan masukkan <strong>catatan persetujuan</strong> (Opsional).
                @endif
            </p>
            
            <div class="mb-8">
                @if($modalAction === 'Rejected')
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Catatan / Alasan Penolakan</label>
                    <textarea wire:model="alasan" rows="4" class="w-full bg-slate-50 dark:bg-[#0f0e2c] border border-slate-200 dark:border-white/20 rounded-2xl p-4 text-sm font-medium text-navy-900 dark:text-white focus:outline-none focus:border-rose-500 focus:ring-4 focus:ring-rose-500/20 transition-all placeholder:text-slate-300" placeholder="Ketik alasan penolakan di sini..."></textarea>
                    @error('alasan')
                    <p class="text-xs text-rose-500 mt-2 font-bold flex items-center gap-1.5">
                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                    </p>
                    @enderror
                @else
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Rekomendasi Penanganan (Manual)</label>
                    <textarea wire:model="rekomendasi_manual" rows="4" class="w-full bg-slate-50 dark:bg-[#0f0e2c] border border-slate-200 dark:border-white/20 rounded-2xl p-4 text-sm font-medium text-navy-900 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/20 transition-all placeholder:text-slate-300" placeholder="Ketik rekomendasi teknis di sini (opsional)..."></textarea>
                @endif
            </div>
            
            <div class="flex items-center justify-end gap-3">
                <button type="button" wire:click="closeModal" class="px-5 py-2.5 bg-white dark:bg-[#1e1b4b] border border-slate-200 dark:border-white/20 text-slate-500 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-50 dark:bg-[#0f0e2c] transition-colors">Batal</button>
                <button type="button" wire:click="prosesValidasi" class="px-5 py-2.5 {{ $modalAction === 'Rejected' ? 'bg-rose-500 hover:bg-rose-600 shadow-rose-500/20' : 'bg-emerald-500 hover:bg-emerald-600 shadow-emerald-500/20' }} text-white rounded-xl text-xs font-black uppercase tracking-widest transition-colors shadow-lg">
                    @if($modalAction === 'Rejected')
                        <i class="fas fa-times mr-2"></i> Konfirmasi Tolak
                    @else
                        <i class="fas fa-check mr-2"></i> Konfirmasi Setuju
                    @endif
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
