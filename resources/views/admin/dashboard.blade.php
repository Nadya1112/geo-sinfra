@extends('layouts.app')
@section('title', 'Dashboard | Admin SINFRA')
@section('subtitle', 'Portal Administrator')
@section('page_title', 'Beranda Utama')

@section('content')
            
            <!-- Welcome Banner (Premium Dark Mesh UI) -->
            <div class="relative bg-premium-mesh rounded-[2.5rem] p-10 mb-8 overflow-hidden shadow-2xl shadow-navy-950/20 border border-slate-200 dark:border-white/5 text-left">
                <div class="absolute inset-0 bg-pattern opacity-50 dark:opacity-30"></div>
                <div class="absolute -right-10 -top-10 w-64 h-64 bg-white opacity-10 dark:opacity-5 rounded-full blur-3xl"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 text-left">
                    <div class="text-left">
                        <h3 class="text-3xl font-black text-white mb-2 leading-tight">Selamat Datang, Administrator!</h3>
                        <p class="text-white/80 dark:text-slate-300 text-sm font-medium max-w-xl text-left">Pusat kendali manajemen infrastruktur dan pengguna Sistem Pemetaan Infrastruktur Permukiman Kota Banjarmasin. Apa yang ingin Anda kerjakan hari ini?</p>
                    </div>
                    <div class="hidden md:block">
                        <div class="w-20 h-20 bg-white/10 backdrop-blur-md border border-white/20 dark:border-white/10 rounded-2xl flex items-center justify-center shadow-2xl text-gold-400 dark:text-gold-500">
                            <i class="fas fa-shield-alt text-4xl"></i>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Status Infrastruktur Kota & Rekomendasi AI -->
            <div class="mb-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Status Kesehatan Infrastruktur Kota -->
                <div class="lg:col-span-2 bg-white dark:bg-navy-900/90 dark:backdrop-blur-xl rounded-[2.5rem] p-8 border border-slate-200 dark:border-white/10 shadow-xl dark:shadow-navy-950/50 relative overflow-hidden flex flex-col sm:flex-row justify-between items-center gap-6 transition-colors">
                    <div class="absolute -right-10 -top-10 w-40 h-40 bg-gold-500/10 dark:bg-gold-500/20 rounded-full blur-3xl pointer-events-none"></div>
                    
                    <div class="flex items-center gap-6 relative z-10 text-left w-full">
                        <div class="w-20 h-20 bg-slate-50 dark:bg-white/5 backdrop-blur-md rounded-3xl flex items-center justify-center text-gold-500 border border-slate-200 dark:border-white/10 shadow-inner shrink-0">
                            <i class="fas fa-city text-4xl"></i>
                        </div>
                        <div class="text-left">
                            <div class="flex items-center gap-2 mb-1.5">
                                <span class="px-2.5 py-1 bg-emerald-50 dark:bg-emerald-500/20 border border-emerald-200 dark:border-emerald-500/30 text-emerald-600 dark:text-emerald-400 rounded-md text-xs font-black uppercase tracking-widest shadow-sm">Sistem Pemetaan Aktif</span>
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Banjarmasin, Kalsel</span>
                            </div>
                            <h4 class="text-2xl font-black text-navy-900 dark:text-white leading-tight">{{ number_format($totalInfrastruktur) }} Aset Diawasi</h4>
                            <p class="text-sm text-slate-500 dark:text-slate-300 font-medium mt-1">Total infrastruktur di Kota Banjarmasin yang terdata dan dianalisis oleh AI ({{ $persenDianalisis }}% teranalisis).</p>
                        </div>
                    </div>
                    
                    <div class="flex sm:flex-col justify-between sm:justify-center gap-4 sm:gap-3 relative z-10 shrink-0 bg-slate-50/80 dark:bg-white/5 p-4 rounded-2xl border border-slate-200 dark:border-white/10 backdrop-blur-sm w-full sm:w-auto">
                        <div class="text-center sm:text-right">
                            <p class="text-[10px] md:text-xs font-black text-slate-400 uppercase tracking-widest mb-0.5">Kondisi Rusak Berat</p>
                            <p class="text-xl font-black text-red-600 dark:text-red-500">{{ number_format($rusakBerat) }} <span class="text-sm text-red-500 dark:text-red-400"><i class="fas fa-exclamation-triangle"></i></span></p>
                        </div>
                        <div class="w-px sm:w-full h-10 sm:h-px bg-slate-200 dark:bg-white/10 my-auto"></div>
                        <div class="text-center sm:text-right">
                            <p class="text-[10px] md:text-xs font-black text-slate-400 uppercase tracking-widest mb-0.5">Kondisi Rusak Sedang</p>
                            <p class="text-xl font-black text-orange-500 dark:text-orange-400">{{ number_format($rusakSedang) }} <span class="text-sm text-orange-400 dark:text-orange-300"><i class="fas fa-exclamation-circle"></i></span></p>
                        </div>
                        <div class="w-px sm:w-full h-10 sm:h-px bg-slate-200 dark:bg-white/10 my-auto"></div>
                        <div class="text-center sm:text-right">
                            <p class="text-[10px] md:text-xs font-black text-slate-400 uppercase tracking-widest mb-0.5">Kondisi Rusak Ringan</p>
                            <p class="text-xl font-black text-yellow-500 dark:text-yellow-400">{{ number_format($rusakRingan) }} <span class="text-sm text-yellow-400 dark:text-yellow-300"><i class="fas fa-wrench"></i></span></p>
                        </div>
                        <div class="w-px sm:w-full h-10 sm:h-px bg-slate-200 dark:bg-white/10 my-auto"></div>
                        <div class="text-center sm:text-right">
                            <p class="text-[10px] md:text-xs font-black text-slate-400 uppercase tracking-widest mb-0.5">Kondisi Baik</p>
                            <p class="text-xl font-black text-emerald-600 dark:text-emerald-400">{{ number_format($kondisiBaik) }} <span class="text-sm text-emerald-500 dark:text-emerald-300"><i class="fas fa-check-circle"></i></span></p>
                        </div>
                    </div>
                </div>

                <!-- Rekomendasi Prioritas AI -->
                <div class="lg:col-span-1 flex flex-col justify-center bg-white dark:bg-navy-900/90 dark:backdrop-blur-xl rounded-[2.5rem] p-7 border border-slate-200 dark:border-white/10 shadow-xl shadow-slate-200/50 dark:shadow-navy-950/50 relative overflow-hidden group transition-colors duration-300 text-left">
                    <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-red-50 dark:bg-red-500/20 rounded-full blur-3xl group-hover:bg-red-100 dark:group-hover:bg-red-500/20 transition-all duration-500"></div>
                    <div class="w-12 h-12 bg-gold-500/10 text-gold-500 rounded-2xl flex items-center justify-center mb-4 border border-gold-500/20 shadow-sm">
                        <i class="fas fa-robot text-xl animate-pulse"></i>
                    </div>
                    <h5 class="font-black text-navy-900 dark:text-white mb-2">Rekomendasi Prioritas AI</h5>
                    @if($rekomendasi)
                        <h5 class="text-sm font-black text-navy-900 dark:text-white mt-4 mb-2 line-clamp-1 leading-snug">{{ $rekomendasi->nama_objek ?? $rekomendasi->nama_infrastruktur }}</h5>
                        <p class="text-sm font-bold text-slate-500 dark:text-slate-400 mb-6 flex items-center gap-1.5"><i class="fas fa-map-marker-alt text-slate-400"></i> Kelurahan {{ $rekomendasi->nama_kelurahan }}</p>
                        <a href="{{ route('admin.infrastruktur.show', $rekomendasi->id_infrastruktur) }}" class="inline-flex items-center justify-center gap-2 w-full bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 py-3 rounded-xl text-xs font-black hover:bg-red-100 dark:hover:bg-red-500/20 transition-colors uppercase tracking-wider">
                            Lihat Detail <i class="fas fa-arrow-right"></i>
                        </a>
                    @else
                        <div class="w-12 h-12 bg-emerald-50 dark:bg-emerald-500/10 text-emerald-500 rounded-xl flex items-center justify-center mb-4 border border-emerald-100 dark:border-emerald-500/20 shadow-sm">
                            <i class="fas fa-check-circle text-lg"></i>
                        </div>
                        <h5 class="text-sm font-black text-navy-900 dark:text-white mt-4 mb-2 leading-snug">Semua Aman</h5>
                        <p class="text-sm font-bold text-slate-500 dark:text-slate-400 mb-6">Tidak ada infrastruktur dengan prioritas rusak berat saat ini.</p>
                    @endif
                </div>
            </div>

            <!-- Quick Access Cards -->
            <div class="mb-8 text-left">
                <h4 class="font-extrabold text-lg text-navy-900 dark:text-white mb-6">Akses Cepat</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 text-left">
                    
                    <button onclick="openQuickModal('Tambah User', 'Daftarkan Surveyor atau Admin baru ke dalam sistem untuk memperluas tim operasional.', '{{ route('admin.users.create') }}', 'fa-user-plus', 'bg-blue-50 text-blue-500 border border-blue-100')" class="group bg-white dark:bg-navy-900/80 dark:backdrop-blur-xl p-6 rounded-3xl border border-slate-200 dark:border-white/10 shadow-sm hover:shadow-2xl hover:shadow-slate-300/50 dark:hover:shadow-navy-950/50 hover:border-gold-500/50 dark:hover:border-gold-500/50 transition-all text-left w-full relative">
                        <div class="absolute top-4 right-4 bg-blue-50 dark:bg-blue-500/10 text-blue-500 border border-blue-100 dark:border-blue-500/20 text-xs font-black px-2 py-1 rounded-lg">{{ number_format($totalUser) }} User</div>
                        <div class="w-12 h-12 bg-slate-50 dark:bg-navy-950 text-gold-500 border border-slate-200 dark:border-white/10 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-105 transition-all">
                            <i class="fas fa-user-plus text-lg"></i>
                        </div>
                        <h5 class="font-black text-navy-900 dark:text-white mb-1">Tambah User</h5>
                        <p class="text-xs text-slate-500 dark:text-slate-400 font-semibold leading-relaxed text-left">Daftarkan Surveyor atau Admin baru ke dalam sistem.</p>
                    </button>

                    <button onclick="openQuickModal('Kelola Wilayah', 'Tambahkan atau edit data master wilayah kecamatan dan kelurahan untuk pemetaan.', '{{ route('admin.wilayah') }}', 'fa-sitemap', 'bg-emerald-50 text-emerald-500 border border-emerald-100')" class="group bg-white dark:bg-navy-900/80 dark:backdrop-blur-xl p-6 rounded-3xl border border-slate-200 dark:border-white/10 shadow-sm hover:shadow-2xl hover:shadow-slate-300/50 dark:hover:shadow-navy-950/50 hover:border-gold-500/50 dark:hover:border-gold-500/50 transition-all text-left w-full relative">
                        <div class="absolute top-4 right-4 bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-500 border border-emerald-100 dark:border-emerald-500/20 text-xs font-black px-2 py-1 rounded-lg">{{ number_format($totalWilayah) }} Area</div>
                        <div class="w-12 h-12 bg-slate-50 dark:bg-navy-950 text-gold-500 border border-slate-200 dark:border-white/10 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-105 transition-all">
                            <i class="fas fa-sitemap text-lg"></i>
                        </div>
                        <h5 class="font-black text-navy-900 dark:text-white mb-1">Kelola Wilayah</h5>
                        <p class="text-xs text-slate-500 dark:text-slate-400 font-semibold leading-relaxed text-left">Kelola data master wilayah kecamatan dan kelurahan.</p>
                    </button>

                    <button onclick="openQuickModal('Statistik Data', 'Lihat laporan analitik AI, kurva-S, dan distribusi prioritas infrastruktur kota.', '{{ route('admin.statistik') }}', 'fa-chart-pie', 'bg-purple-50 text-purple-500 border border-purple-100')" class="group bg-white dark:bg-navy-900/80 dark:backdrop-blur-xl p-6 rounded-3xl border border-slate-200 dark:border-white/10 shadow-sm hover:shadow-2xl hover:shadow-slate-300/50 dark:hover:shadow-navy-950/50 hover:border-gold-500/50 dark:hover:border-gold-500/50 transition-all text-left w-full relative">
                        <div class="absolute top-4 right-4 bg-purple-50 dark:bg-purple-500/10 text-purple-600 dark:text-purple-500 border border-purple-100 dark:border-purple-500/20 text-xs font-black px-2 py-1 rounded-lg">{{ $persenDianalisis }}% AI</div>
                        <div class="w-12 h-12 bg-slate-50 dark:bg-navy-950 text-gold-500 border border-slate-200 dark:border-white/10 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-105 transition-all">
                            <i class="fas fa-chart-pie text-lg"></i>
                        </div>
                        <h5 class="font-black text-navy-900 dark:text-white mb-1">Statistik Data</h5>
                        <p class="text-xs text-slate-500 dark:text-slate-400 font-semibold leading-relaxed text-left">Lihat rekapitulasi data dan prediksi prioritas harian.</p>
                    </button>

                    <button onclick="openQuickModal('Data Master', 'Telusuri, edit, atau hapus seluruh data survei infrastruktur beserta hasil AI.', '{{ route('admin.infrastruktur') }}', 'fa-database', 'bg-orange-50 text-orange-500 border border-orange-100')" class="group bg-white dark:bg-navy-900/80 dark:backdrop-blur-xl p-6 rounded-3xl border border-slate-200 dark:border-white/10 shadow-sm hover:shadow-2xl hover:shadow-slate-300/50 dark:hover:shadow-navy-950/50 hover:border-gold-500/50 dark:hover:border-gold-500/50 transition-all text-left w-full relative">
                        <div class="absolute top-4 right-4 bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-500 border border-orange-100 dark:border-orange-500/20 text-xs font-black px-2 py-1 rounded-lg">{{ number_format($totalInfrastruktur) }} Aset</div>
                        <div class="w-12 h-12 bg-slate-50 dark:bg-navy-950 text-gold-500 border border-slate-200 dark:border-white/10 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-105 transition-all">
                            <i class="fas fa-database text-lg"></i>
                        </div>
                        <h5 class="font-black text-navy-900 dark:text-white mb-1">Data Master</h5>
                        <p class="text-xs text-slate-500 dark:text-slate-400 font-semibold leading-relaxed text-left">Lihat dan kelola seluruh data infrastruktur lapangan.</p>
                    </button>

                    <button onclick="openQuickModal('Laporan Warga', 'Tindak lanjuti pengaduan warga terkait infrastruktur rusak dan tugaskan surveyor.', '{{ route('admin.laporan-warga') }}', 'fa-bullhorn', 'bg-red-50 text-red-500 border border-red-100')" class="group bg-white dark:bg-navy-900/80 dark:backdrop-blur-xl p-6 rounded-3xl border border-slate-200 dark:border-white/10 shadow-sm hover:shadow-2xl hover:shadow-slate-300/50 dark:hover:shadow-navy-950/50 hover:border-gold-500/50 dark:hover:border-gold-500/50 transition-all text-left w-full relative">
                        <div class="absolute top-4 right-4 bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-500 border border-red-100 dark:border-red-500/20 text-xs font-black px-2 py-1 rounded-lg">{{ number_format($totalLaporanWarga ?? 0) }} Laporan</div>
                        <div class="w-12 h-12 bg-slate-50 dark:bg-navy-950 text-gold-500 border border-slate-200 dark:border-white/10 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-105 transition-all">
                            <i class="fas fa-bullhorn text-lg"></i>
                        </div>
                        <h5 class="font-black text-navy-900 dark:text-white mb-1">Laporan Warga</h5>
                        <p class="text-xs text-slate-500 dark:text-slate-400 font-semibold leading-relaxed text-left">Kelola dan tindak lanjuti laporan kerusakan dari masyarakat.</p>
                    </button>

                </div>
            </div>

            <!-- Security & Maintenance Section -->
            <div class="mb-8 text-left">
                <h4 class="font-extrabold text-lg text-navy-900 dark:text-white mb-6">Pemeliharaan & Keamanan</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-left">
                    
                    <div class="bg-white dark:bg-navy-900/80 dark:backdrop-blur-xl p-8 rounded-[2.5rem] border border-slate-200 dark:border-white/10 shadow-sm flex items-center justify-between group hover:shadow-xl hover:shadow-slate-300/50 dark:hover:shadow-navy-950/50 transition-all">
                        <div class="flex items-center gap-3 md:gap-6">
                            <div class="w-16 h-16 bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-500 rounded-2xl flex items-center justify-center border border-blue-200 dark:border-blue-500/20 shadow-sm group-hover:scale-105 transition-transform">
                                <i class="fas fa-database text-3xl"></i>
                            </div>
                            <div>
                                <h5 class="font-black text-navy-900 dark:text-white text-lg mb-1">Backup Database</h5>
                                <p class="text-xs text-slate-500 dark:text-slate-400 font-semibold leading-relaxed max-w-xs">Unduh salinan keamanan (dump) dari seluruh basis data koordinat dan infrastruktur saat ini.</p>
                            </div>
                        </div>
                        <button onclick="startBackup()" class="px-6 py-3 bg-navy-900 dark:bg-gold-500 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-gold-500 dark:hover:bg-white dark:hover:text-navy-900 hover:shadow-lg transition-all flex items-center gap-2 border border-transparent dark:border-white/10">
                            <i class="fas fa-download"></i> Backup Sekarang
                        </button>
                    </div>

                    <a href="{{ route('admin.activity') }}" class="bg-white dark:bg-navy-900/80 dark:backdrop-blur-xl p-8 rounded-[2.5rem] border border-slate-200 dark:border-white/10 shadow-sm flex items-center justify-between group hover:shadow-xl hover:shadow-slate-300/50 dark:hover:shadow-navy-950/50 transition-all">
                        <div class="flex items-center gap-3 md:gap-6">
                            <div class="w-16 h-16 bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-500 rounded-2xl flex items-center justify-center border border-emerald-200 dark:border-emerald-500/20 shadow-sm group-hover:scale-105 transition-transform">
                                <i class="fas fa-shield-alt text-3xl"></i>
                            </div>
                            <div>
                                <h5 class="font-black text-navy-900 dark:text-white text-lg mb-1">Audit Trail & Log</h5>
                                <p class="text-xs text-slate-500 dark:text-slate-400 font-semibold leading-relaxed max-w-xs">Pantau riwayat aktivitas pengguna, penambahan data, dan perubahan konfigurasi sistem.</p>
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-slate-50 dark:bg-white/5 text-slate-400 dark:text-slate-300 rounded-xl flex items-center justify-center group-hover:bg-emerald-500 group-hover:text-white dark:group-hover:bg-emerald-500 dark:group-hover:text-white transition-colors border border-slate-200 dark:border-white/10">
                            <i class="fas fa-arrow-right"></i>
                        </div>
                    </a>

                </div>
            </div>

            <!-- Status Notice -->
            <div class="bg-navy-50 dark:bg-navy-900/50 border border-navy-200 dark:border-white/10 rounded-2xl p-6 flex items-center gap-4 text-left">
                <div class="w-10 h-10 bg-gold-500/10 text-gold-500 rounded-full flex items-center justify-center shrink-0 border border-gold-500/20">
                    <i class="fas fa-info text-xs"></i>
                </div>
                <div>
                    <p class="text-sm text-navy-900 dark:text-white font-semibold text-left">Sistem berjalan optimal. Hybrid Model (CNN) aktif dan siap memproses data survei terbaru.</p>
                </div>
            </div>

        </div>
    </main>

    <!-- Quick Access Modal -->
    <div id="quick-modal" class="fixed inset-0 z-[60] hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <!-- Overlay -->
            <div class="fixed inset-0 bg-navy-900/60 backdrop-blur-sm transition-opacity" onclick="closeQuickModal()"></div>
            
            <!-- Modal Content -->
            <div class="relative bg-white dark:bg-[#0f0e2c]/95 dark:backdrop-blur-2xl rounded-[2.5rem] w-full max-w-md p-8 shadow-2xl border border-transparent dark:border-white/10 transition-all scale-95 opacity-0 duration-300" id="quickModalContent">
                
                <div class="flex justify-between items-center mb-6 pb-4 border-b border-slate-100 dark:border-white/10">
                    <div class="flex items-center gap-4">
                        <div id="qm-icon-bg" class="w-12 h-12 rounded-xl flex items-center justify-center shadow-sm">
                            <i id="qm-icon" class="text-xl"></i>
                        </div>
                        <div>
                            <h3 id="qm-title" class="text-lg font-black text-navy-900 dark:text-white">Title</h3>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-0.5">Akses Cepat</p>
                        </div>
                    </div>
                    <button onclick="closeQuickModal()" class="w-10 h-10 flex items-center justify-center bg-slate-50 dark:bg-white/5 text-slate-400 dark:text-slate-300 rounded-xl hover:bg-red-50 hover:text-red-500 dark:hover:bg-red-500/20 dark:hover:text-red-400 border border-transparent dark:border-white/10 transition-all shrink-0">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <p id="qm-desc" class="text-xs text-slate-500 dark:text-slate-300 font-medium leading-relaxed mb-8">Description here</p>
                
                <div class="flex gap-3">
                    <button type="button" onclick="closeQuickModal()" class="flex-1 py-4 bg-slate-100 dark:bg-white/5 text-slate-500 dark:text-slate-300 border border-transparent dark:border-white/10 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-slate-200 dark:hover:bg-white/10 transition-all">
                        Batal
                    </button>
                    <a id="qm-btn" href="#" class="flex-[2] py-4 flex items-center justify-center gap-2 bg-navy-900 dark:bg-gold-500 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-gold-500 dark:hover:bg-white dark:hover:text-navy-900 transition-all shadow-xl shadow-navy-900/10 group">
                        Lanjutkan <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function startBackup() {
            Swal.fire({
                title: 'Mempersiapkan Backup',
                html: 'Mengekspor struktur database dan data infrastruktur...',
                timer: 2500,
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading()
                }
            }).then((result) => {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("admin.backup") }}';
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                
                form.appendChild(csrfToken);
                document.body.appendChild(form);
                form.submit();
                document.body.removeChild(form);

                Swal.fire({
                    icon: 'success',
                    title: 'Backup Berhasil!',
                    text: 'File SQL sedang diunduh ke perangkat Anda.',
                    confirmButtonColor: '#0f0e2c'
                });
            });
        }

        function openQuickModal(title, desc, url, icon, colorClass) {
            document.getElementById('qm-title').innerText = title;
            document.getElementById('qm-desc').innerText = desc;
            document.getElementById('qm-btn').href = url;
            document.getElementById('qm-icon').className = `fas ${icon}`;
            document.getElementById('qm-icon-bg').className = `w-12 h-12 rounded-xl flex items-center justify-center shadow-sm shrink-0 ${colorClass}`;
            
            const modal = document.getElementById('quick-modal');
            const content = document.getElementById('quickModalContent');
            modal.classList.remove('hidden');
            
            requestAnimationFrame(() => {
                setTimeout(() => {
                    content.classList.remove('scale-95', 'opacity-0');
                    content.classList.add('scale-100', 'opacity-100');
                }, 10);
            });
        }

        function closeQuickModal() {
            const modal = document.getElementById('quick-modal');
            const content = document.getElementById('quickModalContent');
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }
    </script>
@endpush
