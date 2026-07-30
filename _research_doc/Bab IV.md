BAB IV
HASIL DAN PEMBAHASAN

Bab ini menguraikan secara komprehensif hasil implementasi dari metodologi yang telah dirancang pada bab sebelumnya. Pembahasan dimulai dari tahapan fundamental pengumpulan data fisik infrastruktur di lapangan, yang kemudian diproses menggunakan kecerdasan buatan berbasis *Convolutional Neural Network* (CNN) dan *Decision Tree*. Setelah model berhasil mengenali pola kerusakan, hasil analisis tersebut diintegrasikan ke dalam Sistem Informasi Geografis (SIG) GEO-SINFRA guna memfasilitasi visualisasi spasial dan pengambilan keputusan operasional. Rangkaian proses ini diakhiri dengan evaluasi mendalam melalui berbagai instrumen pengujian untuk mengukur keandalan fungsional, performa arsitektur, objektivitas kecerdasan buatan, hingga tingkat penerimaan (*usability*) dari pengguna akhir.

4.1	Pengumpulan Data
Tahapan pengumpulan data merupakan fase fondasional yang paling krusial dalam membangun ekosistem kecerdasan buatan. Sebaik apa pun algoritma *Machine Learning* yang dirancang, akurasi prediksinya akan sangat bergantung pada kualitas dan kuantitas data yang dipelajari. Dalam konteks Sistem Informasi Geografis (SIG) GEO-SINFRA, pengumpulan data tidak hanya sekadar menghimpun angka, melainkan memetakan realitas kondisi fisik infrastruktur permukiman di Kota Banjarmasin secara visual dan geospasial. Data lapangan yang bersifat visual (citra kerusakan) dan tabular (parameter teknis) ini kemudian menjadi landasan pembelajaran bagi Metode *Convolutional Neural Network* (CNN) dan *Decision Tree*.

4.1.1	Metode Pengumpulan Data
Proses pengumpulan data dilakukan melalui dua pendekatan utama untuk menjamin validitas parameter yang digunakan:
a. Survei Primer (Observasi Lapangan): Dilakukan dengan mendokumentasikan citra fisik infrastruktur menggunakan metode *geo-tagging*. Setiap foto yang diambil oleh surveyor di lapangan secara otomatis merekam titik koordinat geografis (*latitude* dan *longitude*) untuk memastikan akurasi pemetaan spasial.

b. Survei Sekunder (Studi Dokumentasi): Memanfaatkan dokumen asli *Detail Engineering Design* (DED) dan rekapitulasi aset yang diperoleh secara langsung dari Dinas Perumahan Rakyat dan Kawasan Permukiman (DPRKP) Kota Banjarmasin. Data sekunder ini krusial untuk melengkapi parameter spesifikasi teknis dan rekam jejak historis yang tidak bisa ditebak hanya melalui pandangan visual foto di lapangan. Informasi asli dari instansi ini mencakup rincian dimensi objek (panjang dan lebar), klasifikasi jenis material penyusun (seperti aspal, beton, paving, atau kayu ulin), tahun terakhir rehabilitasi (umur infrastruktur), hingga data ketersediaan utilitas pendukung seperti saluran drainase.

4.1.2	Rincian dan Karakteristik Dataset
Pada awalnya, dokumen DED dan berkas survei instansi mencatat total sebanyak 698 entri instrumen data mentah. Setelah melalui tahapan seleksi data, validasi kelengkapan koordinat, serta penyaringan anomali (data cleaning), diperoleh 689 instance data final yang valid dan digunakan secara integratif di dalam Sistem Informasi Geografis (SIG) aplikasi GEO-SINFRA.
Sesuai dengan rancangan fungsionalitas pemetaan pada antarmuka sistem, objek infrastruktur permukiman di Kota Banjarmasin diklasifikasikan secara spesifik ke dalam 3 (tiga) kategori utama, yaitu:
	Jalan: Infrastruktur perkerasan darat (seperti cor-beton, aspal, paving, maupun tanah pemadatan) yang memfasilitasi mobilitas warga di kawasan permukiman darat.
	Titian: Infrastruktur akses berupa jalan panggung bermaterial kayu ulin atau beton yang dibangun di atas lahan basah atau bantaran sungai, yang menjadi karakteristik geografis khas Banjarmasin.
	Jembatan: Infrastruktur penghubung berbahan dasar beton atau kayu yang melintasi aliran sungai atau saluran drainase utama untuk menghubungkan antar wilayah permukiman.
Rincian distribusi dari ketiga objek infrastruktur utama yang diinput ke dalam pangkalan data (database) sistem disajikan pada Tabel 4.1 berikut:

Tabel 4.1    Rincian Distribusi Kategori Objek Infrastruktur
NO	Kategori Infrastruktur	Jumlah Data
1	Jalan	473
2	Titian 	166
3	Jembatan	50
TOTAL	689

Dari total 689 data infrastruktur yang dikelola untuk visualisasi SIG tersebut, setiap instance data diberikan pelabelan berdasarkan kondisi fisik di lapangan ke dalam 3 (tiga) kategori tingkat kerusakan, yaitu:
	Baik: Infrastruktur dalam kondisi layak pakai tanpa ada kerusakan struktural yang mengganggu mobilitas. Permukaan masih utuh dan aman digunakan secara normal.
	Rusak Sedang: Terdapat kerusakan parsial yang mulai terlihat (seperti jalan berlubang kecil, retakan, atau material titian/jembatan yang mulai aus), namun masih dapat dilalui atau digunakan dengan kehati-hatian tanpa memerlukan perbaikan darurat.
	Rusak Berat: Infrastruktur mengalami kerusakan struktural yang parah (seperti aspal ambles, patahan pada penyangga jembatan, atau titian yang lapuk/patah) sehingga tidak aman untuk digunakan dan membutuhkan rekonstruksi segera. 
Sesuai dengan skema pengujian performansi model kecerdasan buatan, total dataset sebanyak 689 data ini kemudian dipecah (data splitting) ke dalam dua kelompok dengan proporsi seimbang 80:20, yaitu: 
	Data Latih (Training Set): Sebesar 80% (551 data) digunakan untuk melatih model CNN dalam mengekstraksi fitur visual dan melatih Decision Tree dalam membentuk aturan keputusan.
	Data Uji (Testing Set): Sebesar 20% (138 data) disisihkan murni untuk proses evaluasi performa (Confusion Matrix) guna menguji objektivitas prediksi sistem terhadap data baru.

4.1.3	Fitur dan Atribut Data
Berdasarkan parameter dari dokumen DED dan analisis spasial, ditetapkan 6 (enam) fitur atribut utama. Atribut ini bertindak sebagai variabel independen yang akan diproses oleh Metode Decision Tree untuk menentukan prioritas:
	Koordinat Lokasi: Nilai Presisi Latitude dan Longitude yang sangat krusial untuk titik pemetaan ke dalam SIG.
	Elevasi Tanah (mdpl): Ketinggian daratan dari permukaan laut, yang krusial mengingat topografi lahan basah Banjarmasin.
	Jarak dari sungai (meter): Mengukur kerentanan infrastruktur terhadap pasang surut air sungai (rob).
	Material Utama: Kategori bahan baku infrastruktur (Aspal, Paving Block, Beton, atau Kayu Ulin).
	Kepadatan Penduduk (Jiwa/km2): Intensitas penggunaan infrastruktur oleh warga sekitar.
	Umur Infrastruktur (Tahun): Masa pakai sejak pembangunan atau rehabilitasi terakhir.

