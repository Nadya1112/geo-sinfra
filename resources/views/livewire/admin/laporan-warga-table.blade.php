<div>
    <!-- Filters & Search -->
    <div class="bg-white p-5 rounded-[2rem] shadow-sm border border-slate-100 flex flex-col md:flex-row gap-4 items-center justify-between mb-4">
        <div class="flex flex-col md:flex-row gap-3 w-full">
            <div class="flex-1 relative">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari nama pelapor, deskripsi, atau no HP..." 
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
            <button wire:click="$set('search', ''); $set('status', 'all')" class="px-4 py-3 bg-red-50 text-red-600 font-bold text-xs uppercase tracking-widest rounded-xl hover:bg-red-100 transition-all text-center flex items-center justify-center shrink-0" title="Reset Filter">
                <i class="fas fa-times"></i>
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
                        <th class="px-4 py-3 font-extrabold uppercase tracking-widest text-xs text-gold-500">Waktu Lapor</th>
                        <th class="px-4 py-3 font-extrabold uppercase tracking-widest text-xs text-gold-500">Pelapor</th>
                        <th class="px-4 py-3 font-extrabold uppercase tracking-widest text-xs text-gold-500">Laporan Kerusakan</th>
                        <th class="px-4 py-3 font-extrabold uppercase tracking-widest text-xs text-gold-500 text-center">Status</th>
                        <th class="px-4 py-3 font-extrabold uppercase tracking-widest text-xs text-gold-500 text-center">Penugasan</th>
                        <th class="px-4 py-3 font-extrabold uppercase tracking-widest text-xs text-gold-500 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($laporanWarga as $laporan)
                    <tr class="hover:bg-slate-50/80 transition-colors group">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <i class="far fa-clock text-slate-400"></i>
                                <div>
                                    <p class="font-bold text-navy-900">{{ \Carbon\Carbon::parse($laporan->created_at)->format('d M Y') }}</p>
                                    <p class="text-xs text-slate-500 font-medium">{{ \Carbon\Carbon::parse($laporan->created_at)->format('H:i') }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <p class="font-bold text-navy-900">{{ $laporan->nama_pelapor }}</p>
                            <p class="text-xs font-semibold text-slate-500 mt-0.5"><i class="fas fa-phone-alt text-xs text-slate-400 mr-1"></i> {{ $laporan->no_hp }}</p>
                        </td>
                        <td class="px-4 py-3 min-w-[250px]">
                            <p class="text-sm font-medium text-slate-700 line-clamp-2 leading-relaxed mb-2">{{ $laporan->deskripsi }}</p>
                            
                            @if($laporan->label_ai)
                                @php
                                    $aiColor = 'bg-[#0f0e2c] text-white border-gold-500/50 shadow-gold-500/20';
                                    $aiIcon = 'fa-robot text-gold-500';
                                    $statusText = '';
                                    if(str_contains(strtolower($laporan->label_ai), 'berat')) {
                                        $statusText = '<span class="text-red-400 font-black">RUSAK BERAT</span>';
                                    } elseif(str_contains(strtolower($laporan->label_ai), 'sedang')) {
                                        $statusText = '<span class="text-orange-400 font-black">RUSAK SEDANG</span>';
                                    } elseif(str_contains(strtolower($laporan->label_ai), 'baik')) {
                                        $statusText = '<span class="text-emerald-400 font-black">BAIK</span>';
                                    } else {
                                        $statusText = '<span class="text-slate-300 font-black">' . strtoupper($laporan->label_ai) . '</span>';
                                    }
                                    $skorPercent = $laporan->skor_ai ? round($laporan->skor_ai * 100) . '%' : '';
                                @endphp
                                <div class="inline-flex items-center gap-2 px-3 py-1.5 mb-3 rounded-lg border {{ $aiColor }} text-xs uppercase tracking-wider shadow-sm">
                                    <i class="fas {{ $aiIcon }} animate-pulse"></i> 
                                    <span>Dianalisis AI: {!! $statusText !!} {!! $skorPercent ? "<span class='text-gold-500 font-black ml-1'>($skorPercent Yakin)</span>" : '' !!}</span>
                                </div>
                            @endif
                            
                            <div class="flex gap-2">
                                @if($laporan->foto)
                                    <button type="button" onclick="showPhotoModal('{{ asset('storage/' . $laporan->foto) }}')" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg text-xs font-bold uppercase tracking-wider transition-colors">
                                        <i class="fas fa-image"></i> Lihat Foto
                                    </button>
                                @endif
                                
                                <a href="https://www.google.com/maps/search/?api=1&query={{ $laporan->latitude }},{{ $laporan->longitude }}" target="_blank" 
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 text-emerald-600 hover:bg-emerald-100 rounded-lg text-xs font-bold uppercase tracking-wider transition-colors">
                                    <i class="fas fa-map-marker-alt"></i> Cek Lokasi
                                </a>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-center">
                            @php
                                $statusColor = 'bg-slate-100 text-slate-700 border-slate-200';
                                if($laporan->status == 'Menunggu') $statusColor = 'bg-yellow-50 text-yellow-700 border-yellow-200';
                                if($laporan->status == 'Ditinjau') $statusColor = 'bg-blue-50 text-blue-700 border-blue-200';
                                if($laporan->status == 'Diproses') $statusColor = 'bg-indigo-50 text-indigo-700 border-indigo-200';
                                if($laporan->status == 'Selesai') $statusColor = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                                if($laporan->status == 'Ditolak') $statusColor = 'bg-red-50 text-red-700 border-red-200';
                            @endphp
                            
                            <div class="relative inline-block w-36 text-left">
                                <select wire:change="updateStatus({{ $laporan->id }}, $event.target.value)" class="w-full appearance-none pl-3 pr-8 py-1.5 rounded-lg text-xs font-bold border {{ $statusColor }} focus:outline-none focus:ring-2 focus:ring-navy-500 cursor-pointer shadow-sm">
                                    <option value="Menunggu" {{ $laporan->status == 'Menunggu' ? 'selected' : '' }}>⏳ Menunggu</option>
                                    <option value="Ditinjau" {{ $laporan->status == 'Ditinjau' ? 'selected' : '' }}>👀 Ditinjau</option>
                                    <option value="Diproses" {{ $laporan->status == 'Diproses' ? 'selected' : '' }}>⚙️ Diproses</option>
                                    <option value="Selesai" {{ $laporan->status == 'Selesai' ? 'selected' : '' }}>✅ Selesai</option>
                                    <option value="Ditolak" {{ $laporan->status == 'Ditolak' ? 'selected' : '' }}>❌ Tidak Valid</option>
                                </select>
                                <i class="fas fa-chevron-down absolute right-2.5 top-1/2 -translate-y-1/2 text-xs opacity-60 pointer-events-none"></i>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-center">
                            @php
                                $assignColor = $laporan->id_surveyor ? 'bg-indigo-50 text-indigo-700 border-indigo-200' : 'bg-slate-100 text-slate-500 border-slate-200';
                            @endphp
                            
                            <div class="relative inline-block w-36 text-left">
                                <select wire:change="assignSurveyor({{ $laporan->id }}, $event.target.value)" class="w-full appearance-none pl-3 pr-8 py-1.5 rounded-lg text-xs font-bold border {{ $assignColor }} focus:outline-none focus:ring-2 focus:ring-indigo-500 cursor-pointer shadow-sm">
                                    <option value="" {{ !$laporan->id_surveyor ? 'selected' : '' }}>Pilih Surveyor</option>
                                    @foreach($surveyors as $surveyor)
                                        <option value="{{ $surveyor->id }}" {{ $laporan->id_surveyor == $surveyor->id ? 'selected' : '' }}>
                                            👷‍♂️ {{ $surveyor->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <i class="fas fa-chevron-down absolute right-2.5 top-1/2 -translate-y-1/2 text-xs opacity-60 pointer-events-none"></i>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-2 mx-auto">
                                @if(!$laporan->id_infrastruktur)
                                <a href="{{ route('admin.laporan-warga.convert', $laporan->id) }}" title="Verifikasi & Tindak Lanjuti" class="w-8 h-8 flex items-center justify-center bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg text-xs font-black transition shadow-sm hover:scale-105">
                                    <i class="fas fa-check-double"></i>
                                </a>
                                @else
                                <a href="{{ route('admin.infrastruktur.show', $laporan->id_infrastruktur) }}" title="Lihat Infrastruktur" class="w-8 h-8 flex items-center justify-center bg-navy-900 hover:bg-navy-950 text-white rounded-lg text-xs font-black transition shadow-sm hover:scale-105">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @endif
                                
                                <button type="button" wire:click="deleteLaporan({{ $laporan->id }})" wire:confirm="Apakah Anda yakin ingin menghapus laporan ini secara permanen?" class="w-8 h-8 flex items-center justify-center bg-rose-500 hover:bg-rose-600 text-white rounded-lg text-xs font-black transition shadow-sm hover:scale-105" title="Hapus Laporan">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
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
        <div class="p-6 border-t border-slate-100 bg-slate-50/50">
            {{ $laporanWarga->links() }}
        </div>
        @endif
    </div>
</div>
