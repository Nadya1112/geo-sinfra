<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta Spasial | GEO-SINFRA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        #map { height: calc(100vh - 80px); width: 100%; border-radius: 0 0 0 2rem; }
    </style>
</head>
<body class="bg-[#1e1b4b] flex h-screen overflow-hidden">

    <aside class="w-20 bg-[#1e1b4b] flex flex-col items-center py-8 border-r border-white/5">
        <a href="{{ url('/admin/dashboard') }}" class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center text-white hover:bg-blue-600 transition mb-8">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-blue-500/20">
            <i class="fas fa-map-marked-alt"></i>
        </div>
    </aside>

    <main class="flex-1 bg-white rounded-l-[3rem] overflow-hidden flex flex-col">
        <header class="px-10 py-6 flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-black text-[#1e1b4b]">Peta Spasial Wilayah</h2>
                <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">Kota Banjarmasin</p>
            </div>
            <div class="flex gap-4">
                <div class="px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl text-[10px] font-bold text-gray-500 uppercase">
                    Total: {{ $semuaWilayah->count() }} Kecamatan
                </div>
            </div>
        </header>

        <div id="map" class="shadow-inner"></div>
    </main>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Inisialisasi Peta
        var map = L.map('map').setView([-3.316694, 114.590111], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        // Menampilkan Wilayah dari Database
        @foreach($semuaWilayah as $wilayah)
            @if($wilayah->geojson_data)
                // Jika kamu menyimpan koordinat dalam format GeoJSON
                var geoData = {!! $wilayah->geojson_data !!};
                L.geoJSON(geoData, {
                    style: function(feature) {
                        return {
                            fillColor: "{{ $wilayah->warna ?? '#3b82f6' }}",
                            weight: 2,
                            opacity: 1,
                            color: 'white',
                            fillOpacity: 0.6
                        };
                    }
                }).addTo(map).bindPopup("<b class='text-blue-700'>Kecamatan {{ $wilayah->nama_wilayah }}</b>");
            @endif
        @endforeach
    </script>
</body>
</html>