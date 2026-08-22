<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan & Rekapitulasi | Tim Teknis SINFRA</title>
    <script src="https://cdn.tailwindcss.com"></script>
        <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
                    colors: {
                        navy: { 50:'#f4f4fa', 100:'#e9e9f3', 200:'#c7c8e3', 300:'#9fb3c8', 400:'#829ab1', 500:'#6366f1', 600:'#486581', 700:'#334e68', 800:'#1e1b4b', 900:'#0f0e2c', 950:'#070617' },
                        gold: { 50:'#fdfbf7', 100:'#fbf7ed', 200:'#eed9b9', 300:'#e5c292', 400:'#dba665', 500:'#c5a059', 600:'#b38f4a', 700:'#9d7c3d', 800:'#7c5327', 900:'#644422', 950:'#382310' }
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        @media print {
            @page { margin: 0; size: A4 portrait; }
            html, body { height: auto !important; overflow: visible !important; background: white; color: black; font-family: 'Times New Roman', Times, serif; font-size: 11pt; padding: 0.5cm 1cm 1cm 1cm !important; margin: 0 !important; }
            .no-print { display: none !important; }
            .print-only { display: block !important; }
            .print\:grid { display: grid !important; }
            .no-break { page-break-inside: avoid; }
            aside, header { display: none !important; }
            main { width: 100%; margin: 0; padding: 0; height: auto !important; overflow: visible !important; display: block !important; }
            
            /* Table Formatting for Formal Document */
            .print-no-style { background: transparent !important; box-shadow: none !important; border: none !important; border-radius: 0 !important; }
            .rounded-\[2rem\] { border-radius: 0 !important; }
            table { border-collapse: collapse !important; width: 100% !important; border: 1px solid black !important; table-layout: fixed !important; }
            th, td { border: 1px solid black !important; padding: 8px !important; color: black !important; font-size: 11pt !important; word-wrap: break-word !important; }
            th { font-weight: bold !important; text-align: center !important; background-color: #f3f4f6 !important; }
            .badge-print { border: none !important; background: transparent !important; padding: 0 !important; }
            
            .custom-scrollbar, .overflow-y-auto, .overflow-hidden { overflow: visible !important; height: auto !important; max-height: none !important; }
            .p-8 { padding: 0 !important; }
            .mt-6 { margin-top: 15px !important; }
            .ttd-box {
                display: block !important;
                margin-top: 15px;
                text-align: right;
                font-family: 'Times New Roman', Times, serif;
                font-size: 11pt;
                page-break-inside: avoid;
            }
            .ttd-inner {
                display: inline-block;
                text-align: center;
                width: 260px;
                line-height: 1.5;
            }
            .print-tfoot-only {
                display: table-row-group !important;
            }
        }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
<style>
    
    
@media (max-width: 767px) { html { font-size: 12px; } }
</style>
</head>
<body class="bg-slate-50 dark:bg-[#0f0e2c] flex h-screen overflow-hidden text-slate-800 dark:text-white text-left font-sans dark:bg-navy-950 transition-colors duration-300">

    @include('tim_teknis.partials.sidebar')

    <main class="flex-1 flex flex-col h-screen overflow-hidden text-left font-sans relative">
        <header class="bg-white dark:bg-[#1e1b4b] border-b border-slate-100 dark:border-white/10 px-4 pl-20 md:px-8 py-4 md:py-5 flex justify-between items-center z-10 no-print sticky top-0">
            <div class="flex items-center gap-4 min-w-0">
                <a href="{{ route('tim_teknis.dashboard') }}" class="w-10 h-10 flex items-center justify-center bg-slate-50 dark:bg-[#0f0e2c] text-slate-400 rounded-xl hover:bg-gold-50 hover:text-gold-500 transition-all border border-slate-100 dark:border-white/10 hidden md:flex">
                    <i class="fas fa-arrow-left text-sm"></i>
                </a>
                <div class="min-w-0">
                    <p class="text-[9px] md:text-xs font-extrabold text-gold-500 uppercase tracking-[0.2em] mb-0.5 md:mb-1 truncate">Pusat Pelaporan</p>
                    <h2 class="text-sm md:text-xl font-black text-navy-900 dark:text-white leading-tight whitespace-normal">Laporan & Rekapitulasi</h2>
                </div>
            </div>
            
            <div class="flex items-center gap-3 md:gap-6 flex-shrink-0">
                <div class="text-right">
                    <p class="text-[10px] md:text-xs font-black text-navy-900 dark:text-white mt-1" id="mini-clock">00:00 WITA</p>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-tighter hidden md:block">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
                <div class="h-6 md:h-8 w-[1px] bg-slate-200 dark:bg-white/10"></div>
                <a href="{{ route('tim_teknis.profile') }}" class="flex items-center gap-2 md:gap-3 group">
                    <div class="text-right">
                        <p class="text-sm font-black text-navy-900 dark:text-white leading-none uppercase group-hover:text-gold-500 transition-colors max-w-[200px] truncate hidden md:block">{{ auth()->user()->name }}</p>
                        <p class="text-[8px] md:text-xs font-bold text-emerald-500 uppercase md:mt-0.5">Aktif</p>
                    </div>
                    <div class="w-8 h-8 md:w-10 md:h-10 bg-navy-900 rounded-xl flex items-center justify-center text-gold-500 shadow-md group-hover:shadow-lg transition-all overflow-hidden shrink-0">
                        @if(auth()->user()->profile_photo)
                            <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-user-circle text-lg md:text-xl"></i>
                        @endif
                    </div>
                </a>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-8 custom-scrollbar">
            @livewire('tim-teknis.laporan-table')

        </div>
    </main>

    <script>
        function updateClock() {
            const now = new Date();
            document.getElementById('mini-clock').textContent = `${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')} WITA`;
        }
        setInterval(updateClock, 1000); updateClock();

        function exportTableToExcel(filename) {
            // Clone table agar kita bisa menghapus TTD (tfoot) tanpa mengubah tampilan di web
            var tempTable = document.getElementById("laporanTable").cloneNode(true);
            var tfoot = tempTable.querySelector('tfoot');
            if (tfoot) tfoot.remove();

            var tableHTML = tempTable.outerHTML;

            // FIX 1: Pecah '-->' agar browser HTML parser tidak salah menutup <script> tag.
            // FIX 2: Pecah '<x:' agar Laravel Blade compiler tidak mengira tag XML ini
            //        adalah Blade component (contoh error 500 karena tag x:DisplayGridlines).
            var x_      = '<' + 'x:';
            var x_close = '</' + 'x:';
            var msoOpen  = '<' + '!--[if gte mso 9]>';
            var msoClose = '<' + '![endif]--' + '>';
            var xmlBlock = msoOpen +
                '<xml>' +
                    x_ + 'ExcelWorkbook xmlns:x="urn:schemas-microsoft-com:office:excel">' +
                        x_ + 'ExcelWorksheets>' +
                            x_ + 'ExcelWorksheet>' +
                                x_ + 'Name>Data Laporan' + x_close + 'Name>' +
                                x_ + 'WorksheetOptions>' + x_ + 'DisplayGridlines/>' + x_close + 'WorksheetOptions>' +
                            x_close + 'ExcelWorksheet>' +
                        x_close + 'ExcelWorksheets>' +
                    x_close + 'ExcelWorkbook>' +
                '</xml>' +
                msoClose;

            var htmlTemplate =
                '<' + 'html xmlns:o="urn:schemas-microsoft-com:office:office"' +
                     ' xmlns:x="urn:schemas-microsoft-com:office:excel"' +
                     ' xmlns="http://www.w3.org/TR/REC-html40">' +
                '<' + 'head><' + 'meta charset="UTF-8">' +
                xmlBlock +
                '<' + 'style>table{border-collapse:collapse;}td,th{border:1px solid black;padding:6px;}<' + '/style>' +
                '<' + '/head>' +
                '<' + 'body>' + tableHTML + '<' + '/body>' +
                '<' + '/html>';

            var blob = new Blob([htmlTemplate], {
                type: "application/vnd.ms-excel;charset=utf-8"
            });

            var downloadLink = document.createElement("a");
            downloadLink.href = window.URL.createObjectURL(blob);
            downloadLink.download = filename;
            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);
        }

        function printAllData() {
            const form = document.getElementById('filterForm');
            if (form) {
                // If there's an existing print param, remove it
                const oldPrint = form.querySelector('input[name="print"]');
                if (oldPrint) oldPrint.remove();
                
                const printInput = document.createElement('input');
                printInput.type = 'hidden';
                printInput.name = 'print';
                printInput.value = 'true';
                form.appendChild(printInput);

                form.submit();
            } else {
                const url = new URL(window.location.href);
                url.searchParams.set('print', 'true');
                window.location.href = url.toString();
            }
        }

        function exportAllDataToExcel() {
            const form = document.getElementById('filterForm');
            if (form) {
                const oldExport = form.querySelector('input[name="autoExportExcel"]');
                if (oldExport) oldExport.remove();
                
                const exportInput = document.createElement('input');
                exportInput.type = 'hidden';
                exportInput.name = 'autoExportExcel';
                exportInput.value = 'true';
                form.appendChild(exportInput);

                form.submit();
            } else {
                const url = new URL(window.location.href);
                url.searchParams.set('autoExportExcel', 'true');
                window.location.href = url.toString();
            }
        }

        // Jika URL punya parameter print=true, otomatis panggil window.print()
        @if(request('print') == 'true')
            window.addEventListener('load', function() {
                setTimeout(function() {
                    window.print();
                    
                    const cleanUrl = new URL(window.location.href);
                    cleanUrl.searchParams.delete('print');
                    window.history.replaceState({}, document.title, cleanUrl.toString());
                }, 500); 
            });
        @endif

        // Jika URL punya parameter autoExportExcel=true
        @if(request('autoExportExcel') == 'true')
            window.addEventListener('load', function() {
                setTimeout(function() {
                    exportTableToExcel('Laporan-Infrastruktur-{{ date("Y-m-d") }}.xls');
                    
                    const cleanUrl = new URL(window.location.href);
                    cleanUrl.searchParams.delete('autoExportExcel');
                    window.history.replaceState({}, document.title, cleanUrl.toString());
                }, 500); 
            });
        @endif
    </script>
</body>
</html>
