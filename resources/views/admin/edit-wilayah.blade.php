<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Wilayah | Admin SINFRA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-gray-50 flex h-screen overflow-hidden text-gray-800 text-left">

    @include('admin.partials.sidebar')

    <main class="flex-1 flex flex-col h-screen overflow-y-auto">
        <header class="bg-white border-b border-gray-100 px-8 py-5 flex justify-between items-center z-10">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.wilayah') }}" class="w-10 h-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center text-gray-400 hover:text-blue-600 transition-all">
                    <i class="fas fa-arrow-left text-xs"></i>
                </a>
                <div>
                    <p class="text-[10px] font-extrabold text-blue-600 uppercase tracking-[0.2em] mb-1">Administrator Portal</p>
                    <h2 class="text-xl font-black text-[#1e1b4b]">Edit Data Wilayah</h2>
                </div>
            </div>
            <div class="text-right">
                <p class="text-[11px] font-black text-[#1e1b4b]">{{ now()->translatedFormat('H:i') }} WITA</p>
            </div>
        </header>

        <div class="p-8 uppercase">
            <div class="max-w-4xl bg-white rounded-[2.5rem] p-10 border border-gray-100 shadow-sm mx-auto">
                <div class="mb-10 border-b border-gray-50 pb-5">
                    <h3 class="text-lg font-black text-[#1e1b4b] tracking-tight">Informasi Kelurahan</h3>
                    <p class="text-xs text-gray-400 font-medium tracking-tighter">Perbarui Koordinat Dan Nama Wilayah</p>
                </div>

                <form action="{{ route('admin.wilayah.update', $wilayah->id_kelurahan) }}" method="POST" class="space-y-8">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="block text-[10px] font-black text-[#1e1b4b] tracking-widest mb-2">Nama Kelurahan *</label>
                            <input type="text" name="nama_kelurahan" value="{{ $wilayah->nama_kelurahan }}" class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-semibold outline-none focus:border-blue-500" required>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-[#1e1b4b] tracking-widest mb-2">Kecamatan</label>
                            <select name="id_kecamatan" class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-semibold outline-none focus:border-blue-500">
                                @foreach($semuaKecamatan as $kec)
                                    <option value="{{ $kec->id_kecamatan }}" {{ $wilayah->id_kecamatan == $kec->id_kecamatan ? 'selected' : '' }}>
                                        {{ $kec->nama_kecamatan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="block text-[10px] font-black text-[#1e1b4b] tracking-widest mb-2">Latitude</label>
                            <input type="text" name="latitude" value="{{ $wilayah->latitude }}" class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-semibold outline-none focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-[#1e1b4b] tracking-widest mb-2">Longitude</label>
                            <input type="text" name="longitude" value="{{ $wilayah->longitude }}" class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-semibold outline-none focus:border-blue-500">
                        </div>
                    </div>

                    <div class="flex gap-4 pt-6">
                        <button type="submit" class="flex-1 bg-blue-600 text-white py-4 rounded-2xl font-bold shadow-lg shadow-blue-200 hover:bg-blue-700 transition tracking-widest text-sm">UPDATE WILAYAH</button>
                        <a href="{{ route('admin.wilayah') }}" class="flex-1 bg-gray-100 text-gray-500 py-4 rounded-2xl font-bold hover:bg-gray-200 transition text-center leading-[1.2rem] tracking-widest text-sm">BATAL</a>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>