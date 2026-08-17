<div>
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

    <!-- Filters & Search -->
    <div class="bg-white p-5 rounded-[2rem] shadow-sm border border-slate-100 flex flex-col md:flex-row gap-4 items-center justify-between mb-4">
        <div class="flex flex-col md:flex-row gap-3 w-full">
            <div class="flex-1 relative">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama pelapor, deskripsi, atau no HP..." 
                       class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all font-medium">
            </div>
            <div class="w-full md:w-48 relative">
                <select wire:model.live="status" class="w-full pl-4 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-navy-900 focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 appearance-none">
                    <option value="all">Semua Status</option>
                    <option value="Menunggu">Menunggu</option>
                    <option value="Ditinjau">Ditinjau</option>
                    <option value="Diproses">Diproses</option>
                    <option value="Selesai">Selesai</option>
                    <option value="Ditolak">Ditolak</option>
                </select>
                <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
            </div>
            @if($search || ($status !== 'all'))
            <button wire:click="$set('search', ''); $set('status', 'all')" class="px-5 py-2.5 bg-red-50 text-red-600 font-bold text-xs uppercase tracking-widest rounded-xl hover:bg-red-100 transition-all text-center flex items-center justify-center shrink-0">
                Reset
            </button>
            @endif
        </div>
    </div>

    <!-- Table Container -->
    <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/40 border border-slate-100 overflow-hidden relative">
        <div wire:loading.delay class="absolute inset-0 bg-white/50 backdrop-blur-sm z-10 flex items-center justify-center">
            <i class="fas fa-circle-notch fa-spin text-4xl text-gold-500"></i>
        </div>
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left text-sm whitespace-nowrap md:whitespace-normal">
                <thead class="bg-gradient-to-r from-navy-900 to-navy-800 border-b border-navy-800 shadow-md">
                    <tr>
                        <th class="px-6 py-4 font-extrabold uppercase tracking-widest text-xs text-gold-500">Waktu Lapor</th>
                        <th class="px-6 py-4 font-extrabold uppercase tracking-widest text-xs text-gold-500">Pelapor</th>
                        <th class="px-6 py-4 font-extrabold uppercase tracking-widest text-xs text-gold-500">Laporan Kerusakan</th>
                        <th class="px-5 py-4 font-extrabold uppercase tracking-widest text-xs text-gold-500 text-center">Status</th>
                        <th class="px-5 py-4 font-extrabold uppercase tracking-widest text-xs text-gold-500 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($laporanWarga as $laporan)
                    <tr class="hover:bg-slate-50 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <i class="far fa-clock text-slate-400"></i>
                                <div>
                                    <p class="font-bold text-navy-900">{{ \Carbon\Carbon::parse($laporan->created_at)->format('d M Y') }}</p>
                                    <p class="text-xs text-slate-500 font-medium">{{ \Carbon\Carbon::parse($laporan->created_at)->format('H:i') }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-bold text-navy-900">{{ $laporan->nama_pelapor }}</p>
                            <p class="text-xs font-semibold text-slate-500 mt-0.5"><i class="fas fa-phone-alt text-xs text-slate-400 mr-1"></i> {{ $laporan->no_hp }}</p>
                        </td>
                        <td class="px-6 py-4 min-w-[250px]">
                            <p class="text-sm font-medium text-slate-700 line-clamp-2 leading-relaxed mb-2">{{ $laporan->deskripsi }}</p>
                            
                            @if($laporan->label_ai)
                                @php
                                    $aiColor = 'bg-slate-100 text-slate-600 border-slate-200';
                                    $aiIcon = 'fa-robot';
                                    if(str_contains(strtolower($laporan->label_ai), 'berat')) {
                                        $aiColor = 'bg-red-50 text-red-600 border-red-200';
                                        $aiIcon = 'fa-exclamation-triangle';
                                    } elseif(str_contains(strtolower($laporan->label_ai), 'sedang')) {
                                        $aiColor = 'bg-yellow-50 text-yellow-600 border-yellow-200';
                                        $aiIcon = 'fa-exclamation-circle';
                                    } elseif(str_contains(strtolower($laporan->label_ai), 'baik')) {
                                        $aiColor = 'bg-emerald-50 text-emerald-600 border-emerald-200';
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
                            @php
                                $statusColor = 'bg-slate-100 text-slate-700 border-slate-200';
                                if($laporan->status == 'Menunggu') $statusColor = 'bg-yellow-50 text-yellow-700 border-yellow-200';
                                if($laporan->status == 'Ditinjau') $statusColor = 'bg-blue-50 text-blue-700 border-blue-200';
                                if($laporan->status == 'Diproses') $statusColor = 'bg-indigo-50 text-indigo-700 border-indigo-200';
                                if($laporan->status == 'Selesai') $statusColor = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                                if($laporan->status == 'Ditolak') $statusColor = 'bg-red-50 text-red-700 border-red-200';
                                
                                $statusIcon = 'fa-clock';
                                if($laporan->status == 'Menunggu') $statusIcon = 'fa-hourglass-half';
                                if($laporan->status == 'Ditinjau') $statusIcon = 'fa-eye';
                                if($laporan->status == 'Diproses') $statusIcon = 'fa-cog fa-spin';
                                if($laporan->status == 'Selesai') $statusIcon = 'fa-check-circle';
                                if($laporan->status == 'Ditolak') $statusIcon = 'fa-times-circle';
                            @endphp
                            
                            <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border {{ $statusColor }} text-xs font-bold tracking-wider shadow-sm">
                                <i class="fas {{ $statusIcon }}"></i> {{ $laporan->status }}
                            </div>
                        </td>
                        <td class="px-4 py-2 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <a href="{{ route('surveyor.laporan.edit', $laporan->id) }}" class="w-7 h-7 flex items-center justify-center bg-white border border-slate-200 text-slate-400 rounded-md hover:bg-gold-500 hover:text-white hover:border-gold-500 hover:shadow-sm transition-all cursor-pointer" title="Ubah Data">
                                    <i class="fas fa-pen text-xs"></i>
                                </a>
                                <a href="{{ route('surveyor.laporan.show', $laporan->id) }}" class="w-7 h-7 flex items-center justify-center bg-navy-900 text-gold-500 rounded-md hover:bg-navy-950 hover:text-white transition-all shadow-sm cursor-pointer" title="Lihat Detail">
                                    <i class="fas fa-eye text-xs"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-20 text-center">
                            <i class="fas fa-file-alt text-4xl text-slate-200 mb-4 block"></i>
                            <p class="text-slate-400 font-bold text-sm">Belum Ada Laporan Warga.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($laporanWarga->hasPages())
        <div class="p-6 border-t border-slate-100 bg-slate-50">
            {{ $laporanWarga->links() }}
        </div>
        @endif
    </div>
</div>
