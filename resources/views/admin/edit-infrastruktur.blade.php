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

        <div class="p-8">
            <div class="max-w-4xl bg-white rounded-[2.5rem] p-10 border border-gray-100 shadow-sm mx-auto">
                <div class="mb-10 border-b border-gray-50 pb-5">
                    <h3 class="text-lg font-black text-[#1e1b4b] tracking-tight">Identitas Objek</h3>
                    <p class="text-xs text-gray-400 font-medium tracking-tighter">Perbarui Informasi Aset SINFRA</p>
                </div>

                <form action="{{ route('admin.infrastruktur.update', $inf->id_infrastruktur) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    @method('PUT')
                    
                    <!-- Bagian 1: Identitas & Lokasi -->
                    <div class="space-y-6">
                        <div class="border-l-4 border-blue-500 pl-4 mb-4">
                            <h4 class="text-sm font-black text-[#1e1b4b] uppercase tracking-wider">1. Identitas & Lokasi</h4>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label class="block text-[10px] font-black text-[#1e1b4b] tracking-widest mb-2">Nama Infrastruktur <span class="text-red-500">*</span></label>
                                <input type="text" name="nama_infrastruktur" value="{{ $inf->nama_objek ?? $inf->nama_infrastruktur }}" class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-semibold outline-none focus:border-blue-500 transition-all" required>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-[#1e1b4b] uppercase mb-2">Jenis Infrastruktur <span class="text-red-500">*</span></label>
                                <select name="jenis_infrastruktur" class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-semibold outline-none focus:border-blue-500">
                                    @php $jenisAktif = strtolower($inf->jenis ?? $inf->jenis_infrastruktur); @endphp
                                    <option value="Jalan" {{ $jenisAktif == 'jalan' ? 'selected' : '' }}>Jalan</option>
                                    <option value="Sanitasi" {{ $jenisAktif == 'sanitasi' ? 'selected' : '' }}>Sanitasi</option>
                                    <option value="Titian" {{ $jenisAktif == 'titian' ? 'selected' : '' }}>Titian</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label class="block text-[10px] font-black text-[#1e1b4b] uppercase mb-2">Kecamatan <span class="text-red-500">*</span></label>
                                <select name="id_kecamatan" class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-semibold outline-none focus:border-blue-500">
                                    @foreach($semuaKecamatan as $kec)
                                        <option value="{{ $kec->id_kecamatan }}" {{ $inf->id_kecamatan == $kec->id_kecamatan ? 'selected' : '' }}>
                                            {{ $kec->nama_kecamatan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-[#1e1b4b] uppercase mb-2">Kelurahan <span class="text-red-500">*</span></label>
                                <select name="id_kelurahan" class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-semibold outline-none focus:border-blue-500">
                                    @foreach($semuaKelurahan as $kel)
                                        <option value="{{ $kel->id_kelurahan }}" {{ $inf->id_kelurahan == $kel->id_kelurahan ? 'selected' : '' }}>
                                            {{ $kel->nama_kelurahan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Bagian 2: Sistem Informasi Geografis (SIG) -->
                    <div class="space-y-6 pt-6 border-t border-gray-100">
                        <div class="border-l-4 border-indigo-500 pl-4 mb-4">
                            <h4 class="text-sm font-black text-[#1e1b4b] uppercase tracking-wider">2. SIG (Sistem Informasi Geografis)</h4>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label class="block text-[10px] font-black text-[#1e1b4b] uppercase mb-2">Latitude <span class="text-red-500">*</span></label>
                                <input type="text" name="latitude" id="lat-input" value="{{ $inf->latitude }}" class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-semibold outline-none focus:border-blue-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-[#1e1b4b] uppercase mb-2">Longitude <span class="text-red-500">*</span></label>
                                <input type="text" name="longitude" id="lng-input" value="{{ $inf->longitude }}" class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-semibold outline-none focus:border-blue-500 transition-all">
                            </div>
                        </div>

                        <!-- Interactive Mini Map -->
                        <div class="mt-6">
                            <label class="block text-[10px] font-black text-[#1e1b4b] uppercase mb-3">Geser Pin Untuk Ubah Lokasi (Interactive Map)</label>
                            <div id="edit-map" class="w-full h-48 rounded-3xl border border-gray-100 shadow-inner z-0"></div>
                        </div>
                    </div>

                    <!-- Bagian 3: Koreksi Analisis AI (CNN & DT) -->
                    <div class="space-y-6 pt-6 border-t border-gray-100">
                        <div class="border-l-4 border-purple-500 pl-4 mb-4">
                            <h4 class="text-sm font-black text-[#1e1b4b] uppercase tracking-wider">3. Koreksi Analisis AI (CNN & DT)</h4>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label class="block text-[10px] font-black text-[#1e1b4b] uppercase mb-2">Akurasi CNN (%)</label>
                                <div class="w-full px-5 py-3 bg-purple-50 border border-purple-100 rounded-2xl flex items-center justify-between cursor-not-allowed">
                                    <span class="text-sm font-black text-purple-700">92.5%</span>
                                    <i class="fas fa-brain text-purple-400"></i>
                                </div>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-[#1e1b4b] uppercase mb-2">Hasil Akhir Decision Tree</label>
                                <input type="hidden" name="kondisi" value="{{ $inf->kondisi }}">
                                <div class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl flex items-center gap-3 cursor-not-allowed">
                                    <span class="px-2.5 py-1 rounded-lg text-[10px] font-black tracking-widest border {{ $inf->kondisi == 'Baik' ? 'bg-green-50 text-green-600 border-green-200' : ($inf->kondisi == 'Rusak Ringan' ? 'bg-yellow-50 text-yellow-600 border-yellow-200' : 'bg-red-50 text-red-600 border-red-200') }}">
                                        {{ strtoupper($inf->kondisi) }}
                                    </span>
                                    <span class="text-[10px] font-bold text-gray-400 italic">Otomatis Terverifikasi</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bagian 4: Dokumentasi Visual -->
                    <div class="space-y-6 pt-6 border-t border-gray-100">
                        <div class="border-l-4 border-emerald-500 pl-4 mb-4">
                            <h4 class="text-sm font-black text-[#1e1b4b] uppercase tracking-wider">4. Dokumentasi Visual</h4>
                        </div>
                        <div class="grid grid-cols-1 gap-8">
                            <div>
                                <label class="block text-[10px] font-black text-[#1e1b4b] uppercase mb-2">Upload Foto Baru</label>
                                <input type="file" name="foto" class="w-full px-5 py-2.5 bg-gray-50 border border-gray-100 rounded-2xl text-xs font-semibold file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-[10px] file:font-bold file:bg-emerald-50 file:text-emerald-600 hover:file:bg-emerald-100 transition-all">
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex gap-4 pt-8 mt-4 border-t border-gray-100">
                        <button type="submit" class="flex-1 bg-blue-600 text-white py-4 rounded-2xl font-bold shadow-lg shadow-blue-100 hover:bg-blue-700 transition tracking-widest text-sm uppercase">Simpan Data</button>
                        <a href="{{ route('admin.infrastruktur') }}" class="flex-1 bg-gray-100 text-gray-500 py-4 rounded-2xl font-bold hover:bg-gray-200 transition text-center leading-[1.2rem] tracking-widest text-sm flex items-center justify-center uppercase">Batal</a>
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

        // Initialize Interactive Map
        const latInput = document.getElementById('lat-input');
        const lngInput = document.getElementById('lng-input');
        
        const lat = parseFloat(latInput.value);
        const lng = parseFloat(lngInput.value);
        
        const map = L.map('edit-map').setView([lat, lng], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        const marker = L.marker([lat, lng], {
            draggable: true
        }).addTo(map);

        // Update inputs when marker is dragged
        marker.on('dragend', function (event) {
            const position = marker.getLatLng();
            latInput.value = position.lat.toFixed(8);
            lngInput.value = position.lng.toFixed(8);
        });

        // Update marker when inputs change
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