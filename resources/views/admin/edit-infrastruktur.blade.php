<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Infrastruktur | Admin SINFRA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
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
                    <p class="text-[10px] font-extrabold text-blue-600 tracking-[0.2em] mb-1">Administrator Portal</p>
                    <h2 class="text-xl font-black text-[#1e1b4b]">Edit Data Infrastruktur</h2>
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

        <div class="p-8 pb-24">
            <div class="max-w-4xl bg-white rounded-[2.5rem] p-10 border border-gray-100 shadow-sm mx-auto">
                <form action="{{ route('admin.infrastruktur.update', $inf->id_infrastruktur) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-6">
                        <div class="border-l-4 border-blue-500 pl-4 mb-4">
                            <h4 class="text-sm font-black text-[#1e1b4b] uppercase tracking-wider">1. Identitas & Wilayah</h4>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-black text-[#1e1b4b] tracking-widest mb-2">Nama Infrastruktur</label>
                                <input type="text" name="nama_infrastruktur" value="{{ $inf->nama_objek ?? $inf->nama_infrastruktur }}" class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-semibold outline-none focus:border-blue-500 transition-all" required>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-[#1e1b4b] uppercase mb-2">Jenis</label>
                                <select name="jenis_infrastruktur" class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-semibold outline-none">
                                    <option value="Jalan" {{ (strtolower($inf->jenis) == 'jalan') ? 'selected' : '' }}>Jalan</option>
                                    <option value="Sanitasi" {{ (strtolower($inf->jenis) == 'sanitasi') ? 'selected' : '' }}>Sanitasi</option>
                                    <option value="Titian" {{ (strtolower($inf->jenis) == 'titian') ? 'selected' : '' }}>Titian</option>
                                </select>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-black text-[#1e1b4b] uppercase mb-2">Kecamatan</label>
                                <select name="id_kecamatan" class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-semibold">
                                    @foreach($semuaKecamatan as $kec)
                                        <option value="{{ $kec->id_kecamatan }}" {{ $inf->id_kecamatan == $kec->id_kecamatan ? 'selected' : '' }}>{{ $kec->nama_kecamatan }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-[#1e1b4b] uppercase mb-2">Kelurahan</label>
                                <select name="id_kelurahan" class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-semibold">
                                    @foreach($semuaKelurahan as $kel)
                                        <option value="{{ $kel->id_kelurahan }}" {{ $inf->id_kelurahan == $kel->id_kelurahan ? 'selected' : '' }}>{{ $kel->nama_kelurahan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6 pt-6 border-t border-gray-100">
                        <div class="border-l-4 border-orange-500 pl-4 mb-4">
                            <h4 class="text-sm font-black text-[#1e1b4b] uppercase tracking-wider">2. Detail Teknis & Kondisi (AI Parameter)</h4>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-[10px] font-black text-[#1e1b4b] uppercase mb-2">Material Utama <span class="text-red-500">*</span></label>
                                <input type="text" name="material_eksisting" value="{{ $inf->material_eksisting }}" placeholder="Contoh: Kayu Ulin, Beton, Paving" class="w-full px-5 py-3 bg-orange-50/30 border border-orange-100 rounded-2xl text-sm font-semibold outline-none focus:border-orange-500" required>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-[#1e1b4b] uppercase mb-2">Panjang (m) <span class="text-red-500">*</span></label>
                                <input type="number" step="0.01" name="panjang" value="{{ $inf->panjang }}" placeholder="0.00" class="w-full px-5 py-3 bg-orange-50/30 border border-orange-100 rounded-2xl text-sm font-semibold outline-none focus:border-orange-500" required>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-[#1e1b4b] uppercase mb-2">Lebar (m) <span class="text-red-500">*</span></label>
                                <input type="number" step="0.01" name="lebar" value="{{ $inf->lebar }}" placeholder="0.00" class="w-full px-5 py-3 bg-orange-50/30 border border-orange-100 rounded-2xl text-sm font-semibold outline-none focus:border-orange-500" required>
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-[#1e1b4b] uppercase mb-2">Ketersediaan Drainase</label>
                            <select name="has_drainase" class="w-full px-5 py-3 bg-orange-50/30 border border-orange-100 rounded-2xl text-sm font-semibold outline-none focus:border-orange-500">
                                <option value="ya" {{ $inf->has_drainase == 'ya' ? 'selected' : '' }}>Ada Drainase</option>
                                <option value="tidak" {{ $inf->has_drainase == 'tidak' ? 'selected' : '' }}>Tidak Ada Drainase</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-[#1e1b4b] uppercase mb-2">Deskripsi Kerusakan (Trigger Decision Tree) <span class="text-red-500">*</span></label>
                            <textarea name="kondisi" id="kondisi-textarea" rows="3" class="w-full px-5 py-3 bg-orange-50/30 border border-orange-100 rounded-2xl text-sm font-semibold outline-none focus:border-orange-500" placeholder="Contoh: titian putus, cor beton retak, amblas" required>{{ $inf->kondisi }}</textarea>
                            <div class="mt-2 flex flex-wrap gap-2">
                                @foreach(['Putus', 'Hancur', 'Amblas', 'Retak', 'Lubang', 'Goyang', 'Total', 'Parah'] as $keyword)
                                    <button type="button" onclick="addKeyword('{{ $keyword }}')" class="px-2 py-0.5 bg-white border border-gray-100 rounded-lg text-[8px] font-bold text-gray-500 hover:bg-orange-500 hover:text-white transition-all shadow-sm">
                                        + {{ $keyword }}
                                    </button>
                                @endforeach
                            </div>
                            <p class="text-[9px] text-gray-400 mt-2 italic font-medium">* Perubahan teks ini akan otomatis mengupdate skor AI saat disimpan.</p>
                        </div>
                    </div>

                    <div class="space-y-6 pt-6 border-t border-gray-100">
                        <div class="border-l-4 border-indigo-500 pl-4 mb-4">
                            <h4 class="text-sm font-black text-[#1e1b4b] uppercase tracking-wider">3. Lokasi Geografis</h4>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-black text-[#1e1b4b] uppercase mb-2">Latitude</label>
                                <input type="text" name="latitude" id="lat-input" value="{{ $inf->latitude }}" class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-semibold">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-[#1e1b4b] uppercase mb-2">Longitude</label>
                                <input type="text" name="longitude" id="lng-input" value="{{ $inf->longitude }}" class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-semibold">
                            </div>
                        </div>
                        <div id="edit-map" class="w-full h-48 rounded-3xl border border-gray-100 shadow-inner z-0"></div>
                    </div>

                    <div class="space-y-6 pt-6 border-t border-gray-100">
                        <div class="border-l-4 border-emerald-500 pl-4 mb-4">
                            <h4 class="text-sm font-black text-[#1e1b4b] uppercase tracking-wider">4. Dokumentasi Visual (Read-Only)</h4>
                        </div>
                        <div class="relative rounded-3xl overflow-hidden border border-gray-100 bg-gray-50 h-56 flex items-center justify-center group">
                            @if($inf->foto_terbaru && $inf->foto_terbaru != 'default.jpg')
                                @php $cleanPath = str_replace('\\', '/', $inf->foto_terbaru); @endphp
                                <img src="{{ asset('storage/' . (str_contains($cleanPath, 'infrastruktur/') ? $cleanPath : 'infrastruktur/' . $cleanPath)) }}" class="absolute inset-0 w-full h-full object-cover">
                            @else
                                <div class="text-center">
                                    <i class="fas fa-image text-4xl text-gray-200 mb-2"></i>
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Tidak ada foto</p>
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-black/20 backdrop-blur-[2px] opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <span class="bg-white/90 px-4 py-2 rounded-xl text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest shadow-xl">
                                    <i class="fas fa-lock mr-2 text-red-500"></i> Foto Tidak Dapat Diedit
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-4 pt-8 mt-4 border-t border-gray-100">
                        <button type="submit" class="flex-1 bg-[#1e1b4b] text-white py-4 rounded-2xl font-black text-[11px] tracking-widest hover:bg-black transition-all shadow-xl shadow-indigo-100 uppercase">
                            <i class="fas fa-save mr-2"></i> Update & Jalankan AI
                        </button>
                        <a href="{{ route('admin.infrastruktur') }}" class="flex-1 bg-gray-100 text-gray-500 py-4 rounded-2xl font-black text-[11px] tracking-widest hover:bg-gray-200 transition text-center flex items-center justify-center uppercase">
                            Batal
                        </a>
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

        const latInput = document.getElementById('lat-input');
        const lngInput = document.getElementById('lng-input');
        const lat = parseFloat(latInput.value) || -3.316694;
        const lng = parseFloat(lngInput.value) || 114.590111;
        
        const map = L.map('edit-map').setView([lat, lng], 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
        const marker = L.marker([lat, lng], { draggable: true }).addTo(map);

        marker.on('dragend', function () {
            const position = marker.getLatLng();
            latInput.value = position.lat.toFixed(8);
            lngInput.value = position.lng.toFixed(8);
        });

        [latInput, lngInput].forEach(input => {
            input.addEventListener('input', () => {
                const newLat = parseFloat(latInput.value);
                const newLng = parseFloat(lngInput.value);
                if (!isNaN(newLat) && !isNaN(newLng)) {
                    marker.setLatLng([newLat, newLng]);
                    map.panTo([newLat, newLng]);
                }
            });
        });
    </script>
</body>
</html>