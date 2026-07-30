	LANDASAN TEORI


	Penelitian Terdahulu
Penyusunan penelitian ini didasari oleh tinjauan terhadap beberapa studi terdahulu yang memiliki relevansi kuat, baik dari sisi Metode klasifikasi maupun implementasi Sistem Informasi Geografis (SIG). Peninjauan literatur ini bertujuan untuk memetakan perkembangan teknologi terkini, memahami parameter performa berbagai Metode yang telah teruji, serta menentukan posisi penelitian ini di antara studi yang sudah ada (Miranda & Aryu, 2021). engan mensintesis hasil riset sebelumnya, dapat ditentukan metodologi yang paling optimal untuk mengotomatisasi pemetaan infrastruktur dengan tingkat akurasi yang dapat dipertanggungjawabkan (Hasibuan & Sulaiman, 2022).

Tabel 2.1     State Of the art 
No.	Penulis, Tahun	Fokus/Objek	Metode/Tools	Hasil Utama/Nilai Akurasi
1	Pradana, Rahajoe, & Sihananto, 2024	Deteksi keretakan jalan lingkungan	Hybrid CNN-LSTM /Android	Menghasilkan nilai akurasi sebesar 94,5% dalam mengenali pola keretakan secara otomatis pada perangkat mobile.
2	Saparudin, Ramdi, & Alfarizi, 2025	Klasifikasi jenis kerusakan infrastruktur	CNN (ResNet-18)/Python	Menggunakan 1.200 dataset citra, Metode ini menghasilkan akurasi 91,2% dalam mengekstraksi fitur visual menjadi label data.
3	Miranda & Aryuni, 2021	Klasifikasi tutupan ahan citra satelit	CNN & Decision Tree	Kombinasi model menghasilkan klasifikasi yang stabil dengan tingkat presisi sebesar 88,7% untuk interpretasi data spasial.
4	Ahmad, Firdawanti, & Agustiani, 2025	Klasifikasi kesiapan digital wilayah	Decision Tree/Spasial	Menghasilkan pola keputusan dengan akurasi 89,5% untuk memvalidasi status digital wilayah secara objektif.
5	Huda, Ardianto, dkk. 2025	Desain Sistem Informasi Geografis (SIG) untuk pengelolaan infrastruktur	Geographic Information System Sistem Informasi Geografis (SIG)	Mereduksi waktu pemantauan lokasi hingga 60% melalui visualisasi peta digital yang dinamis dan transparan.
6	Hasibuan & Sulaiman, 2022	Implementasi Smart City Kota Banjarmasin	E-Government/ Sistem Informasi Geografis (SIG) Berbasis Web	Meningkatkan sinkronisasi akses informasi layanan publik sebesar 75% melalui integrasi data spasial berbasis web.

Berdasarkan tinjauan di atas, implementasi Metode Convolutional Neural Network (CNN) menunjukkan performansi yang konsisten dalam melakukan ekstraksi fitur visual dari citra infrastruktur (Saparudin et al., 2025). Di sisi lain, Metode Decision Tree digunakan untuk mereduksi subjektivitas penilaian teknis melalui alur logika keputusan yang terukur (Fatimah Ahmad et al., 2025). Namun, mayoritas riset terdahulu menerapkan Metode tersebut secara parsial dan belum mengintegrasikan variabel teknis kedinasan ke dalam satu sistem SIG yang terpadu (Huda et al., 2024).
Fokus utama penelitian ini adalah mengintegrasikan klasifikasi visual berbasis CNN dan logika keputusan Decision Tree ke dalam sebuah SIG berbasis web  (Hasibuan & Sulaiman, 2022). Unsur kebaruan (novelty) dalam studi ini terletak pada penggunaan variabel teknis riil dari dokumen Detail Engineering Design (DED) milik Disperkim Kota Banjarmasin periode tahun 2025. Melalui integrasi tersebut, sistem ini dirancang untuk mengakselerasi penyajian data spasial yang menunjang akuntabilitas pengelolaan aset daerah (Pradana et al., 2023).

	Analisis Perbandingan dan Celah Penelitian (Gap)
Tinjauan terhadap studi terdahulu dilakukan untuk memetakan alur Metode serta temuan yang telah dihasilkan sebelumnya. Tahapan ini diperlukan untuk menentukan posisi penelitian saat ini guna mengidentifikasi aspek yang belum dianalisis secara mendalam oleh peneliti lain (Yuniarti et al., 2022). Identifikasi ini bertujuan untuk memvalidasi orisinalitas serta melakukan optimasi terhadap keterbatasan teknis yang muncul pada model-model sebelumnya (D. A. Wati & A. K. Garside., 2021). Analisis kesenjangan (research gap) ini juga menjadi dasar dalam menentukan arah pengembangan sistem agar relevan dengan parameter permasalahan di lapangan (Prihantara et al., 2023).




Tabel 2.2     Penelitian GAP 
No.	Penulis, Tahun	Fokus/Objek	Metode/Tools	Kurang dari Penelitian
1	Nafisa Andika Putri & Waljiyanto, 2020	Peta Kesesuaian Lahan untuk Lokasi Homestay Wisata di Desa Sendang	Analisis Sistem Informasi Geografis (SIG) Metode Analytic Hierarchy Process (AHP)	Ketidakakuratan hasil karena penggunaan data RT/RW lama yang belum diperbarui, sehingga terjadi penyusutan luas lahan potensial.
2	Adi Sabar Ginting, Ruri Prihatini Lubis & Wahyu Hidayat	Tata ruang wilayah dan penentuan lokasi perumahan	SIG participatory dengan Metode pengambilan keputusan multikriteria (MCDA)	Kendala aksesibilitas dan biaya dalam memperoleh data spasial yang terbaru untuk menjaga keberlanjutan model.
3	Arell S. Biyantoro & Budi Prasetiyo	Pengujian algoritma machine learning untuk klasifikasi status kesehatan, dengan penekanan pada penggunaan Metode Decision Tree	Dataset dummy bertema kesehatan	Jumlah data yang digunakan relatif sedikit (44 baris), sehingga hasil model sulit diterapkan pada populasi yang lebih luas.
4	Chesa Saskia Rafika, Revano Maliq Reynanada & Anggraini Puspita Sari	Pengklasifikasian risiko gagal studi mahasiswa berdasarkan data akademik dan non-akademik, seperti IPK, masa studi, keterlibatan KKN/PKL, dan poin SKPM pada mahasiswa Institut Teknologi Sepuluh Nopember (ITS)	Decision Tree dengan algoritma CART (Classification and Regression Tree)	Variabel data masih terbatas pada atribut akademik dan belum melibatkan faktor eksternal seperti kondisi ekonomi atau latar belakang keluarga.
5	Ahmad Fariz Fuady, Dwiky Oldi Amsyah, Muhammad Farhan, Rusma Riansyah& M. Dayyan Dhiyaul Haq	Klasifikasi buah berbasis citra RGB yang dibangun dengan arsitektur Convolutional Neural Network (CNN)	Metode Convolution Neural Network (CNN)	Model belum diuji pada latar belakang yang rumit atau objek yang saling bertumpukan, karena pengujian masih menggunakan latar belakang bersih.
6	Chandra Widi Wiguna, Joseph Dedy Irawan& Mira Orisa	Monitoring DPO dengan mentraining citra wajah dengan Convolutional Neural Network (CNN)	Metode Convolution Neural Network (CNN) dengan bantuan bantuan library OpenCV	Belum adanya pembahasan mengenai protokol keamanan untuk melindungi data biometrik yang disimpan dalam sistem.

