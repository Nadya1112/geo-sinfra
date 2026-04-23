<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Prediksi Infrastruktur</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-4 text-blue-600">Selamat Datang, {{ auth()->user()->name }}!</h1>
        <p class="text-gray-600 mb-6">Ini adalah halaman dashboard admin untuk sistem prediksi infrastruktur kamu.</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-blue-50 p-4 border-l-4 border-blue-500 rounded">
                <h2 class="text-sm font-semibold text-blue-700 uppercase">Jumlah Surveyor</h2>
                <p class="text-3xl font-bold">Lengkapi Variabel di Controller</p>
            </div>
            
            <div class="bg-green-50 p-4 border-l-4 border-green-500 rounded">
                <h2 class="text-sm font-semibold text-green-700 uppercase">Jumlah Kabid</h2>
                <p class="text-3xl font-bold">Lengkapi Variabel di Controller</p>
            </div>
        </div>

        <div class="mt-6">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                    Logout
                </button>
            </form>
        </div>
    </div>
</body>
</html>