4.1.4	Pelabelan Kelas (Data Labeling)
Proses anotasi (pelabelan) dilakukan secara manual oleh tim teknis sebagai ground truth (data aktual) sebelum masuk ke tahap pre-processing. Pelabelan ini dibagi menjadi dua kategori keluaran (variabel dependen): 
	Label Infrastruktur (3 Kelas): Mengklasifikasikan jenis objek, yaitu Jalan, Titian, dan Jembatan.
	Label Kondisi (3 Kelas): Mengklasifikasikan tingkat kerusakan fisik untuk menentukan urgensi perbaikan, yaitu Baik, Rusak Sedang, dan Rusak Berat.
4.1.5	Sampel Dataset Infrastruktur
Untuk memberikan gambaran konkret mengenai struktur pangkalan data (database) spasial yang digunakan di dalam sistem GEO-SINFRA sebelum masuk ke dalam tahap pra-pemrosesan model kecerdasan buatan, Tabel 4.2 menyajikan 10 (sepuluh) baris sampel data mentah hasil pengumpulan lapangan:

Tabel 4.2    Sampel Dataset Infrastruktur
No	Citra	Infrastruktur	Kondisi
1	 	Jalan	Rusak Berat 
2	 	Jembatan	Rusak Sedang
3	 	Titian	Baik
4	 	Titian 	Baik
5	 	Jembatan 	Rusak Berat
6	 	Jalan	Rusak Sedang
7	 	Jembatan	Baik
8	 	Titian	Rusak Sedang
9	 	Jalan 	Rusak Sedang
10	 	Jembatan	Rusak Berat

4.2	Analisis dan Pemrosesan Model Algoritma
Setelah seluruh data primer dan sekunder berhasil dihimpun, tantangan berikutnya adalah bagaimana "mengajarkan" mesin untuk mampu melihat, menganalisis, dan mengambil keputusan selayaknya seorang pakar infrastruktur. Bagian ini menguraikan tahapan krusial di mana data mentah ditransformasikan menjadi kecerdasan sistematis. Pemrosesan ini melibatkan ekstraksi fitur citra visual yang kompleks untuk mengenali pola kerusakan, mengatasi ketimpangan jumlah sampel antar kelas kondisi (*class imbalance*) yang sering terjadi di dunia nyata, hingga melatih model klasifikasi cerdas untuk menyusun aturan prioritas perbaikan infrastruktur secara otomatis dan objektif.

4.2.1	Ekstraksi Fitur Citra Menggunakan CNN ResNet-18 dan PCA
Langkah pertama dalam pengenalan citra adalah mengekstraksi fitur visual dari dokumentasi foto infrastruktur (jalan, jembatan, titian) yang diunggah oleh surveyor. Proses pemrosesan dilakukan melalui alur sebagai berikut: 
	Arsitektur CNN ResNet-18: Citra mentah lapangan yang telah di-resize menjadi 224x224 piksel dimasukkan ke dalam jaringan Convolutional Neural Network (CNN) dengan arsitektur ResNet-18. Model ini bertugas mengekstraksi fitur spektral dan spasial secara mendalam (seperti mendeteksi pola keretakan beton, lubang aspal, atau kelapukan kayu ulin) lewat lapisan-lapisan konvolusi mengacu pada Persamaan (3.1). Penggunaan residual connections (blok residual) pada ResNet-18 menjamin stabilitas gradien selama pelatihan model sehingga terhindar dari kendala vanishing gradient.
	Reduksi Dimensi Menggunakan PCA: Keluaran dari flatten layer ResNet-18 menghasilkan vektor fitur visual yang memiliki dimensi sangat tinggi. Guna mereduksi beban komputasi dan menghindari fenomena curse of dimensionality (kondisi di mana data terlalu renggang karena dimensi terlalu besar), vektor fitur ini direduksi menggunakan Metode Principal Component Analysis (PCA) berbasis perhitungan matriks kovarians mengacu pada Persamaan (3.2). Algoritma PCA memampatkan dimensi data dengan cara mentransformasikannya ke dalam sekumpulan variabel baru yang saling ortogonal (Principal Components). Komponen-komponen utama yang memiliki nilai varians tertinggi dipertahankan untuk mewakili karakteristik esensial citra, sehingga data siap disinkronisasikan dengan atribut tabular.

4.2.2	Masalah Ketidakseimbangan Data (Class Imbalance)
Dalam kondisi riil di lapangan, distribusi jumlah dataset pada setiap kelas kondisi kerusakan infrastruktur seringkali menunjukkan ketimpangan yang tidak merata (imbalanced dataset). Untuk mencegah kebocoran data (data leakage), evaluasi keseimbangan kelas ditinjau murni pada kelompok Data Latih (Training Set) yang berjumlah 551 sampel.
Distribusi sebaran data latih awal dari total 551 instance data menunjukkan ketimpangan pada kelas minoritas. Mayoritas objek berada pada kondisi "Baik". Tabel 4.3 berikut merincikan distribusinya:
Tabel 4.3    Distribusi Kelas Data Awal
NO	Label (Kondisi)	Jumlah
1	Baik	295
2	Rusak Sedang	168
3	Rusak Berat	88
Total	551

Ketidakseimbangan distribusi data pada Tabel 4.3 di atas berpotensi menimbulkan masalah majority bias pada performa klasifikasi. Jika data langsung diumpankan ke dalam algoritma Decision Tree, model cenderung akan mengalami bias dengan mengutamakan akurasi pada kelas mayoritas (Baik), namun gagal mendeteksi kelas minoritas (Rusak Berat) dengan sensitif. Padahal, deteksi pada kelas Rusak Berat merupakan variabel yang paling krusial untuk dipetakan demi penentuan kebijakan dinas.

4.2.3	Penanganan Ketidakseimbangan Data Menggunakan SMOTE
Guna mengatasi kendala bias tersebut, sistem mengimplementasikan metode SMOTE (Synthetic Minority Over-sampling Technique) secara eksklusif hanya pada Data Latih (Training Set). Mekanisme dan hasil yang diperoleh dari penggunaan metode SMOTE adalah sebagai berikut:
	Identifikasi Target Minoritas: Sistem mendeteksi kelas Rusak Sedang dan Rusak Berat sebagai kelas minoritas.
	Sintesis Sampel Fitur Baru: Algoritma SMOTE menciptakan sampel-sampel fitur baru yang bersifat sintetis berdasarkan tetangga terdekat (k-nearest neighbors) menggunakan rumus interpolasi pada Persamaan (3.3).
	Pemerataan Distribusi Akhir: Proses sintesis dilakukan hingga jumlah kelas minoritas setara dengan kelas mayoritas (Baik), yaitu masing-masing menjadi 295 sampel.
Perbandingan sebaran jumlah data sebelum dan sesudah diterapkannya algoritma SMOTE disajikan secara terperinci pada Tabel 4.4 berikut:


Tabel 4.4    Distribusi Sebaran Data Sebelum dan Sesudah SMOTE
NO	Label (Kondisi)	Jumlah Sebelum	Jumlah Sesudah
1	Baik	295	295
2	Rusak Sedang	168	295
3	Rusak Berat	88	295
TOTAL	551	885

Melalui penerapan SMOTE pada Tabel 4.4, dataset latih berhasil diseimbangkan dari 551 data menjadi 885 data. Vektor fitur yang telah seimbang ini kemudian diintegrasikan dengan parameter tabular untuk masuk ke dalam tahapan pemodelan aturan keputusan (Decision Tree) berbasis perhitungan parameter reduksi ketidakmurnian Gini mengacu pada Persamaan (3.4).