Berdasarkan ringkasan di atas, teridentifikasi adanya ruang untuk pengembangan sistem yang terintegrasi secara fungsional. Mayoritas studi terdahulu masih memisahkan antara proses klasifikasi visual menggunakan Metode Convolutional Neural Network (CNN) dengan proses logika pengambilan keputusan menggunakan Metode Decision Tree (Pradana et al., 2023). Selain itu, implementasi penggabungan kedua pendekatan tersebut ke dalam platform SIG yang dikhususkan untuk kebutuhan internal pemerintah daerah masih terbatas (Huda et al., 2024). Pemisahan tersebut berimplikasi pada durasi evaluasi yang belum optimal karena data hasil klasifikasi tidak tersinkronisasi secara langsung dengan sistem penunjang keputusan.
Fokus utama pada Proyek Akhir ini adalah mengintegrasikan penggunaan CNN arsitektur ResNet-18 untuk klasifikasi kondisi fisik secara otomatis (Saparudin et al., 2025) dengan Metode Decision Tree untuk menetapkan skala prioritas pembangunan (Fatimah Ahmad et al., 2025). Guna mengatasi keterbatasan pada penelitian-penelitian sebelumnya, sistem ini mengimplementasikan parameter F1-Score dalam pengujian serta sinkronisasi data teknis DED untuk menjamin ketersediaan data lapangan yang valid. Seluruh proses tersebut divisualisasikan melalui SIG berbasis web sebagai instrumen pendukung keputusan di Disperkim Kota Banjarmasin secara akuntabel.

	Landasan Teori
Bagian ini menguraikan berbagai teori dan konsep yang menjadi dasar ilmiah dalam pengembangan sistem. Pembahasan dimulai dari kerangka kebijakan tata kelola digital hingga implementasi algoritma kecerdasan buatan untuk menunjang pengawasan infrastruktur secara objektif (Yuniarti et al., 2022). Landasan teori yang diintegrasikan dalam perancangan dan implementasi sistem mencakup beberapa poin utama sebagai berikut:

	E-Goverment
E-Government merupakan kerangka kerja penyelenggaraan pemerintahan berbasis teknologi informasi yang bertujuan untuk penguatan transparansi dan akuntabilitas kinerja birokrasi (Yuniarti et al., 2022). Implementasi ini difokuskan pada reduksi durasi pemrosesan data serta integrasi akses informasi melalui digitalisasi layanan (Haerul & Yamin, 2024).
Dalam Proyek Akhir ini, variabel E-Government diimplementasikan pada transformasi dokumen Detail Engineering Design (DED) dari format arsip statis menjadi data digital yang dapat diidentifikasi dan diolah secara sistematis. Penerapan ini bertujuan untuk mengakselerasi proses evaluasi teknis infrastruktur di Disperkim Kota Banjarmasin melalui mekanisme yang terukur dan terautomasi.

	Sistem Informasi Geografis (SIG)
Sistem Informasi Geografis (SIG) merupakan sistem berbasis komputer yang dirancang untuk menangkap, menyimpan, menganalisis, dan menyajikan data yang memiliki referensi geografis (Huda et al., 2024). Secara teknis, SIG berfungsi mensinkronisasikan data lokasi (spasial) dengan basis data atribut untuk menunjang proses pemantauan infrastruktur secara visual dan terukur (Pradana et al., 2023).
Dalam penelitian ini, variabel SIG diimplementasikan untuk memetakan data lapangan dan dokumen Detail Engineering Design (DED) pada wilayah Kota Banjarmasin. Fokus penerapan SIG dalam sistem ini meliputi:
	Sinkronisasi Data Spasial: Menentukan posisi geografis berupa garis lintang (latitude) dan garis bujur (longitude) objek infrastruktur secara presisi di atas peta digital Kota Banjarmasin (Huda et al., 2024).
	Pengolahan Data Atribut: Menghubungkan variabel kondisi fisik hasil klasifikasi CNN dengan parameter teknis DED ke dalam basis data yang dikelola secara sistematis (Dhiecho Mahar Dhiecha & Sukwadi, 2024).
	Transformasi Informasi: Mentransformasikan data tabular menjadi visualisasi spasial yang terstruktur guna mengakselerasi proses identifikasi sebaran kategori kerusakan infrastruktur secara aktual (Hasibuan & Sulaiman, 2022).
Penerapan SIG ini bertujuan untuk memastikan akurasi data pemetaan sehingga hasil penentuan prioritas pembangunan oleh instansi terkait menjadi lebih optimal dan tervalidasi (Pradana et al., 2023).

	Framework Laravel
Laravel merupakan framework berbasis PHP yang menerapkan pola arsitektur Model-View-Controller (MVC) untuk mengorganisir logika sistem secara sistematis (Yuniarti et al., 2022). Penerapan Laravel dalam penelitian ini difokuskan pada pengelolaan alur data teknis dan integrasi algoritma melalui variabel berikut:
	Arsitektur MVC: Memisahkan antara pengelolaan basis data DED (Model), antarmuka peta digital (View), dan pusat logika pemrosesan Metode CNN serta Metode Decision Tree (Controller) guna mengakselerasi durasi pengembangan dan pemeliharaan sistem (Yuniarti et al., 2022).
	Mekanisme Routing: Bertindak sebagai gerbang utama yang mensinkronisasikan setiap permintaan (request) internal menuju fungsi pengolahan data yang spesifik secara terukur (Yuniarti et al., 2022).
	Manajemen Logic Controller: Digunakan untuk mengintegrasikan proses klasifikasi citra dan aturan keputusan if-then ke dalam fungsi sistem yang dapat dieksekusi secara otomatis (Ratino at.al, 2023).
