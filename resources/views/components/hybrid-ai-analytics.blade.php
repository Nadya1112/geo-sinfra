@props([
    'cnnScore' => 0,
    'cnnLabel' => 'Tidak Diketahui',
    'dtScore' => 0,
    'dtLabel' => 'Tidak Diketahui',
    'rekomendasiAi' => 'Belum ada rekomendasi penanganan.',
    'rekomendasiManual' => null,
    'status' => 'Pending'
])

<div class="bg-[#0b0c16] rounded-[2rem] p-5 text-white shadow-xl relative overflow-hidden border border-white/5 font-sans mb-8 w-full max-w-sm mx-auto">
    
    {{-- Header Section --}}
    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3 mb-6">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-gradient-to-br from-[#4a3928] to-[#2a2016] rounded-xl flex items-center justify-center text-gold-500 shadow-lg border border-[#5a4938]/50 shrink-0">
                <i class="fas fa-brain text-lg"></i>
            </div>
            <div>
                <h4 class="text-sm font-black text-white tracking-widest uppercase leading-tight mb-1">HYBRID AI<br>ANALYTICS</h4>
                <p class="text-[9px] font-semibold text-slate-400 leading-tight">Decision Tree + CNN<br>Vision Integration</p>
            </div>
        </div>
        
        @if($status === 'Verified' || $status === 'Validated')
            <div class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 bg-[#059669]/20 border border-[#059669]/30 rounded-full text-[#34d399] shrink-0 self-start">
                <i class="fas fa-shield-alt text-[10px]"></i>
                <span class="text-[9px] font-black tracking-wider">TERVERIFIKASI</span>
            </div>
        @elseif($status === 'Rejected')
            <div class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 bg-rose-500/20 border border-rose-500/30 rounded-full text-rose-400 shrink-0 self-start">
                <i class="fas fa-times-circle text-[10px]"></i>
                <span class="text-[9px] font-black tracking-wider">DITOLAK</span>
            </div>
        @else
            <div class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 bg-amber-500/20 border border-amber-500/30 rounded-full text-amber-400 shrink-0 self-start">
                <i class="fas fa-clock text-[10px]"></i>
                <span class="text-[9px] font-black tracking-wider">PENDING</span>
            </div>
        @endif
    </div>

    {{-- Grid 2 Kolom (CNN & DT) - Selalu 2 Kolom --}}
    <div class="grid grid-cols-2 gap-3 mb-6">
        
        {{-- Card 1: Visual CNN --}}
        <div class="bg-[#151624] rounded-2xl p-4 border border-[#252636] relative group hover:border-[#353646] transition-colors flex flex-col justify-between min-h-[140px]">
            <div class="flex justify-between items-start mb-4">
                <div class="px-2.5 py-1 bg-[#2a2444] text-[#8e85c8] text-[9px] font-black tracking-widest rounded-full uppercase leading-tight">
                    VISUAL<br>CNN
                </div>
                <i class="fas fa-eye text-slate-500 group-hover:text-gold-400 transition-colors text-xs mt-1 shrink-0"></i>
            </div>
            
            <div class="mb-3">
                <div class="flex items-baseline gap-0.5">
                    <span class="text-3xl lg:text-4xl font-black text-white leading-none">{{ $cnnScore }}</span>
                    <span class="text-sm font-bold text-slate-400 leading-none">%</span>
                </div>
                <p class="text-[10px] font-black text-gold-500 uppercase tracking-widest mt-2">{{ $cnnLabel }}</p>
            </div>
            
            <div class="w-full bg-[#1e2030] h-1.5 rounded-full overflow-hidden mt-auto shrink-0">
                <div class="bg-gradient-to-r from-[#6b58c4] to-gold-400 h-full shadow-[0_0_10px_rgba(197,160,89,0.3)] transition-all duration-1000" style="width: {{ $cnnScore }}%"></div>
            </div>
        </div>

        {{-- Card 2: Decision Tree --}}
        <div class="bg-[#151624] rounded-2xl p-4 border border-[#252636] relative group hover:border-[#353646] transition-colors flex flex-col justify-between min-h-[140px]">
            <div class="flex justify-between items-start mb-4">
                <div class="px-2.5 py-1 bg-[#3a2c20] text-[#c09568] text-[9px] font-black tracking-widest rounded-full uppercase leading-tight">
                    DECISION<br>TREE
                </div>
                <i class="fas fa-network-wired text-slate-500 group-hover:text-[#34d399] transition-colors text-xs mt-1 shrink-0"></i>
            </div>
            
            <div class="mb-3">
                <div class="flex items-baseline gap-0.5">
                    <span class="text-3xl lg:text-4xl font-black text-white leading-none">{{ $dtScore }}</span>
                    <span class="text-[10px] font-bold text-slate-400 leading-none">/100</span>
                </div>
                <p class="text-[10px] font-black {{ str_contains(strtolower($dtLabel), 'berat') ? 'text-rose-500' : (str_contains(strtolower($dtLabel), 'sedang') ? 'text-amber-500' : 'text-[#34d399]') }} uppercase tracking-widest mt-2">
                    {{ $dtLabel }}
                </p>
            </div>
            
            <div class="w-full bg-[#1e2030] h-1.5 rounded-full overflow-hidden mt-auto shrink-0">
                @php $dtColor = str_contains(strtolower($dtLabel), 'berat') ? 'from-rose-600 to-rose-400' : (str_contains(strtolower($dtLabel), 'sedang') ? 'from-amber-600 to-amber-400' : 'from-[#059669] to-[#34d399]'); @endphp
                <div class="bg-gradient-to-r {{ $dtColor }} h-full transition-all duration-1000" style="width: {{ $dtScore }}%"></div>
            </div>
        </div>

    </div>

    {{-- Rekomendasi Area --}}
    <div class="bg-[#151624] rounded-[1.5rem] p-5 border border-[#252636]">
        <div class="flex items-start gap-3">
            <div class="w-8 h-8 bg-[#2a2416] rounded-xl flex items-center justify-center text-gold-500 shrink-0 border border-[#3a3020]">
                <i class="fas fa-lightbulb text-sm"></i>
            </div>
            <div class="flex-1 min-w-0">
                <h5 class="text-[9px] font-black text-gold-500 uppercase tracking-[0.15em] mb-2.5">REKOMENDASI AI</h5>
                
                @if($rekomendasiManual)
                    <div class="mb-3">
                        <span class="inline-flex items-center gap-1.5 px-2 py-1 bg-blue-900/40 text-blue-400 rounded text-[8px] font-black uppercase tracking-wider mb-1.5">
                            <i class="fas fa-user-edit"></i> DITIMPA TIM TEKNIS
                        </span>
                        <p class="text-xs font-medium text-slate-300 italic leading-relaxed break-words">"{{ $rekomendasiManual }}"</p>
                    </div>
                    <div>
                        <span class="inline-flex items-center gap-1.5 px-2 py-1 bg-[#3a2c20]/50 text-[#c09568] rounded text-[8px] font-black uppercase tracking-wider mb-1.5">
                            <i class="fas fa-robot"></i> REKOMENDASI ASLI
                        </span>
                        <p class="text-[10px] font-medium text-slate-500 italic leading-relaxed line-through break-words">"{{ $rekomendasiAi }}"</p>
                    </div>
                @else
                    <p class="text-xs font-medium text-slate-300 italic leading-relaxed break-words">
                        "{{ $rekomendasiAi }}"
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>