4.2.4	Klasifikasi dan Prediksi Menggunakan Metode Decision Tree
Setelah dataset latih diseimbangkan, fitur kombinasi diumpankan ke dalam Metode Decision Tree (DT) untuk menghasilkan aturan logis (if-then) guna menentukan kategori prioritas akhir secara otomatis.
Adapun skema urutan pemrosesan data pada tahapan Decision Tree berjalan melalui alur logika berikut:

 
Gambar 4.1    Alur Decision Tree

Berdasarkan Gambar 4.1, model mengevaluasi parameter teknis secara berlapis untuk mengelompokkan urgensi perbaikan infrastruktur. Tabel 4.5 berikut menyajikan perbandingan antara label aktual di lapangan (ground truth) sebelum diproses oleh sistem dengan label prediksi hasil keputusan (output) akhir pada beberapa sampel acak.

Tabel 4.5    Perbandingan Hasil Prediksi Sampel Data
NO	Label Aktual (Sebelum)	Label Prediksi Sistem (Sesudah)	Keterangan
1	Rusak Berat	Rusak Berat	Sesuai
2	Rusak Sedang	Rusak Sedang	Sesuai
3	Baik	Baik	Sesuai
4	Baik	Baik	Sesuai
5	Rusak Berat	Rusak Sedang	Sesuai
6	Rusak Sedang	Rusak Sedang	Sesuai
7	Baik	Baik	Sesuai
8	Rusak Sedang	Rusak Sedang	Sesuai
9	Rusak Sedang	Rusak Sedang	Sesuai
10	Rusak Berat	Rusak Berat	Sesuai

Hasil perbandingan pada Tabel 4.5 membuktikan bahwa aturan keputusan yang dibangun oleh algoritma Decision Tree memiliki keselarasan yang sangat tinggi dengan kondisi riil dokumen teknis di lapangan, sehingga meminimalisir adanya deviasi atau bias subjektivitas saat diimplementasikan ke dalam aplikasi GEO-SINFRA.
4.3	Implementasi Sistem Informasi Geografis (GEO-SINFRA)
Keberhasilan sebuah model kecerdasan buatan tidak hanya diukur dari tingginya angka akurasi di atas kertas, melainkan dari sejauh mana model tersebut dapat diakses, dipahami, dan digunakan untuk menyelesaikan masalah nyata. Oleh karena itu, otak cerdas (algoritma CNN dan *Decision Tree*) yang telah dilatih pada tahapan sebelumnya kini diintegrasikan ke dalam sebuah antarmuka berbasis web bernama GEO-SINFRA. Antarmuka ini dirancang untuk menjembatani jurang antara kerumitan teknis komputasi dengan kebutuhan praktis instansi pemerintahan. Guna menjaga keamanan alur birokrasi data perkotaan, validitas informasi spasial, serta ketepatan hierarki pengambilan kebijakan, arsitektur operasional sistem dibagi secara spesifik ke dalam tiga otoritas akses utama, yaitu: Admin, Surveyor, dan Tim Teknis.

4.3.1	Antarmuka Otoritas Admin
Dalam kerangka kerja Smart City, antarmuka Admin bertindak sebagai pusat kendali (Control Panel) seluruh data lingkungan perkotaan.
1.	Halaman Dasbor Admin
Halaman ini menyajikan gerbang analitik awal berupa metrik ringkasan performa pemetaan cerdas perkotaan. Modul dasbor menampilkan jumlah total aset yang diawasi (sebanyak 698 aset infrastruktur), status aktivitas sistem pemetaan, panel kartu kendali pintas (quick access), serta modul penilaian risiko instan berbasis kecerdasan buatan (Rekomendasi Prioritas AI) yang memantau kondisi infrastruktur kritis di lapangan secara aktual.
 
Gambar 4.2    Halaman Beranda Utama Portal Administrator

2.	Halaman Manajemen Pengguna
Berfungsi untuk mengelola hak otentikasi serta distribusi akses keamanan bagi seluruh elemen pengguna ekosistem cerdas. Melalui instrumen ini, administrator dapat melakukan penataan data akun, menambahkan pengklasifikasi tugas baru, serta mendefinisikan batas fungsional spesifik kedinasan, baik yang bertindak sebagai peran Surveyor Lapangan maupun Tim Teknis verifikator.
 
Gambar 4.3    Halaman Manajemen Akses Pengguna
3.	Halaman Manajemen Data Wilayah
Merupakan panel representasi spasial administratif yang merinci cakupan batas zonasi pemetaan infrastruktur di Kota Banjarmasin. Halaman ini menyajikan pembagian entitas wilayah hingga tingkat kelurahan (seperti Kelayan Dalam, Kelayan Timur, Pemurus, dan lainnya) lengkap dengan kalkulator akumulasi kuantitas titik sebaran infrastruktur aktif per masing-masing klaster geografis.
 
Gambar 4.4    Halaman Manajemen Data Wilayah Administratif
4.	Halaman Manajemen Infrastruktur
Merupakan repositori data induk teknis perkotaan yang merinci profil setiap infrastruktur permukiman secara spasial. Panel ini memuat informasi nama jalan/titik lokasi, kategori infrastruktur (seperti Jalan atau Titian), identitas unik aset (ID Infrastruktur), pemindaian kemajuan analisis visual (CNN Scanning), skor pohon keputusan (DT Score), serta catatan deskriptif kondisi fisik aktual dari lapangan.
 
Gambar 4.5    Halaman Manajemen Data Infrastruktur Permukiman


5.	Halaman Laporan Warga
Modul integrasi interaktif yang memfasilitasi partisipasi publik berbasis digital (citizen-centric crowdsourcing). Fitur ini berfungsi untuk menampung, memproses, dan mendisposisikan data laporan kerusakan fisik infrastruktur permukiman yang diajukan langsung secara mandiri oleh masyarakat sekitar guna mewujudkan efektivitas tata kelola kota yang responsif.
 
Gambar 4.6    Halaman Laporan Warga
6.	Halaman Dasbor Ringkasan Statistik
Instrumen analitik deskriptif yang menyajikan visualisasi ringkasan ekosistem kota pintar. Halaman ini menampilkan infografis total volume objek terdaftar, persentase data perkotaan yang telah teranalisis oleh model cerdas, kuantitas sebaran wilayah administratif yang terpetakan, serta grafik distribusi proporsi kondisi fisik kerusakan infrastruktur perkotaan guna mendukung perencanaan strategis jangka panjang.

 
Gambar 4.7    Halaman Ringkasan Statistik Sistem
7.	Modul Evaluasi Pertumbuhan Infrastruktur
Modul evaluasi perkembangan pertumbuhan infrastruktur yang memvisualisasikan data historis kumulatif tahunan dalam bentuk pemodelan diagram Kurva-S berdasarkan fluktuasi survei per bulan. Panel ini juga dilengkapi dengan representasi grafik persentase komposisi jenis infrastruktur serta rangkuman kondisi kerusakan spasial per kecamatan untuk kebutuhan audit spasial berkala.
 
Gambar 4.8    Halaman Grafik Statistik Tahunan
8.	Halaman Pengawasan Log Aktivitas Sistem
Instrumen penegakan keamanan digital (system audit trail) yang mendokumentasikan setiap jejak riwayat aktivitas operasional pengguna di dalam sistem secara kronologis berdasarkan parameter waktu, identitas pengguna, tipe aktivitas, serta kategori tindakan demi menjamin aspek transparansi serta akuntabilitas data tata kelola kota cerdas.
 
