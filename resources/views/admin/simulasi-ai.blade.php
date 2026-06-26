<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simulasi AI | Admin SINFRA</title>
    <link rel="icon" href="{{ asset('logo_geo-sinfra.png') }}" type="image/png">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
                    colors: {
                        navy: { 50: '#f4f4fa', 100: '#e9e9f3', 200: '#c7c8e3', 500: '#6366f1', 800: '#1e1b4b', 900: '#0f0e2c', 950: '#070617' },
                        gold: { 50: '#fdfbf7', 100: '#fbf7ed', 500: '#c5a059', 600: '#b38f4a', 700: '#9d7c3d' }
                    }
                }
            }
        }
    </script>
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        @keyframes scan {
            0% { top: 0; }
            100% { top: 100%; }
        }
    </style>
<style>
    @media (min-width: 768px) { html { font-size: 14px; } }
    @media (max-width: 767px) { html { font-size: 12px; } }
</style>
</head>
<body class="bg-navy-50 dark:bg-navy-950 text-slate-800 dark:text-slate-200 antialiased flex overflow-hidden h-screen transition-colors duration-300">

    @include('admin.partials.sidebar')

    <main class="flex-1 overflow-y-auto custom-scrollbar text-left relative">
        <header class="sticky top-0 bg-white/80 backdrop-blur-xl border-b border-slate-100 px-4 pl-16 md:px-8 py-4 md:py-5 flex flex-col md:flex-row gap-4 md:gap-0 md:justify-between items-start md:items-center z-40 text-left shadow-sm">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.dashboard') }}" class="w-10 h-10 flex items-center justify-center bg-white text-slate-400 rounded-xl hover:bg-gold-50 hover:text-gold-600 transition-all border border-slate-200 hover:border-gold-200">
                    <i class="fas fa-arrow-left text-sm"></i>
                </a>
                <div class="text-left">
                    <p class="text-xs font-black text-gold-500 uppercase tracking-wider mb-1">Playground</p>
                    <h2 class="text-xl font-black text-navy-900 dark:text-white leading-none">Simulasi Model AI</h2>
                </div>
            </div>

            <div class="flex items-center gap-6">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-black text-navy-900 dark:text-white" id="mini-clock">00:00 WITA</p>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-tighter">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
                <div class="h-8 w-[1px] bg-slate-100"></div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.profile') }}" class="text-right group">
                        <p class="text-sm font-black text-navy-900 dark:text-white leading-none uppercase group-hover:text-gold-500 transition-all">{{ auth()->user()->name }}</p>
                        <p class="text-xs font-bold text-emerald-500 uppercase mt-1">Online</p>
                    </a>
                    <a href="{{ route('admin.profile') }}" class="w-10 h-10 bg-navy-900 rounded-xl flex items-center justify-center text-gold-500 border border-white/10 overflow-hidden shadow-md">
                        @if(auth()->user()->profile_photo)
                            <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-user-circle text-xl"></i>
                        @endif
                    </a>
                </div>
            </div>
        </header>

        <div class="p-4 md:p-8 text-left">
            <div class="max-w-4xl mx-auto space-y-6 animate-fade-in">

                <!-- Header Section -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-navy-900 rounded-[2rem] p-8 shadow-2xl relative overflow-hidden border border-white/5">
                    <div class="relative z-10">
                        <h1 class="text-3xl font-black text-white mb-2 tracking-tight">Simulasi Model AI <span class="text-transparent bg-clip-text bg-gradient-to-r from-gold-500 to-yellow-300">(Playground)</span></h1>
                        <p class="text-slate-400 text-sm font-medium">Uji coba langsung deteksi kerusakan infrastruktur menggunakan model Convolutional Neural Network (CNN) tanpa harus mengisi form survei.</p>
                    </div>
                    <div class="w-16 h-16 bg-white/5 rounded-2xl flex items-center justify-center shrink-0 border border-white/10 relative z-10 group hover:rotate-12 transition-transform">
                        <i class="fas fa-robot text-3xl text-gold-500 group-hover:scale-110 transition-transform"></i>
                    </div>
                    <i class="fas fa-brain absolute -right-6 -bottom-8 text-[120px] text-white/5 z-0 pointer-events-none"></i>
                </div>

                <!-- Playground Area -->
                <div class="bg-white rounded-[2rem] p-8 border border-slate-100 shadow-xl shadow-slate-900/5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        
                        <!-- Left: Upload Section -->
                        <div class="space-y-4">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-sm font-black text-navy-900 uppercase tracking-widest"><i class="fas fa-image text-gold-500 mr-2"></i> Input Citra</h3>
                                <button onclick="document.getElementById('image-input').click()" class="text-xs bg-navy-50 text-navy-600 hover:bg-navy-900 hover:text-white px-3 py-1.5 rounded-lg font-bold transition-colors">
                                    Pilih File
                                </button>
                            </div>
                            
                            <!-- Drag & Drop Zone -->
                            <div id="drop-zone" class="border-2 border-dashed border-slate-300 rounded-3xl p-8 flex flex-col items-center justify-center text-center cursor-pointer hover:border-gold-500 hover:bg-gold-50/30 transition-all min-h-[300px] relative overflow-hidden group">
                                <input type="file" id="image-input" accept="image/jpeg, image/png, image/jpg" class="hidden">
                                
                                <!-- Default State -->
                                <div id="upload-prompt" class="space-y-4">
                                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto group-hover:bg-white group-hover:shadow-md transition-all">
                                        <i class="fas fa-cloud-upload-alt text-3xl text-slate-400 group-hover:text-gold-500 transition-colors"></i>
                                    </div>
                                    <div>
                                        <p class="font-bold text-navy-900">Seret & lepas foto di sini</p>
                                        <p class="text-xs text-slate-400 mt-1">atau klik untuk memilih file (JPG/PNG)</p>
                                    </div>
                                </div>

                                <!-- Image Preview -->
                                <img id="image-preview" class="absolute inset-0 w-full h-full object-cover hidden" alt="Preview">
                                
                                <!-- Scanning Scanner Line -->
                                <div id="scanner-line" class="absolute top-0 left-0 w-full h-1 bg-gold-500 shadow-[0_0_15px_rgba(197,160,89,1)] hidden animate-[scan_2s_ease-in-out_infinite_alternate]"></div>
                            </div>

                            <button id="btn-predict" onclick="runPrediction()" class="w-full bg-navy-900 hover:bg-gold-500 text-white font-black text-xs uppercase tracking-widest py-4 rounded-xl shadow-lg hover:shadow-gold-500/20 transition-all flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                                <i class="fas fa-microchip"></i> Jalankan Prediksi AI
                            </button>
                        </div>

                        <!-- Right: Result Section -->
                        <div class="bg-slate-50 rounded-[2rem] p-6 border border-slate-100 flex flex-col min-h-[400px]">
                            <h3 class="text-sm font-black text-navy-900 uppercase tracking-widest mb-6"><i class="fas fa-poll-h text-gold-500 mr-2"></i> Hasil Analisis</h3>
                            
                            <!-- Waiting State -->
                            <div id="result-waiting" class="flex-1 flex flex-col items-center justify-center text-slate-400 text-center">
                                <i class="fas fa-radar text-4xl mb-4 opacity-20"></i>
                                <p class="text-sm font-bold">Menunggu input citra...</p>
                                <p class="text-xs mt-1 opacity-70">Model CNN siap memproses gambar.</p>
                            </div>

                            <!-- Loading State -->
                            <div id="result-loading" class="flex-1 flex flex-col items-center justify-center text-navy-900 text-center hidden">
                                <div class="w-16 h-16 border-4 border-slate-200 border-t-gold-500 rounded-full animate-spin mb-4"></div>
                                <p class="text-sm font-bold animate-pulse">Menghitung matriks probabilitas...</p>
                            </div>

                            <!-- Error State -->
                            <div id="result-error" class="flex-1 flex flex-col items-center justify-center text-red-500 text-center hidden">
                                <i class="fas fa-exclamation-triangle text-4xl mb-4 opacity-50"></i>
                                <p class="text-sm font-bold" id="error-message">Terjadi kesalahan.</p>
                            </div>

                            <!-- Success State -->
                            <div id="result-success" class="flex-1 flex flex-col justify-center hidden space-y-6">
                                <!-- Status Badge -->
                                <div class="text-center">
                                    <span class="text-xs font-black text-slate-400 uppercase tracking-wider mb-2 block">Klasifikasi Kondisi</span>
                                    <div id="pred-badge" class="inline-block px-6 py-2 rounded-xl text-sm font-black uppercase tracking-wider text-white shadow-lg">
                                        BAIK
                                    </div>
                                </div>

                                <!-- Confidence Meter -->
                                <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
                                    <div class="flex justify-between items-end mb-2">
                                        <span class="text-xs font-black text-navy-900 uppercase tracking-widest">Tingkat Keyakinan (Confidence)</span>
                                        <span id="pred-conf-text" class="text-xl font-black text-navy-900 dark:text-white leading-none">0%</span>
                                    </div>
                                    <div class="w-full bg-slate-100 h-2.5 rounded-full overflow-hidden">
                                        <div id="pred-conf-bar" class="h-full rounded-full bg-gradient-to-r from-navy-800 to-gold-500 transition-all duration-1000 w-0"></div>
                                    </div>
                                </div>

                                <!-- Details -->
                                <div class="bg-navy-900 text-white p-5 rounded-2xl shadow-inner relative overflow-hidden">
                                    <i class="fas fa-info-circle absolute -right-2 -bottom-2 text-white/5 text-5xl"></i>
                                    <span class="text-xs font-bold text-gold-500 uppercase tracking-widest mb-1 block">Rekomendasi Sistem</span>
                                    <p id="pred-rekomendasi" class="text-xs leading-relaxed font-medium">Berdasarkan hasil analisis citra, infrastruktur terpantau aman.</p>
                                </div>
                            </div>
                            
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        function updateClock() {
            const now = new Date();
            const options = { timeZone: 'Asia/Makassar', hour: '2-digit', minute: '2-digit', hour12: false };
            const timeString = new Intl.DateTimeFormat('id-ID', options).format(now);
            const el = document.getElementById('mini-clock');
            if (el) el.textContent = timeString.replace('.', ':') + ' WITA';
        }
        setInterval(updateClock, 1000); updateClock();

        const dropZone = document.getElementById('drop-zone');
        const imageInput = document.getElementById('image-input');
        const imagePreview = document.getElementById('image-preview');
        const uploadPrompt = document.getElementById('upload-prompt');
        const btnPredict = document.getElementById('btn-predict');
        const scannerLine = document.getElementById('scanner-line');

        let currentFile = null;

        // Drag & Drop Handlers
        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('border-gold-500', 'bg-gold-50/30');
        });

        dropZone.addEventListener('dragleave', () => {
            dropZone.classList.remove('border-gold-500', 'bg-gold-50/30');
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('border-gold-500', 'bg-gold-50/30');
            
            if (e.dataTransfer.files.length > 0) {
                handleFile(e.dataTransfer.files[0]);
            }
        });

        dropZone.addEventListener('click', () => {
            if(!currentFile) imageInput.click();
        });

        imageInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                handleFile(e.target.files[0]);
            }
        });

        function handleFile(file) {
            if (!file.type.match('image.*')) {
                alert('Pilih file gambar (JPG/PNG).');
                return;
            }

            currentFile = file;
            
            // Setup Preview
            const reader = new FileReader();
            reader.onload = (e) => {
                imagePreview.src = e.target.result;
                imagePreview.classList.remove('hidden');
                uploadPrompt.classList.add('hidden');
                btnPredict.disabled = false;
                
                // Reset Results
                showResult('waiting');
            };
            reader.readAsDataURL(file);
        }

        function showResult(state) {
            document.getElementById('result-waiting').classList.add('hidden');
            document.getElementById('result-loading').classList.add('hidden');
            document.getElementById('result-error').classList.add('hidden');
            document.getElementById('result-success').classList.add('hidden');

            document.getElementById('result-' + state).classList.remove('hidden');
        }

        async function runPrediction() {
            if (!currentFile) return;

            // UI Loading State
            btnPredict.disabled = true;
            btnPredict.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
            scannerLine.classList.remove('hidden');
            showResult('loading');

            // Form Data
            const formData = new FormData();
            formData.append('image', currentFile);
            formData.append('_token', '{{ csrf_token() }}');

            try {
                const response = await fetch('{{ url("/api/predict-infrastructure") }}', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    // Determine Colors & Text
                    let label = data.kondisi || 'Baik';
                    let conf = data.confidence_kondisi || 0;
                    
                    // Format ulang label agar seragam dengan UI
                    if(label === 'Berat') label = 'Rusak Berat';
                    if(label === 'Sedang') label = 'Rusak Sedang';
                    
                    let bgColor = 'bg-emerald-500 shadow-emerald-500/30';
                    let rekom = "Tidak ditemukan kerusakan berarti secara visual. Kondisi aman.";
                    
                    if(label === 'Rusak Berat' || label === 'Berat') {
                        bgColor = 'bg-red-500 shadow-red-500/30';
                        rekom = "TERDETEKSI KERUSAKAN KRITIS! Diperlukan intervensi perbaikan segera.";
                    } else if(label === 'Rusak Sedang' || label === 'Sedang') {
                        bgColor = 'bg-amber-500 shadow-amber-500/30';
                        rekom = "Terdeteksi kerusakan tingkat menengah. Perlu dijadwalkan pemeliharaan rutin.";
                    }

                    // Update UI
                    const badge = document.getElementById('pred-badge');
                    badge.className = `inline-block px-6 py-2 rounded-xl text-sm font-black uppercase tracking-wider text-white shadow-lg ${bgColor}`;
                    badge.innerText = label;

                    document.getElementById('pred-conf-text').innerText = conf + '%';
                    setTimeout(() => {
                        document.getElementById('pred-conf-bar').style.width = conf + '%';
                    }, 100);

                    document.getElementById('pred-rekomendasi').innerText = rekom;

                    showResult('success');
                } else {
                    document.getElementById('error-message').innerText = data.error || 'Gagal menganalisis gambar.';
                    showResult('error');
                }
            } catch (error) {
                document.getElementById('error-message').innerText = 'Kesalahan koneksi ke server AI.';
                showResult('error');
            } finally {
                // Reset UI
                btnPredict.disabled = false;
                btnPredict.innerHTML = '<i class="fas fa-microchip"></i> Jalankan Prediksi AI';
                scannerLine.classList.add('hidden');
            }
        }
    </script>
</body>
</html>

