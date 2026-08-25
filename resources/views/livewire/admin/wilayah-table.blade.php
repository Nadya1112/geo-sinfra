<div>
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
        <div>
            <h4 class="font-extrabold text-lg text-navy-900">DATA MASTER WILAYAH</h4>
            <p class="text-xs text-slate-400 font-medium text-left">Kelola data wilayah cakupan pemetaan infrastruktur</p>
        </div>
        
        <div class="flex flex-wrap items-center gap-2 w-full md:w-auto relative z-20">
            <div class="flex items-center flex-1 min-w-0 md:w-[400px]">
                <select wire:model.live="show" class="pl-3 pr-7 py-2.5 bg-white border border-slate-100 border-r-0 rounded-l-2xl text-[10px] md:text-xs font-bold text-navy-900 focus:outline-none focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 transition-all shadow-sm shrink-0">
                    <option value="10">10 Data</option>
                    <option value="all">Semua Data</option>
                </select>
                <div class="relative flex-1 min-w-[80px]">
                    <input type="text" 
                        wire:model.live.debounce.300ms="search"
                        placeholder="Cari nama kecamatan..." 
                        class="w-full pl-3 pr-10 py-2.5 bg-white border border-slate-100 text-[10px] md:text-xs font-semibold focus:outline-none focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 transition-all shadow-sm">
                    <div wire:loading wire:target="search" class="absolute right-3 top-1/2 -translate-y-1/2">
                        <i class="fas fa-circle-notch fa-spin text-gold-500 text-xs"></i>
                    </div>
                </div>
                <button type="button" class="bg-white border-y border-r border-slate-100 px-4 md:px-5 py-2.5 rounded-r-2xl hover:bg-slate-50 transition-all shadow-sm group shrink-0 relative">
                    <i class="fas fa-search text-slate-400 group-hover:text-gold-500 transition-colors text-xs" wire:loading.remove wire:target="search"></i>
                    <i class="fas fa-circle-notch fa-spin text-gold-500 text-xs hidden" wire:loading.inline-block wire:target="search"></i>
                </button>
            </div>

            <a href="{{ route('admin.wilayah.create') }}" class="bg-gold-500 text-white text-xs px-4 md:px-6 py-2.5 rounded-2xl font-bold shadow-lg shadow-gold-500/10 hover:bg-gold-600 hover:shadow-gold-500/20 transition flex items-center justify-center gap-2 whitespace-nowrap shrink-0">
                <i class="fas fa-plus text-xs"></i> <span class="hidden sm:inline">Tambah Wilayah</span>
            </a>
        </div>
    </div>

    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden mb-10 relative">


        <div class="overflow-x-auto w-full custom-scrollbar">
            <!-- Table Layout (Desktop & Tablet) -->
            <table class="w-full text-left border-collapse hidden md:table">
            <thead>
                <tr class="bg-gradient-to-r from-navy-900 to-navy-800 border-b border-navy-800 shadow-md">
                    <th class="px-4 py-3 text-xs font-black text-gold-500 uppercase tracking-widest w-24 text-center">No.</th>
                    <th class="px-4 py-3 text-xs font-black text-gold-500 uppercase tracking-widest">Nama Kecamatan</th>
                    <th class="px-4 py-3 text-xs font-black text-gold-500 uppercase tracking-widest">Kelurahan</th>
                    <th class="px-4 py-3 text-xs font-black text-gold-500 uppercase tracking-widest text-center">Total Infrastruktur</th>
                    <th class="px-4 py-3 text-xs font-black text-gold-500 uppercase tracking-widest text-center w-32">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($wilayah as $index => $wly)
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="px-4 py-3 text-center">
                        <span class="px-3 py-1.5 bg-slate-100 text-slate-600 rounded-lg text-xs font-bold font-mono">
                            {{ $show == 'all' ? $index + 1 : ($wilayah->currentPage() - 1) * $wilayah->perPage() + $index + 1 }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <p class="text-xs font-black text-navy-900 uppercase leading-none">{{ $wly->nama_kecamatan }}</p>
                    </td>
                    <td class="px-4 py-3">
                        <p class="text-sm font-bold text-navy-900 leading-relaxed max-w-sm truncate" title="{{ $wly->nama_kelurahan }}">
                            {{ $wly->nama_kelurahan ?? '-' }}
                        </p>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="px-3 py-1 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded-xl text-xs font-black">
                            {{ $wly->total_aset ?? 0 }} Titik
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex justify-center gap-2">
                            <a href="{{ route('admin.wilayah.edit', $wly->id_kelurahan) }}" title="Ubah Wilayah" class="w-8 h-8 flex items-center justify-center bg-gold-500 hover:bg-gold-600 text-white rounded-lg text-xs font-black transition shadow-sm hover:scale-105">
                                <i class="fas fa-edit"></i>
                            </a>
                            
                            <form action="{{ route('admin.wilayah.destroy', $wly->id_kelurahan) }}" method="POST" class="inline-block m-0 p-0" onsubmit="return confirm('PERINGATAN!\n\nApakah Anda yakin ingin menghapus data kelurahan {{ $wly->nama_kelurahan }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="Hapus Wilayah" class="w-8 h-8 flex items-center justify-center bg-rose-500 hover:bg-rose-600 text-white rounded-lg text-xs font-black transition shadow-sm hover:scale-105">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-8 py-10 text-center text-xs font-semibold text-gray-400">
                        <i class="fas fa-folder-open text-2xl mb-2 block text-gray-300"></i>
                        Belum ada data Master Wilayah yang ditambahkan.
                    </td>
                </tr>
                @endforelse
            </tbody>
            </table>

            <!-- Card Layout (Mobile) -->
            <div class="flex flex-col md:hidden divide-y divide-slate-100">
                @forelse($wilayah as $index => $wly)
                <div class="p-4 hover:bg-slate-50 transition">
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <div class="flex-1 min-w-0">
                            <h5 class="text-sm font-black text-navy-900 uppercase leading-none truncate mb-1">{{ $wly->nama_kecamatan }}</h5>
                            <p class="text-xs font-bold text-navy-900 leading-relaxed truncate">{{ $wly->nama_kelurahan ?? '-' }}</p>
                        </div>
                        <span class="px-3 py-1 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded-xl text-[10px] font-black whitespace-nowrap shrink-0">
                            {{ $wly->total_aset ?? 0 }} Titik
                        </span>
                    </div>
                    
                    <div class="flex items-center justify-end gap-2 mt-3 pt-3 border-t border-slate-100">
                        <a href="{{ route('admin.wilayah.edit', $wly->id_kelurahan) }}" title="Ubah Wilayah" class="w-8 h-8 flex items-center justify-center bg-gold-500 hover:bg-gold-600 text-white rounded-lg text-xs font-black transition shadow-sm active:scale-95">
                            <i class="fas fa-edit"></i>
                        </a>
                        
                        <form action="{{ route('admin.wilayah.destroy', $wly->id_kelurahan) }}" method="POST" class="inline-block m-0 p-0" onsubmit="return confirm('PERINGATAN!\n\nApakah Anda yakin ingin menghapus data kelurahan {{ $wly->nama_kelurahan }}?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" title="Hapus Wilayah" class="w-8 h-8 flex items-center justify-center bg-rose-500 hover:bg-rose-600 text-white rounded-lg text-xs font-black transition shadow-sm active:scale-95">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="p-8 text-center text-xs font-semibold text-gray-400">
                    <i class="fas fa-folder-open text-2xl mb-2 block text-gray-300"></i>
                    Belum ada data Master Wilayah yang ditambahkan.
                </div>
                @endforelse
            </div>
        </div>
        @if($show != 'all' && isset($wilayah) && $wilayah instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="px-8 py-4 border-t border-gray-50 bg-gray-50/10">
                {{ $wilayah->links() }}
            </div>
        @endif
    </div>
</div>