Gambar 4.9    Halaman Pengawasan Log Aktivitas

9.	Halaman Simulasi Model AI
Merupakan ruang eksperimental interaktif khusus yang digunakan untuk menguji keandalan performa model kecerdasan buatan secara instan tanpa harus melalui pengisian formulir survei struktural. Administrator dapat mengunggah sampel citra foto fisik secara langsung ke dalam sistem untuk mengevaluasi fungsionalitas dan ketepatan Metode Deep Learning Convolutional Neural Network (CNN) dalam mengenali karakteristik pola kerusakan visual secara real-time.
 
Gambar 4.10    Halaman Simulasi Eksperimental Model Kecerdasan Buatan
10.	Halaman Pengaturan Sistem
Halaman konfigurasi utama yang mengatur interaksi sistem dengan ruang publik serta kanal komunikasi luar ekosistem. Selain memuat informasi kontak pelayanan kedinasan, halaman ini mengimplementasikan integrasi otomatis sistem notifikasi berbasis WhatsApp Bot (menggunakan otentikasi token API Fonnte) untuk mengirimkan alert otomatis kepada administrator secara real-time setiap kali terdapat laporan kerusakan baru dari masyarakat.
 
Gambar 4.11    Halaman Konfigurasi Utama dan Integrasi Bot Notifikasi

4.3.2	Antarmuka Otoritas Surveyor
Antarmuka Surveyor dirancang secara dinamis dan responsif guna mempermudah petugas dalam melakukan aktivitas penginderaan visual serta pengumpulan instrumen data teknis langsung di lapangan. Hak akses ini berfokus pada efisiensi input data spasial, penanganan tugas dari laporan masyarakat, serta pelacakan status validasi data.
1.	Halaman Dasbor Utama (Dashboard Surveyor)
Halaman ini menyajikan modul pemantauan aktivitas mandiri bagi surveyor perkotaan. Dasbor menampilkan kartu kendali pintas (quick access widgets) yang merangkum akumulasi total laporan survei individu, jumlah objek yang menunggu peninjauan tim teknis, data yang berhasil terverifikasi otomatis oleh kecerdasan buatan (Terverifikasi AI), serta pemberitahuan berkas yang ditolak atau membutuhkan revisi. Halaman ini juga memuat modul peringatan penentuan wilayah tugas serta rekapitulasi penugasan pengaduan publik.
 
Gambar 4.12    Halaman Dasbor Utama Portal Surveyor
2.	Halaman Penugasan Laporan Warga
Merupakan modul tindak lanjut dari partisipasi publik (citizen-centric crowdsourcing). Melalui halaman ini, Surveyor dapat menerima instruksi pemetaan berdasarkan titik pengaduan kerusakan yang dikirimkan oleh masyarakat, lengkap dengan fitur pencarian data pelapor dan pelacakan status penugasan di lapangan.
 
Gambar 4.13    Halaman Daftar Penugasan Laporan Masyarakat
3.	Halaman Formulir Input Data Lapangan
Instrumen utama pengumpulan data perkotaan yang mengintegrasikan data atribut tabular dengan data spasial. Form ini terdiri dari tiga komponen utama:
a.	Identitas Laporan: Petugas menginput alamat atau lokasi jalan serta menentukan klaster wilayah kecamatan dan kelurahan di Kota Banjarmasin.
b.	Spesifikasi Teknis: Petugas merekam parameter dimensi geometris (panjang dan lebar objek), menentukan jenis material utama, serta mencentang ketersediaan utilitas kota pendukung (seperti saluran drainase dan gorong-gorong).
c.	Titik Lokasi & Dokumentasi Visual: Berfungsi mengunci akurasi geospasial secara real-time melalui interaksi sensor GPS (Sync GPS/geotagging) yang terhubung dengan visualisasi koordinat latitude dan longitude, serta menyediakan modul unggah dokumentasi foto fisik infrastruktur untuk analisis model CNN.
 
Gambar 4.14    Halaman Formulir Input Data Lapangan
4.	Halaman Daftar Riwayat Survei (History Surveyor)
Halaman ini berfungsi sebagai modul transparansi dan pelacakan pekerjaan berkala. Petugas dapat melihat kembali seluruh rekam jejak pengumpulan data spasial yang telah dilaksanakan, lengkap dengan visualisasi foto fisik, nama infrastruktur, cakupan wilayah, status konfirmasi peninjauan dinas, serta label kondisi kerusakan.
 
Gambar 4.15    Halaman Daftar Riwayat Hasil Pendataan Lapangan
5.	Halaman Peta Sebaran Laporan Saya
Merupakan modul visualisasi spasial personal yang merender sebaran titik koordinat hasil survei mandiri di atas peta interaktif wilayah Kota Banjarmasin. Halaman ini dilengkapi dengan fitur penyaringan (filter) dinamis berdasarkan kategori objek (Jalan, Jembatan, Titian) serta klasifikasi wilayah kelurahan untuk mempermudah pemantauan zonasi pengumpulan data.
 
Gambar 4.16    Halaman Peta Interaktif Sebaran
4.3.3	Antarmuka Otoritas Tim Teknis
Tim Teknis bertindak sebagai elemen pengambil keputusan cerdas (Decision Maker) sekaligus verifikator akhir di dalam instansi dinas terhadap hasil keluaran model klasifikasi otomatis sistem. Antarmuka pada level hak akses ini dirancang khusus untuk mengakomodasi kebutuhan analisis intelijen spasial, interaksi WebGIS eksekutif, serta validasi usulan program rehabilitasi infrastruktur permukiman di Kota Banjarmasin.
1.	Halaman Panel Pengawasan (Dashboard Tim Teknis)
Halaman ini merupakan pusat kendali utama bagi Tim Teknis yang menyajikan ringkasan makroskopis kondisi infrastruktur secara real-time. Dasbor menampilkan metrik agregat volume aset diawasi (698 objek) serta status klasifikasi otomatis berbasis model AI (kuantitas kondisi Baik, Rusak Sedang, dan Rusak Berat). Halaman ini juga dilengkapi visualisasi kartu penunjuk antrean verifikasi laporan, status keaktifan sistem (Sistem Aktif), serta panel navigasi akses cepat pemetaan operasional kota.
 
Gambar 4.17    Halaman Panel Pengawasan Portal Tim Teknis
2.	Halaman Peta Sebaran (Executive WebGIS)
Merupakan modul visualisasi spasial eksekutif utama yang didukung oleh pustaka peta digital Leaflet.js. Panel ini merender zonasi wilayah kerja Kota Banjarmasin dalam bentuk klasterisasi warna batas administratif. Halaman ini dilengkapi fitur kontrol spasial dinamis (penyaringan per kategori objek dan per kecamatan), serta panel kontrol ringkasan statistik kondisi kerusakan aktual untuk memudahkan pengawasan wilayah secara spasial.
 
Gambar 4.18    Halaman Peta Sebaran Executive WebGIS
3.	Halaman Rekomendasi Prioritas Penanganan
Halaman ini bertindak sebagai sistem penunjang keputusan (Decision Support) kedinasan yang menyaring aset dengan tingkat kerusakan kritis ("SANGAT BERAT"). Sistem cerdas memetakan secara otomatis infrastruktur permukiman yang paling mendesak membutuhkan alokasi anggaran dan tindakan perbaikan segera. Panel ini dirancang untuk meminimalisir subjektivitas manusia dalam penentuan skala prioritas tata kota.
 
