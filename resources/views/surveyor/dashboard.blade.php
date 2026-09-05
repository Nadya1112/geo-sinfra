@extends('layouts.app')
@section('title', 'Beranda Surveyor | GEO-SINFRA')
@section('subtitle', 'Portal Surveyor')
@section('page_title', 'Beranda Utama')

@section('content')

            @if($kecamatans->isEmpty())
            {{-- Warning: Kecamatan Belum Dipilih --}}
            <div class="bg-orange-50 border border-orange-100 p-6 rounded-3xl mb-8 flex flex-col md:flex-row items-center justify-between shadow-sm">
                <div class="flex items-center gap-5 mb-4 md:mb-0">
                    <div class="w-14 h-14 bg-white dark:bg-navy-900/90 rounded-2xl flex-shrink-0 flex items-center justify-center text-orange-500 shadow-sm border border-orange-100 text-2xl">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-black text-orange-900 uppercase tracking-tight">Wilayah Tugas Belum Ditentukan!</h4>
                        <p class="text-sm text-orange-700 font-medium mt-1">
                            Anda belum memilih wilayah tugas. Harap tentukan wilayah kerja Anda agar laporan dapat diproses.
                        </p>
                    </div>
                </div>
                <button onclick="toggleModal('territoryModal')" class="px-6 py-3 bg-orange-500 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-orange-600 transition-all shadow-md flex items-center gap-2">
                    <i class="fas fa-map-marked-alt"></i> Pilih Wilayah Sekarang
                </button>
            </div>
            @endif

            {{-- Welcome Card --}}
            <div class="relative bg-white dark:bg-navy-900 border border-slate-200 dark:border-white/5 rounded-[2.5rem] p-10 mb-8 overflow-hidden shadow-xl shadow-navy-900/10">
                <div class="absolute -right-10 -top-10 w-64 h-64 bg-gold-500/20 rounded-full blur-3xl pointer-events-none"></div>
                
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                    <div>
                        <h3 class="text-3xl font-black text-navy-900 dark:text-white mb-3 leading-tight">Selamat Datang, <span class="text-gold-500">{{ auth()->user()->name }}</span>!</h3>
                        <p class="text-slate-500 dark:text-slate-300 text-sm font-medium max-w-xl leading-relaxed">
                            Siap untuk mendata infrastruktur hari ini? Pastikan GPS aktif dan foto yang diambil jelas untuk hasil pemantauan status kondisi yang akurat di lapangan.
                        </p>
                    </div>
                    
                    <div class="flex flex-col md:flex-row items-center gap-6 md:gap-8">
                        {{-- Mini Stats Box sama seperti Admin --}}
                        <div class="flex sm:flex-col justify-between sm:justify-center gap-4 sm:gap-3 shrink-0 bg-slate-50 dark:bg-white/5 p-4 rounded-2xl border border-slate-200 dark:border-white/10 backdrop-blur-sm w-full sm:w-auto">
                            <div class="text-center sm:text-right">
                                <p class="text-[10px] font-black text-slate-400 dark:text-slate-300 uppercase tracking-widest mb-0.5">Rusak Berat</p>
                                <p class="text-lg font-black text-red-500">{{ number_format($rusakBerat ?? 0) }} <span class="text-xs text-red-400"><i class="fas fa-exclamation-triangle"></i></span></p>
                            </div>
                            <div class="w-px sm:w-full h-8 sm:h-px bg-white/10 my-auto"></div>
                            <div class="text-center sm:text-right">
                                <p class="text-[10px] font-black text-slate-400 dark:text-slate-300 uppercase tracking-widest mb-0.5">Rusak Sedang</p>
                                <p class="text-lg font-black text-amber-400">{{ number_format($rusakSedang ?? 0) }} <span class="text-xs text-amber-300"><i class="fas fa-exclamation-circle"></i></span></p>
                            </div>
                            <div class="w-px sm:w-full h-8 sm:h-px bg-white/10 my-auto"></div>
                            <div class="text-center sm:text-right">
                                <p class="text-[10px] font-black text-slate-400 dark:text-slate-300 uppercase tracking-widest mb-0.5">Kondisi Baik</p>
                                <p class="text-lg font-black text-emerald-400">{{ number_format($kondisiBaik ?? 0) }} <span class="text-xs text-emerald-300"><i class="fas fa-check-circle"></i></span></p>
                            </div>
                        </div>

                        <a href="{{ route('surveyor.input') }}" class="shrink-0 px-8 py-4 bg-gold-500 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-gold-600 transition-all shadow-lg shadow-gold-500/20 flex items-center gap-3 group">
                            Mulai Survey <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Alert Revisi / Ditolak (Hanya muncul jika ada data yang butuh perbaikan) --}}
            @if($rejectedItems->isNotEmpty())
            <div class="mb-8 space-y-4">
                <h4 class="font-black text-lg text-red-600 flex items-center gap-2"><i class="fas fa-exclamation-triangle"></i> Perlu Revisi Segera!</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($rejectedItems as $rejected)
                    <div class="bg-red-50 border border-red-200 rounded-3xl p-6 shadow-sm relative overflow-hidden flex flex-col justify-between">
                        <div class="absolute -right-6 -top-6 w-24 h-24 bg-red-100/50 rounded-full blur-xl pointer-events-none"></div>
                        
                        <div class="relative z-10">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <p class="text-[10px] font-black text-red-400 uppercase tracking-widest mb-1">{{ $rejected->kelurahan->nama_kelurahan ?? '-' }}, KEC. {{ $rejected->kelurahan->kecamatan->nama_kecamatan ?? '-' }}</p>
                                    <h5 class="text-base font-black text-red-900 leading-tight">{{ $rejected->nama_infrastruktur }}</h5>
                                </div>
                                <div class="px-2 py-1 bg-red-100 text-red-600 rounded-md text-[10px] font-black uppercase tracking-widest">Ditolak</div>
                            </div>
                            
                            <div class="bg-white/60 p-3 rounded-xl border border-red-100 mb-4">
                                <p class="text-xs font-bold text-red-800 uppercase tracking-wider mb-1"><i class="fas fa-comment-dots text-red-400"></i> Catatan Tim Teknis:</p>
                                <p class="text-sm text-red-900 font-medium italic">"{{ $rejected->alasan_penolakan ?? 'Tidak ada alasan spesifik. Harap periksa kembali foto atau titik koordinat.' }}"</p>
                            </div>
                        </div>
                        
                        <div class="relative z-10 mt-auto">
                            <a href="{{ route('surveyor.infrastruktur.edit', $rejected->id_infrastruktur) }}" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-red-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-red-700 transition-colors shadow-md shadow-red-600/20 active:scale-[0.98]">
                                <i class="fas fa-pen"></i> Revisi Sekarang
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Stats Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-blue-50 p-6 rounded-3xl border border-blue-100 shadow-sm hover:-translate-y-1 transition-transform">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-11 h-11 bg-white dark:bg-navy-900/90 rounded-2xl flex items-center justify-center shadow-sm">
                            <i class="fas fa-file-alt text-blue-500 "></i>
                        </div>
                    </div>
                    <p class="text-xs font-black text-slate-400 dark:text-slate-300 uppercase tracking-widest mb-1">Total Survey Saya</p>
                    <h3 class="text-3xl font-black text-blue-600 ">{{ $totalSurvey }} <span class="text-xs font-bold text-slate-400 dark:text-slate-300">Laporan</span></h3>
                </div>
                
                <div class="bg-orange-50 p-6 rounded-3xl border border-orange-100 shadow-sm hover:-translate-y-1 transition-transform">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-11 h-11 bg-white dark:bg-navy-900/90 text-orange-500 rounded-2xl flex items-center justify-center shadow-sm">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                    <p class="text-xs font-black text-slate-400 dark:text-slate-300 uppercase tracking-widest mb-1">Menunggu Validasi</p>
                    <h3 class="text-3xl font-black text-orange-600 ">{{ $waitingValidation }} <span class="text-xs font-bold text-slate-400 dark:text-slate-300">Objek</span></h3>
                </div>
                
                <div class="bg-emerald-50 p-6 rounded-3xl border border-emerald-100 shadow-sm hover:-translate-y-1 transition-transform">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-11 h-11 bg-white dark:bg-navy-900/90 text-emerald-500 rounded-2xl flex items-center justify-center shadow-sm">
                            <i class="fas fa-check-double"></i>
                        </div>
                    </div>
                    <p class="text-xs font-black text-slate-400 dark:text-slate-300 uppercase tracking-widest mb-1">Terverifikasi AI</p>
                    <h3 class="text-3xl font-black text-emerald-600 ">{{ $verifiedAI }} <span class="text-xs font-bold text-slate-400 dark:text-slate-300">Selesai</span></h3>
                </div>
                
                <div class="bg-red-50 p-6 rounded-3xl border border-red-100 shadow-sm hover:-translate-y-1 transition-transform">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-11 h-11 bg-white dark:bg-navy-900/90 text-red-500 rounded-2xl flex items-center justify-center shadow-sm">
                            <i class="fas fa-rotate-left"></i>
                        </div>
                    </div>
                    <p class="text-xs font-black text-red-400 uppercase tracking-widest mb-1">Ditolak / Revisi</p>
                    <h3 class="text-3xl font-black text-red-600 ">{{ $totalRejected }} <span class="text-xs font-bold text-red-400/50">Tindakan</span></h3>
                </div>
            </div>

            {{-- Stats Grid Laporan Warga --}}
            <h4 class="font-black text-lg text-navy-900 dark:text-white mb-4 flex items-center gap-2"><i class="fas fa-clipboard-list text-gold-500"></i> Penugasan Laporan Warga</h4>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
                <a href="{{ route('surveyor.laporan') }}" class="block bg-indigo-50 p-6 rounded-3xl border border-indigo-100 shadow-sm hover:border-indigo-500/50 transition-all group hover:-translate-y-1">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-white dark:bg-navy-900/90 rounded-2xl flex items-center justify-center text-indigo-500 group-hover:bg-indigo-500 group-hover:text-white transition-colors shadow-sm">
                            <i class="fas fa-tasks text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs font-black text-slate-400 dark:text-slate-300 uppercase tracking-widest mb-1">Total Tugas</p>
                            <h3 class="text-2xl font-black text-indigo-600 ">{{ $totalTugas }}</h3>
                        </div>
                    </div>
                </a>
                
                <a href="{{ route('surveyor.laporan', ['status' => 'Menunggu']) }}" class="block bg-orange-50 p-6 rounded-3xl border border-orange-100 shadow-sm hover:border-orange-500/50 transition-all group hover:-translate-y-1">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-white dark:bg-navy-900/90 rounded-2xl flex items-center justify-center text-orange-500 group-hover:bg-orange-500 group-hover:text-white transition-colors shadow-sm">
                            <i class="fas fa-clock text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs font-black text-slate-400 dark:text-slate-300 uppercase tracking-widest mb-1">Tugas Menunggu</p>
                            <h3 class="text-2xl font-black text-orange-600 ">{{ $tugasMenunggu }}</h3>
                        </div>
                    </div>
                </a>
                
                <a href="{{ route('surveyor.laporan', ['status' => 'Selesai']) }}" class="block bg-emerald-50 p-6 rounded-3xl border border-emerald-100 shadow-sm hover:border-emerald-500/50 transition-all group hover:-translate-y-1">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-white dark:bg-navy-900/90 rounded-2xl flex items-center justify-center text-emerald-500 group-hover:bg-emerald-500 group-hover:text-white transition-colors shadow-sm">
                            <i class="fas fa-check-circle text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs font-black text-slate-400 dark:text-slate-300 uppercase tracking-widest mb-1">Tugas Selesai</p>
                            <h3 class="text-2xl font-black text-emerald-600 ">{{ $tugasSelesai }}</h3>
                        </div>
                    </div>
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                {{-- Panduan Survey --}}
                <div class="bg-white dark:bg-navy-900/90 rounded-[2.5rem] p-8 border border-slate-100 dark:border-white/10 shadow-sm">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-8 h-8 bg-white dark:bg-navy-900 border border-slate-200 dark:border-white/5 rounded-xl flex items-center justify-center text-gold-500 shrink-0">
                            <i class="fas fa-book-open text-xs"></i>
                        </div>
                        <h4 class="font-black text-lg text-navy-900 dark:text-white ">Panduan Survey Cepat</h4>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-start gap-4 p-4 bg-slate-50 dark:bg-[#0b0a26]/50 rounded-2xl border border-slate-100 dark:border-white/10 group hover:border-gold-500/30 transition-colors">
                            <div class="w-8 h-8 bg-white dark:bg-navy-900/90 rounded-lg flex items-center justify-center text-gold-500 font-black shadow-sm shrink-0 group-hover:bg-gold-500 group-hover:text-white transition-colors">1</div>
                            <div>
                                <p class="text-xs font-black text-navy-900 dark:text-white uppercase">Pilih Detail Infrastruktur</p>
                                <p class="text-xs text-slate-500 mt-1 font-medium">Pastikan seluruh form mulai dari Jenis hingga Material Utama sesuai kondisi lapangan.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4 p-4 bg-slate-50 dark:bg-[#0b0a26]/50 rounded-2xl border border-slate-100 dark:border-white/10 group hover:border-gold-500/30 transition-colors">
                            <div class="w-8 h-8 bg-white dark:bg-navy-900/90 rounded-lg flex items-center justify-center text-gold-500 font-black shadow-sm shrink-0 group-hover:bg-gold-500 group-hover:text-white transition-colors">2</div>
                            <div>
                                <p class="text-xs font-black text-navy-900 dark:text-white uppercase">Ambil Foto Fokus</p>
                                <p class="text-xs text-slate-500 mt-1 font-medium">AI membutuhkan foto yang jelas dan terpusat pada area yang rusak untuk akurasi.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4 p-4 bg-slate-50 dark:bg-[#0b0a26]/50 rounded-2xl border border-slate-100 dark:border-white/10 group hover:border-gold-500/30 transition-colors">
                            <div class="w-8 h-8 bg-white dark:bg-navy-900/90 rounded-lg flex items-center justify-center text-gold-500 font-black shadow-sm shrink-0 group-hover:bg-gold-500 group-hover:text-white transition-colors">3</div>
                            <div>
                                <p class="text-xs font-black text-navy-900 dark:text-white uppercase">Aktifkan GPS</p>
                                <p class="text-xs text-slate-500 mt-1 font-medium">Koordinat akan terisi otomatis jika GPS HP Anda aktif saat form dibuka.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    {{-- Kondisi Cuaca Lapangan (Real-time via Open-Meteo) --}}
                    <div id="weather-widget" class="bg-gradient-to-br from-sky-50 to-blue-100 dark:from-[#0f0e2c] dark:to-navy-900 rounded-[2.5rem] p-8 text-navy-900 dark:text-white relative overflow-hidden shadow-lg shadow-navy-900/10 border border-sky-200 dark:border-white/5 transition-colors duration-300">
                        <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-blue-400/20 dark:bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
                        <div class="relative z-10 flex items-center justify-between gap-4">
                            <div>
                                <div class="flex items-center gap-2 mb-2">
                                    <span id="weather-badge" class="px-2 py-0.5 bg-sky-200/70 dark:bg-blue-500/20 text-sky-700 dark:text-blue-300 border border-sky-300 dark:border-blue-500/30 rounded text-xs font-black uppercase tracking-widest transition-all">
                                        <i class="fas fa-spinner fa-spin text-[10px] mr-1"></i>Memuat...
                                    </span>
                                </div>
                                <h4 id="weather-desc" class="font-black text-xl leading-none mb-1 text-navy-900 dark:text-white">— —</h4>
                                <p id="weather-city" class="text-xs text-slate-500 dark:text-slate-300 font-bold uppercase tracking-widest">Mendeteksi lokasi...</p>
                            </div>
                            <div id="weather-icon-wrap" class="w-16 h-16 bg-sky-200/50 dark:bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center shadow-inner border border-sky-300 dark:border-white/10 shrink-0 transition-colors duration-300">
                                <i id="weather-icon" class="fas fa-circle-notch fa-spin text-3xl text-sky-500 dark:text-blue-400"></i>
                            </div>
                        </div>
                        <div class="relative z-10 mt-6 pt-4 border-t border-sky-200 dark:border-white/10">
                            <p id="weather-tip" class="text-xs text-slate-500 dark:text-slate-300 font-medium leading-relaxed">
                                <strong class="text-navy-900 dark:text-white">Tips Lapangan:</strong> Sedang mendeteksi kondisi cuaca di lokasi Anda...
                            </p>
                        </div>
                    </div>

                    {{-- Info Wilayah Tugas --}}
                    <div class="bg-white dark:bg-navy-900 border border-slate-200 dark:border-white/5 rounded-[2.5rem] p-8 text-navy-900 dark:text-white relative overflow-hidden shadow-lg shadow-navy-900/10">
                        <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-gold-500/10 rounded-full blur-3xl"></div>
                        <div class="relative z-10">
                            <div class="flex justify-between items-start mb-8">
                                <div>
                                    <h4 class="font-black text-lg mb-1">Wilayah Tugas Anda</h4>
                                    <p class="text-slate-400 dark:text-slate-300 text-xs uppercase tracking-widest font-bold">Kecamatan Tanggung Jawab</p>
                                </div>
                                <button onclick="toggleModal('territoryModal')" class="px-4 py-2 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 hover:bg-white/10 text-gold-500 border border-white/10 rounded-xl text-xs font-black uppercase tracking-widest transition-all backdrop-blur-md">
                                    <i class="fas fa-edit mr-2"></i> Kelola
                                </button>
                            </div>
                            
                            <div class="grid grid-cols-1 gap-3">
                                @forelse($kecamatans as $assignedKec)
                                <div class="bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 border border-white/10 rounded-2xl p-4 backdrop-blur-md flex items-center gap-4 transition-transform hover:-translate-y-1 cursor-default">
                                    <div class="w-10 h-10 bg-gold-500 rounded-xl flex items-center justify-center text-white shadow-md shadow-gold-500/20">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <h5 class="text-sm font-black text-gold-500 uppercase tracking-wider">{{ $assignedKec->nama_kecamatan }}</h5>
                                </div>
                                @empty
                                <div class="bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 border border-white/10 rounded-2xl p-6 backdrop-blur-md text-center">
                                    <p class="text-xs text-slate-400 dark:text-slate-300 font-bold uppercase tracking-widest">Belum ada wilayah tugas.</p>
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    {{-- Unggahan Terbaru --}}
                    <div class="bg-white dark:bg-navy-900/90 rounded-[2.5rem] p-8 border border-slate-100 dark:border-white/10 shadow-sm">
                        <div class="flex justify-between items-center mb-6">
                            <h4 class="font-black text-lg text-navy-900 dark:text-white ">Unggahan Terbaru</h4>
                            <a href="{{ route('surveyor.history') }}" class="text-xs font-black text-gold-500 uppercase tracking-widest hover:text-gold-600 transition-colors">Semua Riwayat</a>
                        </div>
                        <div class="space-y-4">
                            @forelse($recentUploads as $upload)
                            <div class="flex items-center gap-4 p-3 hover:bg-slate-50 rounded-2xl border border-transparent hover:border-slate-100 transition-all group cursor-pointer">
                                <div class="w-12 h-12 rounded-xl bg-slate-100 overflow-hidden flex-shrink-0 relative">
                                    <img src="{{ asset('storage/' . $upload->foto_terbaru) }}" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-navy-900/10 group-hover:bg-transparent transition-colors"></div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-black text-navy-900 dark:text-white truncate uppercase">{{ $upload->nama_infrastruktur }}</p>
                                    <p class="text-xs text-slate-400 dark:text-slate-300 font-bold uppercase">{{ $upload->created_at->diffForHumans() }}</p>
                                </div>
                                @if($upload->status_verifikasi == 'Verified')
                                    <div class="px-2 py-1 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-lg text-xs font-black uppercase tracking-wider">Terverifikasi</div>
                                @else
                                    <div class="px-2 py-1 bg-orange-50 text-orange-600 border border-orange-100 rounded-lg text-xs font-black uppercase tracking-wider">Menunggu</div>
                                @endif
                            </div>
                            @empty
                            <p class="text-xs text-slate-400 dark:text-slate-300 font-bold text-center py-6 uppercase tracking-wider">Belum ada data diunggah.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    {{-- Modal Kelola Wilayah --}}
    <div id="territoryModal" class="fixed inset-0 z-[60] hidden overflow-y-auto">
        <div class="flex items-end md:items-center justify-center min-h-screen p-0 md:p-4">
            <div class="fixed inset-0 bg-slate-900/40 dark:bg-navy-900/60 backdrop-blur-sm transition-opacity" onclick="toggleModal('territoryModal')"></div>
            
            <div class="relative bg-white rounded-t-[2.5rem] md:rounded-[2.5rem] w-full max-w-2xl p-6 md:p-8 shadow-2xl transition-all translate-y-full md:translate-y-0 md:scale-95 opacity-0 duration-300 max-h-[90vh] md:max-h-none overflow-y-auto" id="modalContent">
                <div class="flex justify-between items-center mb-8 pb-4 border-b border-slate-100 dark:border-white/10 ">
                    <div>
                        <h3 class="text-xl font-black text-navy-900 dark:text-white ">Kelola Wilayah Tugas</h3>
                        <p class="text-xs font-bold text-slate-400 dark:text-slate-300 uppercase tracking-wider mt-1">Pilih kecamatan yang menjadi tanggung jawab Anda</p>
                    </div>
                    <button onclick="toggleModal('territoryModal')" class="w-10 h-10 flex items-center justify-center bg-slate-50 dark:bg-[#0b0a26]/50 text-slate-400 dark:text-slate-300 rounded-xl hover:bg-red-50 hover:text-red-500 transition-all">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form action="{{ route('surveyor.territory.update') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                        @php $assignedIds = $kecamatans->pluck('id_kecamatan')->toArray(); @endphp
                        @foreach($semuaKecamatan as $kec)
                        <label class="relative flex items-center gap-3 p-4 bg-slate-50 dark:bg-[#0b0a26]/50 rounded-2xl border border-slate-100 dark:border-white/10 cursor-pointer hover:bg-gold-50 dark:hover:bg-gold-500/10 hover:border-gold-200 transition-all group">
                            <input type="checkbox" name="id_kecamatan[]" value="{{ $kec->id_kecamatan }}" 
                                {{ in_array($kec->id_kecamatan, $assignedIds) ? 'checked' : '' }}
                                class="w-5 h-5 rounded-lg text-gold-500 focus:ring-gold-500 border-slate-300 dark:border-white/20 transition-all cursor-pointer">
                            <span class="text-xs font-bold text-slate-600 group-hover:text-navy-900 uppercase">{{ $kec->nama_kecamatan }}</span>
                        </label>
                        @endforeach
                    </div>

                    <div class="flex gap-3">
                        <button type="button" onclick="toggleModal('territoryModal')" class="flex-1 py-4 bg-slate-100 text-slate-500 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-slate-200 transition-all">
                            Batal
                        </button>
                        <button type="submit" class="flex-[2] py-4 bg-white dark:bg-navy-900 border border-slate-200 dark:border-white/5 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-gold-500 transition-all shadow-xl shadow-navy-900/10">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
@endsection

@push('scripts')
<script>
    // ============================================================
    // Modal Toggle
    // ============================================================
    function toggleModal(id) {
        const modal = document.getElementById(id);
        const content = document.getElementById('modalContent');
        if (modal.classList.contains('hidden')) {
            modal.classList.remove('hidden');
            setTimeout(() => {
                content.classList.remove('translate-y-full', 'md:scale-95', 'opacity-0');
                content.classList.add('translate-y-0', 'md:scale-100', 'opacity-100');
            }, 10);
        } else {
            content.classList.remove('translate-y-0', 'md:scale-100', 'opacity-100');
            content.classList.add('translate-y-full', 'md:scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }
    }

    // ============================================================
    // Real-Time Weather Widget (Open-Meteo + Geolocation API)
    // ============================================================
    (function initWeatherWidget() {
        // WMO Weather Code Mapping (ID: Bahasa Indonesia)
        const WMO_CODES = {
            0:   { desc: 'Cerah Sempurna',    icon: 'fa-sun',                  color: 'text-yellow-500 dark:text-yellow-400', badge: 'Cerah',          badgeCls: 'bg-yellow-100 dark:bg-yellow-500/20 text-yellow-700 dark:text-yellow-300 border-yellow-300 dark:border-yellow-500/30', tip: 'Cuaca cerah, waktu terbaik untuk survei lapangan. Pastikan foto diambil dengan cahaya optimal untuk analisis AI.' },
            1:   { desc: 'Sebagian Berawan',  icon: 'fa-cloud-sun',            color: 'text-blue-400',                        badge: 'Sedikit Awan',   badgeCls: 'bg-sky-100 dark:bg-sky-500/20 text-sky-700 dark:text-sky-300 border-sky-300 dark:border-sky-500/30', tip: 'Kondisi cukup baik untuk survei. Waspadai bayangan awan pada foto yang dapat mempengaruhi kualitas analisis.' },
            2:   { desc: 'Berawan Sebagian',  icon: 'fa-cloud-sun',            color: 'text-blue-400',                        badge: 'Berawan',        badgeCls: 'bg-sky-100 dark:bg-sky-500/20 text-sky-700 dark:text-sky-300 border-sky-300 dark:border-sky-500/30', tip: 'Kondisi cukup baik untuk survei. Waspadai bayangan awan pada foto yang dapat mempengaruhi kualitas analisis.' },
            3:   { desc: 'Mendung Tebal',     icon: 'fa-cloud',                color: 'text-slate-500 dark:text-slate-400',   badge: 'Mendung',        badgeCls: 'bg-slate-100 dark:bg-slate-500/20 text-slate-600 dark:text-slate-300 border-slate-300 dark:border-slate-500/30', tip: 'Cuaca mendung. Perhatikan pencahayaan saat mengambil foto agar detail kerusakan terlihat jelas.' },
            45:  { desc: 'Berkabut',          icon: 'fa-smog',                 color: 'text-slate-400',                       badge: 'Kabut',          badgeCls: 'bg-slate-100 dark:bg-slate-500/20 text-slate-600 dark:text-slate-300 border-slate-300 dark:border-slate-500/30', tip: 'Hati-hati berkabut. Visibilitas terbatas, hindari survei di area jalan raya atau jembatan.' },
            48:  { desc: 'Berkabut Beku',     icon: 'fa-smog',                 color: 'text-slate-400',                       badge: 'Kabut Beku',     badgeCls: 'bg-slate-100 dark:bg-slate-500/20 text-slate-600 dark:text-slate-300 border-slate-300 dark:border-slate-500/30', tip: 'Hati-hati berkabut. Visibilitas terbatas, hindari survei di area jalan raya atau jembatan.' },
            51:  { desc: 'Gerimis Tipis',     icon: 'fa-cloud-rain',           color: 'text-blue-400 dark:text-blue-300',     badge: 'Gerimis',        badgeCls: 'bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-300 border-blue-300 dark:border-blue-500/30', tip: 'Ada gerimis. Lindungi perangkat dari air. Foto mungkin kurang tajam, tambahkan beberapa foto pendukung.' },
            53:  { desc: 'Gerimis Sedang',    icon: 'fa-cloud-rain',           color: 'text-blue-400 dark:text-blue-300',     badge: 'Gerimis',        badgeCls: 'bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-300 border-blue-300 dark:border-blue-500/30', tip: 'Ada gerimis. Lindungi perangkat dari air. Foto mungkin kurang tajam, tambahkan beberapa foto pendukung.' },
            55:  { desc: 'Gerimis Lebat',     icon: 'fa-cloud-rain',           color: 'text-blue-500 dark:text-blue-400',     badge: 'Gerimis Lebat',  badgeCls: 'bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-300 border-blue-300 dark:border-blue-500/30', tip: 'Gerimis cukup lebat. Pertimbangkan untuk menunda survei. Jaga perangkat tetap kering.' },
            61:  { desc: 'Hujan Ringan',      icon: 'fa-cloud-rain',           color: 'text-blue-500 dark:text-blue-400',     badge: 'Hujan Ringan',   badgeCls: 'bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-300 border-blue-300 dark:border-blue-500/30', tip: 'Hujan ringan. Tetap waspada genangan kecil. Pastikan foto jelas meski kondisi basah.' },
            63:  { desc: 'Hujan Sedang',      icon: 'fa-cloud-showers-heavy',  color: 'text-blue-600 dark:text-blue-400',     badge: 'Hujan',          badgeCls: 'bg-blue-100 dark:bg-indigo-500/20 text-blue-700 dark:text-indigo-300 border-blue-300 dark:border-indigo-500/30', tip: 'Hujan sedang. Perhatikan keamanan di area survei. Genangan mungkin menyembunyikan kerusakan drainase.' },
            65:  { desc: 'Hujan Lebat',       icon: 'fa-cloud-showers-heavy',  color: 'text-indigo-500 dark:text-indigo-400', badge: 'Waspada Banjir', badgeCls: 'bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-400 border-red-300 dark:border-red-500/30', tip: '⚠️ Hujan lebat! Hati-hati di area genangan. Pastikan kamera fokus pada titik kerusakan drainase atau aspal terendam.' },
            80:  { desc: 'Hujan Lokal',       icon: 'fa-cloud-rain',           color: 'text-blue-500 dark:text-blue-400',     badge: 'Hujan Lokal',    badgeCls: 'bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-300 border-blue-300 dark:border-blue-500/30', tip: 'Hujan lokal tidak merata. Periksa kondisi aktual di lokasi sebelum memulai survei.' },
            81:  { desc: 'Hujan Deras',       icon: 'fa-cloud-showers-heavy',  color: 'text-indigo-500 dark:text-indigo-400', badge: 'Waspada Banjir', badgeCls: 'bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-400 border-red-300 dark:border-red-500/30', tip: '⚠️ Hujan deras! Hati-hati saat mengambil foto di area genangan. Pastikan Anda berada di posisi aman.' },
            82:  { desc: 'Hujan Sangat Deras',icon: 'fa-cloud-showers-heavy',  color: 'text-red-500 dark:text-red-400',       badge: 'Bahaya Banjir',  badgeCls: 'bg-red-200 dark:bg-red-500/30 text-red-800 dark:text-red-300 border-red-400 dark:border-red-500/50', tip: '🚨 Hujan sangat deras! Tunda survei lapangan. Prioritaskan keselamatan Anda.' },
            95:  { desc: 'Badai Petir',       icon: 'fa-bolt',                 color: 'text-yellow-500 dark:text-yellow-400', badge: 'Badai Petir',    badgeCls: 'bg-yellow-100 dark:bg-yellow-500/20 text-yellow-800 dark:text-yellow-300 border-yellow-400 dark:border-yellow-500/40', tip: '🚨 Badai petir! Hentikan aktivitas lapangan segera. Cari tempat berlindung yang aman.' },
            99:  { desc: 'Badai Hebat',       icon: 'fa-bolt',                 color: 'text-red-500 dark:text-red-400',       badge: 'Bahaya!',        badgeCls: 'bg-red-200 dark:bg-red-500/30 text-red-800 dark:text-red-300 border-red-400 dark:border-red-500/50', tip: '🚨 Badai sangat hebat! Jangan keluar ruangan. Tunda semua kegiatan survei.' },
        };

        function getWmoInfo(code) {
            return WMO_CODES[code] || WMO_CODES[0];
        }

        async function reverseGeocode(lat, lon) {
            try {
                const resp = await fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lon}&format=json&accept-language=id`);
                const data = await resp.json();
                const addr = data.address;
                return addr.city || addr.town || addr.county || addr.state || 'Lokasi Anda';
            } catch (e) {
                return 'Lokasi Anda';
            }
        }

        async function fetchWeather(lat, lon) {
            const url = `https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lon}&current=temperature_2m,weathercode,windspeed_10m,relativehumidity_2m&timezone=auto`;
            const resp = await fetch(url);
            if (!resp.ok) throw new Error('Gagal mengambil data cuaca');
            return await resp.json();
        }

        function updateWidget(info, temp, city) {
            document.getElementById('weather-desc').textContent = info.desc;
            document.getElementById('weather-city').textContent = `${city}, ${Math.round(temp)}°C`;

            const badge = document.getElementById('weather-badge');
            badge.innerHTML = info.badge;
            badge.className = `px-2 py-0.5 rounded text-xs font-black uppercase tracking-widest border transition-all ${info.badgeCls}`;

            const iconEl = document.getElementById('weather-icon');
            iconEl.className = `fas ${info.icon} text-3xl ${info.color}`;

            document.getElementById('weather-tip').innerHTML =
                `<strong class="text-navy-900 dark:text-white">Tips Lapangan:</strong> ${info.tip}`;
        }

        function showError(msg) {
            document.getElementById('weather-desc').textContent = 'Data Tidak Tersedia';
            document.getElementById('weather-city').textContent = msg;
            document.getElementById('weather-badge').innerHTML = 'Gagal Memuat';
            document.getElementById('weather-badge').className = 'px-2 py-0.5 bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-400 border border-slate-300 dark:border-slate-600 rounded text-xs font-black uppercase tracking-widest';
            document.getElementById('weather-icon').className = 'fas fa-exclamation-triangle text-3xl text-slate-400';
            document.getElementById('weather-tip').innerHTML = '<strong class="text-navy-900 dark:text-white">Tips Lapangan:</strong> Tidak dapat mendeteksi cuaca secara otomatis. Periksa kondisi cuaca secara manual sebelum survei.';
        }

        async function loadWeatherWithCoords(lat, lon) {
            try {
                const [weatherData, cityName] = await Promise.all([
                    fetchWeather(lat, lon),
                    reverseGeocode(lat, lon)
                ]);
                const current  = weatherData.current;
                const info     = getWmoInfo(current.weathercode);
                updateWidget(info, current.temperature_2m, cityName);
            } catch (e) {
                showError('Gagal memuat data cuaca');
            }
        }

        // Default fallback: Banjarmasin
        const DEFAULT_LAT = -3.3194;
        const DEFAULT_LON = 114.5908;

        if ('geolocation' in navigator) {
            navigator.geolocation.getCurrentPosition(
                (pos) => loadWeatherWithCoords(pos.coords.latitude, pos.coords.longitude),
                ()    => loadWeatherWithCoords(DEFAULT_LAT, DEFAULT_LON),
                { timeout: 8000, maximumAge: 60000 }
            );
        } else {
            loadWeatherWithCoords(DEFAULT_LAT, DEFAULT_LON);
        }
    })();
</script>
@endpush