Penggunaan framework Laravel bertujuan untuk menjaga performansi sistem tetap optimal dalam menangani data infrastruktur yang kompleks, sehingga proses sinkronisasi antara data spasial dan data atribut terintegrasi secara presisi (Yuniarti et al., 2022).

	Database MySQL
MySQL merupakan Relational Database Management System (RDBMS) yang dirancang untuk mengelola serta menyimpan data secara terstruktur (Dhiecho Mahar Dhiecha & Sukwadi, 2024). Dalam penelitian ini, MySQL berperan sebagai media penyimpanan utama yang mensinkronisasikan variabel data spasial dan atribut melalui fungsi berikut:
	Penyimpanan Data Spasial: Menyimpan variabel koordinat geografis (latitude dan longitude) objek infrastruktur guna kebutuhan visualisasi presisi pada peta digital (Huda et al., 2024).
	Manajemen Data Atribut: Menampung parameter teknis dokumen DED serta hasil klasifikasi kondisi fisik (Baik/Rusak) dari Metode CNN (Pradana et al., 2023).
	Integrasi Data Sistem: Memungkinkan manipulasi data secara sistematis melalui koneksi logic controller pada Laravel guna menunjang pengambilan keputusan yang terakselerasi (Yuniarti et al., 2022).
Penggunaan MySQL bertujuan untuk menjaga integritas data sehingga proses penentuan prioritas pembangunan dilakukan berdasarkan basis data yang valid (Dhiecho Mahar Dhiecha & Sukwadi, 2024).

	Library Leaflet.js
Leaflet.js merupakan pustaka (library) JavaScript open-source yang digunakan untuk membangun visualisasi spasial interaktif (Huda et al., 2024). Dalam penelitian ini, Leaflet.js berfungsi sebagai komponen visualisasi utama dengan fokus pada variabel teknis berikut:
	Optimalisasi Layer Spasial: Menangani proses render lapisan peta (map layers) untuk mengakselerasi durasi pemuatan data geografis pada peramban web (Huda et al., 2024).
	Visualisasi Koordinat Dinamis: Menyajikan sebaran data koordinat (latitude dan longitude) infrastruktur permukiman guna menjaga akurasi penempatan objek pada peta digital secara presisi (Pradana et al., 2023).
	Penyajian Data Real-Time: Mentransformasikan hasil klasifikasi kondisi infrastruktur dari Metode CNN menjadi simbol grafis interaktif guna mengakselerasi proses identifikasi lokasi kategori kerusakan secara aktual (Pradana et al., 2023).
Penggunaan Leaflet.js bertujuan untuk menjaga performansi antarmuka peta tetap optimal dan aksesibel bagi internal instansi saat melakukan pemantauan infrastruktur permukiman di Kota Banjarmasin (Huda et al., 2024).

	Detail Engineering Design (DED)
Detail Engineering Design (DED) merupakan dokumen teknis komprehensif yang memuat rincian perencanaan, spesifikasi fisik, dan standar teknis pembangunan infrastruktur di kawasan permukiman (Dhiecho Mahar Dhiecha & Sukwadi, 2024). Dalam penelitian ini, dokumen DED dari Disperkim Kota Banjarmasin berfungsi sebagai variabel input primer melalui komponen berikut (Dhiecho Mahar Dhiecha & Sukwadi, 2024):
	Variabel Data Visual: Menyediakan citra dokumentasi infrastruktur permukiman yang digunakan sebagai input utama pada Metode CNN untuk mendeteksi dan mengklasifikasikan kategori kerusakan fisik secara otomatis
	Variabel Data Teknis: Memuat rincian dimensi dan material perencanaan yang diolah oleh Metode Decision Tree untuk memvalidasi hasil klasifikasi visual guna menetapkan skala prioritas pembangunan secara objektif.
	Sinkronisasi Parameter: Mengintegrasikan standar perencanaan dokumen DED untuk menjaga akurasi hasil analisis sistem dengan kondisi teknis yang direncanakan, sehingga meminimalisir tingkat subjektivitas dalam penilaian kondisi di lapangan

	Image Preprocessing
Image Preprocessing merupakan tahapan manipulasi data citra digital yang bertujuan untuk meningkatkan kualitas visual dan menyeragamkan format input sebelum dilakukan proses ekstraksi fitur oleh algoritma (Saparudin et al., 2025). Dalam penelitian ini, Image Preprocessing berperan sebagai jembatan teknis untuk memastikan data visual dari dokumen DED dapat diproses oleh Metode CNN secara optimal melalui tahapan berikut:
	Reizing: Melakukan standarisasi dimensi citra (seperti menjadi 224 x 224 piksel) agar sesuai dengan arsitektur input ResNet-18, sehingga penggunaan memori sistem tetap terukur (Saparudin et al., 2025).
	Normalization: Mentransformasikan nilai piksel ke dalam rentang distribusi tertentu guna menjaga stabilitas gradien dan mengakselerasi proses konvergensi saat pelatihan model berlangsung.
	Data Augmentation: Mengaplikasikan teknik rotasi dan pembalikan (flipping) pada citra asli untuk memperkaya variasi dataset secara sistematis guna meningkatkan akurasi validasi model (Pradana et al., 2023).
Penerapan Image Preprocessing bertujuan untuk menjamin bahwa luaran label kondisi (Baik/Rusak) yang akan diteruskan ke Metode Decision Tree memiliki tingkat presisi yang tinggi dan tervalidasi secara objektif.

	Convolutional Neural Network (CNN)
Convolutional Neural Network (CNN) merupakan algoritma Deep Learning yang dirancang untuk memproses input citra digital melalui ekstraksi fitur secara hierarkis (Pradana et al., 2023). Dalam penelitian ini, arsitektur ResNet-18 ditetapkan sebagai model final untuk mengidentifikasi karakteristik fisik infrastruktur permukiman melalui analisis pola piksel secara komprehensif (Saparudin et al., 2025).
Tahapan pemrosesan citra dari input hingga menghasilkan output label kondisi infrastruktur melalui arsitektur ResNet-18 dapat dilihat pada Gambar 2.1 berikut:
 