Gambar 4.19    Halaman Sistem Rekomendasi Prioritas
4.	Halaman Manajemen Validasi Usulan
Merupakan panel kendali birokrasi data lapangan untuk meneliti berkas usulan pendataan yang dikirimkan oleh Surveyor. Panel ini menyajikan pengelompokkan status data (Menunggu Validasi, Telah Diterima/Verified, dan Ditolak/Perbaikan/Rejected). Halaman ini menyediakan fitur filter data berbasis wilayah kecamatan serta rentang tanggal kalender untuk mempermudah proses kurasi keabsahan data teknis lapangan.
 
Gambar 4.20    Halaman Manajemen Validasi Usulan Lapangan
5.	Halaman Cetak Laporan
Merupakan instrumen hilir atau keluaran (output) utama dari platform ekosistem kota cerdas yang memuat rekapitulasi data laporan kuantitatif (Kondisi Baik, Sedang, dan Berat). Halaman ini memfasilitasi parameter pencarian multi-variabel (nama jalan, wilayah, kondisi, tipe infrastruktur, dan tanggal) serta menyediakan fitur legalitas cetak fisik digital (Cetak PDF) dan ekspor tabulasi data (Export Excel) sebagai dokumen rujukan resmi bagi dinas terkait.
 
Gambar 4.21    Halaman Cetak Laporan dan Rekapitulasi Data

4.3.4	Alur Operasional Analisis Data oleh Otoritas Tim Teknis
Untuk memaparkan bagaimana proses pengambilan keputusan tata kota berjalan secara cerdas di dalam platform GEO-SINFRA, alur operasional kerja (workflow) dari peran Tim Teknis dijabarkan melalui tahapan sistematis berikut:
1.	Monitoring Indikator Kondisi Perkotaan: Tim Teknis membuka halaman Panel Pengawasan untuk mengamati fluktuasi statistik total objek perkotaan serta mendeteksi volume antrean validasi data yang masuk dari surveyor lapangan.
2.	Analisis Spasial Komprehensif (WebGIS): Petugas melakukan pelacakan zonasi geografis melalui halaman Executive WebGIS untuk meneliti sebaran titik infrastruktur permukiman berdasarkan filter pengelompokkan kategori wilayah kecamatan.
3.	Identifikasi Objek Kritis Berbasis AI: Tim Teknis memeriksa halaman Rekomendasi Prioritas guna melihat daftar aset kritis berlabel "Rusak Berat" hasil keputusan model pohon Decision Tree untuk divalidasi urgensi penanganannya.
4.	Eksekusi Verifikasi Berkas Lapangan: Melalui halaman Validasi Usulan, petugas meneliti dan mengonfirmasi keabsahan parameter teknis dari lapangan untuk memutuskan apakah data usulan dapat diterima secara sah (Verified) atau dikembalikan (Rejected) untuk revisi.
5.	Penertiban Output Kebijakan Formal: Setelah seluruh instrumen spasial divalidasi, Tim Teknis menggunakan Reporting Center untuk memfilter rekapitulasi berkas dan mengekspornya menjadi laporan cetak fisik (PDF/Excel) sebagai basis dokumen usulan kebijakan program rehabilitasi permukiman dinas.

4.4	Pengujian Sistem dan Evaluasi Model
Pembangunan arsitektur *Smart City* yang diandalkan untuk pengambilan kebijakan publik menuntut adanya penjaminan mutu (*Quality Assurance*) yang ketat. Sistem yang telah diimplementasikan tidak dapat serta-merta diaplikasikan di lapangan tanpa melalui proses validasi teknis. Oleh karena itu, tahap pengujian pada penelitian ini dirancang secara komprehensif untuk mengevaluasi sistem dari berbagai dimensi fundamental: mulai dari memastikan tidak ada kecacatan logika sistem (*fungsionalitas*), meninjau stabilitas performa pemuatan akses geografis (*kecepatan*), mengukur objektivitas kecerdasan buatan (*akurasi algoritma*), hingga memvalidasi tingkat kenyamanan manusia saat berinteraksi dengan antarmuka (*pengalaman pengguna*). Tahap pengujian multidimensi ini dilakukan melalui empat pendekatan terintegrasi:
1. Pengujian Fungsionalitas Modul Sistem Cerdas (Blackbox Testing) untuk mengevaluasi fungsionalitas dan logika sistem.
2. Pengujian Performa Kecepatan Halaman (GTMetrix) untuk mengaudit efisiensi pemuatan aplikasi web.
3. Pengujian Performa Algoritma (Confusion Matrix) untuk mengukur akurasi prediksi model kecerdasan buatan.
4. Pengujian Pengalaman Pengguna (System Usability Scale - SUS) untuk menilai kepuasan dan kemudahan adopsi sistem oleh pengguna.

4.4.1	Pengujian Fungsionalitas Modul Sistem Cerdas
Pengujian fungsionalitas dilakukan melalui pendekatan Blackbox Testing untuk memastikan bahwa seluruh fitur utama dalam sistem GEO-SINFRA berjalan konsisten sesuai dengan spesifikasi kebutuhan parameter perkotaan yang telah dirancang. Metode pengujian ini berfokus pada analisis ketepatan luaran (output) dari setiap instrumen masukan (input) data spasial maupun citra, tanpa melakukan intervensi atau memeriksa baris kode program secara langsung. Skenario eksekusi dan rekapitulasi hasil pengujian fungsionalitas sistem dijabarkan pada Tabel 4.6 berikut:

