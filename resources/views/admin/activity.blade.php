<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Aktivitas Sistem | Admin SINFRA</title>
    <link rel="icon" href="{{ asset('logo_geo-sinfra.png') }}" type="image/png">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
                    colors: {
                        navy: { 50: '#f4f4fa', 100: '#e9e9f3', 200: '#c7c8e3', 500: '#6366f1', 800: '#1e1b4b', 900: '#0f0e2c', 950: '#070617' },
                        gold: { 50: '#fdfbf7', 100: '#fbf7ed', 500: '#c5a059', 600: '#b38f4a', 700: '#9d7c3d' }
                    }
                }
            }
        }
    </script>
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
</head>
<body class="bg-slate-50 flex h-screen overflow-hidden text-slate-800 text-left font-sans">

    @include('admin.partials.sidebar')

    <main class="flex-1 overflow-y-auto custom-scrollbar text-left">
        <header class="sticky top-0 bg-white/80 backdrop-blur-xl border-b border-slate-100 px-8 py-5 flex justify-between items-center z-40 text-left shadow-sm">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.dashboard') }}" class="w-10 h-10 flex items-center justify-center bg-white text-slate-400 rounded-xl hover:bg-gold-50 hover:text-gold-600 transition-all border border-slate-200 hover:border-gold-200">
                    <i class="fas fa-arrow-left text-sm"></i>
                </a>
                <div class="text-left">
                    <p class="text-[10px] font-black text-gold-500 uppercase tracking-[0.2em] mb-1">Security & Audit</p>
                    <h2 class="text-xl font-black text-navy-900 leading-none">Log Aktivitas Sistem</h2>
                </div>
            </div>

            <div class="flex items-center gap-6">
                <div class="text-right hidden sm:block">
                    <p class="text-[11px] font-black text-navy-900" id="mini-clock">00:00 WITA</p>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
                <div class="h-8 w-[1px] bg-slate-100"></div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.profile') }}" class="text-right group">
                        <p class="text-[11px] font-black text-navy-900 leading-none uppercase group-hover:text-gold-500 transition-all">{{ auth()->user()->name }}</p>
                        <p class="text-[9px] font-bold text-emerald-500 uppercase mt-1">Online</p>
                    </a>
                    <a href="{{ route('admin.profile') }}" class="w-10 h-10 bg-navy-900 rounded-xl flex items-center justify-center text-gold-500 border border-white/10 overflow-hidden shadow-md">
                        @if(auth()->user()->profile_photo)
                            <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-user-circle text-xl"></i>
                        @endif
                    </a>
                </div>
            </div>
        </header>

        <div class="p-8 text-left">
            <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm mb-6 flex flex-wrap gap-4 items-center justify-between">
                <div>
                    <h3 class="text-sm font-black text-navy-900 uppercase tracking-widest">Audit Trail</h3>
                    <p class="text-[10px] text-slate-400 font-bold uppercase mt-1">Memantau seluruh aktivitas pengguna di sistem (Contoh Simulasi UI)</p>
                </div>
                <div class="flex gap-2">
                    <button class="px-4 py-2 bg-slate-50 text-slate-500 border border-slate-200 rounded-xl text-[10px] font-bold flex items-center gap-2 hover:bg-slate-100 transition-all">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <button class="px-4 py-2 bg-slate-50 text-slate-500 border border-slate-200 rounded-xl text-[10px] font-bold flex items-center gap-2 hover:bg-slate-100 transition-all">
                        <i class="fas fa-download"></i> Ekspor CSV
                    </button>
                </div>
            </div>

            <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Waktu</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Pengguna</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Aktivitas</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Modul</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">IP Address</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 text-sm font-medium">
                        <!-- Simulated Data Rows -->
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-8 py-5 text-xs text-slate-500 whitespace-nowrap">
                                <i class="fas fa-clock mr-2 text-slate-300"></i> {{ now()->subMinutes(10)->format('Y-m-d H:i:s') }}
                            </td>
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-bold">SY</div>
                                    <div>
                                        <p class="font-bold text-navy-900">Budi Surveyor</p>
                                        <p class="text-[9px] text-slate-400 uppercase tracking-wider">Surveyor</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <span class="px-3 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase tracking-widest rounded-lg border border-emerald-100 inline-flex items-center gap-2">
                                    <i class="fas fa-plus"></i> Menambahkan Data
                                </span>
                            </td>
                            <td class="px-8 py-5 font-bold text-navy-800">Infrastruktur (ID: 1042)</td>
                            <td class="px-8 py-5 text-right font-mono text-[11px] text-slate-400 group-hover:text-slate-600">114.120.30.5</td>
                        </tr>
                        
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-8 py-5 text-xs text-slate-500 whitespace-nowrap">
                                <i class="fas fa-clock mr-2 text-slate-300"></i> {{ now()->subHours(1)->format('Y-m-d H:i:s') }}
                            </td>
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-gold-100 text-gold-600 flex items-center justify-center text-xs font-bold">KB</div>
                                    <div>
                                        <p class="font-bold text-navy-900">Hendra Kabid</p>
                                        <p class="text-[9px] text-slate-400 uppercase tracking-wider">Kepala Bidang</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <span class="px-3 py-1 bg-blue-50 text-blue-600 text-[10px] font-black uppercase tracking-widest rounded-lg border border-blue-100 inline-flex items-center gap-2">
                                    <i class="fas fa-check-circle"></i> Memvalidasi Data
                                </span>
                            </td>
                            <td class="px-8 py-5 font-bold text-navy-800">Infrastruktur (ID: 1021)</td>
                            <td class="px-8 py-5 text-right font-mono text-[11px] text-slate-400 group-hover:text-slate-600">192.168.1.10</td>
                        </tr>

                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-8 py-5 text-xs text-slate-500 whitespace-nowrap">
                                <i class="fas fa-clock mr-2 text-slate-300"></i> {{ now()->subHours(2)->format('Y-m-d H:i:s') }}
                            </td>
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-navy-100 text-navy-600 flex items-center justify-center text-xs font-bold">AD</div>
                                    <div>
                                        <p class="font-bold text-navy-900">Admin Utama</p>
                                        <p class="text-[9px] text-slate-400 uppercase tracking-wider">Admin</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <span class="px-3 py-1 bg-amber-50 text-amber-600 text-[10px] font-black uppercase tracking-widest rounded-lg border border-amber-100 inline-flex items-center gap-2">
                                    <i class="fas fa-pen"></i> Mengubah Konfigurasi
                                </span>
                            </td>
                            <td class="px-8 py-5 font-bold text-navy-800">Manajemen Pengguna</td>
                            <td class="px-8 py-5 text-right font-mono text-[11px] text-slate-400 group-hover:text-slate-600">10.0.0.2</td>
                        </tr>
                        
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-8 py-5 text-xs text-slate-500 whitespace-nowrap">
                                <i class="fas fa-clock mr-2 text-slate-300"></i> {{ now()->subHours(3)->format('Y-m-d H:i:s') }}
                            </td>
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center text-xs font-bold">SYS</div>
                                    <div>
                                        <p class="font-bold text-navy-900">SYSTEM</p>
                                        <p class="text-[9px] text-slate-400 uppercase tracking-wider">Automated</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <span class="px-3 py-1 bg-rose-50 text-rose-600 text-[10px] font-black uppercase tracking-widest rounded-lg border border-rose-100 inline-flex items-center gap-2">
                                    <i class="fas fa-trash"></i> Auto-Delete Cache
                                </span>
                            </td>
                            <td class="px-8 py-5 font-bold text-navy-800">System Core</td>
                            <td class="px-8 py-5 text-right font-mono text-[11px] text-slate-400 group-hover:text-slate-600">127.0.0.1</td>
                        </tr>

                    </tbody>
                </table>
                
                <div class="px-8 py-4 border-t border-slate-50 bg-slate-50/30 flex justify-between items-center text-xs text-slate-500 font-bold">
                    <span>Menampilkan 4 dari 4,092 aktivitas</span>
                    <div class="flex gap-2">
                        <button class="px-3 py-1 border border-slate-200 rounded-lg hover:bg-white transition-all text-slate-400 cursor-not-allowed">Sebelummya</button>
                        <button class="px-3 py-1 bg-navy-900 text-white rounded-lg hover:bg-navy-800 transition-all">Selanjutnya</button>
                    </div>
                </div>
            </div>
            
            <div class="mt-8 bg-blue-50 border border-blue-100 p-6 rounded-3xl flex gap-4 items-start shadow-sm">
                <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-blue-500 shadow-sm shrink-0">
                    <i class="fas fa-info-circle text-xl"></i>
                </div>
                <div>
                    <h4 class="text-xs font-black text-blue-900 uppercase tracking-widest mb-1">Informasi Backend</h4>
                    <p class="text-[11px] text-blue-700 font-medium leading-relaxed max-w-3xl">Fitur <strong>Activity Log / Audit Trail</strong> memerlukan pembuatan tabel baru di database (misal: <code>activity_logs</code>) dan pemanggilan <code>Model::created()</code>, <code>Model::updated()</code> event listeners di Laravel Backend untuk menyimpan seluruh aktivitas secara real-time. Tampilan di atas adalah desain antarmuka yang siap disambungkan dengan logika tersebut.</p>
                </div>
            </div>

        </div>
    </main>

    <script>
        function updateClock() {
            const now = new Date();
            const options = { timeZone: 'Asia/Makassar', hour: '2-digit', minute: '2-digit', hour12: false };
            const timeString = new Intl.DateTimeFormat('id-ID', options).format(now);
            const el = document.getElementById('mini-clock');
            if (el) el.textContent = timeString.replace('.', ':') + ' WITA';
        }
        setInterval(updateClock, 1000); updateClock();
    </script>
</body>
</html>
