<div>
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
        <div>
            <h4 class="font-extrabold text-lg text-navy-900 uppercase">LOG AKTIVITAS</h4>
            <p class="text-xs text-slate-400 font-medium text-left">Memantau seluruh aktivitas pengguna di sistem</p>
        </div>
        <div class="flex flex-row flex-nowrap items-center gap-2 w-full md:w-auto relative z-20">
            <div class="flex items-center flex-1 min-w-0 md:w-[400px]">
                <div class="relative flex-1 min-w-[80px]">
                    <input type="text" 
                        wire:model.live.debounce.300ms="search"
                        placeholder="Cari log aktivitas..." 
                        class="w-full pl-3 pr-10 py-2.5 bg-white border border-slate-100 text-[10px] md:text-xs font-semibold focus:outline-none focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 transition-all shadow-sm rounded-l-2xl">
                    <div wire:loading wire:target="search" class="absolute right-3 top-1/2 -translate-y-1/2">
                        <i class="fas fa-circle-notch fa-spin text-gold-500 text-xs"></i>
                    </div>
                </div>
                <button type="button" class="bg-white border-y border-r border-slate-100 px-4 md:px-5 py-2.5 hover:bg-slate-50 transition-all shadow-sm group shrink-0 relative" title="Cari">
                    <i class="fas fa-search text-slate-400 group-hover:text-gold-500 transition-colors text-xs" wire:loading.remove wire:target="search"></i>
                    <i class="fas fa-circle-notch fa-spin text-gold-500 text-xs hidden" wire:loading.inline-block wire:target="search"></i>
                </button>
            </div>

            <a href="{{ route('admin.activity.export') }}" class="bg-emerald-500 text-white text-xs px-4 md:px-6 py-2.5 rounded-2xl font-bold shadow-lg shadow-emerald-500/10 hover:bg-emerald-600 hover:shadow-emerald-500/20 transition flex items-center justify-center gap-2 whitespace-nowrap shrink-0">
                <i class="fas fa-file-excel text-xs"></i> <span class="hidden sm:inline">Ekspor Excel</span>
            </a>
        </div>
    </div>

    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden mb-10 relative">


        <div class="overflow-x-auto w-full custom-scrollbar">
            <!-- Table Layout (Desktop & Tablet) -->
            <table class="w-full text-left border-collapse min-w-full hidden md:table">
            <thead>
                <tr class="bg-gradient-to-r from-navy-900 to-navy-800 border-b border-navy-800 shadow-md">
                    <th class="px-4 py-3 text-xs font-black text-gold-500 uppercase tracking-widest">Waktu</th>
                    <th class="px-4 py-3 text-xs font-black text-gold-500 uppercase tracking-widest">Pengguna</th>
                    <th class="px-4 py-3 text-xs font-black text-gold-500 uppercase tracking-widest">Aktivitas</th>
                    <th class="px-4 py-3 text-xs font-black text-gold-500 uppercase tracking-widest">Kategori</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50 text-xs font-medium">
                <!-- Dynamic Data Rows -->
                @forelse($activities as $activity)
                    @php
                        $badgeColor = 'slate';
                        $icon = 'fa-info-circle';
                        $actionName = $activity->description;
                        
                        if (str_contains(strtolower($actionName), 'login')) {
                            $badgeColor = 'emerald';
                            $icon = 'fa-sign-in-alt';
                        } elseif (str_contains(strtolower($actionName), 'tambah')) {
                            $badgeColor = 'blue';
                            $icon = 'fa-plus';
                        } elseif (str_contains(strtolower($actionName), 'hapus') || str_contains(strtolower($actionName), 'delete')) {
                            $badgeColor = 'rose';
                            $icon = 'fa-trash';
                        } elseif (str_contains(strtolower($actionName), 'ubah') || str_contains(strtolower($actionName), 'edit') || str_contains(strtolower($actionName), 'perbarui')) {
                            $badgeColor = 'amber';
                            $icon = 'fa-pen';
                        } elseif (str_contains(strtolower($actionName), 'validasi')) {
                            $badgeColor = 'emerald';
                            $icon = 'fa-check-circle';
                        }

                        $roleColor = 'slate';
                        if($activity->user) {
                            if ($activity->user->role == 'admin') $roleColor = 'navy';
                            elseif ($activity->user->role == 'tim_teknis') $roleColor = 'gold';
                            elseif ($activity->user->role == 'surveyor') $roleColor = 'blue';
                        }
                    @endphp
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-4 py-3 text-xs text-slate-500 whitespace-nowrap">
                            <div class="flex flex-col gap-0.5">
                                <span class="font-bold text-navy-900">{{ $activity->created_at->format('Y-m-d') }}</span>
                                <span class="text-[10px] text-slate-400"><i class="fas fa-clock mr-1 text-slate-300"></i> {{ $activity->created_at->format('H:i:s') }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div>
                                <p class="font-bold text-navy-900">{{ $activity->user ? $activity->user->name : 'Sistem Otomatis' }}</p>
                                <p class="text-[10px] text-slate-400 uppercase tracking-wider">{{ $activity->user ? $activity->user->role : 'System' }}</p>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2.5 py-1.5 bg-{{$badgeColor}}-50 text-{{$badgeColor}}-600 text-[10px] font-bold uppercase tracking-wider rounded-lg border border-{{$badgeColor}}-100 inline-flex items-start gap-1.5 shadow-sm max-w-[200px] md:max-w-xs whitespace-normal text-left leading-relaxed">
                                <i class="fas {{ $icon }} shrink-0 mt-0.5"></i> <span>{{ $actionName }}</span>
                            </span>
                        </td>
                        <td class="px-4 py-3 font-bold text-navy-800 uppercase text-xs">{{ $activity->type }} {!! $activity->reference_id ? "<span class='text-slate-400 text-xs'>(ID: {$activity->reference_id})</span>" : "" !!}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-8 py-10 text-center text-sm font-semibold text-gray-400">
                            <i class="fas fa-history text-2xl mb-2 block text-gray-300"></i>
                            Belum ada aktivitas yang direkam oleh sistem.
                        </td>
                    </tr>
                @endforelse

            </tbody>
            </table>

            <!-- Card Layout (Mobile) -->
            <div class="flex flex-col md:hidden divide-y divide-slate-100">
                @forelse($activities as $activity)
                    @php
                        $badgeColor = 'slate';
                        $icon = 'fa-info-circle';
                        $actionName = $activity->description;
                        
                        if (str_contains(strtolower($actionName), 'login')) {
                            $badgeColor = 'emerald';
                            $icon = 'fa-sign-in-alt';
                        } elseif (str_contains(strtolower($actionName), 'tambah')) {
                            $badgeColor = 'blue';
                            $icon = 'fa-plus';
                        } elseif (str_contains(strtolower($actionName), 'hapus') || str_contains(strtolower($actionName), 'delete')) {
                            $badgeColor = 'rose';
                            $icon = 'fa-trash';
                        } elseif (str_contains(strtolower($actionName), 'ubah') || str_contains(strtolower($actionName), 'edit') || str_contains(strtolower($actionName), 'perbarui')) {
                            $badgeColor = 'amber';
                            $icon = 'fa-pen';
                        } elseif (str_contains(strtolower($actionName), 'validasi')) {
                            $badgeColor = 'emerald';
                            $icon = 'fa-check-circle';
                        }
                    @endphp
                    <div class="p-4 hover:bg-slate-50/50 transition-colors">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <p class="font-bold text-navy-900 text-sm">{{ $activity->user ? $activity->user->name : 'Sistem Otomatis' }}</p>
                                <p class="text-[10px] text-slate-400 uppercase tracking-wider">{{ $activity->user ? $activity->user->role : 'System' }}</p>
                            </div>
                            <div class="text-right">
                                <span class="font-bold text-navy-900 text-[10px] block">{{ $activity->created_at->format('Y-m-d') }}</span>
                                <span class="text-[9px] text-slate-400"><i class="fas fa-clock mr-1 text-slate-300"></i> {{ $activity->created_at->format('H:i:s') }}</span>
                            </div>
                        </div>
                        <div class="mb-2">
                            <span class="px-2.5 py-1.5 bg-{{$badgeColor}}-50 text-{{$badgeColor}}-600 text-[10px] font-bold uppercase tracking-wider rounded-lg border border-{{$badgeColor}}-100 inline-flex items-start gap-1.5 shadow-sm w-full leading-relaxed">
                                <i class="fas {{ $icon }} shrink-0 mt-0.5"></i> <span>{{ $actionName }}</span>
                            </span>
                        </div>
                        <div class="flex justify-end pt-2 border-t border-slate-50">
                            <p class="font-bold text-navy-800 uppercase text-[9px]">{{ $activity->type }} {!! $activity->reference_id ? "<span class='text-slate-400'>(ID: {$activity->reference_id})</span>" : "" !!}</p>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-sm font-semibold text-gray-400">
                        <i class="fas fa-history text-2xl mb-2 block text-gray-300"></i>
                        Belum ada aktivitas yang direkam oleh sistem.
                    </div>
                @endforelse
            </div>
        </div>
        
        @if($activities instanceof \Illuminate\Pagination\LengthAwarePaginator && $activities->hasPages())
            <div class="px-8 py-4 border-t border-slate-50 bg-slate-50/30">
                {{ $activities->links() }}
            </div>
        @else
            <div class="px-8 py-4 border-t border-slate-50 bg-slate-50/30 flex justify-between items-center text-xs text-slate-500 font-bold">
                <span>Menampilkan total {{ $activities->count() }} aktivitas</span>
            </div>
        @endif
    </div>
</div>