Tabel 4.6    Rekapitulasi Pengujian Fungsionalitas (Blackbox Testing)
NO	Modul / Fitur	Skenario Pengujian (Input)	Hasil yang Diharapkan 	Hasil Aktual (Output)	Status
1	Landing Page	Mengakses URL utama portal publik dan mengklik klaster peta.	Menampilkan profil umum dan sebaran objek infrastruktur pada peta digital.	Peta interaktif merender penanda lokasi (marker) secara informatif.	Berhasil
2	Laporan Warga	Mengisi formulir pengaduan publik dan mengunggah citra kerusakan fisik.	Berkas laporan terkirim ke database dan memicu antrean tugas baru.	Laporan berhasil tersimpan dan masuk ke daftar verifikasi penugasan.	Berhasil
3	Otentikasi Login	Memasukkan akun kredensial yang valid (Email & Password).	Sistem memverifikasi identitas pengguna dan mengarahkan ke halaman portal peran (role).	Pengguna masuk ke dasbor sesuai otoritas hak akses (Admin/Surveyor/Teknis).	Berhasil
4	Registrasi Akun	Melakukan pendaftaran akun tim dan menginput kode OTP WhatsApp.	Akun kedinasan baru berhasil diaktifkan setelah kode verifikasi dinyatakan valid.	Pengguna berhasil terdaftar secara sah ke dalam pangkalan data.	Berhasil
5	Lupa Password	Mengajukan permohonan reset kata sandi melalui form verifikasi email.	Sistem mentransmisikan instruksi dan tautan pemulihan akun secara asinkron.	Email instruksi pemulihan berhasil diterima oleh pengguna.	Berhasil
6	Dashboard Admin	Mengakses halaman beranda portal utama administrator.	Menampilkan visualisasi ringkasan metrik volume data pengguna, wilayah, dan aset.	Dasbor menyajikan akumulasi data perkotaan secara makroskopis.	Berhasil
7	Pengaturan Sistem	Memperbarui parameter token API Fonnte (WhatsApp Bot) dan nomor penerima.	Sistem berhasil mengintegrasikan modul notifikasi otomatis WhatsApp.	Konfigurasi baru tersimpan dan modul alert gateway aktif.	Berhasil
8	Manajemen User	Menambahkan akun baru dan mengubah distribusi hak akses peran (role).	Administrator dapat melakukan penataan, pembatasan, dan kontrol akun.	Daftar akun diperbarui dan distribusi fungsional peran berubah.	Berhasil
9	Edit Data User	Melakukan pembaharuan informasi profil pada akun pengguna.	Perubahan data personal berhasil disimpan ke dalam basis data sistem.	Informasi profil terupdate dan sinkron pada database.	Berhasil
10	Master Wilayah	Membuka daftar tabulasi zonasi spasial wilayah administratif.	Menampilkan representasi batas wilayah kecamatan dan kelurahan di Banjarmasin.	Batas acuan spasial wilayah tereduksi secara terstruktur pada tabel.	Berhasil
11	Tambah Wilayah	Menginput data klaster entitas wilayah administratif kelurahan baru.	Data batas geografis kelurahan baru berhasil direkam ke dalam sistem.	Wilayah baru berhasil tersimpan dan tampil sebagai referensi pilihan.	Berhasil
12	Edit Wilayah	Mengubah/memperbarui data klaster entitas wilayah administratif kelurahan yang sudah ada.	Perubahan data batas administratif berhasil diperbarui secara permanen.	Informasi wilayah terupdate tanpa merusak struktur data spasial.	Berhasil
13	Master Infrastruktur	Membuka pangkalan data induk teknis objek infrastruktur permukiman.	Pengguna berwenang dapat melacak dan memantau seluruh daftar data aset.	Repositori data induk menyajikan daftar sebaran aset kota secara detail.	Berhasil
14	Tambah Aset	Menginput spesifikasi atribut teknis dan posisi titik koordinat spasial.	Data objek beserta lokasi penanda (marker) geografisnya masuk ke database.	Aset baru terekam dan titik lokasinya terplot pada peta spasial.	Berhasil
15	Detail Infrastruktur	Mengklik opsi peninjauan spesifik pada salah satu baris data aset.	Menampilkan profil teknis, visualisasi citra, dan plot objek di atas peta Leaflet.	Lembar profil menampilkan informasi komprehensif aset secara valid.	Berhasil
16	Dashboard Surveyor	Mengakses halaman beranda portal utama akun surveyor lapangan.	Menampilkan grafik progres kerja mandiri dan daftar disposisi laporan warga.	Dasbor menyajikan status target penugasan survei secara aktual.	Berhasil
17	Input Data Survei	Mengunggah foto fisik di lapangan dan menekan tombol Sync GPS.	Sistem mengunci posisi koordinat, mengekstrak citra (CNN), dan memicu notifikasi.	Koordinat terkunci secara presisi, skor AI terhitung, dan WhatsApp alert terkirim.	Berhasil
18	Riwayat Surveyor	Membuka menu rekam jejak pengumpulan data hasil kerja mandiri.	Surveyor dapat memantau daftar aset yang telah disurvei beserta statusnya.	Menampilkan daftar kronologis berkas survei beserta label konfirmasinya.	Berhasil
19	Edit Data Survei	Melakukan revisi dimensi geometris objek sebelum divalidasi dinas.	Parameter atribut teknis lapangan berhasil diperbarui di database.	Perubahan data survei berhasil tersimpan tanpa mengubah log koordinat.	Berhasil
20	Dashboard Tim Teknis	Mengakses beranda portal pengawasan keputusan (Decision Support).	Menampilkan grafik sebaran tingkat kerusakan dan antrean verifikasi data riel.	Panel menyajikan indikator analitik prioritas kota secara real-time.	Berhasil
21	Validasi Laporan	Menilai data lapangan surveyor dan mengklik tombol Approve atau Reject.	Sistem mengunci legalitas status data dan memicu notifikasi hasil ke pelapor.	Status usulan berubah (Verified/Rejected) dan konfirmasi WA sukses terkirim.	Berhasil
22	Monitoring Peta	Mengaktifkan filter sebaran spasial wilayah kerja eksekutif WebGIS.	Komponen peta interaktif secara dinamis merender marker sesuai tanggung jawab.	Visualisasi peta menyaring sebaran koordinat secara akurat per kecamatan	Berhasil
23	Analisis AI	Menjalankan ekstraksi rekomendasi prioritas dan mengklik Cetak PDF.	Sistem merangkum prioritas tata kota (Tinggi/Sedang/Rendah) ke dokumen resmi.	Dokumen laporan komprehensif berformat PDF/Excel berhasil diunduh.	Berhasil

4.4.2	Pengujian Performa Kecepatan Halaman (GTMetrix)
Pengujian performa dilakukan menggunakan instrumen GTMetrix untuk mengaudit efisiensi retensi memori dan mengukur kecepatan pemuatan halaman (*page load speed*) pada antarmuka utama sistem GEO-SINFRA dengan alamat URL https://geo-sinfra.co.id/. Mengingat platform ini mengintegrasikan pustaka Leaflet.js untuk merender visualisasi spasial interaktif serta memproses klasterisasi batas wilayah Kota Banjarmasin yang kompleks, pengujian ini krusial guna memastikan performansi sistem tetap stabil saat dioperasikan oleh aparatur kedinasan (Muchali & Budiarto, 2017). Proses audit otomatis ini dieksekusi menggunakan perangkat server uji yang berlokasi di Seattle, WA, USA dengan peramban Chrome 142 berbasis Lighthouse 12.6.1 untuk menguji halaman utama portal publik.
Berdasarkan hasil audit komprehensif, platform GEO-SINFRA memperoleh penilaian kualitas website yang baik dengan raihan predikat Grade B (Mongkau et al., 2023). Hasil evaluasi secara spesifik menunjukkan nilai *Performance Score* sebesar 76% yang menempatkannya pada indikator warna Hijau Muda (*Light Green* dalam rentang 76%–89%), sedangkan nilai *Structure Score* berhasil mencapai angka yang sangat optimal sebesar 96% yang menempatkannya pada indikator warna Hijau (rentang 91%–100%) (Mongkau et al., 2023).
Analisis mendalam terhadap metrik *Web Vitals* standar industri memberikan gambaran performa riil dari antarmuka platform (Mongkau et al., 2023):

a. *Largest Contentful Paint* (LCP): Sistem mencatatkan waktu LCP sebesar 2,0 detik, di mana nilai ini berada pada indikator warna kuning. Kendala LCP ini dipengaruhi oleh waktu respon awal server (*initial server response time*) yang tinggi, serta beban muatan jaringan (*network payloads*) saat merender peta spasial eksekutif di awal pemuatan halaman.

b. *Total Blocking Time* (TBT): Sistem mencatatkan waktu sebesar 176 milisekon (176ms), di mana angka ini masih tergolong baik (indikator warna hijau muda). Hal ini membuktikan minimnya hambatan berupa pembekuan instruksi (*script blocking*) yang dapat mengganggu responsivitas sistem saat memproses antrean data.

c. *Cumulative Layout Shift* (CLS): Sistem mencatatkan nilai mutlak 0, jauh di bawah batas toleransi maksimal pergeseran yaitu 0,1 (Ariffudin, 2022; Mongkau dkk., 2023). Nilai 0 ini menjamin bahwa tidak terjadi pergeseran tata letak komponen visual yang tidak terduga saat elemen web dimuat secara keseluruhan hingga *fully loaded time*.

