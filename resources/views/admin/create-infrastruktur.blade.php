<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Infrastruktur | Admin SINFRA</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 flex h-screen overflow-hidden text-gray-800 text-left">

    @include('admin.partials.sidebar')

    <main class="flex-1 flex flex-col h-screen overflow-y-auto">
        <header class="bg-white border-b border-gray-100 px-8 py-5 flex justify-between items-center z-10">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.infrastruktur') }}" class="w-10 h-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center text-gray-400 hover:text-blue-600 transition-all">
                    <i class="fas fa-arrow-left text-xs"></i>
                </a>
                <div>
                    <p class="text-[10px] font-extrabold text-blue-600 uppercase tracking-[0.2em] mb-1">Administrator Portal</p>
                    <h2 class="text-xl font-black text-[#1e1b4b]">Tambah Aset Infrastruktur</h2>
                </div>
            </div>
            <div class="flex items-center gap-6">
                <div class="text-right hidden sm:block">
                    <p class="text-[11px] font-black text-[#1e1b4b]" id="mini-clock">00:00 WITA</p>
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
                
                <div class="h-8 w-[1px] bg-gray-100"></div>
                
                <div class="flex items-center gap-3">
                    <div class="text-right">
                        <p class="text-[11px] font-black text-[#1e1b4b] leading-none uppercase">Admin SINFRA</p>
                        <p class="text-[9px] font-bold text-green-500 uppercase mt-1">Online</p>
                    </div>
                    <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 border border-indigo-100">
                        <i class="fas fa-user-circle text-xl"></i>
                    </div>
                </div>
            </div>
        </header>

        <div class="p-8">
            <div class="max-w-4xl bg-white rounded-[2.5rem] p-10 border border-gray-100 shadow-sm mx-auto">
                <div class="mb-10 border-b border-gray-50 pb-5">
                    <h3 class="text-lg font-black text-[#1e1b4b] uppercase tracking-tight">Identitas Objek</h3>
                    <p class="text-xs text-gray-400 font-medium uppercase">Tambah Data Infrastruktur Baru</p>
                </div>

                @if($errors->any())
                <div class="mb-8 p-5 bg-red-50 border-l-4 border-red-500 rounded-2xl flex items-center gap-4 animate-pulse">
                    <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center text-red-500 shadow-sm border border-red-100">
                        <i class="fas fa-exclamation-triangle text-xl"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-black text-red-900 uppercase">Validasi Gagal!</h4>
                        <p class="text-[11px] text-red-700 font-medium">Beberapa bidang wajib diisi belum lengkap, terutama Foto Dokumentasi.</p>
                    </div>
                </div>
                @endif

                <form action="{{ route('admin.infrastruktur.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    
                    <div>
                        <label class="block text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest mb-2">Nama Infrastruktur *</label>
                        <input type="text" name="nama_infrastruktur" class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-semibold focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" placeholder="Contoh: Titian Sungai Bilu" required>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="block text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest mb-2">Jenis Infrastruktur</label>
                            <select name="jenis_infrastruktur" class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-semibold outline-none focus:border-blue-500">
                                <option value="Jalan">Jalan</option>
                                <option value="Sanitasi">Sanitasi</option>
                                <option value="Titian">Titian</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest mb-2">Material Utama <span class="text-red-500">*</span></label>
                            <input type="text" name="material_eksisting" class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-semibold outline-none focus:border-blue-500" placeholder="Contoh: Kayu Ulin / Beton" required>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest mb-2">Dokumentasi Lapangan <span class="text-red-500">*</span></label>
                            <input type="file" name="foto" class="w-full px-5 py-2.5 bg-gray-50 border border-gray-100 rounded-2xl text-xs font-semibold file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-[10px] file:font-bold file:bg-blue-50 file:text-blue-600 transition-all" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="block text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest mb-2">Panjang (m) <span class="text-red-500">*</span></label>
                            <input type="number" step="0.01" name="panjang" class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-semibold focus:border-blue-500" placeholder="0.00" required>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest mb-2">Lebar (m) <span class="text-red-500">*</span></label>
                            <input type="number" step="0.01" name="lebar" class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-semibold focus:border-blue-500" placeholder="0.00" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="block text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest mb-2">Kecamatan</label>
                            <select name="id_kecamatan" class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-semibold focus:border-blue-500">
                                @foreach($semuaKecamatan as $kec)
                                    <option value="{{ $kec->id_kecamatan }}">{{ $kec->nama_kecamatan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest mb-2">Kelurahan</label>
                            <select name="id_kelurahan" class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-semibold focus:border-blue-500">
                                @foreach($semuaKelurahan as $kel)
                                    <option value="{{ $kel->id_kelurahan }}">{{ $kel->nama_kelurahan }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div>
                            <label class="block text-[10px] font-black text-[#1e1b4b] uppercase mb-2">Latitude</label>
                            <input type="text" name="latitude" placeholder="-3.31..." class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-semibold">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-[#1e1b4b] uppercase mb-2">Longitude</label>
                            <input type="text" name="longitude" placeholder="114.59..." class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-semibold">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-[#1e1b4b] uppercase mb-2">Deskripsi Kerusakan (AI Parameter) <span class="text-red-500">*</span></label>
                            <textarea name="kondisi" id="kondisi-textarea" rows="3" class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-semibold outline-none focus:border-blue-500" placeholder="Contoh: titian putus, cor beton retak, amblas" required></textarea>
                            <div class="mt-2 flex flex-wrap gap-2">
                                @foreach(['Putus', 'Hancur', 'Amblas', 'Retak', 'Lubang', 'Goyang', 'Total', 'Parah'] as $keyword)
                                    <button type="button" onclick="addKeyword('{{ $keyword }}')" class="px-2 py-0.5 bg-white border border-gray-100 rounded-lg text-[8px] font-bold text-gray-400 hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                                        + {{ $keyword }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-4 pt-6">
                        <button type="submit" class="flex-1 bg-blue-600 text-white py-4 rounded-2xl font-bold shadow-lg shadow-blue-200 hover:bg-blue-700 transition uppercase text-sm">Simpan Data</button>
                        <a href="{{ route('admin.infrastruktur') }}" class="flex-1 bg-gray-100 text-gray-500 py-4 rounded-2xl font-bold hover:bg-gray-200 transition text-center uppercase text-sm leading-[1.2rem]">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <script>
        function addKeyword(word) {
            const textarea = document.getElementById('kondisi-textarea');
            const currentVal = textarea.value.trim();
            if (currentVal === "") {
                textarea.value = word;
            } else {
                textarea.value = currentVal + ", " + word;
            }
            textarea.focus();
        }

        function updateClock() {
            const now = new Date();
            document.getElementById('mini-clock').textContent = `${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')} WITA`;
        }
        setInterval(updateClock, 1000); updateClock();
    </script>
</body>
</html>