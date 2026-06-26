<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offline Mode | GEO-SINFRA</title>
    <link rel="icon" href="{{ asset('logo_geo-sinfra.png') }}" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
                    colors: {
                        navy: { 50:'#f4f4fa', 100:'#e9e9f3', 500:'#6366f1', 800:'#1e1b4b', 900:'#0f0e2c', 950:'#070617' },
                        gold: { 50:'#fdfbf7', 100:'#fbf7ed', 500:'#c5a059', 600:'#b38f4a', 700:'#9d7c3d' }
                    }
                }
            }
        }
    </script>
    <style> 
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen text-slate-800 font-sans p-6">
    <div class="max-w-md w-full bg-white rounded-[2.5rem] p-8 md:p-10 text-center border border-slate-100 shadow-xl shadow-slate-900/5 relative overflow-hidden">
        
        <!-- Decorative Background -->
        <div class="absolute -top-24 -right-24 w-48 h-48 bg-orange-500/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-navy-500/10 rounded-full blur-3xl"></div>

        <div class="relative z-10">
            <div class="w-24 h-24 bg-orange-50 rounded-[2rem] flex items-center justify-center mx-auto mb-6 text-orange-500 shadow-sm border border-orange-100">
                <i class="fas fa-wifi-slash text-4xl"></i>
            </div>
            
            <h1 class="text-3xl font-black text-navy-900 tracking-tight mb-2">Tidak Ada Sinyal</h1>
            <p class="text-sm text-slate-500 leading-relaxed font-medium mb-8">
                Anda sedang berada di luar jangkauan internet. Jangan khawatir, Anda tetap bisa membuka halaman form input jika sudah membukanya sebelumnya.
            </p>

            <div class="bg-slate-50 p-5 rounded-2xl border border-slate-200 mb-8 text-left flex gap-4">
                <i class="fas fa-info-circle text-gold-500 mt-1"></i>
                <p class="text-xs text-slate-600 font-semibold leading-relaxed">
                    Data survei yang sudah Anda simpan dalam mode offline akan otomatis terkirim begitu koneksi internet kembali normal.
                </p>
            </div>

            <button onclick="window.history.back()" class="w-full py-4 bg-navy-900 hover:bg-gold-500 hover:text-navy-900 text-white rounded-2xl font-black text-sm uppercase tracking-widest transition-all shadow-md flex items-center justify-center gap-3">
                <i class="fas fa-arrow-left"></i> Kembali Sebelumnya
            </button>
            <button onclick="window.location.reload()" class="w-full py-4 mt-3 bg-white hover:bg-slate-50 text-navy-900 border border-slate-200 rounded-2xl font-black text-sm uppercase tracking-widest transition-all shadow-sm flex items-center justify-center gap-3">
                <i class="fas fa-sync-alt"></i> Coba Muat Ulang
            </button>
        </div>
    </div>
</body>
</html>
