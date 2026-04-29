<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Data Lapangan | GEO-SINFRA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-gray-50 flex h-screen overflow-hidden text-gray-800 text-left">

    @include('surveyor.partials.sidebar')

    <main class="flex-1 flex flex-col h-screen overflow-y-auto">
        <header class="bg-white border-b border-gray-100 px-8 py-5 flex justify-between items-center z-10">
            <div>
                <p class="text-[10px] font-extrabold text-emerald-600 uppercase tracking-[0.2em] mb-1">Survey Baru</p>
                <h2 class="text-xl font-black text-[#1e1b4b]">Input Data Lapangan</h2>
            </div>
            
            <div class="flex items-center gap-6">
                <div class="text-right hidden sm:block">
                    <p class="text-[11px] font-black text-[#1e1b4b]" id="mini-clock">00:00 WITA</p>
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
            </div>
        </header>

        <div class="p-8">
            <div class="max-w-6xl mx-auto">
                <form action="{{ route('surveyor.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    @csrf
                    
                    <!-- Sisi Kiri: Form Data Utama -->
                    <div class="space-y-6">
                        <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm">
                            <h4 class="font-black text-[#1e1b4b] mb-6 border-b border-gray-50 pb-4 italic">Detail Infrastruktur</h4>
                            
                            <div class="space-y-5">
                                <div>
                                    <label class="block text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest mb-2">Nama Infrastruktur / Objek <span class="text-red-500">*</span></label>
                                    <input type="text" name="nama_infrastruktur" placeholder="Masukkan nama objek survey" class="w-full px-5 py-3 bg-gray-50 border border-gray-300 rounded-2xl text-sm font-semibold focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500 outline-none transition-all" required>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest mb-2">Jenis <span class="text-red-500">*</span></label>
                                        <select name="jenis_infrastruktur" class="w-full px-5 py-3 bg-gray-50 border border-gray-300 rounded-2xl text-sm font-semibold focus:border-emerald-500 outline-none appearance-none cursor-pointer" required>
                                            <option value="Jalan">Jalan</option>
                                            <option value="Jembatan">Jembatan</option>
                                            <option value="Drainase">Drainase</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest mb-2">Kondisi Lapangan <span class="text-red-500">*</span></label>
                                        <select name="kondisi" class="w-full px-5 py-3 bg-gray-50 border border-gray-300 rounded-2xl text-sm font-semibold focus:border-emerald-500 outline-none appearance-none cursor-pointer" required>
                                            <option value="Baik">BAIK</option>
                                            <option value="Rusak Ringan">RUSAK RINGAN</option>
                                            <option value="Rusak Berat">RUSAK BERAT</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest mb-2">Kecamatan <span class="text-red-500">*</span></label>
                                        <select name="id_kecamatan" class="w-full px-5 py-3 bg-gray-50 border border-gray-300 rounded-2xl text-sm font-semibold focus:border-emerald-500 outline-none appearance-none cursor-pointer" required>
                                            @foreach($semuaKecamatan as $kec)
                                                <option value="{{ $kec->id_kecamatan }}" {{ auth()->user()->id_kecamatan == $kec->id_kecamatan ? 'selected' : '' }}>{{ $kec->nama_kecamatan }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest mb-2">Kelurahan <span class="text-red-500">*</span></label>
                                        <select name="id_kelurahan" class="w-full px-5 py-3 bg-gray-50 border border-gray-300 rounded-2xl text-sm font-semibold focus:border-emerald-500 outline-none appearance-none cursor-pointer" required>
                                            @foreach($semuaKelurahan as $kel)
                                                <option value="{{ $kel->id_kelurahan }}">{{ $kel->nama_kelurahan }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest mb-2">Alamat Detail / Catatan Lokasi</label>
                                    <textarea name="alamat" rows="2" placeholder="Sebutkan patokan atau alamat detail..." class="w-full px-5 py-3 bg-gray-50 border border-gray-300 rounded-2xl text-sm font-semibold focus:border-emerald-500 outline-none transition-all resize-none"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm">
                            <h4 class="font-black text-[#1e1b4b] mb-6 border-b border-gray-50 pb-4 italic">Unggah Dokumentasi</h4>
                            <div class="relative group cursor-pointer">
                                <input type="file" name="foto" id="foto" class="hidden" accept="image/*" required onchange="previewImage(this)">
                                <label for="foto" class="block w-full h-52 rounded-[2rem] border-2 border-dashed border-gray-300 flex flex-col items-center justify-center bg-gray-50 group-hover:bg-emerald-50 group-hover:border-emerald-200 transition-all cursor-pointer overflow-hidden relative">
                                    <div id="upload-placeholder" class="text-center">
                                        <div class="w-16 h-16 bg-white rounded-2xl shadow-sm flex items-center justify-center mx-auto mb-3">
                                            <i class="fas fa-camera text-2xl text-emerald-500"></i>
                                        </div>
                                        <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Klik Untuk Ambil Foto Lapangan</p>
                                    </div>
                                    <img id="preview" class="absolute inset-0 w-full h-full object-cover hidden">
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Sisi Kanan: Geolocation -->
                    <div class="space-y-6 flex flex-col h-full">
                        <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm flex-1 flex flex-col">
                            <div class="flex justify-between items-center mb-6 border-b border-gray-50 pb-4">
                                <h4 class="font-black text-[#1e1b4b] italic">Titik Koordinat</h4>
                                <button type="button" onclick="getLocation()" class="px-4 py-2 bg-emerald-100 text-emerald-700 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-emerald-200 transition-all shadow-sm shadow-emerald-900/5">
                                    <i class="fas fa-crosshairs mr-2"></i> Sinkron GPS
                                </button>
                            </div>

                            <div class="grid grid-cols-2 gap-4 mb-6">
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Latitude</label>
                                    <input type="text" name="latitude" id="lat-input" placeholder="-3.31..." class="w-full px-5 py-3 bg-gray-50 border border-gray-300 rounded-2xl text-sm font-semibold outline-none focus:border-emerald-500" required>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Longitude</label>
                                    <input type="text" name="longitude" id="lng-input" placeholder="114.59..." class="w-full px-5 py-3 bg-gray-50 border border-gray-300 rounded-2xl text-sm font-semibold outline-none focus:border-emerald-500" required>
                                </div>
                            </div>

                            <div id="map" class="flex-1 min-h-[300px] rounded-[2rem] border border-gray-200 shadow-inner z-0"></div>
                            
                            <button type="submit" class="w-full mt-8 bg-[#1e1b4b] text-white py-4 rounded-2xl font-black shadow-xl shadow-indigo-100 hover:bg-emerald-600 transition-all tracking-widest text-xs uppercase flex items-center justify-center gap-3">
                                <i class="fas fa-paper-plane"></i>
                                Kirim Laporan Survey
                            </button>
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

        function previewImage(input) {
            const preview = document.getElementById('preview');
            const placeholder = document.getElementById('upload-placeholder');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        const map = L.map('map').setView([-3.316694, 114.590111], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

        let marker;

        map.on('click', function(e) {
            updateMarker(e.latlng.lat, e.latlng.lng);
        });

        function updateMarker(lat, lng) {
            if (marker) map.removeLayer(marker);
            marker = L.marker([lat, lng]).addTo(map);
            document.getElementById('lat-input').value = lat.toFixed(8);
            document.getElementById('lng-input').value = lng.toFixed(8);
        }

        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    map.setView([lat, lng], 17);
                    updateMarker(lat, lng);
                }, function() {
                    alert('Gagal mendapatkan lokasi. Pastikan GPS aktif.');
                });
            }
        }
    </script>
</body>
</html>
