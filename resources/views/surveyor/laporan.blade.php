<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Penugasan Laporan Warga | GEO-SINFRA</title>
    <link rel="icon" href="{{ asset('logo_geo-sinfra.png') }}" type="image/png">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @livewireStyles
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
                    colors: {
                        navy: { 50:'#f4f4fa', 100:'#e9e9f3', 200:'#c7c8e3', 500:'#6366f1', 800:'#1e1b4b', 900:'#0f0e2c', 950:'#070617' },
                        gold: { 50:'#fdfbf7', 100:'#fbf7ed', 500:'#c5a059', 600:'#b38f4a', 700:'#9d7c3d' }
                    }
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; height: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>

<style>
    @media (min-width: 768px) { html { font-size: 14px; } }
    @media (max-width: 767px) { html { font-size: 12px; } }
</style>
</head>
<body class="bg-slate-50  flex h-screen overflow-hidden text-slate-800 text-left font-sans   transition-colors duration-300">

    @include('surveyor.partials.sidebar')

    <main class="flex-1 flex flex-col h-screen overflow-hidden text-left font-sans relative">
        <header class="bg-white/85  backdrop-blur-xl border-b border-slate-100  sticky top-0 px-4 pl-20 md:px-8 py-4 flex justify-between items-center z-40 text-left">
            <div class="flex items-center gap-4">
                <a href="{{ route('surveyor.dashboard') }}"
                   class="hidden md:flex w-10 h-10 bg-white  border border-slate-100  rounded-xl  items-center justify-center text-slate-400 hover:text-gold-500 hover:border-gold-500/20 hover:shadow-lg hover:shadow-gold-500/5 transition-all group"
                   title="Kembali ke Beranda Utama">
                    <i class="fas fa-arrow-left text-xs group-hover:-translate-x-1 transition-transform"></i>
                </a>
                <div class="text-left">
                    <p class="text-xs font-black text-gold-500 uppercase tracking-[0.2em] mb-1">Portal Surveyor</p>
                    <h2 class="text-xl font-black text-navy-900  leading-none">Penugasan Laporan Warga</h2>
                </div>
            </div>

            <div class="flex items-center gap-3 md:gap-6">
                <div class="text-right">
                    <p class="text-[10px] md:text-sm font-black text-navy-900 mt-1" id="mini-clock">00:00 WITA</p>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter hidden md:block">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
                <div class="h-6 md:h-8 w-[1px] bg-slate-200"></div>
                <div class="flex items-center gap-2 md:gap-3">
                    <a href="{{ route('surveyor.profile') }}" class="text-right group">
                        <p class="text-xs md:text-sm font-black text-navy-900 leading-none uppercase group-hover:text-gold-500 transition-all max-w-[200px] truncate hidden md:block">{{ auth()->user()->name }}</p>
                        <p class="text-[8px] md:text-xs font-bold text-emerald-500 uppercase md:mt-0.5">Aktif</p>
                    </a>
                    <a href="{{ route('surveyor.profile') }}" class="w-8 h-8 md:w-10 md:h-10 bg-navy-900 rounded-xl flex items-center justify-center text-gold-500 border border-white/10 overflow-hidden hover:shadow-lg hover:shadow-navy-950/20 transition-all shadow-md shrink-0">
                        @if(auth()->user()->profile_photo)
                            <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-user-circle text-lg md:text-xl"></i>
                        @endif
                    </a>
                </div>
            </div>
        </header>

        <!-- Content -->
        <div class="flex-1 overflow-y-auto custom-scrollbar p-6 md:p-8 bg-slate-50 ">
            <div class="max-w-7xl mx-auto space-y-6">
                
                {{-- ── Toolbar: Judul + Filter + Ekspor ── --}}
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-2">
                    <div>
                        <h4 class="font-extrabold text-lg text-navy-900 ">Daftar Penugasan Laporan Warga</h4>
                        <p class="text-xs text-slate-400 font-semibold mt-0.5">Tinjau lokasi laporan di lapangan dan ubah status laporan</p>
                    </div>
                </div>

                @livewire('surveyor.laporan-table')

            </div>
        </div>
    </main>

    <!-- Modal Foto -->
    <div id="photoModal" class="fixed inset-0 bg-navy-950/90 backdrop-blur-sm z-[9999] hidden opacity-0 transition-opacity duration-300 flex items-center justify-center p-4">
        <button onclick="closePhotoModal()" class="absolute top-6 right-6 w-12 h-12 bg-white/10  hover:bg-white/20  text-white rounded-full flex items-center justify-center transition-colors">
            <i class="fas fa-times text-xl"></i>
        </button>
        <div class="max-w-4xl w-full max-h-[90vh] relative transform scale-95 transition-transform duration-300" id="photoModalContent">
            <img id="modalImage" src="" alt="Foto Laporan" class="w-full h-full object-contain rounded-xl shadow-2xl">
        </div>
    </div>

    <script>
        function showPhotoModal(src) {
            const modal = document.getElementById('photoModal');
            const modalContent = document.getElementById('photoModalContent');
            const img = document.getElementById('modalImage');
            
            img.src = src;
            modal.classList.remove('hidden');
            
            // Trigger animation
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modalContent.classList.remove('scale-95');
                modalContent.classList.add('scale-100');
            }, 10);
            
            document.body.style.overflow = 'hidden';
        }

        function closePhotoModal() {
            const modal = document.getElementById('photoModal');
            const modalContent = document.getElementById('photoModalContent');
            
            modal.classList.add('opacity-0');
            modalContent.classList.remove('scale-100');
            modalContent.classList.add('scale-95');
            
            setTimeout(() => {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }, 300);
        }

        // Close on background click
        document.getElementById('photoModal').addEventListener('click', function(e) {
            if (e.target === this) closePhotoModal();
        });

        // Real-time Clock function
        function updateClock() {
            const now = new Date();
            const options = { timeZone: 'Asia/Makassar', hour: '2-digit', minute: '2-digit', hour12: false };
            const timeString = new Intl.DateTimeFormat('id-ID', options).format(now);
            const el = document.getElementById('mini-clock');
            if (el) el.textContent = timeString.replace('.', ':') + ' WITA';
        }
        setInterval(updateClock, 1000); updateClock();

        function confirmUpdate(id, newStatus) {
            Swal.fire({
                title: 'Konfirmasi Perubahan',
                text: "Apakah Anda yakin ingin mengubah status laporan ini menjadi " + newStatus + "?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'Ya, Ubah!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('updateStatus', { id: id, status: newStatus });
                    Swal.fire({
                        title: 'Berhasil!',
                        text: 'Status laporan berhasil diperbarui.',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @livewireScripts
</body>
</html>
