<div>
    <div class="flex justify-between items-end mb-6 flex-col md:flex-row gap-4">
        <div>
            <h3 class="text-lg font-black text-navy-900">Daftar Data Lapangan</h3>
            <p class="text-xs text-slate-400 font-medium mt-1">Seluruh laporan infrastruktur yang telah Anda kumpulkan.</p>
        </div>
        <div class="flex flex-col md:flex-row gap-2 w-full md:w-auto mt-3 md:mt-0">
            <div class="relative w-full md:w-64">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari infrastruktur..." class="w-full pl-10 pr-4 py-3 bg-white border border-slate-200 rounded-xl text-xs font-bold text-navy-900 shadow-sm focus:outline-none focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 transition-all">
            </div>
            <select wire:model.live="status" class="w-full md:w-40 bg-white border border-slate-200 rounded-xl px-4 py-3 text-xs font-black text-navy-900 shadow-sm focus:outline-none focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 transition-all cursor-pointer">
                <option value="">Semua Status</option>
                <option value="Menunggu">Menunggu</option>
                <option value="Terverifikasi">Terverifikasi AI</option>
                <option value="Di-ACC">Di-ACC</option>
                <option value="Ditolak">Ditolak</option>
            </select>
            <select wire:model.live="show" class="w-full md:w-48 bg-white border border-slate-200 rounded-xl px-4 py-3 text-xs font-black text-navy-900 shadow-sm focus:outline-none focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 transition-all cursor-pointer">
                <option value="">Tampilkan 10 Data</option>
                <option value="all">Tampilkan Semua</option>
            </select>
            
            @if($search || $status || $show)
            <button wire:click="$set('search', ''); $set('status', ''); $set('show', '')" class="px-4 py-3 bg-red-50 text-red-600 font-bold text-xs uppercase tracking-widest rounded-xl hover:bg-red-100 transition-all text-center flex items-center justify-center shrink-0" title="Reset Filter">
                <i class="fas fa-times"></i>
            </button>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden mb-10 relative">
        <div wire:loading.delay class="absolute inset-0 bg-white/50 backdrop-blur-sm z-10 flex items-center justify-center">
            <i class="fas fa-circle-notch fa-spin text-4xl text-gold-500"></i>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gradient-to-r from-navy-900 to-navy-800 border-b border-navy-800 shadow-md">
                        <th class="px-4 py-2 text-xs font-black text-gold-500 uppercase tracking-widest text-center w-12">NO</th>
                        <th class="px-4 py-2 text-xs font-black text-gold-500 uppercase tracking-widest w-20 text-center">FOTO</th>
                        <th class="px-4 py-2 text-xs font-black text-gold-500 uppercase tracking-widest">INFRASTRUKTUR</th>
                        <th class="px-4 py-2 text-xs font-black text-gold-500 uppercase tracking-widest">WILAYAH</th>
                        <th class="px-4 py-2 text-xs font-black text-gold-500 uppercase tracking-widest text-center">STATUS VALIDASI</th>
                        <th class="px-4 py-2 text-xs font-black text-gold-500 uppercase tracking-widest text-center">STATUS KONDISI</th>
                        <th class="px-4 py-2 text-xs font-black text-gold-500 uppercase tracking-widest text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($riwayat as $index => $item)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-4 py-2 text-center">
                            <span class="text-xs font-black text-slate-400">{{ $show == 'all' ? $index + 1 : ($riwayat->currentPage() - 1) * $riwayat->perPage() + $index + 1 }}</span>
                        </td>

                        {{-- FOTO --}}
                        <td class="px-4 py-2 text-center">
                            <div class="w-10 h-10 rounded-xl overflow-hidden shadow-sm mx-auto bg-slate-100 flex items-center justify-center relative">
                                @if($item->foto_terbaru)
                                    @php $cleanPath = str_replace('\\', '/', $item->foto_terbaru); @endphp
                                    <img src="{{ asset('storage/' . (str_contains($cleanPath, 'infrastruktur/') ? $cleanPath : 'infrastruktur/' . $cleanPath)) }}" class="w-full h-full object-cover">
                                @else
                                    <i class="fas fa-image text-slate-300 text-sm"></i>
                                @endif

                                {{-- Indikator Verified (Ceklis Hijau) --}}
                                @if($item->status_verifikasi == 'Verified')
                                <div class="absolute -top-1 -right-1 w-5 h-5 bg-emerald-500 rounded-full border-[2px] border-white flex items-center justify-center shadow-sm">
                                    <i class="fas fa-check text-xs text-white"></i>
                                </div>
                                @endif
                            </div>
                        </td>

                        {{-- NAMA INFRASTRUKTUR --}}
                        <td class="px-4 py-2">
                            <p class="text-xs font-black text-navy-900 uppercase tracking-tight mb-0.5">{{ $item->nama_infrastruktur ?? $item->nama_objek }}</p>
                            <span class="inline-flex px-1.5 py-0.5 bg-navy-50 text-navy-600 rounded-md text-xs font-black uppercase tracking-widest">{{ ucfirst($item->jenis) }}</span>
                        </td>

                        {{-- LOKASI --}}
                        <td class="px-4 py-2">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-md bg-gold-50 text-gold-500 flex items-center justify-center shrink-0">
                                    <i class="fas fa-map-marker-alt text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-black text-navy-900 uppercase tracking-wider">{{ $item->kelurahan ? $item->kelurahan->nama_kelurahan : '-' }}</p>
                                    <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-0.5">
                                        KEC. {{ $item->kelurahan && $item->kelurahan->kecamatan ? $item->kelurahan->kecamatan->nama_kecamatan : '-' }}
                                    </p>
                                </div>
                            </div>
                        </td>

                        {{-- STATUS VALIDASI --}}
                        <td class="px-4 py-2 text-center">
                            @if($item->status_validasi == 'Rejected')
                                <div class="inline-flex flex-col items-center">
                                    <span class="inline-flex px-2 py-0.5 bg-red-100 text-red-600 border border-red-200 rounded-md text-xs font-black uppercase tracking-widest shadow-sm mb-0.5">Ditolak</span>
                                    <button onclick="alert('Alasan Penolakan: {{ addslashes($item->alasan_penolakan) }}')" class="text-xs font-bold text-red-500 hover:text-red-700 underline cursor-pointer">Lihat Alasan</button>
                                </div>
                            @elseif($item->status_validasi == 'Validated')
                                <span class="inline-flex px-2 py-1 bg-emerald-100 text-emerald-600 border border-emerald-200 rounded-lg text-xs font-black uppercase tracking-widest shadow-sm">Di-ACC</span>
                            @elseif($item->status_verifikasi == 'Verified')
                                <span class="inline-flex px-2 py-1 bg-blue-100 text-blue-600 border border-blue-200 rounded-lg text-xs font-black uppercase tracking-widest shadow-sm">Terverifikasi</span>
                            @else
                                <span class="inline-flex px-2 py-1 bg-slate-100 text-slate-500 border border-slate-200 rounded-lg text-xs font-black uppercase tracking-widest shadow-sm">Menunggu</span>
                            @endif
                        </td>

                        {{-- SKOR AI --}}
                        <td class="px-4 py-2">
                            @if($item->cnn || $item->analisis)
                                <div class="flex justify-center">
                                    <div class="inline-flex items-center bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                                        @if($item->analisis)
                                            @php
                                                $labelMap = [
                                                    'Baik'        => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-600', 'icon' => 'fa-check-circle'],
                                                    'Rusak Sedang'=> ['bg' => 'bg-orange-50',  'text' => 'text-orange-600',  'icon' => 'fa-hammer'],
                                                    'Rusak Berat' => ['bg' => 'bg-red-50',     'text' => 'text-red-600',     'icon' => 'fa-exclamation-triangle'],
                                                ];
                                                $style = $labelMap[$item->analisis->label_prioritas] ?? ['bg' => 'bg-slate-50', 'text' => 'text-slate-600', 'icon' => 'fa-info-circle'];
                                            @endphp
                                            <div class="flex items-center gap-1.5 px-3 py-1.5 {{ $style['bg'] }}">
                                                <i class="fas {{ $style['icon'] }} {{ $style['text'] }} text-xs"></i>
                                                <span class="text-xs font-black {{ $style['text'] }} uppercase tracking-wider">{{ $item->analisis->label_prioritas }}</span>
                                            </div>
                                        @else
                                            <div class="flex items-center gap-1 px-3 py-1.5 bg-slate-50">
                                                <span class="text-xs font-black text-slate-500 uppercase tracking-wider">Menunggu Status</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <p class="text-xs text-slate-400 font-bold uppercase text-center">-</p>
                            @endif
                        </td>

                        {{-- AKSI --}}
                        <td class="px-4 py-2 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <a href="{{ route('surveyor.infrastruktur.edit', $item->id_infrastruktur) }}" class="w-7 h-7 flex items-center justify-center bg-white border border-slate-200 text-slate-400 rounded-md hover:bg-gold-500 hover:text-white hover:border-gold-500 hover:shadow-sm transition-all cursor-pointer" title="Ubah Data">
                                    <i class="fas fa-pen text-xs"></i>
                                </a>
                                <a href="{{ route('surveyor.infrastruktur.show', $item->id_infrastruktur) }}" class="w-7 h-7 flex items-center justify-center bg-navy-900 text-gold-500 rounded-md hover:bg-navy-950 hover:text-white transition-all shadow-sm cursor-pointer" title="Lihat Detail">
                                    <i class="fas fa-eye text-xs"></i>
                                </a>

                                {{-- Tombol Hapus: hanya muncul jika data masih Pending --}}
                                @if($item->status_verifikasi === 'Pending')
                                <button
                                    onclick="konfirmasiHapus({{ $item->id_infrastruktur }}, '{{ addslashes($item->nama_objek ?? $item->nama_infrastruktur) }}')"
                                    class="w-7 h-7 flex items-center justify-center bg-white border border-red-200 text-red-400 rounded-md hover:bg-red-500 hover:text-white hover:border-red-500 hover:shadow-sm transition-all cursor-pointer"
                                    title="Hapus Data (hanya Pending)">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                                <form id="form-hapus-{{ $item->id_infrastruktur }}"
                                    action="{{ route('surveyor.infrastruktur.destroy', $item->id_infrastruktur) }}"
                                    method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-8 py-24 text-center">
                            <div class="flex flex-col items-center gap-4">
                                <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center text-slate-300">
                                    <i class="fas fa-folder-open text-5xl"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-navy-900 font-black uppercase tracking-wider mb-1">Riwayat Kosong</p>
                                    <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">Anda belum memiliki riwayat survey lapangan.</p>
                                </div>
                                <a href="{{ route('surveyor.input') }}" class="mt-4 px-8 py-3 bg-gold-500 text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-md shadow-gold-500/20 hover:bg-gold-600 transition-all active:scale-95">
                                    Mulai Survey Pertama
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($show != 'all' && isset($riwayat) && $riwayat instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="px-8 py-5 border-t border-slate-100 bg-white">
                {{ $riwayat->links() }}
            </div>
        @endif
    </div>
</div>