Gambar 2.1     Arsitektur Model ResNet-18
Berdasarkan Gambar 2.1, model memproses citra melalui blok konvolusi yang disertai dengan residual block untuk mengekstraksi fitur secara mendalam, serta diakhiri dengan Fully Connected Layer ($F_c$) untuk menetapkan klasifikasi akhir dari kondisi infrastruktur yang diamati.
	Arsitektur dan Komponen Utama
Struktur fungsional CNN disusun oleh beberapa lapisan yang bekerja secara hierarkis untuk mengekstraksi fitur visual, mulai dari pola dasar hingga karakteristik spesifik kerusakan infrastruktur (Pradana et al., 2023). Komponen utama dalam arsitektur ini meliputi:
	Convolutional Layer: Bertindak sebagai lapisan inti yang melakukan operasi konvolusi menggunakan filter untuk mengekstraksi fitur visual spesifik, seperti tekstur material dan pola keretakan pada citra infrastruktur permukiman (Saparudin et al., 2025).
	Pooling Layer: Berfungsi untuk menurunkan dimensi matriks fitur (downsampling) guna mengakselerasi durasi komputasi dan mengoptimalkan efisiensi memori tanpa menghilangkan informasi fitur yang krusial bagi akurasi klasifikasi (Miranda & Aryu, 2021).
	Fully Connected Layer: Berperan untuk menghubungkan seluruh fitur yang telah diekstraksi menjadi vektor probabilitas guna menetapkan klasifikasi akhir citra ke dalam label kondisi "Baik" atau "Rusak" (Saparudin et al., 2025).

	Prinsip Kerja
Mekanisme kerja Metode CNN dimulai dengan menerima input gambar dalam bentuk matriks nilai piksel. Proses pengolahan data dilakukan melalui tahapan berikut:
	Ekstraksi Fitur: Gambar melewati proses filtrasi pada lapisan konvolusi untuk menghasilkan feature map. Performansi model ini ditentukan oleh ekstraksi fitur spektral dan spasial yang dilakukan secara bertahap (Nurhidayat & Dewi, 2023).
	Proses Pelatihan (Training): Model mempelajari karakteristik fisik dari dataset gambar infrastruktur untuk mengenali pola kerusakan secara sistematis (Saparudin et al., 2025).
	Klasifikasi Otomatis: Berfungsi mengklasifikasikan data baru secara otomatis dengan tingkat akurasi yang tervalidasi berdasarkan hasil pembelajaran pola sebelumnya (Saparudin et al., 2025).
	Keterkaitan dengan Proyek Akhir
Metode CNN merupakan variabel utama dalam sistem ini yang berfungsi sebagai mesin analis untuk mengklasifikasikan kondisi fisik infrastruktur berdasarkan dokumen visual DED.
	Penerapan: Metode ini diimplementasikan pada tahap perancangan model cerdas untuk mentransformasikan data citra mentah dari Disperkim menjadi label data terstruktur, seperti kategori kondisi "Baik" atau "Rusak"  (Ratino at.al, 2023).
	Ruang Lingkup dan Integrasi: Batas penerapan Metode CNN dalam sistem ini difokuskan pada proses klasifikasi awal. Luaran (output) dari model ResNet-18 akan divalidasi menggunakan Confusion Matrix untuk menjaga reliabilitas hasil analisis sebelum data tersebut diteruskan sebagai variabel input bagi Metode Decision Tree (Nurhidayat & Dewi, 2023).

	Metode Decision Tree
Decision Tree merupakan Metode klasifikasi yang memetakan pola data ke dalam struktur hierarki berbentuk pohon keputusan. Algoritma ini bekerja dengan mentransformasikan data yang kompleks menjadi aturan logika terstruktur (if-then rules) yang dapat dipertanggungjawabkan secara teknis oleh pengambil kebijakan (Fatimah Ahmad et al., 2025).
Penerapan struktur logika ini diintegrasikan ke dalam framework Laravel untuk mengoptimalkan pengelolaan fungsi pada sisi controller (Ratino at.al, 2023). elain itu, penggunaan Metode ini menunjang evaluasi kinerja sistem yang terukur melalui perbandingan hasil prediksi dengan data aktual (Nurhidayat & Dewi, 2023). Penentuan variabel dalam pohon keputusan ini merujuk pada parameter teknis dalam dokumen perencanaan infrastruktur guna menjaga ketepatan hasil prioritas pembangunan (Dhiecho Mahar Dhiecha & Sukwadi, 2024).
 
Gambar 2.2     Struktur Hierarki Decision Tree

	Komponen dan Ruang Lingkup
Berdasarkan struktur hierarki pada Gambar 2.2, Metode Decision Tree bekerja melalui koordinasi antar elemen yang merepresentasikan tahapan pengambilan keputusan secara sistematis (Fatimah Ahmad et al., 2025). Komponen-komponen utama tersebut meliputi:
	Root Node (Akar): Bagian teratas yang mewakili atribut dengan pengaruh paling signifikan. Pemilihan root node didasarkan pada perhitungan untuk meminimalisir ketidakpastian data sehingga proses klasifikasi menjadi lebih presisi (Nurhidayat & Dewi, 2023).
	Internal Node (Cabang): Titik percabangan yang merepresentasikan proses pengujian terhadap parameter tertentu. Dalam sistem ini, internal node berfungsi untuk menguji kriteria teknis dari dokumen DED guna menentukan kelayakan infrastruktur (Dhiecho Mahar Dhiecha & Sukwadi, 2024).
	Leaf Node (Daun): Titik akhir yang merepresentasikan hasil keputusan final. Komponen ini memberikan keluaran berupa label prioritas pembangunan, seperti "Baik", "Sedang", atau "Berat"  (Fatimah Ahmad et al., 2025).



	Mekanisme Kerja
Mekanisme kerja Metode Decision Tree didasarkan pada proses pemecahan masalah secara hierarkis melalui evaluasi variabel input untuk menemukan atribut pemisah terbaik (Fatimah Ahmad et al., 2025). Secara teknis, tahapan mekanisme kerja algoritma ini adalah sebagai berikut:
	Evaluasi Variabel: Mengevaluasi seluruh atribut input menggunakan kriteria seperti Information Gain atau Gini Index untuk mengidentifikasi pola kondisi wilayah berdasarkan variabel pendukungnya (Fatimah Ahmad et al., 2025).
	Pemrosesan Rekursif: Membagi data ke dalam sub-grup secara berulang hingga mencapai tingkat akurasi tertentu. Hasil prediksi ini divalidasi menggunakan Confusion Matrix untuk menjamin kesesuaian dengan data aktual di lapangan (Nurhidayat & Dewi, 2023).
	Implementasi Logika Bisnis: Struktur aturan if-then yang dihasilkan digunakan untuk memetakan fungsi-fungsi sistem ke dalam logic controller pada framework Laravel (Ratino at.al, 2023).
	Integrasi Data Teknis: Memproses variabel dari data teknis DED sehingga output yang dihasilkan tetap memenuhi standar operasional dan keamanan infrastruktur (Dhiecho Mahar Dhiecha & Sukwadi, 2024).

	Hubungan Terhadap Solusi Proyek Akhir
