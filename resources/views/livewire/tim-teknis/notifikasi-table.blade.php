<div>
    <div class="bg-white dark:bg-[#1e1b4b] rounded-[2rem] md:rounded-[3rem] shadow-sm border border-slate-100 dark:border-white/10 overflow-hidden mb-10 relative">
        <div wire:loading.delay wire:target="search, show, statusFilter, setStatusFilter" class="absolute inset-0 bg-white/50 dark:bg-navy-950/50 backdrop-blur-sm z-50 flex flex-col items-center justify-center no-print">
            <i class="fas fa-circle-notch fa-spin text-4xl text-gold-500 mb-2"></i>
            <span class="text-xs font-black text-navy-900 dark:text-white uppercase tracking-widest drop-shadow-md">Memuat Data...</span>
        </div>

        <!-- HEADER -->
        <div class="px-4 md:px-8 py-4 md:py-5 border-b border-slate-50 dark:border-white/5 flex flex-col md:flex-row justify-between items-center bg-white dark:bg-[#1e1b4b] gap-4 relative z-20">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-gold-50 dark:bg-gold-500/10 flex items-center justify-center text-gold-500 border border-gold-100 dark:border-gold-500/20">
                    <i class="fas fa-bell"></i>
                </div>
                <div>
                    <h3 class="text-sm font-black text-navy-900 dark:text-white uppercase tracking-widest">Daftar Notifikasi</h3>
                    <p class="text-xs text-slate-400 dark:text-slate-300 font-bold uppercase mt-1">Pusat informasi dan peringatan sistem</p>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <div class="flex items-center gap-2">
                    <label class="text-xs font-black text-slate-400 dark:text-slate-300 uppercase tracking-widest whitespace-nowrap">Tampilan:</label>
                    <select wire:model.live="show" class="text-xs font-bold text-navy-900 dark:text-white bg-slate-50 dark:bg-[#0f0e2c] border border-slate-200 dark:border-white/20 rounded-xl px-3 py-2 focus:outline-none focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20 transition-all shadow-sm cursor-pointer">
                        <option value="10">10 Data</option>
                        <option value="25">25 Data</option>
                        <option value="50">50 Data</option>
                        <option value="all">Semua Data</option>
                    </select>
                </div>
                @if($counts['unread'] > 0)
                <button wire:click="markAllAsRead" class="px-4 py-2 bg-emerald-50 text-emerald-600 hover:bg-emerald-100 dark:bg-emerald-500/10 dark:text-emerald-400 dark:hover:bg-emerald-500/20 text-xs font-black uppercase tracking-widest rounded-xl transition-all flex items-center gap-2 shadow-sm border border-emerald-100 dark:border-emerald-500/20">
                    <i class="fas fa-check-double"></i> Tandai Semua Dibaca
                </button>
                @endif
            </div>
        </div>

        <!-- FILTER TABS & SEARCH -->
        <div class="px-4 md:px-8 py-4 bg-white dark:bg-[#1e1b4b] border-b border-slate-100 dark:border-white/5 relative z-20">
            <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
                
                <!-- Status Filter -->
                <div class="flex flex-wrap gap-2 w-full md:w-auto">
                    <button wire:click="setStatusFilter('All')" class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all cursor-pointer flex items-center gap-2 {{ $statusFilter == 'All' ? 'bg-navy-900 text-white shadow-md' : 'bg-slate-50 dark:bg-[#0f0e2c] text-slate-400 dark:text-slate-300 hover:bg-slate-100 border border-slate-200 dark:border-white/20' }}">
                        <span>Semua</span> 
                        <span class="px-1.5 py-0.5 rounded-md bg-white/20 text-[10px]">{{ $counts['all'] }}</span>
                    </button>
                    <button wire:click="setStatusFilter('Unread')" class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all cursor-pointer flex items-center gap-2 {{ $statusFilter == 'Unread' ? 'bg-amber-500 text-white shadow-md shadow-amber-500/20' : 'bg-slate-50 dark:bg-[#0f0e2c] text-slate-400 dark:text-slate-300 hover:bg-slate-100 border border-slate-200 dark:border-white/20' }}">
                        <span>Belum Dibaca</span>
                        @if($counts['unread'] > 0)
                            <span class="px-1.5 py-0.5 rounded-md bg-white/20 text-[10px]">{{ $counts['unread'] }}</span>
                        @endif
                    </button>
                    <button wire:click="setStatusFilter('Read')" class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all cursor-pointer flex items-center gap-2 {{ $statusFilter == 'Read' ? 'bg-emerald-500 text-white shadow-md shadow-emerald-500/20' : 'bg-slate-50 dark:bg-[#0f0e2c] text-slate-400 dark:text-slate-300 hover:bg-slate-100 border border-slate-200 dark:border-white/20' }}">
                        <span>Sudah Dibaca</span>
                        <span class="px-1.5 py-0.5 rounded-md bg-white/20 text-[10px]">{{ $counts['read'] }}</span>
                    </button>
                </div>

                <!-- Search Input -->
                <div class="w-full md:w-1/3">
                    <div class="relative w-full">
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari notifikasi..." class="w-full pl-11 pr-4 py-2.5 bg-slate-50 dark:bg-[#0f0e2c] border border-slate-200 dark:border-white/20 rounded-xl text-xs font-bold text-navy-900 dark:text-white shadow-sm focus:outline-none focus:border-gold-500 transition-all">
                        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 dark:text-slate-300 text-xs" wire:loading.remove wire:target="search"></i>
                        <div wire:loading wire:target="search" class="absolute left-4 top-1/2 -translate-y-1/2 flex items-center justify-center">
                            <i class="fas fa-circle-notch fa-spin text-gold-500 text-xs"></i>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- LIST SECTION -->
        <div class="p-0 bg-white dark:bg-[#1e1b4b] relative z-10" wire:loading.class="opacity-50 transition-opacity duration-300" wire:target="search, show, statusFilter, setStatusFilter">
            @forelse($notifications as $notif)
            <div class="p-5 md:p-6 border-b border-slate-50 dark:border-white/5 flex flex-col md:flex-row gap-4 justify-between transition-all group {{ is_null($notif->read_at) ? 'bg-gold-50/30 dark:bg-gold-500/5' : 'hover:bg-slate-50 dark:hover:bg-white/5' }}">
                <div class="flex items-start gap-4 flex-1">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0 {{ is_null($notif->read_at) ? 'bg-gold-100 text-gold-600 dark:bg-gold-500/20 dark:text-gold-500' : 'bg-slate-100 text-slate-400 dark:bg-white/5' }} border border-transparent group-hover:border-gold-200 dark:group-hover:border-gold-500/30 transition-all">
                        @if(isset($notif->data['type']) && $notif->data['type'] == 'alert')
                            <i class="fas fa-exclamation-circle text-lg"></i>
                        @else
                            <i class="fas fa-bell text-lg"></i>
                        @endif
                    </div>
                    <div class="flex-1">
                        <div class="flex flex-wrap items-center gap-2 mb-1">
                            <h3 class="text-sm font-black text-navy-900 dark:text-white">
                                {{ $notif->data['title'] ?? 'Notifikasi Sistem' }}
                            </h3>
                            @if(is_null($notif->read_at))
                                <span class="px-2 py-0.5 rounded-md bg-amber-100 dark:bg-amber-500/20 text-amber-600 dark:text-amber-400 text-[9px] font-black uppercase tracking-widest border border-amber-200 dark:border-amber-500/30 animate-pulse">Baru</span>
                            @endif
                        </div>
                        <p class="text-xs font-medium text-slate-500 dark:text-slate-400 mb-3 leading-relaxed max-w-3xl">
                            {{ $notif->data['message'] ?? 'Ada pembaruan data yang memerlukan perhatian Anda.' }}
                        </p>
                        <p class="text-[10px] font-bold text-slate-400 dark:text-slate-300 uppercase tracking-widest flex items-center gap-1.5">
                            <i class="fas fa-clock text-gold-500"></i> 
                            {{ $notif->created_at->diffForHumans() }} 
                            <span class="text-slate-300 dark:text-slate-600 mx-1">•</span> 
                            {{ $notif->created_at->translatedFormat('d M Y, H:i') }}
                        </p>
                    </div>
                </div>
                
                <div class="flex flex-row md:flex-col items-center gap-2 md:self-center mt-2 md:mt-0">
                    @if(is_null($notif->read_at))
                    <button wire:click="markAsRead('{{ $notif->id }}')" class="w-10 h-10 md:w-8 md:h-8 rounded-xl bg-emerald-50 text-emerald-600 hover:bg-emerald-100 dark:bg-emerald-500/10 dark:text-emerald-400 hover:scale-110 flex items-center justify-center transition-all border border-emerald-100 dark:border-emerald-500/20" title="Tandai Dibaca">
                        <i class="fas fa-check text-xs"></i>
                    </button>
                    @endif
                    <button wire:click="delete('{{ $notif->id }}')" class="w-10 h-10 md:w-8 md:h-8 rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-100 dark:bg-rose-500/10 dark:text-rose-400 hover:scale-110 flex items-center justify-center transition-all border border-rose-100 dark:border-rose-500/20" title="Hapus Notifikasi">
                        <i class="fas fa-trash text-xs"></i>
                    </button>
                </div>
            </div>
            @empty
            <div class="p-12 text-center flex flex-col items-center justify-center h-64">
                <div class="w-20 h-20 rounded-full bg-slate-50 dark:bg-white/5 flex items-center justify-center text-slate-300 dark:text-slate-600 mb-4 border border-slate-100 dark:border-white/10 shadow-inner">
                    <i class="fas fa-bell-slash text-3xl"></i>
                </div>
                <h3 class="text-lg font-black text-navy-900 dark:text-white mb-2">Tidak Ada Notifikasi</h3>
                <p class="text-xs font-medium text-slate-500 max-w-xs mx-auto leading-relaxed">
                    @if(!empty($search))
                        Tidak ada notifikasi yang cocok dengan kata kunci pencarian Anda.
                    @elseif($statusFilter == 'Unread')
                        Bagus! Anda telah membaca semua notifikasi terbaru.
                    @else
                        Saat ini Anda tidak memiliki notifikasi.
                    @endif
                </p>
            </div>
            @endforelse
        </div>
        
        @if($show != 'all' && $notifications->hasPages())
        <div class="p-5 border-t border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-[#0f0e2c]/50">
            {{ $notifications->links() }}
        </div>
        @endif
    </div>
</div>
