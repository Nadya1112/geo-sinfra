<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Infrastruktur | Admin SINFRA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 flex h-screen overflow-hidden text-gray-800 text-left">
    <main class="flex-1 flex flex-col h-screen overflow-hidden">
        <header class="bg-white border-b border-gray-100 px-8 py-5 flex justify-between items-center">
            <h2 class="text-xl font-black text-[#1e1b4b]">Manajemen Infrastruktur</h2>
        </header>

        <div class="flex-1 p-8 overflow-y-auto">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h4 class="font-extrabold text-lg text-[#1e1b4b]">DATA INFRASTRUKTUR</h4>
                    <p class="text-xs text-gray-400 font-medium">Kelola titik aset infrastruktur di Banjarmasin</p>
                </div>
                <a href="{{ route('admin.infrastruktur.create') }}" class="bg-blue-600 text-white text-xs px-6 py-2.5 rounded-2xl font-bold shadow-lg hover:bg-blue-700 transition">
                    <i class="fas fa-plus mr-2"></i> Tambah Aset
                </a>
            </div>

            <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-100">
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Nama Aset</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Jenis</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Kondisi</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($infrastruktur as $inf)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-8 py-5 text-sm font-bold text-[#1e1b4b]">{{ $inf->nama_infrastruktur }}</td>
                            <td class="px-8 py-5 text-xs text-gray-500">{{ $inf->jenis_infrastruktur }}</td>
                            <td class="px-8 py-5">
                                <span class="px-3 py-1 bg-green-50 text-green-600 rounded-lg text-[10px] font-bold">{{ $inf->kondisi }}</span>
                            </td>
                            <td class="px-8 py-5">
                                <form action="{{ route('admin.infrastruktur.destroy', $inf->id_infrastruktur) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-600"><i class="fas fa-trash text-xs"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-8 py-10 text-center text-gray-400">Belum ada data infrastruktur.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>