Dalam Proyek Akhir ini, Decision Tree berperan sebagai dasar perancangan modul pendukung keputusan untuk menentukan skala prioritas pembangunan.
	Dukungan Metode: Digunakan untuk mengolah data hasil klasifikasi CNN (kondisi fisik) yang dikombinasikan dengan variabel teknis dari dokumen DED (Dhiecho Mahar Dhiecha & Sukwadi, 2024).
	Perancang Modul: Menjadi logika utama dalam modul "Penentuan Prioritas". Implementasi aturan keputusan ke dalam Laravel memudahkan manajemen kontrol pada sisi back-end agar rekomendasi bersifat objektif  (Ratino at.al, 2023).
	Dasar Evaluasi: Memberikan kategori prioritas secara otomatis. Keandalan hasil keputusan ini divalidasi menggunakan Confusion Matrix guna memastikan performansi model sebelum diimplementasikan (Nurhidayat & Dewi, 2023).
Kontribusi utama dari Metode Decision Tree dalam Proyek Akhir ini adalah sebagai kerangka kerja sistematis untuk menjamin alokasi penanganan infrastruktur permukiman di Disperkim Kota Banjarmasin berjalan selaras dengan kebutuhan teknis di lapangan. Melalui implementasi alur logika yang terstruktur, hasil penentuan skala prioritas dapat dipertanggungjawabkan secara ilmiah guna meminimalisir subjektivitas dalam proses pengambilan keputusan (Fatimah Ahmad et al., 2025).

	Waterfall 
Metode Waterfall merupakan model pengembangan perangkat lunak sekuensial yang menekankan pada alur kerja yang sistematis, mulai dari tahap analisis kebutuhan hingga tahap pemeliharaan sistem. Implementasi Metode ini dalam pembangunan sistem informasi berbasis web terbukti efektif dalam menjaga konsistensi antara desain awal dengan luaran sistem yang dihasilkan  (Setiaji et al., 2023). Dalam penelitian ini, Metode Waterfall dipilih karena sifatnya yang terstruktur, sehingga memungkinkan setiap variabel teknis seperti integrasi model ResNet-18 dan Decision Tree dapat diimplementasikan secara bertahap dan terukur.
 
Gambar 2.3     Struktur Waterfall

	Tahapan Pengembangan Sistem
Tahapan pengembangan sistem dalam penelitian ini dilakukan melalui enam siklus terintegrasi sebagai berikut:
	Analisis Kebutuhan (Requirements Analysis): Tahap awal dilakukan untuk mengidentifikasi variabel kebutuhan data yang mencakup dokumen DED dan variabel spasial infrastruktur di internal Disperkim. Proses identifikasi data primer ini dilakukan untuk memastikan kesiapan dataset sebelum diproses oleh algoritma klasifikasi guna meminimalisir kesalahan input (Saparudin et al., 2025).
	Perancangan Sistem (System Planning): Menyusun alur logika kerja sistem secara makro, termasuk pemodelan alur integrasi antara Metode CNN untuk klasifikasi citra dengan Metode Decision Tree untuk penentuan prioritas pembangunan yang objektif dan sesuai parameter teknis yang ditetapkan.
	Desain Sistem (System Design): Merancang arsitektur teknis yang mencakup skema basis data MySQL, antarmuka pengguna (UI/UX), serta pemetaan koordinat menggunakan pustaka Leaflet.js guna menjaga reliabilitas operasional sistem saat diakses oleh pihak internal (Ratino at.al, 2023).
	Implementasi (Implementation): Mentransformasikan rancangan ke dalam bahasa pemrograman PHP (framework Laravel) dan JavaScript. Pada tahap ini, arsitektur ResNet-18 dan aturan logika if-then diintegrasikan ke dalam sistem untuk memproses data secara otomatis sesuai dengan spesifikasi yang telah dirancang (Pradana et al., 2023).
	Pengujian (Testing): Melakukan validasi terhadap fungsionalitas fitur menggunakan Metode Blackbox Testing serta menguji performansi model menggunakan Confusion Matrix guna mengukur tingkat kesesuaian antara prediksi sistem dengan data aktual di lapangan (Nurhidayat & Dewi, 2023).
	Penerapan & Pemeliharaan (Deployment & Maintenance): Tahap ini mencakup instalasi sistem pada lingkungan infrastruktur internal Disperkim (Deployment) serta pemantauan berkala untuk memastikan hasil penentuan skala prioritas tetap akurat dan selaras dengan kebutuhan teknis instansi (Fatimah Ahmad et al., 2025).
Secara keseluruhan, penerapan keenam tahapan dalam Metode Waterfall ini dilakukan agar proses pembangunan sistem berjalan secara terstruktur dan terukur. Alur yang linier memastikan bahwa setiap fase, mulai dari identifikasi data hingga operasional di internal Disperkim, dapat terdokumentasi secara sistematis sehingga meminimalisir adanya deviasi antara spesifikasi teknis dengan luaran sistem yang dihasilkan (Setiaji et al., 2023). Hal ini mendukung integrasi kecerdasan buatan dalam penentuan skala prioritas pembangunan agar memiliki reliabilitas yang tinggi dan dapat divalidasi secara objektif sesuai dengan parameter teknis yang telah ditetapkan (Nurhidayat & Dewi, 2023).

	Confusion Matrix
Confusion Matrix merupakan tabel evaluasi yang digunakan untuk memetakan jumlah data uji yang diklasifikasikan secara tepat maupun tidak tepat oleh sistem guna mengukur performansi klasifikasi secara mendalam (Nurhidayat & Dewi, 2023). Dalam penelitian ini, Confusion Matrix berfungsi untuk memvalidasi sejauh mana model mampu mengenali variabel kondisi fisik infrastruktur secara akurat berdasarkan data primer dari Disperkim Kota Banjarmasin (Saparudin et al., 2025).
 Struktur perbandingan variabel tersebut disajikan dalam bentuk matriks 2x2 pada Tabel 2.3 berikut:

