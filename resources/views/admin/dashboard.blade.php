@extends('layouts.app')
@section('title', 'Dashboard | Admin SINFRA')
@section('subtitle', 'Portal Administrator')
@section('page_title', 'Beranda Utama')

@section('content')
            
            <!-- Welcome Banner (Premium Dark Mesh UI) -->
            <div class="relative bg-premium-mesh rounded-[2.5rem] p-10 mb-8 overflow-hidden shadow-2xl shadow-navy-950/20 border border-white/5 text-left">
                <div class="absolute inset-0 bg-pattern opacity-50"></div>
                <div class="absolute -right-10 -top-10 w-64 h-64 bg-white opacity-5 rounded-full blur-3xl"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 text-left">
                    <div class="text-left">
                        <h3 class="text-3xl font-black text-white mb-2 leading-tight">Selamat Datang, Administrator!</h3>
                        <p class="text-slate-300 text-sm font-medium max-w-xl text-left">Pusat kendali manajemen infrastruktur dan pengguna Sistem Pemetaan Infrastruktur Permukiman Kota Banjarmasin. Apa yang ingin Anda kerjakan hari ini?</p>
                    </div>
                    <div class="hidden md:block">
                        <div class="w-20 h-20 bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl flex items-center justify-center shadow-2xl text-gold-500">
                            <i class="fas fa-shield-alt text-4xl"></i>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Status Infrastruktur Kota & Rekomendasi AI -->
            <div class="mb-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Status Kesehatan Infrastruktur Kota -->
                <div class="lg:col-span-2 bg-gradient-to-br from-[#0f0e2c] to-navy-900 rounded-[2.5rem] p-8 border border-white/10 shadow-xl relative overflow-hidden flex flex-col sm:flex-row justify-between items-center gap-6">
                    <div class="absolute -right-10 -top-10 w-40 h-40 bg-gold-500/20 rounded-full blur-3xl pointer-events-none"></div>
                    
                    <div class="flex items-center gap-6 relative z-10 text-left w-full">
                        <div class="w-20 h-20 bg-white/5 backdrop-blur-md rounded-3xl flex items-center justify-center text-gold-500 border border-white/10 shadow-inner shrink-0">
                            <i class="fas fa-city text-4xl"></i>
                        </div>
                        <div class="text-left">
                            <div class="flex items-center gap-2 mb-1.5">
                                <span class="px-2.5 py-1 bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 rounded-md text-xs font-black uppercase tracking-widest shadow-sm">Sistem Pemetaan Aktif</span>
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Banjarmasin, Kalsel</span>
                            </div>
                            <h4 class="text-2xl font-black text-white leading-tight">{{ number_format($totalInfrastruktur) }} Aset Diawasi</h4>
                            <p class="text-sm text-slate-300 font-medium mt-1">Total infrastruktur di Kota Banjarmasin yang terdata dan dianalisis oleh AI ({{ $persenDianalisis }}% teranalisis).</p>
                        </div>
                    </div>
                    
                    <div class="flex sm:flex-col justify-between sm:justify-center gap-4 sm:gap-3 relative z-10 shrink-0 bg-white/5 p-4 rounded-2xl border border-white/10 backdrop-blur-sm w-full sm:w-auto">
                        <div class="text-center sm:text-right">
                            <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-0.5">Rusak Berat</p>
                            <p class="text-xl font-black text-red-500">{{ number_format($rusakBerat) }} <span class="text-sm text-red-400"><i class="fas fa-exclamation-triangle"></i></span></p>
                        </div>
                        <div class="w-px sm:w-full h-10 sm:h-px bg-white/10 my-auto"></div>
                        <div class="text-center sm:text-right">
                            <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-0.5">Rusak Sedang</p>
                            <p class="text-xl font-black text-amber-400">{{ number_format($rusakSedang) }} <span class="text-sm text-amber-300"><i class="fas fa-exclamation-circle"></i></span></p>
                        </div>
                        <div class="w-px sm:w-full h-10 sm:h-px bg-white/10 my-auto"></div>
                        <div class="text-center sm:text-right">
                            <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-0.5">Kondisi Baik</p>
                            <p class="text-xl font-black text-emerald-400">{{ number_format($kondisiBaik) }} <span class="text-sm text-emerald-300"><i class="fas fa-check-circle"></i></span></p>
                        </div>
                    </div>
                </div>

                <!-- Rekomendasi Prioritas AI -->
                <div class="lg:col-span-1 flex flex-col justify-center bg-white dark:bg-[#1e1b4b] rounded-[2.5rem] p-7 border border-slate-100 dark:border-white/5 shadow-xl shadow-slate-200/50 dark:shadow-black/50 relative overflow-hidden group transition-colors duration-300 text-left">
                    <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-red-500/10 dark:bg-red-500/20 rounded-full blur-3xl group-hover:bg-red-500/20 transition-all duration-500"></div>
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
                <h4 class="font-extrabold text-lg text-navy-900 mb-6">Akses Cepat</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 text-left">
                    
                    <button onclick="openQuickModal('Tambah User', 'Daftarkan Surveyor atau Admin baru ke dalam sistem untuk memperluas tim operasional.', '{{ route('admin.users.create') }}', 'fa-user-plus', 'bg-blue-50 text-blue-500 border border-blue-100')" class="group bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-2xl hover:shadow-navy-950/5 hover:border-gold-500/50 transition-all text-left w-full relative">
                        <div class="absolute top-4 right-4 bg-blue-50 text-blue-500 border border-blue-100 text-xs font-black px-2 py-1 rounded-lg">{{ number_format($totalUser) }} User</div>
                        <div class="w-12 h-12 bg-navy-50 text-gold-500 border border-navy-100 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-105 transition-all">
                            <i class="fas fa-user-plus text-lg"></i>
                        </div>
                        <h5 class="font-black text-navy-900 mb-1">Tambah User</h5>
                        <p class="text-xs text-slate-400 font-semibold leading-relaxed text-left">Daftarkan Surveyor atau Admin baru ke dalam sistem.</p>
                    </button>

                    <button onclick="openQuickModal('Kelola Wilayah', 'Tambahkan atau edit data master wilayah kecamatan dan kelurahan untuk pemetaan.', '{{ route('admin.wilayah') }}', 'fa-sitemap', 'bg-emerald-50 text-emerald-500 border border-emerald-100')" class="group bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-2xl hover:shadow-navy-950/5 hover:border-gold-500/50 transition-all text-left w-full relative">
                        <div class="absolute top-4 right-4 bg-emerald-50 text-emerald-500 border border-emerald-100 text-xs font-black px-2 py-1 rounded-lg">{{ number_format($totalWilayah) }} Area</div>
                        <div class="w-12 h-12 bg-navy-50 text-gold-500 border border-navy-100 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-105 transition-all">
                            <i class="fas fa-sitemap text-lg"></i>
                        </div>
                        <h5 class="font-black text-navy-900 mb-1">Kelola Wilayah</h5>
                        <p class="text-xs text-slate-400 font-semibold leading-relaxed text-left">Kelola data master wilayah kecamatan dan kelurahan.</p>
                    </button>

                    <button onclick="openQuickModal('Statistik Data', 'Lihat laporan analitik AI, kurva-S, dan distribusi prioritas infrastruktur kota.', '{{ route('admin.statistik') }}', 'fa-chart-pie', 'bg-purple-50 text-purple-500 border border-purple-100')" class="group bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-2xl hover:shadow-navy-950/5 hover:border-gold-500/50 transition-all text-left w-full relative">
                        <div class="absolute top-4 right-4 bg-purple-50 text-purple-500 border border-purple-100 text-xs font-black px-2 py-1 rounded-lg">{{ $persenDianalisis }}% AI</div>
                        <div class="w-12 h-12 bg-navy-50 text-gold-500 border border-navy-100 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-105 transition-all">
                            <i class="fas fa-chart-pie text-lg"></i>
                        </div>
                        <h5 class="font-black text-navy-900 mb-1">Statistik Data</h5>
                        <p class="text-xs text-slate-400 font-semibold leading-relaxed text-left">Lihat rekapitulasi data dan prediksi prioritas harian.</p>
                    </button>

                    <button onclick="openQuickModal('Data Master', 'Telusuri, edit, atau hapus seluruh data survei infrastruktur beserta hasil AI.', '{{ route('admin.infrastruktur') }}', 'fa-database', 'bg-orange-50 text-orange-500 border border-orange-100')" class="group bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-2xl hover:shadow-navy-950/5 hover:border-gold-500/50 transition-all text-left w-full relative">
                        <div class="absolute top-4 right-4 bg-orange-50 text-orange-500 border border-orange-100 text-xs font-black px-2 py-1 rounded-lg">{{ number_format($totalInfrastruktur) }} Aset</div>
                        <div class="w-12 h-12 bg-navy-50 text-gold-500 border border-navy-100 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-105 transition-all">
                            <i class="fas fa-database text-lg"></i>
                        </div>
                        <h5 class="font-black text-navy-900 mb-1">Data Master</h5>
                        <p class="text-xs text-slate-400 font-semibold leading-relaxed text-left">Lihat dan kelola seluruh data infrastruktur lapangan.</p>
                    </button>

                    <button onclick="openQuickModal('Laporan Warga', 'Tindak lanjuti pengaduan warga terkait infrastruktur rusak dan tugaskan surveyor.', '{{ route('admin.laporan-warga') }}', 'fa-bullhorn', 'bg-red-50 text-red-500 border border-red-100')" class="group bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-2xl hover:shadow-navy-950/5 hover:border-gold-500/50 transition-all text-left w-full relative">
                        <div class="absolute top-4 right-4 bg-red-50 text-red-500 border border-red-100 text-xs font-black px-2 py-1 rounded-lg">{{ number_format($totalLaporanWarga ?? 0) }} Laporan</div>
                        <div class="w-12 h-12 bg-navy-50 text-gold-500 border border-navy-100 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-105 transition-all">
                            <i class="fas fa-bullhorn text-lg"></i>
                        </div>
                        <h5 class="font-black text-navy-900 mb-1">Laporan Warga</h5>
                        <p class="text-xs text-slate-400 font-semibold leading-relaxed text-left">Kelola dan tindak lanjuti laporan kerusakan dari masyarakat.</p>
                    </button>

                </div>
            </div>

            <!-- Security & Maintenance Section -->
            <div class="mb-8 text-left">
                <h4 class="font-extrabold text-lg text-navy-900 mb-6">Pemeliharaan & Keamanan</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-left">
                    
                    <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm flex items-center justify-between group hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-3 md:gap-6">
                            <div class="w-16 h-16 bg-blue-50 text-blue-500 rounded-2xl flex items-center justify-center border border-blue-100 shadow-sm group-hover:scale-105 transition-transform">
                                <i class="fas fa-database text-3xl"></i>
                            </div>
                            <div>
                                <h5 class="font-black text-navy-900 text-lg mb-1">Backup Database</h5>
                                <p class="text-xs text-slate-400 font-semibold leading-relaxed max-w-xs">Unduh salinan keamanan (dump) dari seluruh basis data koordinat dan infrastruktur saat ini.</p>
                            </div>
                        </div>
                        <button onclick="startBackup()" class="px-6 py-3 bg-navy-900 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-gold-500 hover:shadow-lg transition-all flex items-center gap-2">
                            <i class="fas fa-download"></i> Backup Sekarang
                        </button>
                    </div>

                    <a href="{{ route('admin.activity') }}" class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm flex items-center justify-between group hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-3 md:gap-6">
                            <div class="w-16 h-16 bg-emerald-50 text-emerald-500 rounded-2xl flex items-center justify-center border border-emerald-100 shadow-sm group-hover:scale-105 transition-transform">
                                <i class="fas fa-shield-alt text-3xl"></i>
                            </div>
                            <div>
                                <h5 class="font-black text-navy-900 text-lg mb-1">Audit Trail & Log</h5>
                                <p class="text-xs text-slate-400 font-semibold leading-relaxed max-w-xs">Pantau riwayat aktivitas pengguna, penambahan data, dan perubahan konfigurasi sistem.</p>
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-slate-50 text-slate-400 rounded-xl flex items-center justify-center group-hover:bg-emerald-500 group-hover:text-white transition-colors">
                            <i class="fas fa-arrow-right"></i>
                        </div>
                    </a>

                </div>
            </div>

            <!-- Status Notice -->
            <div class="bg-navy-50 border border-navy-100/50 rounded-2xl p-6 flex items-center gap-4 text-left">
                <div class="w-10 h-10 bg-gold-500/10 text-gold-500 rounded-full flex items-center justify-center shrink-0 border border-gold-500/20">
                    <i class="fas fa-info text-xs"></i>
                </div>
                <div>
                    <p class="text-sm text-navy-900 font-semibold text-left">Sistem berjalan optimal. Hybrid Model (CNN) aktif dan siap memproses data survei terbaru.</p>
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
            <div class="relative bg-white rounded-[2.5rem] w-full max-w-md p-8 shadow-2xl transition-all scale-95 opacity-0 duration-300" id="quickModalContent">
                
                <div class="flex justify-between items-center mb-6 pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-4">
                        <div id="qm-icon-bg" class="w-12 h-12 rounded-xl flex items-center justify-center shadow-sm">
                            <i id="qm-icon" class="text-xl"></i>
                        </div>
                        <div>
                            <h3 id="qm-title" class="text-lg font-black text-navy-900">Title</h3>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-0.5">Akses Cepat</p>
                        </div>
                    </div>
                    <button onclick="closeQuickModal()" class="w-10 h-10 flex items-center justify-center bg-slate-50 text-slate-400 rounded-xl hover:bg-red-50 hover:text-red-500 transition-all shrink-0">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <p id="qm-desc" class="text-xs text-slate-500 font-medium leading-relaxed mb-8">Description here</p>
                
                <div class="flex gap-3">
                    <button type="button" onclick="closeQuickModal()" class="flex-1 py-4 bg-slate-100 text-slate-500 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-slate-200 transition-all">
                        Batal
                    </button>
                    <a id="qm-btn" href="#" class="flex-[2] py-4 flex items-center justify-center gap-2 bg-navy-900 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-gold-500 transition-all shadow-xl shadow-navy-900/10 group">
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
