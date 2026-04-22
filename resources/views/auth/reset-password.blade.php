<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Sandi | GEO-SINFRA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } .bg-government-gradient { background: radial-gradient(circle at center, #1e40af 0%, #1e1b4b 100%); } </style>
</head>
<body class="antialiased bg-gray-50">
    <div class="flex flex-col md:flex-row h-screen overflow-hidden">
        <div class="w-full md:w-1/2 bg-government-gradient flex flex-col items-center justify-center p-12 text-center relative text-white">
            <h1 class="text-5xl font-extrabold tracking-tight mb-4">GEO-SINFRA</h1>
            <p class="text-xl font-light text-blue-100 max-w-sm">Amankan kembali akses akun Anda.</p>
        </div>

        <div class="w-full md:w-1/2 bg-white flex flex-col items-center justify-center p-8 md:p-20">
            <div class="w-full max-w-md">
                <div class="mb-10 text-center">
                    <h2 class="text-4xl font-extrabold text-[#1e1b4b] mb-2 tracking-tight">Atur Ulang Sandi</h2>
                    <p class="text-gray-500 font-medium text-sm">Silakan buat kata sandi baru untuk akun Anda</p>
                </div>

                <form action="{{ route('password.update') }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Email Kedinasan <span class="text-red-500">*</span></label>
                        <input type="email" name="email" placeholder="nama@disperkim.go.id" required class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm font-medium">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Buat Sandi Baru <span class="text-red-500">*</span></label>
                        <input type="password" name="password" placeholder="Minimal 8 karakter" required class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm font-medium">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Konfirmasi Kata Sandi <span class="text-red-500">*</span></label>
                        <input type="password" name="password_confirmation" placeholder="Ulangi kata sandi baru" required class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm font-medium">
                    </div>

                    <button type="submit" class="w-full py-4 bg-[#5c56e1] text-white text-sm font-bold rounded-xl shadow-lg hover:bg-blue-800 transition-all uppercase tracking-widest">
                        PERBARUI SANDI SEKARANG
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>