Gambar 2.4     Confusion Matrix
Aktual Positif	True Positive (TP)	False Negative (FN)
Aktual Negatif	False Positive (FP)	True Negative (TN)

Berdasarkan Tabel 2.3, kinerja model dievaluasi melalui empat parameter yang merepresentasikan kondisi infrastruktur di lapangan (Saparudin et al., 2025):
	True Positive (TP): Kondisi di mana sistem berhasil memprediksi infrastruktur berkondisi Rusak secara sesuai dengan data aktual.
	True Negative (TN): Kondisi di mana sistem melakukan prediksi infrastruktur berkondisi Baik secara tepat sesuai dengan data aktual.
	False Positive (FP): Kesalahan sistem yang mengidentifikasi infrastruktur kondisi Baik sebagai kondisi Rusak.
	False Negative (FN): Kesalahan sistem yang mengidentifikasi infrastruktur kondisi Rusak sebagai kondisi Baik.
Data yang dihasilkan dari variabel di atas kemudian diolah menggunakan rumusan matematis untuk memperoleh nilai performansi sistem yang objektif sebagai berikut (Nurhidayat & Dewi, 2023):
	Akurasi (Accuracy)
Merepresentasikan persentase ketepatan total model dalam mengklasifikasikan seluruh kondisi infrastruktur secara benar seperti dirumuskan pada Persamaan (2.1) berikut:

Accuracy =(TP + TN)/(TP + TN + FP + FN)× 100%	(2.1)


	Presisi (Precision)
Menunjukkan tingkat ketepatan antara data yang diprediksi rusak oleh sistem dengan jumlah data yang memang benar-benar rusak secara aktual, yang dirumuskan pada Persamaan (2.2) berikut:

Precision =TP/(TP + FP)× 100%	(2.2)

	Recall
Mengukur kemampuan model dalam menemukan kembali seluruh informasi infrastruktur yang rusak dari total keseluruhan data aktual yang tersedia, yang dirumuskan pada Persamaan (2.3) berikut:

Recall =TP/(TP + FN)×100%	(2.3)

	F1-Score
Merupakan rata-rata harmonik dari presisi dan recall yang digunakan untuk memberikan keseimbangan nilai performansi model, terutama apabila terdapat ketidakseimbangan distribusi data pada kelas aktual, yang dirumuskan pada Persamaan (2.4) berikut:

F1-Score = ×2 (Precision * Recall)/(Precision + Recall)	(2.4)


Penerapan tabel dan rumusan evaluasi ini bertujuan untuk memvalidasi performansi algoritma sebelum data diproses lebih lanjut oleh Metode Decision Tree, sehingga mampu meminimalisir potensi subjektivitas dalam pengambilan keputusan teknis pembangunan (Pradana et al., 2023).

	Pengujian Blackbox Testing
Blackbox testing merupakan Metode pengujian perangkat lunak yang berfokus pada fungsionalitas aplikasi tanpa melibatkan analisis terhadap struktur internal atau kode programnya (Pratama et al., 2023). Pengujian ini bertujuan untuk memvalidasi bahwa setiap fungsi dalam sistem, mulai dari proses input data citra hingga luaran informasi prioritas, beroperasi sesuai dengan spesifikasi kebutuhan teknis yang telah ditetapkan.
Terdapat beberapa teknik yang umum digunakan dalam pengujian blackbox untuk menjamin kualitas fungsionalitas sistem, antara lain (Yuniarti et al., 2022):
	Equivalence Partitioning: Teknik yang membagi domain input ke dalam kelas-kelas data untuk menentukan skenario uji yang representatif, sehingga pengujian menjadi lebih sistematis dan terarah (Pratama et al., 2023).
	Boundary Value Analysis: Teknik yang berfokus pada pengujian nilai ambang batas (minimum dan maksimum) untuk mengidentifikasi potensi kesalahan pada limitasi input data teknis infrastruktur (Yuniarti et al., 2022).
	Decision Table Testing: Teknik pengujian yang didasarkan pada kombinasi input logis. Teknik ini digunakan untuk memvalidasi alur Metode Decision Tree yang diimplementasikan dalam sistem guna memastikan akurasi penetapan label prioritas secara objektif (Pratama et al., 2023).
	Interface Testing: Pengujian yang dilakukan untuk menjamin bahwa fungsi navigasi dan interaksi antar antarmuka pada framework Laravel berjalan dengan stabil serta meminimalisir kendala aksesibilitas bagi petugas internal instansi (Ratino at.al, 2023).

	GTMetrix
GTMetrix merupakan instrumen analisis performansi berbasis web yang digunakan untuk menguji, mengukur, dan mengevaluasi kecepatan pemuatan halaman (page load speed) serta tingkat optimasi arsitektur sebuah platform digital. Pengujian menggunakan platform GTMetrix memberikan parameter penilaian objektif terhadap efisiensi retensi memori dan rendering struktur kode web melalui beberapa metrik standar industri seperti Largest Contentful Paint (LCP), First Input Delay (FID), dan Cumulative Layout Shift (CLS). Dalam konteks pengembangan Sistem Informasi Geografis (SIG) GEO-SINFRA, platform ini memproses visualisasi spasial interaktif serta pemetaan klaster batas wilayah menggunakan pustaka Leaflet.js yang membutuhkan konsumsi memori browser cukup besar. Oleh karena itu, penerapan GTMetrix sebagai instrumen uji dalam Proyek Akhir ini difokuskan untuk mengaudit performa antarmuka pada sisi pengguna (front-end efficiency) guna memastikan bahwa eksekutif WebGIS dan modul rekomendasi AI dapat diakses secara cepat, stabil, dan responsif oleh aparatur Dinas Perumahan Rakyat dan Kawasan Permukiman (DPRKP) Kota Banjarmasin saat dioperasikan di lingkungan kerja kedinasan. (Rospricilia & Ma’ady, 2024)

	System Usability Scale (SUS)
System Usability Scale (SUS) merupakan Metode evaluasi usability yang digunakan untuk menilai kelayakan dan pengalaman pengguna terhadap sebuah sistem secara objektif (Pratama et al., 2023). Metode ini dipilih karena reliabilitasnya dalam memberikan penilaian terukur terhadap aspek kepuasan, kompleksitas, dan reliabilitas operasional guna memastikan bahwa antarmuka Sistem Informasi Geografis (SIG) yang dibangun dapat dioperasikan secara optimal oleh petugas internal instansi.
Penilaian SUS berfokus pada variabel aksesibilitas dan fungsionalitas interaksi sistem melalui 10 butir pernyataan standar. Untuk memperoleh nilai akhir yang valid, hasil jawaban kuesioner diolah menggunakan rumusan perhitungan SUS yang ditampilkan pada Persamaan (2.5) berikut (Pratama et al., 2023):
Skor SUS = ∑ (Skor Ganjil + Skor Genap) × 2,5	(2.5)

