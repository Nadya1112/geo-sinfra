import re

html_table = """
<div style="overflow-x: auto;">
<table border="1" style="border-collapse: collapse; width: 100%; text-align: center; font-size: 12px;">
    <thead>
        <tr style="background-color: #f1f5f9;">
            <th rowspan="2" style="padding: 8px;">No</th>
            <th rowspan="2" style="padding: 8px;">Tahapan Waterfall</th>
            <th rowspan="2" style="padding: 8px; min-width: 200px;">Kegiatan</th>
            <th colspan="4" style="padding: 4px;">Feb</th>
            <th colspan="4" style="padding: 4px;">Mar</th>
            <th colspan="4" style="padding: 4px;">Apr</th>
            <th colspan="4" style="padding: 4px;">Mei</th>
            <th colspan="4" style="padding: 4px;">Jun</th>
            <th colspan="4" style="padding: 4px;">Jul</th>
            <th rowspan="2" style="padding: 8px; min-width: 150px;">Output Utama</th>
        </tr>
        <tr style="background-color: #f8fafc;">
            <!-- Feb -->
            <th>1</th><th>2</th><th>3</th><th>4</th>
            <!-- Mar -->
            <th>1</th><th>2</th><th>3</th><th>4</th>
            <!-- Apr -->
            <th>1</th><th>2</th><th>3</th><th>4</th>
            <!-- Mei -->
            <th>1</th><th>2</th><th>3</th><th>4</th>
            <!-- Jun -->
            <th>1</th><th>2</th><th>3</th><th>4</th>
            <!-- Jul -->
            <th>1</th><th>2</th><th>3</th><th>4</th>
        </tr>
    </thead>
    <tbody>
        <!-- TAHAP 1 -->
        <tr>
            <td rowspan="3"><b>1</b></td>
            <td rowspan="3" style="text-align: left;">Analisis Kebutuhan & Data</td>
            <td style="text-align: left;">Wawancara & Observasi Lapangan</td>
            <td style="background-color: #3b82f6;"></td><td style="background-color: #3b82f6;"></td><td></td><td></td> <!-- Feb -->
            <td></td><td></td><td></td><td></td> <!-- Mar -->
            <td></td><td></td><td></td><td></td> <!-- Apr -->
            <td></td><td></td><td></td><td></td> <!-- Mei -->
            <td></td><td></td><td></td><td></td> <!-- Jun -->
            <td></td><td></td><td></td><td></td> <!-- Jul -->
            <td rowspan="3" style="text-align: left;">Dokumen Kebutuhan & Dataset Koordinat</td>
        </tr>
        <tr>
            <td style="text-align: left;">Pengumpulan Dataset Citra & Koordinat</td>
            <td></td><td></td><td style="background-color: #3b82f6;"></td><td style="background-color: #3b82f6;"></td> <!-- Feb -->
            <td></td><td></td><td></td><td></td> <!-- Mar -->
            <td></td><td></td><td></td><td></td> <!-- Apr -->
            <td></td><td></td><td></td><td></td> <!-- Mei -->
            <td></td><td></td><td></td><td></td> <!-- Jun -->
            <td></td><td></td><td></td><td></td> <!-- Jul -->
        </tr>
        <tr>
            <td style="text-align: left;">Penyusunan Dokumen Proposal</td>
            <td></td><td></td><td></td><td></td> <!-- Feb -->
            <td style="background-color: #3b82f6;"></td><td style="background-color: #3b82f6;"></td><td></td><td></td> <!-- Mar -->
            <td></td><td></td><td></td><td></td> <!-- Apr -->
            <td></td><td></td><td></td><td></td> <!-- Mei -->
            <td></td><td></td><td></td><td></td> <!-- Jun -->
            <td></td><td></td><td></td><td></td> <!-- Jul -->
        </tr>

        <!-- TAHAP 2 -->
        <tr>
            <td rowspan="3"><b>2</b></td>
            <td rowspan="3" style="text-align: left;">Desain Sistem & Pre-processing</td>
            <td style="text-align: left;">Perancangan UML, ERD, dan Arsitektur Sistem</td>
            <td></td><td></td><td></td><td></td> <!-- Feb -->
            <td></td><td></td><td style="background-color: #3b82f6;"></td><td style="background-color: #3b82f6;"></td> <!-- Mar -->
            <td></td><td></td><td></td><td></td> <!-- Apr -->
            <td></td><td></td><td></td><td></td> <!-- Mei -->
            <td></td><td></td><td></td><td></td> <!-- Jun -->
            <td></td><td></td><td></td><td></td> <!-- Jul -->
            <td rowspan="3" style="text-align: left;">Wireframe UI, Desain Database & Data Bersih</td>
        </tr>
        <tr>
            <td style="text-align: left;">Desain Antarmuka (UI/UX) WebGIS</td>
            <td></td><td></td><td></td><td></td> <!-- Feb -->
            <td></td><td></td><td></td><td></td> <!-- Mar -->
            <td style="background-color: #3b82f6;"></td><td></td><td></td><td></td> <!-- Apr -->
            <td></td><td></td><td></td><td></td> <!-- Mei -->
            <td></td><td></td><td></td><td></td> <!-- Jun -->
            <td></td><td></td><td></td><td></td> <!-- Jul -->
        </tr>
        <tr>
            <td style="text-align: left;">Pre-processing Dataset Citra (Untuk AI)</td>
            <td></td><td></td><td></td><td></td> <!-- Feb -->
            <td></td><td></td><td></td><td></td> <!-- Mar -->
            <td></td><td style="background-color: #3b82f6;"></td><td></td><td></td> <!-- Apr -->
            <td></td><td></td><td></td><td></td> <!-- Mei -->
            <td></td><td></td><td></td><td></td> <!-- Jun -->
            <td></td><td></td><td></td><td></td> <!-- Jul -->
        </tr>

        <!-- TAHAP 3 -->
        <tr>
            <td rowspan="3"><b>3</b></td>
            <td rowspan="3" style="text-align: left;">Pengembangan (Implementasi)</td>
            <td style="text-align: left;">Pelatihan Model AI (CNN & Decision Tree)</td>
            <td></td><td></td><td></td><td></td> <!-- Feb -->
            <td></td><td></td><td></td><td></td> <!-- Mar -->
            <td></td><td></td><td style="background-color: #3b82f6;"></td><td style="background-color: #3b82f6;"></td> <!-- Apr -->
            <td style="background-color: #3b82f6;"></td><td></td><td></td><td></td> <!-- Mei -->
            <td></td><td></td><td></td><td></td> <!-- Jun -->
            <td></td><td></td><td></td><td></td> <!-- Jul -->
            <td rowspan="3" style="text-align: left;">Sistem Aplikasi Web & AI Terintegrasi</td>
        </tr>
        <tr>
            <td style="text-align: left;">Pembuatan WebGIS & Dasbor Pelaporan</td>
            <td></td><td></td><td></td><td></td> <!-- Feb -->
            <td></td><td></td><td></td><td></td> <!-- Mar -->
            <td></td><td></td><td></td><td></td> <!-- Apr -->
            <td></td><td style="background-color: #3b82f6;"></td><td style="background-color: #3b82f6;"></td><td style="background-color: #3b82f6;"></td> <!-- Mei -->
            <td></td><td></td><td></td><td></td> <!-- Jun -->
            <td></td><td></td><td></td><td></td> <!-- Jul -->
        </tr>
        <tr>
            <td style="text-align: left;">Integrasi API Fonnte & Layanan Cloud Hosting</td>
            <td></td><td></td><td></td><td></td> <!-- Feb -->
            <td></td><td></td><td></td><td></td> <!-- Mar -->
            <td></td><td></td><td></td><td></td> <!-- Apr -->
            <td></td><td></td><td></td><td></td> <!-- Mei -->
            <td style="background-color: #3b82f6;"></td><td style="background-color: #3b82f6;"></td><td></td><td></td> <!-- Jun -->
            <td></td><td></td><td></td><td></td> <!-- Jul -->
        </tr>

        <!-- TAHAP 4 -->
        <tr>
            <td rowspan="3"><b>4</b></td>
            <td rowspan="3" style="text-align: left;">Pengujian Sistem</td>
            <td style="text-align: left;">Evaluasi Akurasi AI (Confusion Matrix)</td>
            <td></td><td></td><td></td><td></td> <!-- Feb -->
            <td></td><td></td><td></td><td></td> <!-- Mar -->
            <td></td><td></td><td></td><td></td> <!-- Apr -->
            <td></td><td></td><td></td><td></td> <!-- Mei -->
            <td></td><td></td><td style="background-color: #3b82f6;"></td><td></td> <!-- Jun -->
            <td></td><td></td><td></td><td></td> <!-- Jul -->
            <td rowspan="3" style="text-align: left;">Dokumen Laporan Hasil Uji (Valid)</td>
        </tr>
        <tr>
            <td style="text-align: left;">Blackbox Testing & Uji Kecepatan (GTMetrix)</td>
            <td></td><td></td><td></td><td></td> <!-- Feb -->
            <td></td><td></td><td></td><td></td> <!-- Mar -->
            <td></td><td></td><td></td><td></td> <!-- Apr -->
            <td></td><td></td><td></td><td></td> <!-- Mei -->
            <td></td><td></td><td></td><td style="background-color: #3b82f6;"></td> <!-- Jun -->
            <td></td><td></td><td></td><td></td> <!-- Jul -->
        </tr>
        <tr>
            <td style="text-align: left;">Pengujian Usability Pengguna Akhir (SUS)</td>
            <td></td><td></td><td></td><td></td> <!-- Feb -->
            <td></td><td></td><td></td><td></td> <!-- Mar -->
            <td></td><td></td><td></td><td></td> <!-- Apr -->
            <td></td><td></td><td></td><td></td> <!-- Mei -->
            <td></td><td></td><td></td><td></td> <!-- Jun -->
            <td style="background-color: #3b82f6;"></td><td style="background-color: #3b82f6;"></td><td></td><td></td> <!-- Jul -->
        </tr>

        <!-- TAHAP 5 -->
        <tr>
            <td rowspan="2"><b>5</b></td>
            <td rowspan="2" style="text-align: left;">Finalisasi & Pelaporan</td>
            <td style="text-align: left;">Perbaikan Bug & Dokumentasi Kode</td>
            <td></td><td></td><td></td><td></td> <!-- Feb -->
            <td></td><td></td><td></td><td></td> <!-- Mar -->
            <td></td><td></td><td></td><td></td> <!-- Apr -->
            <td></td><td></td><td></td><td></td> <!-- Mei -->
            <td></td><td></td><td></td><td></td> <!-- Jun -->
            <td></td><td></td><td style="background-color: #3b82f6;"></td><td></td> <!-- Jul -->
            <td rowspan="2" style="text-align: left;">Website Rilis Publik & Buku TA Selesai</td>
        </tr>
        <tr>
            <td style="text-align: left;">Penyusunan & Penjilidan Buku Tugas Akhir (TA)</td>
            <td></td><td></td><td></td><td></td> <!-- Feb -->
            <td></td><td></td><td></td><td></td> <!-- Mar -->
            <td></td><td></td><td></td><td></td> <!-- Apr -->
            <td></td><td></td><td></td><td></td> <!-- Mei -->
            <td></td><td></td><td></td><td></td> <!-- Jun -->
            <td></td><td></td><td></td><td style="background-color: #3b82f6;"></td> <!-- Jul -->
        </tr>
    </tbody>
</table>
</div>
"""

with open('_research_doc/Bab III.md', 'r', encoding='utf-8') as f:
    content = f.read()

# Replace the HTML table in Bab III.md
pattern = r"<div style=\"overflow-x: auto;\">.*?</div>"

new_content = re.sub(pattern, html_table, content, flags=re.DOTALL)

with open('_research_doc/Bab III.md', 'w', encoding='utf-8') as f:
    f.write(new_content)

print("Done")
