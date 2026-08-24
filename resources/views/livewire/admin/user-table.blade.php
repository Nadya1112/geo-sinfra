<div>
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
        <div>
            <h4 class="font-extrabold text-lg text-navy-900">Daftar Pengguna Sistem</h4>
            <p class="text-xs text-slate-400 font-medium text-left font-sans">Kelola hak akses untuk Admin, Surveyor, dan Tim Teknis</p>
        </div>
        
        <div class="flex flex-row flex-nowrap items-center gap-2 w-full md:w-auto">
            <div class="flex items-center flex-1 min-w-0 md:w-[400px]">
                <select wire:model.live="show" class="pl-3 pr-7 py-2.5 bg-white border border-slate-100 border-r-0 rounded-l-2xl text-[10px] md:text-xs font-bold text-navy-900 focus:outline-none focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 transition-all shadow-sm shrink-0">
                    <option value="10">Per 10 Data</option>
                    <option value="all">Semua Data</option>
                </select>
                <div class="relative flex-1 min-w-[80px]">
                    <input type="text" 
                        wire:model.live.debounce.300ms="search"
                        placeholder="Ketik nama pengguna..." 
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

            <a href="{{ route('admin.users.create') }}" class="bg-gold-500 text-white text-xs px-4 md:px-6 py-2.5 rounded-2xl font-bold shadow-lg shadow-gold-500/10 hover:bg-gold-600 hover:shadow-gold-500/20 transition flex items-center justify-center gap-2 whitespace-nowrap shrink-0">
                <i class="fas fa-user-plus text-xs"></i> <span class="hidden sm:inline">Tambah User</span>
            </a>
        </div>
    </div>

    <div class="bg-white rounded-3xl md:rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden mb-10 relative">
        <!-- Loading Overlay for Table -->
        <div wire:loading class="absolute inset-0 bg-white/60 backdrop-blur-sm z-10 flex flex-col items-center justify-center">
            <div class="w-12 h-12 border-4 border-slate-200 border-t-gold-500 rounded-full animate-spin"></div>
            <p class="mt-3 text-xs font-bold text-navy-900">Memuat Data...</p>
        </div>

        <div class="overflow-x-auto w-full custom-scrollbar">
            <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gradient-to-r from-navy-900 to-navy-800 border-b border-navy-800 shadow-md">
                    <th class="px-4 md:px-4 py-3 text-xs font-black text-gold-500 uppercase tracking-widest w-12 text-center">No.</th>
                    <th class="px-4 md:px-4 py-3 text-xs font-black text-gold-500 uppercase tracking-widest">Nama User</th>
                    <th class="px-4 md:px-4 py-3 text-xs font-black text-gold-500 uppercase tracking-widest">Alamat Email</th>
                    <th class="px-4 md:px-4 py-3 text-xs font-black text-gold-500 uppercase tracking-widest">Role / Jabatan</th>
                    <th class="px-4 md:px-4 py-3 text-xs font-black text-gold-500 uppercase tracking-widest text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($users as $index => $user)
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="px-4 md:px-4 py-3 text-center">
                        <span class="text-xs font-black text-navy-900">{{ $show == 'all' ? $index + 1 : ($users->currentPage() - 1) * $users->perPage() + $index + 1 }}</span>
                    </td>
                    <td class="px-4 md:px-4 py-3">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 {{ $user->role == 'admin' ? 'bg-gold-500/10 text-gold-500 border-gold-500/20' : ($user->role == 'tim_teknis' ? 'bg-navy-900/10 text-navy-900 border-navy-900/20' : 'bg-slate-100 text-slate-600 border-slate-200') }} rounded-xl flex items-center justify-center font-bold text-xs border shrink-0">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-xs font-black text-navy-900 uppercase leading-none">{{ $user->name }}</p>
                                <p class="text-xs text-slate-400 font-bold uppercase mt-1 italic">ID: #{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 md:px-4 py-3 text-xs font-medium text-slate-500">{{ $user->email }}</td>
                    <td class="px-4 md:px-4 py-3">
                        <span class="px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-tighter inline-block
                            {{ $user->role == 'admin' ? 'bg-gold-500/10 text-gold-500 border border-gold-500/20' : ($user->role == 'tim_teknis' ? 'bg-navy-900/10 text-navy-900 border border-navy-900/20' : 'bg-slate-100 text-slate-600 border border-slate-200') }}">
                            {{ str_replace('_', ' ', $user->role) }}
                        </span>
                    </td>
                    <td class="px-4 md:px-4 py-3">
                        <div class="flex justify-center gap-2">
                            @if($user->role !== 'tim_teknis')
                            
                            <a href="{{ route('admin.users.edit', $user->id) }}" title="Ubah User" class="w-8 h-8 flex items-center justify-center bg-gold-500 hover:bg-gold-600 text-white rounded-lg text-xs font-black transition shadow-sm hover:scale-105">
                                <i class="fas fa-edit"></i>
                            </a>
                            
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline-block m-0 p-0" onsubmit="return confirm('PERINGATAN!\n\nApakah Anda yakin ingin menghapus akun milik {{ $user->name }}?\nTindakan ini tidak dapat dibatalkan.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="Hapus User" class="w-8 h-8 flex items-center justify-center bg-rose-500 hover:bg-rose-600 text-white rounded-lg text-xs font-black transition shadow-sm hover:scale-105">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>

                            @else
                            <span class="px-2 h-8 bg-slate-100 text-slate-400 text-[10px] md:text-xs font-bold rounded-lg flex items-center justify-center cursor-not-allowed gap-1 md:gap-1.5 whitespace-nowrap" title="Akun Tim Teknis dilindungi sistem">
                                <i class="fas fa-lock"></i> Terkunci
                            </span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-slate-500 text-xs font-medium">
                        Tidak ada data pengguna yang ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        </div>
        
        @if($show != 'all' && $users->hasPages())
            <div class="px-8 py-4 border-t border-gray-50 bg-gray-50/10">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
