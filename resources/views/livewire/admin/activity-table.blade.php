<div>
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
        <div>
            <h4 class="font-extrabold text-lg text-navy-900 uppercase">LOG AKTIVITAS</h4>
            <p class="text-xs text-slate-400 font-medium text-left">Memantau seluruh aktivitas pengguna di sistem</p>
        </div>
        <div class="flex flex-row flex-nowrap items-center gap-2 w-full md:w-auto relative z-20">
            <div class="flex items-center flex-1 min-w-0 md:w-[400px]">
                <input type="text" wire:model.live.debounce.500ms="search" placeholder="Cari log aktivitas..." class="flex-1 min-w-[80px] pl-4 pr-3 py-2.5 bg-white border border-slate-100 rounded-2xl text-[10px] md:text-xs font-semibold focus:outline-none focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 transition-all shadow-sm">
            </div>

            <a href="{{ route('admin.activity.export') }}" class="bg-emerald-500 text-white text-xs px-4 md:px-6 py-2.5 rounded-2xl font-bold shadow-lg shadow-emerald-500/10 hover:bg-emerald-600 hover:shadow-emerald-500/20 transition flex items-center justify-center gap-2 whitespace-nowrap shrink-0">
                <i class="fas fa-file-excel text-xs"></i> <span class="hidden sm:inline">Ekspor Excel</span>
            </a>
        </div>
    </div>

    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden mb-10 relative">
        <div wire:loading.delay class="absolute inset-0 bg-white/50 backdrop-blur-sm z-10 flex items-center justify-center no-print">
            <i class="fas fa-circle-notch fa-spin text-4xl text-gold-500"></i>
        </div>

        <div class="overflow-x-auto w-full custom-scrollbar">
        <table class="w-full text-left border-collapse min-w-full">
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