Keterangan:
	Skor Ganjil: Diperoleh dari nilai jawaban pada butir pertanyaan bernomor ganjil (1, 3, 5, 7, dan 9) yang dikurangi dengan 1 (Skor = X - 1).
	Skor Genap: Diperoleh dari nilai 5 yang dikurangi dengan nilai jawaban pada butir pertanyaan bernomor genap (2, 4, 6, 8, dan 10) (Skor = 5 - X).
	Nilai Akhir: Merupakan hasil penjumlahan seluruh skor dari ke-10 butir pertanyaan yang kemudian dikalikan dengan 2,5 untuk memperoleh skor akhir SUS pada rentang nilai 0–100.
Skor akhir SUS yang diperoleh kemudian dievaluasi berdasarkan kriteria interpretasi kelayakan sistem (seperti kategori Acceptability Range, Grade Scale, maupun Adjective Rating) guna memvalidasi tingkat penerimaan pengguna terhadap Sistem Informasi Geografis pemetaan infrastruktur permukiman, sehingga mampu meminimalisir kendala teknis saat proses pendataan dilakukan secara operasional (Pratama et al., 2023).

	Python
Python adalah bahasa pemrograman yang mudah digunakan untuk melakukan pengolahan dan penyajian data di era teknologi modern. Python ini merupakan bahasa pemrograman tingkat tinggi yang dibuat oleh Guido van Rossum yang mana berorientasi pada objek. Python adalah bahasa multifungsi, kaya akan fitur, dan dapat dijelaskan secara maksimal serta tersusun rapi. Seringkali, kumpulan data yang besar dan kompleks dapat disederhanakan dengan menggunakan bahasa pemrograman berorientasi objek ini. Karena komunitas Python yang besar dan aktif, banyak sumber daya, tutorial, dan dukungan yang tersedia. Jika menghadapi masalah atau kesulitan dengan analisis data, dapat dengan mudah menemukan bantuan. Python mudah diintegrasikan dengan banyak platform dan perangkat lunak. Data analis dapat bekerja dengan data dari berbagai sumber dan mengintegrasikannya ke dalam alur kerja mereka berkat hal ini. Karena popularitasnya yang tinggi dalam bidang pembelajaran mesin dan kecerdasan buatan, data analis dapat dengan mudah membuat model prediktif dan algoritma cerdas. (Putri et al., 2023)

	Use Case
Use case adalah suatu kontrak interaksi antara actor dan system. Use case dapat digunakan untuk melakukan klasifikasi dan identifikasi proses-proses sebagai komponen dari aplikasi yang akan dikembangkan. Proses klasifikasi dan identifikasi proses-proses sebagai komponen dari aplikasi yang akan dikembangkan merupakan proses dalam melakukan user requirement. (Rospricilia & Ma’ady, 2024)

	HTML
HTML merupakan bahasa yang tidak case sensitive tidak seperti bahasa pemrograman server-side seperti PHP atau ASP. Contohnya untuk menebalkan dokumen bisa menggunakan tag atau dengan huruf capital , HTML dalam hal ini bisa memakluminya. HTML merupakan pengembangan dari standar pemformatan dokumen teks, yaituSGML (Standar Generalized Markup Language). Pada dasarnya dokumen HTML memiliki tiga tag utama, yaitu HTML , HEAD, dan BODY. Tag HTML digunakan untuk menyatakan suatu dokumen HTML, tag HEAD untuk menyimpan atau menampilkan informasi tertentu dan tag BODY digunakan untuk menampilkan sisi dokumen HTML yang akan ditampilkan dalam suatu web browser yang digunakan oleh user untuk mengakses informasi. (Rahmawan et al., 2023)

	CSS
