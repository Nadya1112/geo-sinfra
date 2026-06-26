@extends('layouts.app') <!-- Pastikan layout ini sesuai dengan yang lain -->

@section('content')
<div class="min-h-screen bg-navy-50 dark:bg-navy-950 text-slate-800 dark:text-slate-200 transition-colors duration-300">
    @include('admin.partials.sidebar')
    <main class="flex-1 overflow-y-auto custom-scrollbar flex flex-col h-screen relative">
        <header class="sticky top-0 bg-white/80 dark:bg-navy-950/80 backdrop-blur-xl border-b border-slate-100 dark:border-white/5 px-4 md:px-8 py-4 flex justify-between items-center z-40">
            <div class="flex items-center gap-2 md:gap-4">
                <div class="text-left">
                    <p class="text-xs font-black text-gold-500 uppercase tracking-wider mb-1">Administrator Portal</p>
                    <h2 class="text-lg md:text-xl font-black text-navy-900 dark:text-white leading-none">Pengaturan Sistem</h2>
                </div>
            </div>
        </header>

        <div class="p-4 md:p-8 flex-1">
            <div class="max-w-3xl mx-auto">
                @if(session('success'))
                <div class="mb-6 px-4 py-3 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center gap-3 shadow-sm">
                    <i class="fas fa-check-circle text-lg"></i>
                    <p class="text-sm font-bold">{{ session('success') }}</p>
                </div>
                @endif

                <form action="{{ route('admin.settings.update') }}" method="POST" class="bg-white dark:bg-navy-900 border border-slate-100 dark:border-white/10 rounded-3xl p-6 shadow-sm text-left">
                    @csrf
                    
                    <h4 class="text-lg font-black text-navy-900 dark:text-white mb-6 border-b border-slate-100 dark:border-white/10 pb-4">Kontak & Informasi Publik</h4>
                    
                    <div class="space-y-5 mb-8">
                        <div>
                            <label class="block text-xs font-black text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-2 ml-1">Email Kontak Utama</label>
                            <input type="email" name="contact_email" value="{{ $settings['contact_email'] ?? 'admin@geo-sinfra.co.id' }}" class="w-full px-5 py-3.5 bg-slate-50 dark:bg-navy-950 border border-slate-200 dark:border-white/10 rounded-2xl text-sm font-semibold text-navy-900 dark:text-white focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-2 ml-1">Nomor WhatsApp Pelayanan</label>
                            <input type="text" name="contact_wa" value="{{ $settings['contact_wa'] ?? '+62 800 0000 0000' }}" class="w-full px-5 py-3.5 bg-slate-50 dark:bg-navy-950 border border-slate-200 dark:border-white/10 rounded-2xl text-sm font-semibold text-navy-900 dark:text-white focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-2 ml-1">Jam Operasional</label>
                            <input type="text" name="operational_hours" value="{{ $settings['operational_hours'] ?? 'Senin - Jumat, 08:00 - 16:00' }}" class="w-full px-5 py-3.5 bg-slate-50 dark:bg-navy-950 border border-slate-200 dark:border-white/10 rounded-2xl text-sm font-semibold text-navy-900 dark:text-white focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 outline-none transition-all">
                        </div>
                    </div>

                    <h4 class="text-lg font-black text-navy-900 dark:text-white mb-6 border-b border-slate-100 dark:border-white/10 pb-4">Pengaturan Peta Dasar</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-8">
                        <div>
                            <label class="block text-xs font-black text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-2 ml-1">Latitude Tengah (Titik Awal)</label>
                            <input type="text" name="map_center_lat" value="{{ $settings['map_center_lat'] ?? '-3.3276' }}" class="w-full px-5 py-3.5 bg-slate-50 dark:bg-navy-950 border border-slate-200 dark:border-white/10 rounded-2xl text-sm font-semibold text-navy-900 dark:text-white focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-2 ml-1">Longitude Tengah (Titik Awal)</label>
                            <input type="text" name="map_center_lng" value="{{ $settings['map_center_lng'] ?? '114.5901' }}" class="w-full px-5 py-3.5 bg-slate-50 dark:bg-navy-950 border border-slate-200 dark:border-white/10 rounded-2xl text-sm font-semibold text-navy-900 dark:text-white focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 outline-none transition-all">
                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-100 dark:border-white/10 flex justify-end">
                        <button type="submit" class="px-8 py-3.5 bg-gold-500 hover:bg-gold-600 text-navy-950 font-black rounded-xl shadow-xl shadow-gold-500/20 hover:shadow-gold-500/40 transition-all flex items-center gap-2">
                            <i class="fas fa-save"></i> Simpan Pengaturan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>
@endsection