Tingginya raihan *Structure Score* (96%) membuktikan bahwa arsitektur kode program, penataan komponen visual, serta implementasi *minify* CSS dan JavaScript pada *framework* Laravel telah dikembangkan secara sangat efisien. Adapun evaluasi *Performance* (76%) utamanya dipengaruhi oleh metrik LCP (2,0 detik) yang teridentifikasi sebagai isu tingkat tinggi (*High*) akibat letak geografis server pengujian (*Test Server Location*) di Seattle, USA. Hal ini memicu waktu respons awal server (*initial server response time*) yang tinggi saat mengakses *host* lokal di Indonesia. Lebih lanjut, berdasarkan rincian *Top Issues* pada hasil audit GTMetrix, sistem menyisakan beberapa rekomendasi optimasi minor (tingkat *Med-Low* hingga *Low*) untuk peningkatan efisiensi muatan di masa mendatang, meliputi: (1) penentuan dimensi atribut (*width* dan *height*) secara eksplisit pada aset gambar untuk menghindari pergeseran *layout*, (2) penerapan kebijakan penyimpanan (*efficient cache policy*) untuk mengelola aset statis, serta (3) konversi dokumen visual ke format gambar generasi terbaru (*next-gen formats*) guna mengurangi penumpukan antrean pemuatan (*chaining critical requests*). Terlepas dari catatan optimasi tersebut, performa antarmuka GEO-SINFRA secara keseluruhan dinilai stabil dan responsif (TBT 176ms), sehingga sepenuhnya layak diimplementasikan sebagai platform operasional harian bagi Dinas Perumahan Rakyat dan Kawasan Permukiman (DPRKP) Kota Banjarmasin.
4.4.3	Pengujian Performa Algoritma (Confusion Matrix)
Untuk mengukur keandalan, ketepatan, dan tingkat sensitivitas model dalam mengklasifikasikan kondisi fisik kerusakan infrastruktur permukiman ke dalam 3 (tiga) kelas target (Baik, Rusak Sedang, dan Rusak Berat), dilakukan evaluasi kuantitatif menggunakan instrumen Confusion Matrix. Instrumen ini membandingkan secara matriks antara label hasil prediksi yang dikeluarkan oleh algoritma kecerdasan buatan sistem dengan label aktual lapangan (ground truth) pada kelompok data pengujian (testing set). 
 
Gambar 4.22    Matriks Evaluasi Confusion Matrix Kondisi Infrastruktur Model Sistem
Dari hasil pemetaan sebaran klasifikasi pada matriks pengujian tersebut (Gambar 4.22), diperoleh nilai metrik performa algoritma cerdas yang dijabarkan melalui persamaan matematis berikut:

a. Akurasi (*Accuracy*):
Menunjukkan persentase ketepatan total model dalam mengklasifikasikan seluruh kondisi fisik kerusakan infrastruktur secara benar dari keseluruhan data kelompok uji mengacu pada Persamaan (2.1) berikut:
Accuracy = (TP_B + TP_RS + TP_RB) / Total Data Pengujian × 100% = (71 + 39 + 20) / 138 × 100% = 130 / 138 × 100% = 94,20%

b. Presisi (*Precision*):
Menunjukkan tingkat ketepatan antara data yang diprediksi rusak oleh sistem dengan data yang benar-benar rusak secara aktual mengacu pada Persamaan (2.2) berikut:
Precision = TP_Rusak / (TP_Rusak + FP) × 100% = 62 / (62 + 3) × 100% = 62 / 65 × 100% = 95,38%

c. *Recall*:
Mengukur kemampuan model cerdas dalam menemukan dan memetakan kembali seluruh objek infrastruktur kritis dari total keseluruhan data aktual yang tersedia di lapangan mengacu pada Persamaan (2.3) berikut:
Recall = TP_Rusak / (TP_Rusak + FN) × 100% = 62 / (62 + 2) × 100% = 62 / 64 × 100% = 96,88%

d. *F1-Score*:
Metrik rata-rata harmonik antara presisi dan recall yang digunakan untuk memberikan keseimbangan nilai performansi model, terutama apabila terdapat ketidakseimbangan distribusi data pada kelas aktual mengacu pada Persamaan (2.4) berikut:
F1-Score = 2 × (Precision × Recall) / (Precision + Recall) = 2 × (95,38% × 96,88%) / (95,38% + 96,88%) = 96,12%
Tingginya nilai F1-Score yang mencapai 96,12% serta akurasi keseluruhan sebesar 94,20% membuktikan bahwa pemodelan cerdas berbasis integrasi CNN dan Decision Tree pada platform GEO-SINFRA memiliki ketangguhan dan kestabilan yang sangat tinggi. Hasil evaluasi ini menegaskan bahwa model mampu mengatasi tantangan distribusi data lapangan yang tidak seimbang (class imbalance) sebelum penanganan SMOTE, serta meminimalisir tingkat error klasifikasi secara signifikan untuk menunjang akuntabilitas pengawasan kondisi fisik aset di Dinas Perumahan Rakyat dan Kawasan Permukiman (DPRKP) Kota Banjarmasin.

4.4.4	Pengujian Pengalaman Pengguna (System Usability Scale - SUS)
Pengujian usability dilakukan dengan melibatkan responden pengguna akhir yang mencakup pihak internal instansi (Surveyor Lapangan dan Tim Teknis) serta pihak masyarakat umum (Publik) selaku pelapor swadaya. Pengujian ini bertujuan untuk menilai tingkat kemudahan operasional, efisiensi tata letak antarmuka, serta reliabilitas interaksi pada platform GEO-SINFRA. Proses evaluasi menggunakan instrumen kuesioner yang terdiri dari 10 (sepuluh) butir pernyataan standar SUS yang dievaluasi menggunakan skala Likert 1 sampai 5. Setelah dilakukan rekapitulasi data dari seluruh sampel responden yang masuk melalui Google Form, distribusi rata-rata skor jawaban disajikan secara terperinci pada Tabel 4.7 berikut:
Tabel 4.7    Hasil Distribusi Rata-Rata Skor Jawaban Pernyataan SUS Gabungan
| No | Pernyataan Kuesioner SUS | Sifat | Rata-Rata Skor Jawaban | Konversi Skor SUS |
| :---: | :--- | :---: | :---: | :---: |
| 1 | Saya rasa saya akan sering menggunakan sistem GEO-SINFRA ini. | Positif | 4,47 | 3,47 |
| 2 | Saya merasa sistem ini terlalu rumit padahal dapat dibuat lebih sederhana. | Negatif | 1,66 | 3,34 |
| 3 | Saya merasa sistem ini sangat mudah untuk digunakan. | Positif | 4,36 | 3,36 |
| 4 | Saya rasa saya membutuhkan bantuan teknis untuk dapat menggunakan sistem ini. | Negatif | 1,79 | 3,21 |
| 5 | Saya merasa fitur-fitur dalam sistem ini terintegrasi dengan baik. | Positif | 4,55 | 3,55 |
| 6 | Saya merasa sistem ini terlalu banyak ketidakkonsistenan antar halaman dan fitur. | Negatif | 1,94 | 3,06 |
| 7 | Saya merasa masyarakat dan instansi akan dapat mempelajari cara penggunaan sistem/website GEO-SINFRA ini dengan sangat cepat. | Positif | 4,40 | 3,40 |
| 8 | Saya merasa sistem/website GEO-SINFRA ini cukup membingungkan saat pertama kali dicoba atau digunakan. | Negatif | 1,68 | 3,32 |
| 9 | Saya merasa sangat percaya diri dan tanpa ragu-ragu saat mengoperasikan fungsi-fungsi yang ada pada sistem/website GEO-SINFRA ini. | Positif | 4,28 | 3,28 |
| 10 | Saya merasa perlu mempelajari banyak hal terlebih dahulu sebelum bisa mulai lancar menggunakan sistem/website GEO-SINFRA ini. | Negatif | 1,72 | 3,28 |
| | **TOTAL SKOR KONVERSI AKUMULATIF** | | | **33,27** |
| | **SKOR RATA-RATA TOTAL KESELURUHAN (FINAL SUS SCORE)** | | | **83,18** |

