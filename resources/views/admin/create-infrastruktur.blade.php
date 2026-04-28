<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Aset | Admin SINFRA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-gray-50 flex h-screen overflow-hidden text-gray-800 text-left">
    @include('admin.partials.sidebar')
    <main class="flex-1 flex flex-col h-screen overflow-hidden text-left">
        <header class="bg-white border-b border-gray-100 px-8 py-5 flex justify-between items-center z-10 text-left">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.infrastruktur') }}" class="w-10 h-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center text-gray-400 hover:text-blue-600 transition-all"><i class="fas fa-arrow-left text-xs"></i></a>
                <h2 class="text-xl font-black text-[#1e1b4b]">Tambah Aset Infrastruktur</h2>
            </div>
        </header>

        <div class="flex-1 p-8 overflow-y-auto">
            <div class="max-w-4xl bg-white rounded-[2.5rem] p-10 border border-gray-100 shadow-sm mx-auto">
                <form action="{{ route('admin.infrastruktur.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest mb-2">Nama Aset Infrastruktur <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_infrastruktur" class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-semibold focus:ring-2 focus:ring-blue-500/20 outline-none" placeholder="Contoh: Jembatan Merdeka" required>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest mb-2">Jenis Infrastruktur</label>
                            <select name="jenis_infrastruktur" class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-semibold outline-none">
                                <option value="Jalan">Jalan</option>
                                <option value="Jembatan">Jembatan</option>
                                <option value="Drainase">Drainase</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest mb-2">Lokasi Kelurahan</label>
                            <select name="id_kelurahan" class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-semibold outline-none">
                                @foreach($semuaKelurahan as $kel)
                                    <option value="{{ $kel->id_kelurahan }}">{{ $kel->nama_kelurahan }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <input type="text" name="latitude" placeholder="Latitude" class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm" required>
                        <input type="text" name="longitude" placeholder="Longitude" class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm" required>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 text-white py-4 rounded-2xl font-bold shadow-lg hover:bg-blue-700 transition">Simpan Aset Baru</button>
                </form>
            </div>
        </div>
    </main>
</body>
</html>