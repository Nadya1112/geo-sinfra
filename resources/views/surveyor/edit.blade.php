<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Lapangan | GEO-SINFRA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style> 
        body { font-family: 'Plus Jakarta Sans', sans-serif; } 
        /* Kustomisasi tombol zoom peta agar melengkung dan lebih kecil */
        .leaflet-bar { border: none !important; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1) !important; border-radius: 8px !important; overflow: hidden; }
        .leaflet-bar a { width: 26px !important; height: 26px !important; line-height: 26px !important; font-size: 14px !important; }
        .leaflet-bar a:first-child { border-top-left-radius: 8px !important; border-top-right-radius: 8px !important; }
        .leaflet-bar a:last-child { border-bottom-left-radius: 8px !important; border-bottom-right-radius: 8px !important; border-bottom: none !important; }
    </style>
</head>
<body class="bg-gray-50 flex h-screen overflow-hidden text-gray-800 text-left">

    @include('surveyor.partials.sidebar')

    <main class="flex-1 flex flex-col h-screen overflow-y-auto">
        <header class="bg-white border-b border-gray-100 px-8 py-5 flex justify-between items-center z-10">
            <div class="flex items-center gap-4">
                <a href="{{ route('surveyor.history') }}" class="w-10 h-10 flex items-center justify-center bg-gray-50 text-gray-400 rounded-xl hover:bg-emerald-50 hover:text-emerald-600 transition-all border border-gray-100">
                    <i class="fas fa-arrow-left text-sm"></i>
                </a>
                <div>
                    <p class="text-[10px] font-extrabold text-emerald-600 uppercase tracking-[0.2em] mb-1">Edit Data</p>
                    <h2 class="text-xl font-black text-[#1e1b4b]">Perbarui Laporan Lapangan</h2>
                </div>
            </div>
            
            <div class="flex items-center gap-6">
                <div class="text-right hidden sm:block">
                    <p class="text-[11px] font-black text-[#1e1b4b]" id="mini-clock">00:00 WITA</p>
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
                <div class="h-8 w-[1px] bg-gray-100"></div>
                <a href="{{ route('surveyor.profile') }}" class="flex items-center gap-3">
                    <div class="text-right">
                        <p class="text-[11px] font-black text-[#1e1b4b] leading-none uppercase">{{ auth()->user()->name }}</p>
                        <p class="text-[9px] font-bold text-emerald-500 uppercase mt-1">ONLINE</p>
                    </div>
                    <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 border border-emerald-100 overflow-hidden">
                        @if(auth()->user()->profile_photo)
                            <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-user-circle text-xl"></i>
                        @endif
                    </div>
                </a>
            </div>
        </header>

        <div class="p-8">
            <div class="max-w-6xl mx-auto">
                <form id="survey-form" action="{{ route('surveyor.infrastruktur.update', $infrastruktur->id_infrastruktur) }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-2 gap-8" onsubmit="disableSubmitButton()">
                    @csrf
                    @method('PUT')
                    
                    <!-- Sisi Kiri: Form Data Utama -->
                    <div class="space-y-6">
                        <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm">
                            <div class="flex items-center justify-between mb-6 border-b border-gray-50 pb-4">
                                <h4 class="font-black text-[#1e1b4b] italic">Status Terkini</h4>
                                <div class="flex flex-col items-end gap-2">
                                    <span class="px-4 py-1.5 rounded-xl text-[10px] font-black tracking-widest border {{ $infrastruktur->kondisi == 'Baik' ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : ($infrastruktur->kondisi == 'Rusak Ringan' ? 'bg-yellow-50 text-yellow-600 border-yellow-200' : ($infrastruktur->kondisi == 'Rusak Berat' ? 'bg-red-50 text-red-600 border-red-200' : 'bg-gray-50 text-gray-500 border-gray-200')) }}">
                                        {{ strtoupper($infrastruktur->kondisi) }}
                                    </span>
                                    @if($infrastruktur->cnn || $infrastruktur->analisis)
                                    <div class="flex gap-3">
                                        @if($infrastruktur->cnn)
                                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter">CNN: <span class="text-emerald-500">{{ number_format($infrastruktur->cnn->skor_cnn * 100, 1) }}%</span></p>
                                        @endif
                                        @if($infrastruktur->analisis)
                                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter">D-Tree: <span class="text-blue-500">{{ $infrastruktur->analisis->label_prioritas }}</span></p>
                                        @endif
                                    </div>
                                    @endif
                                </div>
                            </div>
                            
                            <h4 class="font-black text-[#1e1b4b] mb-6 border-b border-gray-50 pb-4 italic">Detail Infrastruktur</h4>
                            
                            <div class="space-y-5">
                                <div>
                                    <label class="block text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest mb-2">Nama Infrastruktur / Objek <span class="text-red-500">*</span></label>
                                    <input type="text" name="nama_infrastruktur" value="{{ old('nama_infrastruktur', $infrastruktur->nama_infrastruktur) }}" placeholder="Masukkan nama objek survey" class="w-full px-5 py-3 bg-gray-50 border border-gray-300 rounded-2xl text-sm font-semibold focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500 outline-none transition-all" required>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest mb-2">Jenis <span class="text-red-500">*</span></label>
                                    <select name="jenis_infrastruktur" class="w-full px-5 py-3 bg-gray-50 border border-gray-300 rounded-2xl text-sm font-semibold focus:border-emerald-500 outline-none appearance-none cursor-pointer" required>
                                        <option value="Jalan" {{ $infrastruktur->jenis_infrastruktur == 'Jalan' ? 'selected' : '' }}>Jalan</option>
                                        <option value="Jembatan" {{ $infrastruktur->jenis_infrastruktur == 'Jembatan' ? 'selected' : '' }}>Jembatan</option>
                                        <option value="Drainase" {{ $infrastruktur->jenis_infrastruktur == 'Drainase' ? 'selected' : '' }}>Drainase</option>
                                    </select>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest mb-2">Kecamatan <span class="text-red-500">*</span></label>
                                        <select name="id_kecamatan" id="id_kecamatan" class="w-full px-5 py-3 bg-gray-50 border border-gray-300 rounded-2xl text-sm font-semibold focus:border-emerald-500 outline-none appearance-none cursor-pointer" required onchange="filterKelurahan()">
                                            @foreach($semuaKecamatan as $kec)
                                                <option value="{{ $kec->id_kecamatan }}" {{ $infrastruktur->id_kecamatan == $kec->id_kecamatan ? 'selected' : '' }}>{{ $kec->nama_kecamatan }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest mb-2">Kelurahan <span class="text-red-500">*</span></label>
                                        <select name="id_kelurahan" id="id_kelurahan" class="w-full px-5 py-3 bg-gray-50 border border-gray-300 rounded-2xl text-sm font-semibold focus:border-emerald-500 outline-none appearance-none cursor-pointer" required>
                                            <option value="">Pilih Kelurahan...</option>
                                            @foreach($semuaKelurahan as $kel)
                                                <option value="{{ $kel->id_kelurahan }}" data-kecamatan="{{ $kel->id_kecamatan }}" {{ $infrastruktur->id_kelurahan == $kel->id_kelurahan ? 'selected' : '' }}>{{ $kel->nama_kelurahan }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest mb-2">Alamat Detail / Catatan Lokasi</label>
                                    <textarea name="alamat" rows="2" placeholder="Sebutkan patokan atau alamat detail..." class="w-full px-5 py-3 bg-gray-50 border border-gray-300 rounded-2xl text-sm font-semibold focus:border-emerald-500 outline-none transition-all resize-none">{{ old('alamat', $infrastruktur->alamat) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm">
                            <h4 class="font-black text-[#1e1b4b] mb-6 border-b border-gray-50 pb-4 italic">Unggah Dokumentasi (Opsional)</h4>
                            <div class="relative group cursor-pointer">
                                <input type="file" name="foto" id="foto" class="hidden" accept="image/*" capture="environment" onchange="previewImage(this)">
                                <label for="foto" class="block w-full h-52 rounded-[2rem] border-2 border-dashed border-gray-300 flex flex-col items-center justify-center bg-gray-50 group-hover:bg-emerald-50 group-hover:border-emerald-200 transition-all cursor-pointer overflow-hidden relative">
                                    <div id="upload-placeholder" class="text-center {{ $infrastruktur->foto_terbaru ? 'hidden' : '' }}">
                                        <div class="w-16 h-16 bg-white rounded-2xl shadow-sm flex items-center justify-center mx-auto mb-3">
                                            <i class="fas fa-camera text-2xl text-emerald-500"></i>
                                        </div>
                                        <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Klik Untuk Ubah Foto (Opsional)</p>
                                    </div>
                                    <img id="preview" src="{{ $infrastruktur->foto_terbaru ? asset('storage/' . $infrastruktur->foto_terbaru) : '' }}" class="absolute inset-0 w-full h-full object-cover {{ $infrastruktur->foto_terbaru ? '' : 'hidden' }}">
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Sisi Kanan: Geolocation -->
                    <div class="space-y-6">
                        <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm">
                            <div class="flex justify-between items-center mb-6 border-b border-gray-50 pb-4">
                                <h4 class="font-black text-[#1e1b4b] italic">Titik Koordinat</h4>
                                <button type="button" onclick="getLocation()" class="px-4 py-2 bg-emerald-100 text-emerald-700 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-emerald-200 transition-all shadow-sm shadow-emerald-900/5">
                                    <i class="fas fa-crosshairs mr-2"></i> Sinkron GPS
                                </button>
                            </div>

                            <div class="grid grid-cols-2 gap-4 mb-6">
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Latitude</label>
                                    <input type="text" name="latitude" id="lat-input" value="{{ old('latitude', $infrastruktur->latitude) }}" placeholder="-3.31..." class="w-full px-5 py-3 bg-gray-50 border border-gray-300 rounded-2xl text-sm font-semibold outline-none focus:border-emerald-500" required>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Longitude</label>
                                    <input type="text" name="longitude" id="lng-input" value="{{ old('longitude', $infrastruktur->longitude) }}" placeholder="114.59..." class="w-full px-5 py-3 bg-gray-50 border border-gray-300 rounded-2xl text-sm font-semibold outline-none focus:border-emerald-500" required>
                                </div>
                            </div>

                            <div id="map" class="h-[200px] w-full rounded-xl border border-gray-200 shadow-inner z-0 mb-8"></div>
                            
                            <button type="submit" id="btn-submit" class="w-full bg-[#1e1b4b] text-white py-4 rounded-2xl font-black shadow-xl shadow-indigo-100 hover:bg-emerald-600 transition-all tracking-widest text-xs uppercase flex items-center justify-center gap-3">
                                <span id="btn-text"><i class="fas fa-save"></i> Simpan Perubahan</span>
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

        const initialLat = {{ $infrastruktur->latitude ?? -3.316694 }};
        const initialLng = {{ $infrastruktur->longitude ?? 114.590111 }};

        const map = L.map('map').setView([initialLat, initialLng], 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

        let marker = L.marker([initialLat, initialLng]).addTo(map);

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

        // --- Fitur Tambahan ---

        function disableSubmitButton() {
            const btn = document.getElementById('btn-submit');
            const text = document.getElementById('btn-text');
            btn.disabled = true;
            btn.classList.add('opacity-50', 'cursor-not-allowed');
            text.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...';
        }

        function filterKelurahan() {
            const idKecamatan = document.getElementById('id_kecamatan').value;
            const kelurahanSelect = document.getElementById('id_kelurahan');
            const options = kelurahanSelect.querySelectorAll('option');
            
            let firstVisible = null;
            let currentSelected = kelurahanSelect.value;
            let currentStillVisible = false;

            options.forEach(opt => {
                if (opt.value === "") return;
                if (opt.getAttribute('data-kecamatan') === idKecamatan) {
                    opt.hidden = false;
                    opt.disabled = false;
                    if (!firstVisible) firstVisible = opt.value;
                    if (opt.value === currentSelected) currentStillVisible = true;
                } else {
                    opt.hidden = true;
                    opt.disabled = true;
                }
            });
            
            if (!currentStillVisible) {
                kelurahanSelect.value = firstVisible ? firstVisible : "";
            }
        }

        document.getElementById('lat-input').addEventListener('input', updateMapFromInput);
        document.getElementById('lng-input').addEventListener('input', updateMapFromInput);

        function updateMapFromInput() {
            const latStr = document.getElementById('lat-input').value.replace(',', '.');
            const lngStr = document.getElementById('lng-input').value.replace(',', '.');
            const lat = parseFloat(latStr);
            const lng = parseFloat(lngStr);
            
            if (!isNaN(lat) && !isNaN(lng)) {
                if (marker) map.removeLayer(marker);
                marker = L.marker([lat, lng]).addTo(map);
                map.setView([lat, lng]);
            }
        }

        window.addEventListener('DOMContentLoaded', () => {
            filterKelurahan(); // Panggil saat halaman pertama kali dimuat
        });
    </script>
</body>
</html>
