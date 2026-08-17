<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Status Laporan Warga | Surveyor SINFRA</title>
    <link rel="icon" href="{{ asset('logo_geo-sinfra.png') }}" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
                    colors: {
                        navy: { 50:'#f4f4fa', 100:'#e9e9f3', 500:'#6366f1', 800:'#1e1b4b', 900:'#0f0e2c', 950:'#070617' },
                        gold: { 50:'#fdfbf7', 100:'#fbf7ed', 500:'#c5a059', 600:'#b38f4a', 700:'#9d7c3d' }
                    }
                }
            }
        }
    </script>
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-slate-50 flex h-screen overflow-hidden text-slate-800 font-sans transition-colors duration-300">

    @include('surveyor.partials.sidebar')

    <main class="flex-1 flex flex-col h-screen overflow-hidden text-left relative">
        <header class="bg-white/85 backdrop-blur-xl border-b border-slate-100 sticky top-0 px-4 pl-20 md:px-8 py-4 flex justify-between items-center z-40">
            <div class="flex items-center gap-4">
                <a href="{{ route('surveyor.laporan') }}" class="w-10 h-10 bg-white border border-slate-100 rounded-xl flex items-center justify-center text-slate-400 hover:text-gold-500 hover:border-gold-500/20 hover:shadow-lg hover:shadow-gold-500/5 transition-all group" title="Kembali ke Daftar Laporan">
                    <i class="fas fa-arrow-left text-xs group-hover:-translate-x-1 transition-transform"></i>
                </a>
                <div class="text-left">
                    <p class="text-xs font-black text-gold-500 uppercase tracking-[0.2em] mb-1">Edit Status</p>
                    <h2 class="text-xl font-black text-navy-900 leading-none">Ubah Status Laporan Warga</h2>
                </div>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto custom-scrollbar p-6 md:p-8">
            <div class="max-w-4xl mx-auto">
                <div class="bg-white rounded-[2rem] p-6 md:p-8 shadow-sm border border-slate-100 mb-6">
                    <form id="editForm" action="{{ route('surveyor.laporan.update', $laporan->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-8 p-6 bg-slate-50 rounded-[1.5rem] border border-slate-100">
                            <h3 class="text-sm font-black text-navy-900 uppercase tracking-widest mb-4">Informasi Laporan</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Nama Pelapor</p>
                                    <p class="text-sm font-bold text-navy-900">{{ $laporan->nama_pelapor }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Waktu Laporan</p>
                                    <p class="text-sm font-bold text-navy-900">{{ \Carbon\Carbon::parse($laporan->created_at)->translatedFormat('l, d M Y H:i') }}</p>
                                </div>
                                <div class="md:col-span-2">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Deskripsi Kerusakan</p>
                                    <p class="text-sm font-medium text-slate-700">{{ $laporan->deskripsi }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="mb-8">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Pilih Status Laporan</label>
                            <div class="relative">
                                <select id="statusSelect" name="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3.5 text-sm font-bold text-navy-900 focus:outline-none focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 transition-all appearance-none cursor-pointer">
                                    <option value="Menunggu" {{ $laporan->status == 'Menunggu' ? 'selected' : '' }}>⏳ Menunggu</option>
                                    <option value="Ditinjau" {{ $laporan->status == 'Ditinjau' ? 'selected' : '' }}>👀 Ditinjau</option>
                                    <option value="Diproses" {{ $laporan->status == 'Diproses' ? 'selected' : '' }}>⚙️ Diproses</option>
                                    <option value="Selesai" {{ $laporan->status == 'Selesai' ? 'selected' : '' }}>✅ Selesai</option>
                                    <option value="Ditolak" {{ $laporan->status == 'Ditolak' ? 'selected' : '' }}>❌ Ditolak</option>
                                </select>
                                <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                            </div>
                        </div>

                        <div class="flex justify-end pt-4 border-t border-slate-100">
                            <button type="button" onclick="confirmSave()" class="px-8 py-3 bg-emerald-500 text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-lg shadow-emerald-500/20 hover:bg-emerald-600 hover:shadow-emerald-500/30 transition-all flex items-center gap-2 group">
                                <i class="fas fa-save group-hover:scale-110 transition-transform"></i>
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <script>
        function confirmSave() {
            const status = document.getElementById('statusSelect').value;
            Swal.fire({
                title: 'Simpan Perubahan?',
                text: "Status laporan akan diubah menjadi " + status,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'Ya, Simpan!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('editForm').submit();
                }
            })
        }
    </script>
</body>
</html>
