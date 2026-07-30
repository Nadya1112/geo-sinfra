BAB V
KESIMPULAN DAN SARAN

5.1	Kesimpulan
Berdasarkan hasil analisis, perancangan, implementasi, dan pengujian yang telah dilakukan pada platform ekosistem kota cerdas GEO-SINFRA untuk pemetaan kondisi kerusakan infrastruktur permukiman di Kota Banjarmasin, dapat ditarik beberapa kesimpulan utama sebagai berikut:

1.	Keberhasilan Model Kecerdasan Buatan (Artificial Intelligence)
Integrasi arsitektur *Convolutional Neural Network* (CNN) ResNet-18 untuk ekstraksi fitur citra visual dan algoritma *Decision Tree* sebagai penentu aturan klasifikasi berhasil diimplementasikan dengan sangat optimal. Penerapan teknik penyeimbangan data sintetis SMOTE terbukti krusial dalam mengatasi bias kelas minoritas, sehingga model mampu mengevaluasi 689 data objek spasial dan menghasilkan akurasi yang sangat tinggi sebesar 94,20%, dengan nilai *F1-Score* mencapai 96,12%. Capaian ini memastikan bahwa sistem sangat peka dan sensitif dalam mendeteksi infrastruktur kritis ("Rusak Berat") yang memerlukan alokasi perbaikan prioritas.

2.	Efisiensi Fungsionalitas Sistem dan Birokrasi Digital
Platform GEO-SINFRA yang dikembangkan berbasis kerangka kerja (*framework*) Laravel dan pustaka peta digital Leaflet.js telah berhasil menjembatani alur birokrasi tata kelola kota yang responsif. Sistem mampu memfasilitasi tiga otoritas akses secara spesifik (Admin, Surveyor, dan Tim Teknis), sekaligus membuka pintu partisipasi publik (pelaporan warga). Pemetaan spasial ini juga didukung oleh integrasi *gateway* notifikasi otomatis menggunakan API Fonnte WhatsApp, yang menjamin transparansi serta percepatan informasi dari masyarakat langsung ke ranah pembuat kebijakan.

3.	Ketangguhan Performa dan Akseptabilitas Pengguna
Pengujian reliabilitas sistem menggunakan GTMetrix menghasilkan predikat *Grade B* dengan metrik kecepatan yang sangat responsif, ditandai oleh perolehan *Structure Score* sebesar 96%, LCP 2,0 detik, dan hambatan *Total Blocking Time* (TBT) yang minim sebesar 176ms. Di sisi lain, evaluasi kemudahan antarmuka melalui instrumen *System Usability Scale* (SUS) menghasilkan skor akhir akumulatif sebesar 83,18. Nilai ini menempatkan platform GEO-SINFRA pada tingkat akseptabilitas *Acceptable* dengan predikat *Excellent* (*Grade B*), membuktikan bahwa sistem dinilai sangat intuitif, ramah pengguna, dan sepenuhnya layak digunakan sebagai instrumen pendukung operasional harian bagi Dinas Perumahan Rakyat dan Kawasan Permukiman (DPRKP) Kota Banjarmasin.


5.2	Saran
Terlepas dari berbagai keberhasilan yang telah dicapai dalam penelitian ini, terdapat beberapa rekomendasi konstruktif yang dapat dijadikan landasan bagi pengembangan sistem GEO-SINFRA di masa mendatang:

1.	Peningkatan Infrastruktur Server (Hosting)
Mengingat hasil evaluasi performa GTMetrix menunjukkan adanya waktu tunggu (*Initial Server Response Time*) yang masih dapat dioptimalkan, disarankan agar instansi terkait melakukan migrasi pangkalan data sistem ke infrastruktur *server* lokal (berbasis di Indonesia) atau layanan *cloud computing* terdedikasi. Langkah ini akan memangkas latensi jaringan secara signifikan sehingga metrik *Largest Contentful Paint* (LCP) dapat ditekan secara ideal di bawah batas rekomendasi 1,2 detik.

2.	Ekspansi Teknologi Perekaman Data Geospasial
Untuk menjangkau area pemetaan yang memiliki medan topografi sulit, khususnya pada infrastruktur titian kayu ulin di atas lahan basah atau jembatan pelintasan sungai yang ekstrim, disarankan untuk mengintegrasikan teknologi *drone* (Unmanned Aerial Vehicle/UAV) atau kamera pemindai 360 derajat. Integrasi ini akan memperkaya variasi citra visual pada pangkalan data (database) pelatihan sehingga model CNN dapat mengekstrak pola kerusakan struktural dari berbagai sudut pandang (perspektif udara).

3.	Pengembangan Platform Berbasis Mobile Native (Offline Mode)
Guna meningkatkan fleksibilitas dan mempercepat proses inspeksi lapangan, sistem GEO-SINFRA disarankan untuk diekspansi menjadi aplikasi portabel berbasis perangkat bergerak (*mobile application* Android/iOS). Pengembangan ini diharapkan mencakup fitur penyimpanan luring (*offline mode data caching*), sehingga surveyor lapangan tetap dapat melakukan kegiatan pelacakan (*geotagging*) koordinat dan pengambilan foto fisik di wilayah permukiman pinggiran yang tidak memiliki jangkauan sinyal internet yang memadai.
