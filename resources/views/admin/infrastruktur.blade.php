<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Infrastruktur | Admin SINFRA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #e5e1d8; }
        .neo-brutalism { border: 3px solid #000000; box-shadow: 4px 4px 0px 0px #000000; }
        .neo-brutalism-sm { border: 2px solid #000000; }
        .neo-brutalism-btn:hover { transform: translate(-2px, -2px); box-shadow: 6px 6px 0px 0px #000000; }
        .neo-brutalism-btn:active { transform: translate(1px, 1px); box-shadow: 2px 2px 0px 0px #000000; }
    </style>
</head>
<body class="flex h-screen overflow-hidden p-6">

    @include('admin.partials.sidebar')

    <main class="flex-1 flex flex-col h-screen overflow-y-auto p-4">
        <div class="max-w-4xl mx-auto w-full bg-white rounded-[2rem] neo-brutalism p-10 my-10">
            <div class="mb-10 border-b-2 border-black pb-5">
                <h3 class="text-2xl font-black uppercase tracking-tight">Identitas Objek</h3>
                <p class="text-xs font-bold text-gray-500 uppercase">Tambah Data Infrastruktur Baru</p>
            </div>

            <form action="{{ route('admin.infrastruktur.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf
                
                <div>
                    <label class="block text-[10px] font-black uppercase mb-2">Nama Infrastruktur *</label>
                    <input type="text" name="nama_infrastruktur" class="w-full px-5 py-3 rounded-xl neo-brutalism-sm font-bold focus:outline-none" placeholder="Masukkan nama objek..." required>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-[10px] font-black uppercase mb-2">Jenis Infrastruktur</label>
                        <select name="jenis_infrastruktur" class="w-full px-5 py-3 rounded-xl neo-brutalism-sm font-bold focus:outline-none bg-white">
                            <option value="Jalan">Jalan</option>
                            <option value="Jembatan">Jembatan</option>
                            <option value="Drainase">Drainase</option>
                            <option value="Titian">Titian</option> </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase mb-2">Dokumentasi Lapangan</label>
                        <input type="file" name="foto" class="w-full px-4 py-2 rounded-xl neo-brutalism-sm font-bold text-xs bg-gray-50 cursor-pointer">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-[10px] font-black uppercase mb-2">Kecamatan</label>
                        <select name="id_kecamatan" class="w-full px-5 py-3 rounded-xl neo-brutalism-sm font-bold bg-white">
                            @foreach($semuaKecamatan as $kec)
                                <option value="{{ $kec->id_kecamatan }}">{{ $kec->nama_kecamatan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase mb-2">Kelurahan</label>
                        <select name="id_kelurahan" class="w-full px-5 py-3 rounded-xl neo-brutalism-sm font-bold bg-white">
                            @foreach($semuaKelurahan as $kel)
                                <option value="{{ $kel->id_kelurahan }}">{{ $kel->nama_kelurahan }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div>
                        <label class="block text-[10px] font-black uppercase mb-2">Kondisi Awal</label>
                        <select name="kondisi" class="w-full px-5 py-3 rounded-xl neo-brutalism-sm font-bold bg-white">
                            <option value="Baik">Baik</option>
                            <option value="Rusak Ringan">Rusak Ringan</option>
                            <option value="Rusak Berat">Rusak Berat</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase mb-2">Latitude</label>
                        <input type="text" name="latitude" placeholder="-3.31..." class="w-full px-5 py-3 rounded-xl neo-brutalism-sm font-bold focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase mb-2">Longitude</label>
                        <input type="text" name="longitude" placeholder="114.59..." class="w-full px-5 py-3 rounded-xl neo-brutalism-sm font-bold focus:outline-none">
                    </div>
                </div>

                <div class="flex gap-4 pt-6">
                    <button type="submit" class="flex-1 bg-[#2ecc71] py-4 rounded-2xl neo-brutalism neo-brutalism-btn font-black uppercase text-sm tracking-widest transition-all">
                        SIMPAN DATA
                    </button>
                    <a href="{{ route('admin.infrastruktur') }}" class="flex-1 bg-white py-4 rounded-2xl neo-brutalism neo-brutalism-btn font-black uppercase text-sm text-center tracking-widest transition-all">
                        BATAL
                    </a>
                </div>
            </form>
        </div>
    </main>
</body>
</html>