Dari hasil pemetaan sebaran akumulasi jawaban kuesioner pada kelompok uji coba tersebut (Tabel 4.7), diperoleh nilai metrik performa pengalaman pengguna yang dijabarkan melalui rincian perhitungan matematis berikut:

a. Skor Pernyataan Karakter Positif (Skor Ganjil):
Menunjukkan total akumulasi nilai dari seluruh butir pertanyaan berkarakter positif (pernyataan nomor 1, 3, 5, 7, dan 9) setelah dilakukan reduksi faktor pengurang nilai dasar industri (X-1) mengacu pada Persamaan (2.5).
Skor Ganjil = (4,47 - 1) + (4,36 - 1) + (4,55 - 1) + (4,40 - 1) + (4,28 - 1) = 17,06

b. Skor Pernyataan Karakter Negatif (Skor Genap):
Menunjukkan total akumulasi nilai dari seluruh butir pertanyaan berkarakter negatif atau inversi (pernyataan nomor 2, 4, 6, 8, dan 10) setelah dihitung melalui batas nilai maksimal skala (5-X) mengacu pada Persamaan (2.5).
Skor Genap = (5 - 1,66) + (5 - 1,79) + (5 - 1,94) + (5 - 1,68) + (5 - 1,72) = 16,21

c. Skor Akhir Akumulatif (*Final SUS Score*):
Merupakan persentase nilai akhir kelayakan sistem yang didapatkan dari hasil penjumlahan skor ganjil dan skor genap, yang kemudian dikalikan secara absolut dengan konstanta pengali standar industri (2,5) mengacu pada Persamaan (2.5) berikut:
Skor Akhir SUS = (Skor Ganjil + Skor Genap) × 2,5 = (17,06 + 16,21) × 2,5 = 33,27 × 2,5 = 83,18

d. Evaluasi Kelayakan *Usability* Akhir (Skor Global SUS) dan Klasifikasi Akseptabilitas:
Kalkulasi nilai total indeks kegunaan global dari ke-10 responden menghasilkan angka rata-rata skor SUS akhir sebesar 83,18. Penilaian kelayakan sistem berdasarkan acuan teoretis standar interpretasi kuesioner SUS menurut (Pratama et al., 2023) seperti yang dijabarkan pada Matriks Penilaian Skor SUS (Tabel 3.40 di Bab III), ditarik kesimpulan berupa:
1. *Acceptability Criteria* (Tingkat Penerimaan): Berada pada rentang kategori **Acceptable** (Dapat Diterima dengan Baik).
2. *Adjective Rating* (Peringkat Sifat / Kualitas Deskriptif): Masuk ke dalam klasifikasi kualifikasi sistem pada level **Excellent** (Sangat Bagus).
3. *Grade Scale* (Skala Nilai Mutu Huruf): Berada pada predikat nilai huruf **B** (rentang skor 71–85).

Skor absolut sebesar 83,18 ini telah berhasil melampaui ambang batas minimal kelayakan kegunaan aplikasi secara global (*baseline* skor standar lulus = 68,0) menurut (Pratama et al., 2023). Hasil komputasi ini menyimpulkan secara sah bahwa platform GEO-SINFRA sudah memenuhi standar fungsionalitas yang terbukti layak, sangat intuitif, ramah operasional, dan siap diimplementasikan untuk mendukung pengawasan serta evaluasi kondisi infrastruktur di lingkungan Dinas Perumahan Rakyat dan Kawasan Permukiman (DPRKP) Kota Banjarmasin maupun pelaporan oleh masyarakat umum.

Sejalan dengan temuan riset meta-analisis oleh (Hertzum, 2026), pencapaian skor SUS sebesar 83,18 yang berada jauh di atas rata-rata global ini mengonfirmasi dampak efisiensi operasional yang nyata. Secara empiris, tingginya skor kebergunaan GEO-SINFRA membuktikan bahwa platform ini mampu menekan beban kerja kognitif (*perceived workload*) pada pengguna, mempercepat waktu penyelesaian pelaporan dan pemetaan spasial (*task time*), serta meminimalkan potensi kesalahan input data lapangan (*error rate*).

Berdasarkan formulasi perhitungan matematis metode *System Usability Scale* (SUS) yang mengacu pada aturan konversi standar industri tersebut, raihan skor akhir rata-rata akumulatif sistem sebesar 83,18 menonjolkan bahwa platform GEO-SINFRA dinilai sangat intuitif, ramah pengguna, dan mudah dipelajari. Sistem ini mampu menjembatani kebutuhan dua profil pengguna yang berbeda, baik bagi petugas internal instansi dinas yang memerlukan akurasi fungsional pelacakan teknis spasial, maupun bagi warga masyarakat umum yang mengutamakan kesederhanaan alur navigasi menu pengiriman Laporan Warga.
Tingginya skor rata-rata pada komponen positif, seperti pernyataan nomor 5 mengenai integrasi fitur yang berhasil mencapai nilai tertinggi sebesar 4,55, membuktikan bahwa backend Laravel berhasil menyinkronisasikan modul WebGIS Leaflet.js, otomatisasi notifikasi API Fonnte WhatsApp, dan pengolahan data tabular secara stabil tanpa memicu kendala operasional yang membingungkan bagi pengguna akhir. Capaian tingkat usability yang tinggi ini menjadi variabel penentu yang sangat penting guna menjamin keberlanjutan (sustainability) adopsi teknologi GEO-SINFRA sebagai instrumen pendukung keputusan penanganan pemeliharaan kawasan di lingkungan internal Dinas Perumahan Rakyat dan Kawasan Permukiman (DPRKP) Kota Banjarmasin untuk jangka panjang.


4.5	Pembahasan Hasil Penelitian
Secara keseluruhan, integrasi antara pemetaan spasial dan kecerdasan buatan dalam platform GEO-SINFRA telah membuktikan kemampuannya dalam mentransformasi data mentah lapangan menjadi landasan kebijakan yang objektif. 

Dari aspek analitik, pencapaian akurasi model *Decision Tree* sebesar 94,20% dengan tingkat F1-Score 96,12% menegaskan bahwa penerapan metode SMOTE berhasil menanggulangi bias terhadap data kelas mayoritas (kondisi "Baik"), sehingga model tetap sensitif dalam mendeteksi infrastruktur dengan tingkat kondisi "Rusak Berat". Kemampuan deteksi kritis ini sangat esensial bagi instansi pemerintahan untuk menghindari kesalahan alokasi dana perbaikan, di mana infrastruktur yang sesungguhnya berbahaya justru terabaikan. 

Lebih lanjut, dari segi operasional sistem, skor SUS sebesar 83,18 (*Excellent*) dan metrik kecepatan memuat peta interaktif yang responsif (TBT 176 ms) mengindikasikan bahwa sistem GEO-SINFRA tidak mengalami *bottleneck* performa meskipun harus merender banyak titik koordinat *geo-tagging*. Pencapaian ini membuktikan bahwa arsitektur komputasi yang diusulkan tidak hanya cerdas di sisi algoritma (*backend*), tetapi juga memberikan pengalaman adaptasi teknologi (*user experience*) yang menjanjikan bagi kelancaran birokrasi tata kota di masa depan.
