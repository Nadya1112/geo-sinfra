<div>
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
        <div>
            <h4 class="font-extrabold text-lg text-navy-900 dark:text-white">Daftar Laporan Warga</h4>
            <p class="text-xs text-slate-400 font-medium text-left font-sans">Pantau dan kelola laporan kerusakan dari warga</p>
        </div>
        
        <div class="flex flex-col md:flex-row items-center gap-2 w-full md:w-auto">
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center w-full lg:w-[500px]">
                <select wire:model.live="status" class="w-full sm:w-auto sm:pl-3 sm:pr-7 py-2.5 bg-white dark:bg-navy-900/90 dark:backdrop-blur-xl border border-slate-100 dark:border-white/10 sm:border-r-0 rounded-t-xl sm:rounded-t-none sm:rounded-l-2xl text-[10px] md:text-xs font-bold text-navy-900 dark:text-white focus:outline-none focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 transition-all shadow-sm shrink-0">
                    <option value="all">Semua Status</option>
                    <option value="Menunggu">Menunggu</option>
                    <option value="Ditinjau">Ditinjau</option>
                    <option value="Diproses">Diproses</option>
                    <option value="Selesai">Selesai</option>
                    <option value="Ditolak">Ditolak</option>
                </select>
                <div class="relative w-full sm:flex-1">
                    <input type="text" 
                        wire:model.live.debounce.300ms="search"
                        placeholder="Cari nama, deskripsi..." 
                        class="w-full pl-3 pr-10 py-2.5 bg-white dark:bg-navy-900/90 dark:backdrop-blur-xl border-y sm:border-y border-x sm:border-x-0 border-slate-100 dark:border-white/10 text-[10px] md:text-xs font-semibold focus:outline-none focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 transition-all shadow-sm">
                    <div wire:loading wire:target="search" class="absolute right-3 top-1/2 -translate-y-1/2">
                        <i class="fas fa-circle-notch fa-spin text-gold-500 text-xs"></i>
                    </div>
                </div>
                <button type="button" wire:click="$set('search', ''); $set('status', 'all')" class="w-full sm:w-auto bg-white dark:bg-navy-900/90 dark:backdrop-blur-xl border border-slate-100 dark:border-white/10 sm:border-y sm:border-r sm:border-l-0 px-4 md:px-5 py-2.5 rounded-b-xl sm:rounded-b-none sm:rounded-r-2xl hover:bg-slate-50 dark:hover:bg-white/5 dark:bg-navy-950/50 transition-all shadow-sm group shrink-0 relative" title="Reset Filter">
                    <i class="fas fa-times text-slate-400 group-hover:text-gold-500 transition-colors text-xs" wire:loading.remove wire:target="search"></i>
                    <i class="fas fa-circle-notch fa-spin text-gold-500 text-xs hidden" wire:loading.inline-block wire:target="search"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Table Container -->
    <div class="bg-white dark:bg-navy-900/90 dark:backdrop-blur-xl rounded-[2rem] shadow-xl shadow-slate-200/40 border border-slate-100 dark:border-white/10 overflow-hidden relative">

        <div class="overflow-x-auto custom-scrollbar">
            <!-- Table Layout (Desktop & Tablet) -->
            <table class="w-full text-left text-sm whitespace-nowrap md:whitespace-normal hidden md:table">
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
                <tbody class="divide-y divide-slate-100 dark:divide-white/10">
                    @forelse($laporanWarga as $laporan)
                    <tr class="hover:bg-slate-50/80 transition-colors group">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <i class="far fa-clock text-slate-400"></i>
                                <div>
                                    <p class="font-bold text-navy-900 dark:text-white">{{ \Carbon\Carbon::parse($laporan->created_at)->format('d M Y') }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">{{ \Carbon\Carbon::parse($laporan->created_at)->format('H:i') }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <p class="font-bold text-navy-900 dark:text-white">{{ $laporan->nama_pelapor }}</p>
                            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-0.5"><i class="fas fa-phone-alt text-xs text-slate-400 mr-1"></i> {{ $laporan->no_hp }}</p>
                        </td>
                        <td class="px-4 py-3 min-w-[250px]">
                            <p class="text-sm font-medium text-slate-700 dark:text-slate-300 line-clamp-2 leading-relaxed mb-2">{{ $laporan->deskripsi }}</p>
                            
                            @if($laporan->label_ai)
                                @php
                                    $aiColor = 'bg-[#0f0e2c] text-white border-gold-500/50 shadow-gold-500/20';
                                    $aiIcon = 'fa-robot text-gold-500';
                                    $statusText = '';
                                    if(str_contains(strtolower($laporan->label_ai), 'rusak berat')) {
                                        $statusText = '<span class="text-red-400 font-black">KONDISI RUSAK BERAT</span>';
                                    } elseif(str_contains(strtolower($laporan->label_ai), 'rusak sedang')) {
                                        $statusText = '<span class="text-orange-400 font-black">KONDISI RUSAK SEDANG</span>';
                                    } elseif(str_contains(strtolower($laporan->label_ai), 'rusak ringan')) {
                                        $statusText = '<span class="text-yellow-400 font-black">KONDISI RUSAK RINGAN</span>';
                                    } elseif(str_contains(strtolower($laporan->label_ai), 'baik')) {
                                        $statusText = '<span class="text-emerald-400 font-black">KONDISI BAIK</span>';
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
                                    <button type="button" onclick="showPhotoModal('{{ asset('storage/' . $laporan->foto) }}')" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-500 hover:bg-blue-100 rounded-lg text-xs font-bold uppercase tracking-wider transition-colors">
                                        <i class="fas fa-image"></i> Lihat Foto
                                    </button>
                                @endif
                                
                                <a href="https://www.google.com/maps/search/?api=1&query={{ $laporan->latitude }},{{ $laporan->longitude }}" target="_blank" 
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-500 hover:bg-emerald-100 rounded-lg text-xs font-bold uppercase tracking-wider transition-colors">
                                    <i class="fas fa-map-marker-alt"></i> Cek Lokasi
                                </a>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-center">
                            @php
                                $statusColor = 'bg-slate-100 dark:bg-navy-950/50 text-slate-700 dark:text-slate-300 border-slate-200';
                                if($laporan->status == 'Menunggu') $statusColor = 'bg-yellow-50 dark:bg-yellow-500/10 dark:bg-yellow-500/10 text-yellow-700 dark:text-yellow-500 dark:text-yellow-500 border-yellow-200 dark:border-yellow-500/20 dark:border-yellow-500/20';
                                if($laporan->status == 'Ditinjau') $statusColor = 'bg-blue-50 dark:bg-blue-500/10 dark:bg-blue-500/10 text-blue-700 dark:text-blue-500 dark:text-blue-500 border-blue-200 dark:border-blue-500/20 dark:border-blue-500/20';
                                if($laporan->status == 'Diproses') $statusColor = 'bg-indigo-50 dark:bg-indigo-500/10 dark:bg-indigo-500/10 text-indigo-700 dark:text-indigo-500 dark:text-indigo-500 border-indigo-200 dark:border-indigo-500/20 dark:border-indigo-500/20';
                                if($laporan->status == 'Selesai') $statusColor = 'bg-emerald-50 dark:bg-emerald-500/10 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-500 dark:text-emerald-500 border-emerald-200 dark:border-emerald-500/20 dark:border-emerald-500/20';
                                if($laporan->status == 'Ditolak') $statusColor = 'bg-red-50 dark:bg-red-500/10 dark:bg-red-500/10 text-red-700 dark:text-red-500 dark:text-red-500 border-red-200 dark:border-red-500/20 dark:border-red-500/20';
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
                                $assignColor = $laporan->id_surveyor ? 'bg-indigo-50 dark:bg-indigo-500/10 dark:bg-indigo-500/10 text-indigo-700 dark:text-indigo-500 dark:text-indigo-500 border-indigo-200 dark:border-indigo-500/20 dark:border-indigo-500/20' : 'bg-slate-100 dark:bg-navy-950/50 text-slate-500 dark:text-slate-400 border-slate-200';
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

            <!-- Card Layout (Mobile) -->
            <div class="flex flex-col md:hidden divide-y divide-slate-100 dark:divide-white/10">
                @forelse($laporanWarga as $laporan)
                <div class="p-4 hover:bg-slate-50/80 transition-colors">
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex items-center gap-2">
                            <i class="far fa-clock text-slate-400"></i>
                            <div>
                                <p class="font-bold text-navy-900 dark:text-white text-xs">{{ \Carbon\Carbon::parse($laporan->created_at)->format('d M Y') }}</p>
                                <p class="text-[10px] text-slate-500 dark:text-slate-400 font-medium">{{ \Carbon\Carbon::parse($laporan->created_at)->format('H:i') }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-navy-900 dark:text-white text-xs">{{ $laporan->nama_pelapor }}</p>
                            <p class="text-[10px] font-semibold text-slate-500 dark:text-slate-400 mt-0.5"><i class="fas fa-phone-alt text-[10px] text-slate-400 mr-1"></i> {{ $laporan->no_hp }}</p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <p class="text-xs font-medium text-slate-700 dark:text-slate-300 line-clamp-3 leading-relaxed mb-2">{{ $laporan->deskripsi }}</p>
                        
                        @if($laporan->label_ai)
                            @php
                                $aiColor = 'bg-[#0f0e2c] text-white border-gold-500/50 shadow-gold-500/20';
                                $aiIcon = 'fa-robot text-gold-500';
                                $statusText = '';
                                if(str_contains(strtolower($laporan->label_ai), 'rusak berat')) {
                                    $statusText = '<span class="text-red-400 font-black">KONDISI RUSAK BERAT</span>';
                                } elseif(str_contains(strtolower($laporan->label_ai), 'rusak sedang')) {
                                    $statusText = '<span class="text-orange-400 font-black">KONDISI RUSAK SEDANG</span>';
                                } elseif(str_contains(strtolower($laporan->label_ai), 'rusak ringan')) {
                                    $statusText = '<span class="text-yellow-400 font-black">KONDISI RUSAK RINGAN</span>';
                                } elseif(str_contains(strtolower($laporan->label_ai), 'baik')) {
                                    $statusText = '<span class="text-emerald-400 font-black">KONDISI BAIK</span>';
                                } else {
                                    $statusText = '<span class="text-slate-300 font-black">' . strtoupper($laporan->label_ai) . '</span>';
                                }
                                $skorPercent = $laporan->skor_ai ? round($laporan->skor_ai * 100) . '%' : '';
                            @endphp
                            <div class="inline-flex items-center gap-1.5 px-2 py-1 mb-2 rounded-lg border {{ $aiColor }} text-[10px] uppercase tracking-wider shadow-sm w-full">
                                <i class="fas {{ $aiIcon }} animate-pulse"></i> 
                                <span class="truncate">Dianalisis AI: {!! $statusText !!} {!! $skorPercent ? "<span class='text-gold-500 font-black ml-1'>($skorPercent)</span>" : '' !!}</span>
                            </div>
                        @endif
                        
                        <div class="flex gap-2">
                            @if($laporan->foto)
                                <button type="button" onclick="showPhotoModal('{{ asset('storage/' . $laporan->foto) }}')" class="flex-1 items-center justify-center gap-1.5 px-3 py-1.5 bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-500 hover:bg-blue-100 rounded-lg text-[10px] font-bold uppercase tracking-wider transition-colors">
                                    <i class="fas fa-image"></i> Lihat Foto
                                </button>
                            @endif
                            
                            <a href="https://www.google.com/maps/search/?api=1&query={{ $laporan->latitude }},{{ $laporan->longitude }}" target="_blank" 
                               class="flex-1 flex items-center justify-center gap-1.5 px-3 py-1.5 bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-500 hover:bg-emerald-100 rounded-lg text-[10px] font-bold uppercase tracking-wider transition-colors">
                                <i class="fas fa-map-marker-alt"></i> Cek Lokasi
                            </a>
                        </div>
                    </div>

                    <div class="flex flex-col gap-2 pt-3 border-t border-slate-100 dark:border-white/10">
                        <div class="flex gap-2">
                            @php
                                $statusColor = 'bg-slate-100 dark:bg-navy-950/50 text-slate-700 dark:text-slate-300 border-slate-200';
                                if($laporan->status == 'Menunggu') $statusColor = 'bg-yellow-50 dark:bg-yellow-500/10 dark:bg-yellow-500/10 text-yellow-700 dark:text-yellow-500 dark:text-yellow-500 border-yellow-200 dark:border-yellow-500/20 dark:border-yellow-500/20';
                                if($laporan->status == 'Ditinjau') $statusColor = 'bg-blue-50 dark:bg-blue-500/10 dark:bg-blue-500/10 text-blue-700 dark:text-blue-500 dark:text-blue-500 border-blue-200 dark:border-blue-500/20 dark:border-blue-500/20';
                                if($laporan->status == 'Diproses') $statusColor = 'bg-indigo-50 dark:bg-indigo-500/10 dark:bg-indigo-500/10 text-indigo-700 dark:text-indigo-500 dark:text-indigo-500 border-indigo-200 dark:border-indigo-500/20 dark:border-indigo-500/20';
                                if($laporan->status == 'Selesai') $statusColor = 'bg-emerald-50 dark:bg-emerald-500/10 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-500 dark:text-emerald-500 border-emerald-200 dark:border-emerald-500/20 dark:border-emerald-500/20';
                                if($laporan->status == 'Ditolak') $statusColor = 'bg-red-50 dark:bg-red-500/10 dark:bg-red-500/10 text-red-700 dark:text-red-500 dark:text-red-500 border-red-200 dark:border-red-500/20 dark:border-red-500/20';
                            @endphp
                            <div class="relative flex-1">
                                <select wire:change="updateStatus({{ $laporan->id }}, $event.target.value)" class="w-full appearance-none pl-2 pr-6 py-1.5 rounded-lg text-[10px] font-bold border {{ $statusColor }} focus:outline-none focus:ring-2 focus:ring-navy-500 cursor-pointer shadow-sm">
                                    <option value="Menunggu" {{ $laporan->status == 'Menunggu' ? 'selected' : '' }}>⏳ Menunggu</option>
                                    <option value="Ditinjau" {{ $laporan->status == 'Ditinjau' ? 'selected' : '' }}>👀 Ditinjau</option>
                                    <option value="Diproses" {{ $laporan->status == 'Diproses' ? 'selected' : '' }}>⚙️ Diproses</option>
                                    <option value="Selesai" {{ $laporan->status == 'Selesai' ? 'selected' : '' }}>✅ Selesai</option>
                                    <option value="Ditolak" {{ $laporan->status == 'Ditolak' ? 'selected' : '' }}>❌ Ditolak</option>
                                </select>
                                <i class="fas fa-chevron-down absolute right-2 top-1/2 -translate-y-1/2 text-[10px] opacity-60 pointer-events-none"></i>
                            </div>

                            @php
                                $assignColor = $laporan->id_surveyor ? 'bg-indigo-50 dark:bg-indigo-500/10 dark:bg-indigo-500/10 text-indigo-700 dark:text-indigo-500 dark:text-indigo-500 border-indigo-200 dark:border-indigo-500/20 dark:border-indigo-500/20' : 'bg-slate-100 dark:bg-navy-950/50 text-slate-500 dark:text-slate-400 border-slate-200';
                            @endphp
                            <div class="relative flex-1">
                                <select wire:change="assignSurveyor({{ $laporan->id }}, $event.target.value)" class="w-full appearance-none pl-2 pr-6 py-1.5 rounded-lg text-[10px] font-bold border {{ $assignColor }} focus:outline-none focus:ring-2 focus:ring-indigo-500 cursor-pointer shadow-sm">
                                    <option value="" {{ !$laporan->id_surveyor ? 'selected' : '' }}>Pilih Surveyor</option>
                                    @foreach($surveyors as $surveyor)
                                        <option value="{{ $surveyor->id }}" {{ $laporan->id_surveyor == $surveyor->id ? 'selected' : '' }}>
                                            👷‍♂️ {{ substr($surveyor->name, 0, 10) }}...
                                        </option>
                                    @endforeach
                                </select>
                                <i class="fas fa-chevron-down absolute right-2 top-1/2 -translate-y-1/2 text-[10px] opacity-60 pointer-events-none"></i>
                            </div>
                        </div>
                        
                        <div class="flex gap-2 mt-1">
                            @if(!$laporan->id_infrastruktur)
                            <a href="{{ route('admin.laporan-warga.convert', $laporan->id) }}" class="flex-1 flex items-center justify-center gap-1.5 bg-emerald-500 hover:bg-emerald-600 text-white py-1.5 rounded-lg text-[10px] font-black transition shadow-sm active:scale-95">
                                <i class="fas fa-check-double"></i> Tindak Lanjuti
                            </a>
                            @else
                            <a href="{{ route('admin.infrastruktur.show', $laporan->id_infrastruktur) }}" class="flex-1 flex items-center justify-center gap-1.5 bg-navy-900 hover:bg-navy-950 text-white py-1.5 rounded-lg text-[10px] font-black transition shadow-sm active:scale-95">
                                <i class="fas fa-eye"></i> Lihat Infrastruktur
                            </a>
                            @endif
                            
                            <button type="button" wire:click="deleteLaporan({{ $laporan->id }})" wire:confirm="Apakah Anda yakin ingin menghapus laporan ini secara permanen?" class="w-10 flex items-center justify-center bg-rose-500 hover:bg-rose-600 text-white rounded-lg text-xs font-black transition shadow-sm active:scale-95" title="Hapus Laporan">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="px-8 py-16 text-center">
                    <i class="fas fa-file-alt text-4xl text-slate-200 mb-4 block"></i>
                    <p class="text-slate-400 font-bold text-sm">Belum Ada Laporan Warga.</p>
                </div>
                @endforelse
            </div>
        </div>
        
        @if($laporanWarga->hasPages())
        <div class="p-6 border-t border-slate-100 dark:border-white/10 bg-slate-50/50">
            {{ $laporanWarga->links() }}
        </div>
        @endif
    </div>
</div>
