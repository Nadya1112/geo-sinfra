<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Wilayah | Admin SINFRA</title>
    <link rel="icon" href="{{ asset('logo_geo-sinfra.png') }}" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            darkMode: 'class',
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
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
<style>
    @media (min-width: 768px) { html { font-size: 14px; } }
    @media (max-width: 767px) { html { font-size: 12px; } }
</style>
</head>
<body class="bg-navy-50 dark:bg-navy-950 text-slate-800 dark:text-slate-200 antialiased flex overflow-hidden h-screen transition-colors duration-300">

    @include('admin.partials.sidebar')

    <main class="flex-1 flex flex-col h-screen overflow-y-auto font-sans">
        <header class="bg-white/85 dark:bg-navy-950/80 backdrop-blur-xl border-b border-slate-100 dark:border-white/5 px-4 pl-16 md:px-8 py-4 flex justify-between items-center z-40 text-left transition-colors duration-300">
            <div class="flex items-center gap-4 text-left">
                <a href="{{ route('admin.wilayah') }}" class="hidden md:flex w-10 h-10 bg-white border border-slate-100 rounded-xl flex items-center justify-center text-slate-400 hover:text-gold-500 hover:border-gold-500/20 hover:shadow-lg hover:shadow-gold-500/5 transition-all group">
                    <i class="fas fa-arrow-left text-xs group-hover:-translate-x-1 transition-transform"></i>
                </a>
                <div class="text-left">
                    <p class="text-xs font-black text-gold-500 uppercase tracking-wider mb-1">Administrator Portal</p>
                    <h2 class="text-xl font-black text-navy-900 dark:text-white leading-none">Edit Data Wilayah</h2>
                </div>
            </div>
            
            <div class="flex items-center gap-6 text-left">
                <div class="text-right">
                    <p class="text-xs font-black text-navy-900 dark:text-white" id="mini-clock">00:00 WITA</p>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">{{ now()->translatedFormat('d M Y') }}</p>
                </div>
                <div class="h-8 w-[1px] bg-slate-100"></div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.profile') }}" class="text-right group hidden md:block">
                        <p class="text-sm font-black text-navy-900 dark:text-white leading-none uppercase group-hover:text-gold-500 transition-all max-w-[100px] sm:max-w-[150px] md:max-w-[300px] truncate">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] md:text-xs font-bold text-emerald-500 uppercase mt-0.5">Online</p>
                    </a>
                    <a href="{{ route('admin.profile') }}" class="w-10 h-10 bg-navy-900 rounded-xl flex items-center justify-center text-gold-500 border border-white/10 overflow-hidden hover:shadow-lg hover:shadow-navy-950/20 transition-all shadow-md">
                        @if(auth()->user()->profile_photo)
                            <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-user-circle text-xl"></i>
                        @endif
                    </a>
                </div>
            </div>
        </header>

        <div class="p-4 md:p-8 text-left">
            <div class="max-w-4xl bg-white rounded-[2.5rem] p-10 border border-slate-100 shadow-sm mx-auto">
                <div class="mb-10 border-b border-slate-50 pb-5 text-left">
                    <h3 class="text-lg font-black text-navy-900 tracking-tight">Informasi Wilayah</h3>
                    <p class="text-xs text-slate-400 font-medium font-sans">Perbarui koordinat dan nama wilayah administratif</p>
                </div>

                <form action="{{ route('admin.wilayah.update', $wilayah->id_kelurahan) }}" method="POST" class="space-y-8 text-left">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-left">
                        <div class="text-left">
                            <label class="block text-xs font-black text-navy-900 tracking-widest mb-2 uppercase">Nama Kelurahan <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_kelurahan" value="{{ $wilayah->nama_kelurahan }}" class="w-full px-5 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-semibold outline-none focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500" required>
                        </div>
                        <div class="text-left">
                            <label class="block text-xs font-black text-navy-900 tracking-widest mb-2 uppercase">Kecamatan <span class="text-red-500">*</span></label>
                            <select name="id_kecamatan" class="w-full px-5 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-semibold outline-none focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500">
                                @foreach($semuaKecamatan as $kec)
                                    <option value="{{ $kec->id_kecamatan }}" {{ $wilayah->id_kecamatan == $kec->id_kecamatan ? 'selected' : '' }}>
                                        {{ $kec->nama_kecamatan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="text-left">
                        <label class="block text-xs font-black text-navy-900 tracking-widest mb-2 uppercase">Data Geometri (GeoJSON) <span class="text-slate-400 font-medium normal-case ml-1">(Opsional)</span></label>
                        <textarea name="geometri" rows="8" placeholder='{"type": "Polygon", "coordinates": [...]}' class="w-full px-5 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-xs font-mono focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 outline-none transition-all">{{ old('geometri', $wilayah->geometri) }}</textarea>
                        <p class="text-xs text-slate-400 mt-2 italic font-medium text-left">Masukkan format GeoJSON untuk menampilkan poligon di peta.</p>
                    </div>

                    <div class="flex gap-4 pt-6 text-left">
                        <button type="submit" class="flex-1 bg-gold-500 text-white py-4 rounded-2xl font-bold shadow-lg shadow-gold-500/10 hover:bg-gold-600 transition tracking-widest text-xs uppercase">SIMPAN</button>
                        <a href="{{ route('admin.wilayah') }}" class="flex-1 bg-slate-100 text-slate-500 py-4 rounded-2xl font-bold hover:bg-slate-200 transition text-center leading-[1.2rem] tracking-widest text-xs uppercase">Batal</a>
                    </div>
                </form>
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

