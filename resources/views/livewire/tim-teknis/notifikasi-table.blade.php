<div>
    <div class="bg-white dark:bg-[#1e1b4b] rounded-[2rem] border border-slate-100 dark:border-white/10 shadow-sm overflow-hidden relative z-20">
        <!-- Controls -->
        <div class="p-5 md:p-8 border-b border-slate-100 dark:border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h2 class="text-lg font-black text-navy-900 dark:text-white">Semua Notifikasi</h2>
            
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2">
                    <label class="text-xs font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Tampilan:</label>
                    <select wire:model.live="show" class="text-xs font-bold text-navy-900 dark:text-white bg-slate-50 dark:bg-[#0f0e2c] border border-slate-200 dark:border-white/20 rounded-xl px-3 py-2 focus:outline-none focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20 transition-all shadow-sm cursor-pointer">
                        <option value="10">10 Data</option>
                        <option value="all">Semua Data</option>
                    </select>
                </div>
                
                @if(auth()->user()->unreadNotifications->count() > 0)
                <button wire:click="markAllAsRead" class="px-4 py-2 bg-emerald-50 text-emerald-600 hover:bg-emerald-100 dark:bg-emerald-500/10 dark:text-emerald-400 dark:hover:bg-emerald-500/20 text-xs font-black uppercase tracking-widest rounded-xl transition-all flex items-center gap-2">
                    <i class="fas fa-check-double"></i> Tandai Semua Dibaca
                </button>
                @endif
            </div>
        </div>

        <div class="p-0">
            @forelse($notifications as $notif)
            <div class="p-5 md:p-6 border-b border-slate-50 dark:border-white/5 flex flex-col md:flex-row gap-4 justify-between transition-all {{ is_null($notif->read_at) ? 'bg-gold-50/30 dark:bg-gold-500/5' : 'hover:bg-slate-50 dark:hover:bg-white/5' }}">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0 {{ is_null($notif->read_at) ? 'bg-gold-100 text-gold-600 dark:bg-gold-500/20 dark:text-gold-500' : 'bg-slate-100 text-slate-400 dark:bg-white/5' }}">
                        @if(isset($notif->data['type']) && $notif->data['type'] == 'alert')
                            <i class="fas fa-exclamation-circle text-lg"></i>
                        @else
                            <i class="fas fa-bell text-lg"></i>
                        @endif
                    </div>
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <h3 class="text-sm font-bold text-navy-900 dark:text-white">
                                {{ $notif->data['title'] ?? 'Notifikasi Sistem' }}
                            </h3>
                            @if(is_null($notif->read_at))
                                <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                            @endif
                        </div>
                        <p class="text-xs font-medium text-slate-500 dark:text-slate-400 mb-2 leading-relaxed">
                            {{ $notif->data['message'] ?? 'Ada pembaruan data yang memerlukan perhatian Anda.' }}
                        </p>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest flex items-center gap-1.5">
                            <i class="fas fa-clock"></i> {{ $notif->created_at->diffForHumans() }} 
                            <span class="text-slate-300 dark:text-slate-600 mx-1">•</span> 
                            {{ $notif->created_at->translatedFormat('d M Y, H:i') }}
                        </p>
                    </div>
                </div>
                
                <div class="flex items-center gap-2 self-start md:self-center">
                    @if(is_null($notif->read_at))
                    <button wire:click="markAsRead('{{ $notif->id }}')" class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 hover:bg-emerald-100 dark:bg-emerald-500/10 dark:text-emerald-400 flex items-center justify-center transition-all" title="Tandai Dibaca">
                        <i class="fas fa-check text-xs"></i>
                    </button>
                    @endif
                    <button wire:click="delete('{{ $notif->id }}')" class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-100 dark:bg-rose-500/10 dark:text-rose-400 flex items-center justify-center transition-all" title="Hapus Notifikasi">
                        <i class="fas fa-trash text-xs"></i>
                    </button>
                </div>
            </div>
            @empty
            <div class="p-12 text-center flex flex-col items-center justify-center">
                <div class="w-20 h-20 rounded-full bg-slate-50 dark:bg-white/5 flex items-center justify-center text-slate-300 dark:text-slate-600 mb-4">
                    <i class="fas fa-bell-slash text-3xl"></i>
                </div>
                <h3 class="text-lg font-black text-navy-900 dark:text-white mb-2">Belum Ada Notifikasi</h3>
                <p class="text-xs font-medium text-slate-500">Saat ini Anda tidak memiliki notifikasi baru.</p>
            </div>
            @endforelse
        </div>
        
        @if($show != 'all' && $notifications->hasPages())
        <div class="p-5 border-t border-slate-100 dark:border-white/10">
            {{ $notifications->links() }}
        </div>
        @endif
    </div>
</div>
