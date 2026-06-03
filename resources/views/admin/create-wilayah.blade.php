<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Wilayah | Admin SINFRA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                    colors: {
                        navy: {
                            50: '#f4f4fa',
                            100: '#e9e9f3',
                            200: '#c7c8e3',
                            500: '#6366f1',
                            800: '#1e1b4b',
                            900: '#0f0e2c',
                            950: '#070617',
                        },
                        gold: {
                            50: '#fdfbf7',
                            100: '#fbf7ed',
                            500: '#c5a059',
                            600: '#b38f4a',
                            700: '#9d7c3d',
                        }
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
    
    <main class="flex-1 flex flex-col h-screen overflow-hidden text-left font-sans">
        <header class="bg-white/85 backdrop-blur-xl border-b border-slate-100 px-8 py-5 flex justify-between items-center z-40 text-left">
            <div class="flex items-center gap-4 text-left">
                <a href="{{ route('admin.wilayah') }}" class="w-10 h-10 bg-white border border-slate-100 rounded-xl flex items-center justify-center text-slate-400 hover:text-gold-500 hover:border-gold-500/20 hover:shadow-lg hover:shadow-gold-500/5 transition-all group">
                    <i class="fas fa-arrow-left text-xs group-hover:-translate-x-1 transition-transform"></i>
                </a>
                <div class="text-left">
                    <p class="text-[10px] font-black text-gold-500 uppercase tracking-[0.2em] mb-1">Administrator Portal</p>
                    <h2 class="text-xl font-black text-navy-900 leading-none">Tambah Data Wilayah</h2>
                </div>
            </div>
            
            <div class="flex items-center gap-6 text-left">
                <div class="text-right hidden sm:block">
                    <p class="text-[11px] font-black text-navy-900" id="mini-clock">00:00 WITA</p>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
                <div class="h-8 w-[1px] bg-slate-100"></div>
                <div class="flex items-center gap-3 text-left">
                    <div class="text-right">
                        <p class="text-[11px] font-black text-navy-900 leading-none uppercase">Admin SINFRA</p>
                        <p class="text-[9px] font-bold text-emerald-500 uppercase mt-1">Online</p>
                    </div>
                    <div class="w-10 h-10 bg-navy-900 rounded-xl flex items-center justify-center text-gold-500 border border-white/10 overflow-hidden hover:shadow-lg hover:shadow-navy-950/20 transition-all shadow-md">
                        <i class="fas fa-user-circle text-xl"></i>
                    </div>
                </div>
            </div>
        </header>

        <div class="flex-1 p-8 overflow-y-auto custom-scrollbar text-left">
            
            @if ($errors->any())
            <div class="max-w-3xl mx-auto mb-6 px-6 py-4 bg-red-50 border border-red-100 text-red-700 rounded-2xl">
                <p class="text-xs font-bold mb-2">Gagal menyimpan data:</p>
                <ul class="list-disc list-inside text-[11px] font-medium ml-4">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="max-w-3xl bg-white rounded-[2.5rem] p-10 border border-slate-100 shadow-sm mx-auto text-left">
                <form action="{{ route('admin.wilayah.store') }}" method="POST">
                    @csrf
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-[10px] font-black text-navy-900 uppercase tracking-widest mb-2">Pilih Kecamatan <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <select name="id_kecamatan" class="w-full px-5 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-semibold focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 outline-none appearance-none" required>
                                    <option value="" disabled selected>-- Pilih Kecamatan --</option>
                                    @foreach($semuaKecamatan as $kec)
                                        <option value="{{ $kec->id_kecamatan }}" {{ old('id_kecamatan') == $kec->id_kecamatan ? 'selected' : '' }}>
                                            {{ $kec->nama_kecamatan }}
                                        </option>
                                    @endforeach
                                </select>
                                <i class="fas fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-navy-900 uppercase tracking-widest mb-2">Nama Kelurahan <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_kelurahan" value="{{ old('nama_kelurahan') }}" placeholder="Contoh: Antasan Besar" class="w-full px-5 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-semibold focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 outline-none transition-all" required>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-navy-900 uppercase tracking-widest mb-2">Data Geometri (GeoJSON) <span class="text-slate-400 font-medium normal-case ml-1">(Opsional)</span></label>
                            <textarea name="geometri" rows="5" placeholder='{"type": "Polygon", "coordinates": [...]}' class="w-full px-5 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-xs font-mono focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 outline-none transition-all">{{ old('geometri') }}</textarea>
                            <p class="text-[9px] text-slate-400 mt-2 italic font-medium">Masukkan format GeoJSON untuk menampilkan poligon di peta.</p>
                        </div>

                        <div class="pt-8 flex gap-3">
                            <button type="submit" class="flex-1 bg-gold-500 text-white text-xs px-6 py-4 rounded-2xl font-bold shadow-lg shadow-gold-500/10 hover:bg-gold-600 transition tracking-widest uppercase flex items-center justify-center gap-2">
                                <i class="fas fa-save mr-2"></i> Simpan Data Wilayah
                            </button>
                            <a href="{{ route('admin.wilayah') }}" class="flex-1 bg-slate-100 text-slate-500 text-xs px-6 py-4 rounded-2xl font-bold hover:bg-slate-200 transition text-center flex items-center justify-center gap-2 tracking-widest uppercase">
                                Batal
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <script>
        function updateClock() {
            const now = new Date();
            document.getElementById('mini-clock').textContent = `${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')} WITA`;
        }
        setInterval(updateClock, 1000); updateClock();
    </script>
</body>
</html>