CSS (Cascading Style Sheet) adalah salah satu bahasa desain web (style sheet language) yang mengontrol format tampilan sebuah halaman web yang ditulis dengan menggunakan penanda(markup laguage. Biasanya CSS digunakan untuk mendesain sebuah halaman HTML dan XHTML, tetapi sekarang CSS bisa diaplikasikan untuk segala dokumen XML, termasuk SVG dan XUL bahkan ANDROID. CSS dibuat untuk memisahkan konten utama dengan tampilan dokumen yang meliputi layout, warna dan font. Pemisahan ini dapat meningkatkann daya akses konten pada web, menyediakan lebih banyak fleksibilitas dan kontrol dalam spesifikasi darisebuah karakteristik dari sebuah tampilan, memungkinkan untuk membagi halaman untuk sebuah formatting dan mengurangi kerumitan dalam penulisan kode dan struktur dari konten, contohnya teknik tableless pada desain web. (Irawan & Novianto, 2020)

	Java Script
JavaScript merupakan bahasa pemrograman yang berbentuk kumpulan skrip yang memiliki fungsi untuk memberikan tampilan agar tampak lebih interaktif pada dokumen web. Pengertian di atas dapat disimpulkan bahwa Javascript adalah bahasa pemrograman untuk memberikan kemampuan tambahan ke dalam bahasa pemrograman HTML atau juga digunakan untuk menjelaskan tampilan dalam halaman website (Amarta Sholehuddin et al., 2021).

	PHP
PHP (Hypertext Preprocessor) merupakan skrip yang bersifat server site dimana proses pengerjaan skripnya berlangsung di server. Dengan menggunkan PHP maka perawatan suatu situs Web akan menjadi lebih mudah. PHP pertama kali ditemukan oleh Rasmus Lerdoff. Penulisan skrip PHP tersebut dengan cara disisipkan pada HTML. PHP merupakan bahasa pemograman yang digunakan untuk membangun aplikasi-aplikasi berbasis Web khususnya aplikasi Web yang bersifat dinamis. (Irawan & Novianto, 2020)

	Tailwind CSS
Tailwind CSS, yakni framework CSS dengan pendekatan desain fleksibel dan responsif.  Tailwind  memungkinkan pengembang  menulis  style  secara  efisien  tanpa  harus  membuat  aturan  CSS dari  nol,  sekaligus  memberikan  kebebasan  dalam  mendesain  sesuai  kebutuhan  tanpa  terikat  gaya  bawaan framework  lain.  Selainitu,  Tailwind  juga  memudahkan  pembuatan  komponen  antarmuka  pengguna  (UI),  dan dengan dukungan library tambahan seperti Flowbite, penggunaannya semakin efektif untuk membangun tampilan website yang interaktif, modern, dan cepat. (Benz & Testiana, 2025)

	API
Restful  API  merupakan  arsitektur  untuk penerapan   web service dalam   menerapkan konsep peralihan antar negara. Negara disini dapat diilustrasikan   sebagai   peramban   yang meminta  halaman  web,  pada  sisi server akan mengirimkan keadaan halaman web saat ini ke peramban.  Dengan  REST  API  memungkinkan berbagai sistem untuk dapat berkomunikasi dan mengirim atau menerima data dengan cara yang cukup sederhana. Di dalam    RESTful    API terdapat  REST client yang  dapat  mengakses data  atau resource pada  REST server dimana setiap resource akan  dibedakan  berdasarkan dari global  ID  atau  URI  (Universal  Resource Identifiers).  Hal  ini  membuat  RESTful  API sangat  cocok  diterapkan  pada  aplikasi  yang terintegrasi dengan ponsel pintar. (Hasanuddin et al., 2022)

	Fonnte API
Fonnte merupakan platform penyedia layanan Application Programming Interface (API) Gateway WhatsApp berbasis cloud yang berfungsi sebagai jembatan komunikasi otomatis antara sistem aplikasi dengan pengguna. Di dalam pengembangan sistem informasi, integrasi API ini bertugas mentransformasikan data digital menjadi pesan notifikasi otomatis (automated reminder) yang dikirim secara real-time melalui metode HTTP POST menggunakan parameter token akses, nomor target, dan isi pesan.
Dalam implementasinya, integrasi ini memanfaatkan keunggulan WhatsApp yang memiliki tingkat keterbacaan (open-rate) jauh lebih tinggi dibandingkan SMS atau email. Berdasarkan pengujian empiris, Fonnte API memiliki performa pengiriman yang andal dengan rata-rata latency (Round-Trip Time) sebesar 265 ms dan response time server sebesar 62 ms. Dengan rata-rata efektivitas keseluruhan di angka 163,5 ms, teknologi ini masuk dalam kategori Cukup Efektif (di bawah standar ≤ 299 ms) untuk mendukung otomatisasi pengiriman pesan peringatan operasional secara instan. (Ignasius Mario Bele Waton et al., 2025)

	Open Street Map
OpenStreetMap (OSM) merupakan platform pemetaan berbasis internet yang mengusung konsep pemetaan partisipatif/kolaboratif atau dikenal pula dengan istilah crowdmapping, dimana peta dibuat oleh banyak orang secara bersama-sama, namun tetap dengan kontrol kualitas yang baik. Pengguna OSM sendiri terdiri dari berbagai kalangan, baik dengan maupun tanpa latar belakang pemetaan. Meskipun demikian, data OSM memiliki kualitas cukup baik dan telah banyak menjadi alternatif sumber data spasial. (Nurrohmah & Sulistioningrum, 2019)


	Laragon
Laragon adalah sebuah alat lunak sumber terbuka yang dapat digunakan di berbagai sistem operasi. Fungsinya sebagai server virtual atau localhost yang dapat mendukung banyak sistem operasi. Laragon memungkinkan pengguna untuk menggunakan domain sesuai keinginan mereka. Aplikasi ini sangat berguna dalam pengelolaan aplikasi berbasis web. (Angelina & Phonna, 2023)

	PhpMyAdmin
PhpMyAdmin adalah perangkat lunak gratis (freeware) yang ditulis menggunakan bahasa pemrograman dimaksudkan untuk PHP, yang menangani administrasi database MySQL melalui interface Web. PhpMyAdmin mendukung berbagai operasi di database MySQL dan MariaDB. Operasi paling yang sering digunakan seperti mengelola database, tabel, kolom, relasi, indeks, pengguna, izin, dan lainnya, Dapat kita lakukan melalui antarmuka pengguna, sementara itu kita juga masih bisa menulis perintah SQL secara langsung untuk operasi pengelolaan database nya. (Ery Hartati, 2022)

	Visual Studio Code
Visual Studio Code adalah kode editor sumber yang dikembangkan oleh Microsoft untuk Windows, Linux, dan macOS. Visual Studio Code memudahkan dalam penulisan kode yang mendukung beberapa jenis bahasa pemrograman yang digunakan dan memberi variasi warna sesuai dengan fungsi dalam rangkaian kode tersebut. Selain itu, fitur lainnya adalah kemampuan untuk menambah ekstensi di mana para pengembang dapat menambah ekstensi untuk menambah fitur yang tidak ada di Visual Studio Code. Visual Studio Code bersifat open source, yaitu aplikasi dengan source code yang dapat dilihat oleh siapa pun untuk berkontribusi pada pengembangan aplikasi tersebut. Kode juga dapat dilihat melalui link GitHub, menjadikan aplikasi Visual Studio Code memiliki banyak penggemar dalam mengembangkan aplikasi ke depannya. (Firnando et al., n.d.)

	Github
Platform GitHub hadir sebagai layanan hosting repositori berbasis cloud yang menyediakan antarmuka pengguna grafis dan fitur kolaborasi sosial untuk para pengembang. GitHub tidak hanya berfungsi sebagai tempat penyimpanan kode, tetapi juga menyediakan fitur pelacakan masalah (issue tracking) dan pull request yang memfasilitasi review kode antar anggota tim. Lebih jauh lagi, dalam konteks publikasi web, GitHub menawarkan fitur GitHub Pages. Fitur ini memungkinkan repositori kode diubah langsung menjadi website statis yang dapat diakses publik melalui protokol HTTPS. Pendekatan ini menawarkan paradigma baru dalam proses deployment yang lebih efisien dibandingkan metode konvensional menggunakan File Transfer Protocol (FTP). (Santoso, 2023)

	Overpass Turbo
Overpass Turbo adalah kemampuannya  untuk memfilter dan menampilkan data dengan cepat serta mendukung  ekspor  dalam  format  populer  seperti GeoJSON   atau   KML   untuk   digunakan   dalam platform SIG lainnya, seperti QSIGatau Leaflet.js. Penggunaan  Overpass  Turbo  sebagai  alat  bantu dalam  pemetaan  spasial  sangat  membantu  dalam menyeleksi data yang relevan dari OSM, khususnya untuk  aplikasi  seperti  pemetaan  fasilitas  umum, infrastruktur, dan rencana tata ruang berbasis digital. (Nurrohmah & Sulistioningrum, 2019)