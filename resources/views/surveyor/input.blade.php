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
            <div class="flex items-center gap-4">
                <a href="{{ route('surveyor.dashboard') }}" class="w-10 h-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center text-gray-400 hover:text-emerald-600 transition-all">
                    <i class="fas fa-arrow-left text-xs"></i>
                </a>
                <div>
                    <p class="text-[10px] font-extrabold text-emerald-600 uppercase tracking-[0.2em] mb-1">Survey Baru</p>
                    <h2 class="text-xl font-black text-[#1e1b4b]">Input Data Lapangan</h2>
                </div>
            </div>
            
            <div class="flex items-center gap-6">
                <div class="text-right hidden sm:block">
                    <p class="text-[11px] font-black text-[#1e1b4b]" id="mini-clock">00:00 WITA</p>
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
            </div>
        </header>

        <div class="p-8">
            <div class="max-w-5xl mx-auto">
                <form action="{{ route('surveyor.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    @csrf
                    
                    <!-- Sisi Kiri: Form Data -->
                    <div class="space-y-6">
                        <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm">
                            <h4 class="font-black text-[#1e1b4b] mb-6 border-b border-gray-50 pb-4 italic">Detail Infrastruktur</h4>
                            
                            <div class="space-y-5">
                                <div>
                                    <label class="block text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest mb-2">Nama Infrastruktur <span class="text-red-500">*</span></label>
                                    <input type="text" name="nama_infrastruktur" placeholder="Contoh: Jembatan Dewi" class="w-full px-5 py-3 bg-gray-50 border border-gray-300 rounded-2xl text-sm font-semibold focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none" required>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest mb-2">Jenis <span class="text-red-500">*</span></label>
                                        <select name="jenis_infrastruktur" class="w-full px-5 py-3 bg-gray-50 border border-gray-300 rounded-2xl text-sm font-semibold focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none appearance-none" required>
                                            <option value="Jalan">Jalan</option>
                                            <option value="Jembatan">Jembatan</option>
                                            <option value="Drainase">Drainase</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest mb-2">Wilayah (Kec) <span class="text-red-500">*</span></label>
                                        <select name="id_kecamatan" class="w-full px-5 py-3 bg-gray-50 border border-gray-300 rounded-2xl text-sm font-semibold focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none appearance-none" required>
                                            @foreach($semuaKecamatan as $kec)
                                                <option value="{{ $kec->id_kecamatan }}" {{ auth()->user()->id_kecamatan == $kec->id_kecamatan ? 'selected' : '' }}>
                                                    {{ $kec->nama_kecamatan }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm">
                            <h4 class="font-black text-[#1e1b4b] mb-6 border-b border-gray-50 pb-4 italic">Dokumentasi Foto</h4>
                            
                            <div class="relative group cursor-pointer">
                                <input type="file" name="foto" id="foto" class="hidden" accept="image/*" required onchange="previewImage(this)">
                                <label for="foto" class="block w-full h-48 rounded-3xl border-2 border-dashed border-gray-100 flex flex-col items-center justify-center bg-gray-50 group-hover:bg-emerald-50 group-hover:border-emerald-200 transition-all cursor-pointer overflow-hidden relative">
                                    <div id="upload-placeholder" class="text-center">
                                        <i class="fas fa-camera text-3xl text-gray-300 mb-2 group-hover:text-emerald-400 transition-colors"></i>
                                        <p class="text-[10px] font-bold text-gray-400 group-hover:text-emerald-600 uppercase tracking-widest">Klik Untuk Upload Foto</p>
                                    </div>
                                    <img id="preview" class="absolute inset-0 w-full h-full object-cover hidden">
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Sisi Kanan: Lokasi & Map -->
                    <div class="space-y-6">
                        <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm h-full flex flex-col">
                            <div class="flex justify-between items-center mb-6 border-b border-gray-50 pb-4">
                                <h4 class="font-black text-[#1e1b4b] italic">Lokasi Geografis</h4>
                                <button type="button" onclick="getLocation()" class="px-3 py-1.5 bg-emerald-100 text-emerald-700 rounded-lg text-[9px] font-black uppercase tracking-tighter hover:bg-emerald-200 transition-all">
                                    <i class="fas fa-crosshairs mr-1"></i> Dapatkan GPS
                                </button>
                            </div>

                            <div class="grid grid-cols-2 gap-4 mb-6">
                                <div>
                                    <label class="block text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest mb-2">Latitude</label>
                                    <input type="text" name="latitude" id="lat-input" placeholder="-3.31..." class="w-full px-5 py-3 bg-gray-50 border border-gray-300 rounded-2xl text-sm font-semibold outline-none focus:border-emerald-500" required>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest mb-2">Longitude</label>
                                    <input type="text" name="longitude" id="lng-input" placeholder="114.59..." class="w-full px-5 py-3 bg-gray-50 border border-gray-300 rounded-2xl text-sm font-semibold outline-none focus:border-emerald-500" required>
                                </div>
                            </div>

                            <div id="map" class="flex-1 min-h-[300px] rounded-3xl border border-gray-100 shadow-inner z-0"></div>
                            
                            <button type="submit" class="w-full mt-8 bg-[#1e1b4b] text-white py-4 rounded-2xl font-black shadow-xl shadow-indigo-100 hover:bg-emerald-600 transition-all tracking-widest text-xs uppercase">
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

        // Map Initialization
        const map = L.map('map').setView([-3.316694, 114.590111], 13); // Center of Banjarmasin
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

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
            } else {
                alert('Browser tidak mendukung Geolocation.');
            }
        }

        // Sync inputs back to map
        [document.getElementById('lat-input'), document.getElementById('lng-input')].forEach(input => {
            input.addEventListener('input', () => {
                const lat = parseFloat(document.getElementById('lat-input').value);
                const lng = parseFloat(document.getElementById('lng-input').value);
                if (!isNaN(lat) && !isNaN(lng)) {
                    map.setView([lat, lng], 17);
                    updateMarker(lat, lng);
                }
            });
        });
    </script>
</body>
</html>
