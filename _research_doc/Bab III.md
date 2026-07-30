BAB III
METODOLOGI PENELITIAN

3.1	Gambaran Awal Sistem
*Detail Engineering Design* (DED) merupakan perencanaan teknis yang disusun secara rinci dan lengkap dalam bentuk desain gambar beserta spesifikasinya (Abdullah, 2022). Dokumen *Detail Engineering Design* (DED) mencakup gambar detail, Rencana Anggaran Biaya (RAB), Rencana Kerja dan Syarat-Syarat (RKS), serta laporan akhir perencanaan yang meliputi perhitungan struktur hingga mekanikal dan elektrikal (Dhiecho Mahar Dhiecha & Sukwadi, 2024). Visualisasi dalam DED merupakan hasil produk analisis teknis yang dijabarkan melalui denah maupun tampak bangunan guna memberikan perbandingan skala yang presisi (Abdullah, 2022). Fungsi utama dari dokumen ini berfokus pada transformasi konsep dasar menjadi rencana implementasi operasional di lapangan untuk pembangunan infrastruktur permukiman secara berkelanjutan (Ibnu Sasongko, 2023).
Pada proyek akhir ini, data DED dari Disperkim Kota Banjarmasin bertindak sebagai sumber data primer yang digunakan sebagai input untuk diproses menggunakan arsitektur *Deep Learning* (Mulyana & Wahyudi, 2025). Alur pemrosesan data dirancang melalui dua tahapan utama yang terintegrasi:
1.	Metode *Convolutional Neural Network* (CNN): Diterapkan pada fase awal untuk memproses data citra digital melalui tahapan ekstraksi fitur secara optimal guna mengenali karakteristik fisik infrastruktur (Hidayat & Prasetya, 2024). 
2.	Metode *Decision Tree*: Data hasil ekstraksi fitur citra tersebut kemudian diintegrasikan dengan variabel atribut DED lainnya untuk diklasifikasikan ke dalam struktur diagram pohon secara sistematis (Fatimah Ahmad et al., 2025). 
Metode ini bekerja dengan menganalisis variabel teknis lapangan untuk klasifikasi tersebut menjadi instrumen pendukung keputusan bagi Disperkim Kota Banjarmasin dalam menetapkan kebijakan strategis, seperti penentuan prioritas penanganan kerusakan serta alokasi anggaran pembangunan berdasarkan kategori data yang telah teridentifikasi secara akurat (Karimah, 2025).

3.2	Alur Metodologi Penelitian
Alur penelitian ini mengadopsi kerangka kerja sistem informasi sistematis untuk mengintegrasikan variabel teknis lapangan dengan algoritma kecerdasan buatan (Yuniarti et al., 2022). Tahap awal dilakukan dengan memproses variabel input berupa citra fisik infrastruktur permukiman dan dokumen teknis DED dari Disperkim Kota Banjarmasin (Abdullah, 2022; Dhiecho Mahar Dhiecha & Sukwadi, 2024). Secara visual, alur penelitian ini digambarkan melalui diagram alir (*flowchart*) pada Gambar 3.1 sebagai berikut:

 
Gambar 3.1     *Flowchart* Penelitian

Melalui *Flowchart* tersebut merepresentasikan urutan pengerjaan sistem dari hulu ke hilir. Untuk memahami mekanismenya secara lebih rinci, berikut adalah pemaparan dari setiap tahapan yang dilewati dalam penelitian ini:
1.	Pengumpulan Data: Tahap awal dimulai dengan mengumpulkan dataset citra infrastruktur yang menjadi objek penelitian. Data dikumpulkan melalui dua pendekatan: (a) survei primer berupa dokumentasi foto kondisi fisik infrastruktur secara langsung oleh peneliti di lapangan Kota Banjarmasin, dan (b) survei sekunder berupa studi dokumentasi terhadap dokumen *Detail Engineering Design* (DED) dari Dinas Perumahan Rakyat dan Kawasan Permukiman (DPRKP) Kota Banjarmasin. Secara keseluruhan, terdapat 689 data infrastruktur valid yang terkumpul dengan rincian distribusi per kelas kondisi sebagai berikut:

Tabel 3.A     Distribusi Dataset Citra Berdasarkan Kelas Kondisi
NO	LABEL (KONDISI)	JUMLAH CITRA
1	Baik	369
2	Rusak Sedang	210
3	Rusak Berat	110
TOTAL	689

2.	Pelabelan Data (*Data Labeling*): Citra yang telah dikumpulkan kemudian dikategorikan secara manual ke dalam tiga kelas kondisi infrastruktur berdasarkan tingkat kerusakannya, yaitu Baik, Rusak Sedang, dan Rusak Berat.
3.	*Pre-Processing Data*: Sebelum data citra dimasukkan ke dalam model, dilakukan tiga sub-tahap pra-pemrosesan untuk menyeragamkan karakteristik data lapangan:
a.	*Resize* Citra: Menyamakan dimensi seluruh gambar menjadi ukuran 224 x 224 piksel agar sesuai dengan standar arsitektur input ResNet-18.
b.	Normalisasi Piksel: Mengubah rentang nilai intensitas piksel citra menjadi skala standar (0 hingga 1) melalui operasi pembagian 255.0 guna mempercepat proses konvergensi saat pelatihan model.
c.	Format Seragam: Menyelaraskan seluruh ekstensi file citra ke dalam satu format standar yang sama (seperti file .jpg atau .png) sebelum diumpankan ke dalam sistem cerdas.
4.	Ekstraksi Fitur Menggunakan CNN ResNet-18: Dataset citra hasil pra-pemrosesan (sebanyak 689 citra) diumpankan secara langsung ke dalam jaringan *Convolutional Neural Network* (CNN) dengan arsitektur ResNet-18. Pada tahap ekstraksi fitur mendalam (*deep feature extraction*) ini, lapisan-lapisan konvolusi bekerja secara otomatis mengenali dan mengekstrak fitur spasial, tekstur, dan karakteristik bentuk fisik kerusakan (misalnya, lubang atau keretakan jalan) tanpa ada intervensi manusia, menghasilkan matriks dimensi vektor berukuran 512 untuk tiap sampel citra. Operasi matematis dasar pada lapisan konvolusi dirumuskan pada Persamaan (3.1) berikut:
S(i, j) = Σ(m) Σ(n) I(i+m, j+n) * K(m, n)	(3.1)
Keterangan:
- S(i, j): Nilai piksel hasil konvolusi pada posisi (i, j)
- I: Matriks citra input
- K: Matriks kernel (filter) ukuran m x n
5.	Tahap Reduksi Dimensi Data Menggunakan *Principal Component Analysis* (PCA): Setelah fitur citra berhasil diekstrak oleh arsitektur CNN ResNet-18 menjadi kumpulan fitur (512 matriks dimensi per gambar), data fitur tersebut masuk ke dalam proses reduksi dimensi menggunakan metode *Principal Component Analysis* (PCA). Tahapan ini bertujuan untuk menyusutkan informasi esensial dari nilai matriks tersebut dan mengeliminasi unsur *redundancy* (data yang tumpang tindih) yang dapat membebani proses komputasi. Melalui mekanisme reduksi dimensi, dimensi fitur 512 dari CNN ResNet-18 dipadatkan ke dalam vektor matriks berukuran 256 dimensi komponen utama (*Principal Components*). Pemadatan vektor yang ringkas namun representatif ini krusial agar tahapan pembelajaran mesin pada tahap berikutnya dapat berjalan dengan lebih hemat komputasi. Proses dekomposisi PCA ini didasarkan pada perhitungan matriks kovarians yang dirumuskan pada Persamaan (3.2) berikut:
Cov(X, Y) = Σ [ (Xi - X_mean) * (Yi - Y_mean) ] / (n - 1)	(3.2)
Keterangan:
- n: Jumlah sampel data
- Xi, Yi: Nilai komponen fitur ke-i
- X_mean, Y_mean: Nilai rata-rata dari fitur kelas X dan Y
6.	Pembagian Data (*Data Splitting*): Vektor fitur hasil reduksi dimensi dari 689 dataset tersebut kemudian dibagi secara acak ke dalam dua kelompok dengan proporsi 80:20, yaitu:
a.	80% *Training Set* (551 Data Fitur): Digunakan sebagai data latih algoritma agar dapat mengenali pola karakteristik kerusakan objek infrastruktur.
b.	20% *Testing Set* (138 Data Fitur): Digunakan sebagai data uji murni untuk mengevaluasi tingkat objektivitas dan akurasi prediksi model.
7.	Tahap Penyeimbangan Fitur Menggunakan *Synthetic Minority Over-sampling Technique* (SMOTE): Tahapan selanjutnya setelah data dibagi adalah penyeimbangan representasi kelas secara khusus pada kelompok *Training Set* menggunakan Metode *Synthetic Minority Over-sampling Technique* (SMOTE). Distribusi data citra infrastruktur pada kelas kondisi Baik, Rusak Sedang, dan Rusak Berat sering kali menunjukkan ketidakseimbangan (*imbalanced dataset*), di mana jumlah sampel pada kelas kerusakan tertentu jauh lebih sedikit dibandingkan kelas lainnya. Kondisi ini dapat menyebabkan model klasifikasi cenderung bias terhadap kelas mayoritas. Untuk mengatasi masalah tersebut, SMOTE diterapkan pada tingkat vektor fitur hasil PCA. Algoritma ini bekerja dengan cara menghasilkan sampel-sampel fitur sintetis baru bagi kelas minoritas berdasarkan prinsip tetangga terdekat (*k-nearest neighbors*). Melalui pendekatan ini, proporsi kelas menjadi seimbang tanpa melakukan duplikasi data mentah yang dapat memicu *overfitting*. Rincian distribusi data latih sebelum dan sesudah penerapan SMOTE disajikan pada Tabel 3.B berikut:

Tabel 3.B     Distribusi Data Latih Sebelum dan Sesudah SMOTE
NO	LABEL (KONDISI)	JUMLAH SEBELUM	JUMLAH SESUDAH
1	Baik	295	295
2	Rusak Sedang	168	295
3	Rusak Berat	88	295
TOTAL	551	885

Perlu ditegaskan bahwa penerapan SMOTE dilakukan secara eksklusif hanya pada kelompok Data Latih (*Training Set*). Kelompok Data Uji (*Testing Set*) yang berjumlah 138 data tetap dipertahankan dalam distribusi aslinya tanpa *oversampling* untuk menjaga objektivitas dan validitas evaluasi performa model. Pembuatan data sampel sintetis pada SMOTE dihitung menggunakan rumus interpolasi jarak seperti dirumuskan pada Persamaan (3.3) berikut:
X_new = Xi + (X_zi - Xi) * δ	(3.3)
Keterangan:
- X_new: Vektor fitur sintetis baru
- Xi: Vektor fitur kelas minoritas yang sedang diproses
- X_zi: Vektor fitur tetangga terdekat dari Xi
- δ: Nilai acak (random) dengan rentang 0 hingga 1

Vektor fitur yang telah diseimbangkan ini kemudian siap digunakan sebagai input untuk fase klasifikasi menggunakan Metode *Decision Tree*.
8.	Klasifikasi Kondisi Infrastruktur Menggunakan *Decision Tree*: Vektor fitur yang telah terbentuk selanjutnya diumpankan ke dalam Metode *Decision Tree*. Algoritma ini bertindak sebagai pengklasifikasi akhir yang menyusun struktur aturan keputusan pohon untuk mengategorikan citra ke dalam salah satu label kondisi: Baik, Rusak Sedang, atau Rusak Berat. Pemisahan simpul (*node splitting*) pada *Decision Tree* dihitung berdasarkan parameter reduksi nilai ketidakmurnian (*Gini Impurity*) dengan rumus matematis pada Persamaan (3.4) berikut:
Gini(D) = 1 - Σ (p_i)²	(3.4)
Keterangan:
- Gini(D): Nilai ketidakmurnian pada himpunan data D
- p_i: Probabilitas kemunculan sampel kelas ke-i pada simpul tertentu

Selain memprediksi kondisi fisik, sistem secara otomatis menurunkan label prioritas penanganan berdasarkan hasil klasifikasi kondisi tersebut, yaitu: Rusak Berat → Prioritas Tinggi, Rusak Sedang → Prioritas Sedang, dan Baik → Prioritas Rendah.
9.	Monitoring Berbasis SIG dan Evaluasi Model (*GIS Monitoring & Evaluation*): Hasil klasifikasi kondisi infrastruktur diintegrasikan ke dalam Sistem Informasi Geografis (SIG) untuk visualisasi dan *monitoring* berbasis pemetaan wilayah. Akhirnya, kinerja keseluruhan sistem dievaluasi menggunakan *Confusion Matrix*.
10.	Evaluasi Model Menggunakan *Confusion Matrix*: Setelah proses klasifikasi selesai, performa model diuji menggunakan data *Testing Set* (20%). Hasil prediksi dari model akan dipetakan ke dalam instrumen *Confusion Matrix* untuk melihat perbandingan antara kelas aktual dan kelas prediksi. Berdasarkan *Confusion Matrix* tersebut, kualitas dan keandalan sistem diukur secara kuantitatif melalui empat metrik evaluasi:
a.	*Accuracy*: Mengukur tingkat keakuratan total prediksi benar model terhadap keseluruhan data mengacu pada Persamaan (2.1) berikut:
Accuracy = (TP + TN) / (TP + TN + FP + FN)
b.	*Precision*: Mengukur rasio prediksi positif yang benar-benar sesuai dengan data aktual positif mengacu pada Persamaan (2.2) berikut:
Precision = TP / (TP + FP)
c.	*Recall*: Mengukur kemampuan model dalam mengidentifikasi kembali seluruh data yang aktualnya bernilai positif mengacu pada Persamaan (2.3) berikut:
Recall = TP / (TP + FN)
d.	*F1-Score*: Nilai rata-rata harmonis yang menyeimbangkan antara *Precision* dan *Recall*, terutama untuk menguji ketangguhan model jika terdapat ketidakseimbangan jumlah data (*imbalance dataset*) mengacu pada Persamaan (2.4) berikut:
F1-Score = 2 * (Precision * Recall) / (Precision + Recall)
*(Keterangan: TP = True Positive, TN = True Negative, FP = False Positive, FN = False Negative).*

3.3	Metode Pengembangan Sistem
Penerapan Metode pengembangan sistem dalam penelitian ini dilakukan melalui pendekatan Waterfall. Tahapan ini merupakan fase krusial untuk menerjemahkan hasil analisis kebutuhan ke dalam spesifikasi teknis yang terstruktur guna memastikan pengembangan platform berjalan selaras dengan kebutuhan fungsional (Yuniarti et al., 2022). Sebagaimana teori yang telah dijabarkan pada Bab II, proses perancangan yang matang diperlukan untuk mengintegrasikan logika Metode Convolutional Neural Network (CNN) dan Metode Decision Tree ke dalam arsitektur sistem berbasis framework Laravel (Dhiecho Mahar Dhiecha & Sukwadi, 2024).
Perancangan ini difokuskan pada sinkronisasi variabel teknis antara citra fisik dan parameter dokumen DED untuk meminimalisir subjektivitas dalam penentuan prioritas penanganan infrastruktur (Karimah, 2025; Ratino at.al, 2023). Secara teknis, rancangan ini mengatur alur penerapan algoritma secara sekuensial, di mana fitur visual yang diekstraksi melalui flatten layer pada Metode CNN ditransformasikan menjadi dataset tabular. Data tersebut kemudian diintegrasikan dengan variabel atribut DED sebagai input bagi Metode Decision Tree untuk menghasilkan status prioritas perbaikan (Fatimah Ahmad et al., 2025). Tahapan pengembangan ini secara komprehensif mencakup pendefinisian kebutuhan fungsional dan kebutuhan non-fungsional, serta pemodelan interaksi aktor melalui Use Case Diagram (Yuniarti et al., 2022).

3.4	Analisis Kebutuhan
Analisis kebutuhan dilakukan untuk menentukan fungsionalitas sistem agar selaras dengan kebutuhan instansi dalam memetakan infrastruktur permukiman (Yuniarti et al., 2022). Rincian kebutuhan fungsional dan non-fungsional pada sistem ini adalah sebagai berikut: 
1.	Kebutuhan fungsional
a.	Sistem Autentikasi: Sistem harus mampu mengelola hak akses login dan logout untuk memastikan keamanan data berdasarkan peran Admin, Surveyor, dan Tim Teknis (Yuniarti et al., 2022).
b.	Manajemen Profil: Sistem mampu mengelola informasi data diri pengguna guna membedakan identitas aktor dalam operasional sistem (Yuniarti et al., 2022).
c.	Pengolahan Data DED: Sistem menyediakan fitur untuk mengunggah dan mengelola dokumen teknis DED serta citra lapangan sebagai variabel input utama analisis  (Karimah, 2025).
d.	Ekstraksi Fitur Otomatis CNN: Sistem memiliki kemampuan memproses citra (ukuran 224 x 224 piksel) melalui flatten layer pada Metode CNN guna menghasilkan vektor fitur numerik secara objektif (Mulyana & Wahyudi, 2025).
e.	Klasifikasi Prioritas Decision Tree: Sistem mampu mengolah gabungan data fitur citra dan atribut DED menjadi kategori prioritas penanganan (Baik, Rusak Sedang, Rusak Berat) secara akurat melalui Metode Decision Tree (Karimah, 2025).
f.	Visualisasi Geografis (SIG): Sistem dapat menampilkan hasil analisis dalam bentuk titik koordinat pada peta interaktif berbasis library Leaflet.js guna mendukung monitoring wilayah secara sistematis (Prihantara et al., 2023).
2.	Kebutuhan non-fungsional
a.	Keamanan (Security): Sistem wajib memiliki mekanisme enkripsi pada kata sandi pengguna untuk memastikan integritas informasi (Yuniarti et al., 2022).
b.	Kemudahan Penggunaan (Usability): Antarmuka pengguna (User Interface) dirancang secara intuitif guna memastikan staf instansi dapat mengoperasikan fitur pemetaan dan analisis AI secara optimal  (Pratama et al., 2023; Prihantara et al., 2023).
c.	Ketersediaan (Availability): Sistem berbasis web ini harus dapat diakses secara stabil melalui berbagai peramban (web browser) guna mendukung fleksibilitas pemantauan data infrastruktur di lingkungan pemerintahan (Prihantara et al., 2023).
d.	Akurasi Klasifikasi (Performance): Integrasi Metode CNN dan Metode Decision Tree harus memastikan hasil klasifikasi memiliki tingkat akurasi yang tinggi sesuai parameter teknis (Karimah, 2025).
e.	Efisiensi Pengolahan Data: Proses transformasi data citra menjadi tabular harus memiliki waktu komputasi yang terukur agar hasil keputusan dapat muncul segera setelah data diunggah (Mulyana & Wahyudi, 2025).

3.5	Desain Sistem
Setelah tahap analisis selesai dan kebutuhan sistem telah diperoleh, dilakukan rancangan sistem yang meliputi use case, rancangan basis data, dan rancangan antarmuka. Tahap desain ini bertujuan untuk menyusun gambaran sistem secara terstruktur sebagai acuan dalam proses pengembangan, sehingga implementasi dapat berjalan lebih terarah serta memastikan sistem yang dibangun sesuai dengan kebutuhan pengguna (Yuniarti et al., 2022).
1.	Use Case Diagram
Use Case Diagram mendeskripsikan interaksi antara aktor (user) terhadap fungsionalitas sistem informasi pemetaan ini. Perancangan use case ini menjadi krusial untuk memastikan alur integrasi antara penginputan data lapangan dengan proses analisis Metode Convolutional Neural Network (CNN) dan Metode Decision Tree berjalan secara sinkron (Prihantara et al., 2023).
a.	Use Case Diagram Admin
Use Case Diagram Admin menggambarkan ruang lingkup otoritas penuh dalam pengelolaan sistem, keamanan data, dan pemeliharaan basis data (Yuniarti et al., 2022). Admin bertanggung jawab penuh terhadap validasi data pengguna dan parameter master guna menjamin operasional sistem berjalan secara optimal.

 
Gambar 3.2     Use Case Diagram Admin

 Tabel 3.1     Use Case Login Admin
Use case	Login
Aktor	Admin
Deskripsi	Proses bagi Admin untuk memverifikasi identitas dan mendapatkan hak akses masuk ke dalam sistem.
Pra kondisi	Admin berada di halaman login dan belum masuk ke sistem.
Pasca kondisi	Admin berhasil terautentikasi dan mendapatkan akses ke halaman Dashboard utama.
Alur	1.	Admin mengakses URL halaman web sistem.
2.	Sistem menampilkan form isian Login.
3.	Admin memasukkan kredensial (Email dan Password) lalu menekan tombol "Masuk".
4.	Sistem melakukan pengecekan data di database.
5.	Jika valid, sistem memunculkan notifikasi berhasil dan mengarahkan Admin ke Dashboard.

Tabel 3.2     Use Case Mengelola Data User
Use case	Mengelola Data User
Aktor	Admin
Deskripsi	Admin dapat menambah akun baru, melihat detail, mengedit, dan menghapus akun pengguna (termasuk role Surveyor dan Tim Teknis).
Pra kondisi	Admin sudah terautentikasi dan berada di halaman Dashboard sistem.
Pasca kondisi	Data pengguna (user) pada database berhasil diperbarui sesuai dengan aksi yang dilakukan Admin.
Alur	1.	Admin memilih menu "Manajemen Pengguna" di sidebar.
2.	Sistem menampilkan daftar seluruh akun yang terdaftar.
3.	Admin memilih salah satu aksi (misal: "Tambah Pengguna").
4.	Admin mengisi form identitas pengguna baru.
5.	Admin menekan tombol "Simpan".
6.	Sistem memvalidasi form dan menyimpan data ke database.
7.	Sistem menampilkan pesan sukses di layar.

Tabel 3.3     Use Case Mengelola Data Wilayah
Use case	Mengelola Data Wilayah
Aktor	Admin
Deskripsi	Admin mengatur dan mendata batas wilayah-wilayah administratif (seperti nama kecamatan) beserta data area (polygon) wilayahnya.
Pra kondisi	Admin sudah terautentikasi ke dalam sistem.
Pasca kondisi	Basis data wilayah terbaru tersimpan dan langsung diterapkan pada visualisasi pemetaan (WebGIS).
Alur	1.	Admin menekan menu "Manajemen Wilayah".
2.	Sistem memunculkan tabel wilayah yang telah tercatat.
3.	Admin menekan aksi tambah/ubah batas koordinat sebuah area.
4.	Admin mengisi data nama wilayah beserta data spasial (Polygon).
5.	Admin menekan “Simpan”
6.	Sistem memperbarui peta dan tabel wilayah.

 Tabel 3.4     Use Case Mengelola Data Infrastruktur
Use case	Mengelola Data Infrastruktur
Aktor	Admin
Deskripsi	Admin mendaftarkan dan memelihara pangkalan data (master data) infrastruktur fisik berserta rincian kondisinya.
Pra kondisi	Admin memiliki akses aktif ke dalam sistem.
Pasca kondisi	Basis data titik infrastruktur beserta informasi kondisinya selalu Real-Time.
Alur	1.	Admin mengklik menu "Manajemen Infrastruktur".
2.	Sistem menampilkan tabel data infrastruktur eksisting.
3.	Admin menekan menu Tambah Infrastruktur.
4.	Admin memasukkan detail meliputi deskripsi, status, gambar, dan koordinat lokasi (map).
5.	Sistem memproses penyimpanan data.
6.	Sistem menampilkan notifikasi berhasil dan menampilkan data tersebut pada daftar.

Tabel 3.5     Use Case Meninjau Laporan Warga
Use case	Meninjau Laporan Warga
Aktor	Admin
Deskripsi	Admin melihat dan melakukan penyaringan (screening) terhadap aduan masyarakat sebelum diteruskan kepada Tim Teknis atau Surveyor.
Pra kondisi	Terdapat laporan warga yang masuk ke dalam sistem dengan status "Menunggu".
Pasca kondisi	Laporan warga selesai ditinjau dan statusnya berubah (misal: Ditolak atau Diverifikasi untuk tindak lanjut).
Alur	1.	Admin memilih menu "Laporan Warga".
2.	Sistem menampilkan daftar laporan yang masih menumpuk.
3.	Admin membuka detail laporan salah satu warga.
4.	Admin memverifikasi lampiran foto serta informasi lokasi aduan.
5.	Admin memilih opsi untuk menerima atau menolak laporan.
6.	Sistem memperbarui dan menyiarkan status laporan tersebut.

Tabel 3.6     Use Case Melihat Dashboard Statistik
Use case	Melihat Dashboard Statistik
Aktor	Admin
Deskripsi	Menampilkan ringkasan data infrastruktur secara visual dalam bentuk grafik atau diagram.
Pra kondisi	Admin sudah login dan data lapangan sudah diinput oleh Surveyor.
Pasca kondisi	Admin mendapatkan gambaran umum kondisi infrastruktur kota.
Alur	1.	Admin memilih menu Dashboard.
2.	Sistem melakukan agregasi data dari hasil klasifikasi Decision Tree.
3.	Sistem menyajikan grafik persentase tingkat kerusakan dan prioritas perbaikan.



Tabel 3.7     Use Case  Memantau Statistik Sistem
Use case	Memantau Statistik Sistem
Aktor	Admin
Deskripsi	Admin memonitor kinerja pelaporan serta tren penyelesaian perbaikan infrastruktur dalam wujud metrik dan grafik (Ringkasan/Tahunan).
Pra kondisi	Admin sudah terautentikasi dan berada di dalam sistem.
Pasca kondisi	Admin memperoleh wawasan analisis performa dari pengolahan data sistem saat ini.
Alur	Admin menavigasi ke menu "Ringkasan Statistik" atau "Statistik Tahunan".
Sistem melakukan agregasi (kalkulasi) data jumlah pelaporan dan status perbaikan secara real-time..
Sistem menampilkan wujud visualnya berupa grafik interaktif.
Admin melakukan filter data laporan berdasarkan parameter waktu atau kategori.

Tabel 3.8     Use Case Memantau Log Aktivitas
Use case	Memantau Log Aktivitas
Aktor	Admin
Deskripsi	Admin memeriksa riwayat jejak digital seluruh tindakan operasional (audit trail) di dalam sistem yang dilakukan oleh user manapun.
Pra kondisi	Admin sudah terautentikasi dan berada di dalam sistem.
Pasca kondisi	Admin mendapatkan detail rekam jejak yang dapat dimanfaatkan untuk keperluan audit keamanan.
Alur	1.	Admin memilih menu "Log Aktivitas".
2.	Sistem menarik catatan riwayat interaksi dari tabel log.
3.	Sistem menampilkannya pada tabel kronologis yang mencakup nama user, jenis aksi, dan waktu aksi. Admin melihat pratinjau dan mengunduh laporan.
4.	Admin meninjau jejak-jejak operasional dari tampilan tabel tersebut.

Tabel 3.9     Use Case Pengujian Simulasi AI
Use case	Pengujian Simulasi AI
Aktor	Admin
Deskripsi	Admin melakukan tes deteksi pengenalan tipe kerusakan (seperti jalan berlubang) memanfaatkan algoritma Artificial Intelligence (AI).
Pra kondisi	Admin login, dan API (endpoint model AI) terhubung ke sistem.
Pasca kondisi	Admin memperoleh hasil klasifikasi dan akurasi kerusakan dari gambar yang diuji.
Alur	1.	Admin masuk ke menu "Simulasi Model AI".
2.	Admin mengunggah sampel foto kerusakan.
3.	Admin menekan tombol analisis.
4.	Sistem mengirimkan gambar tersebut menuju mesin AI terintegrasi.
5.	Sistem menampilkan kelas kerusakan dan persentase confidence (kepercayaan) dari prediksi AI.



Tabel 3.10      Use Case Mengelola Pengaturan Sistem
Use case	Mengelola Pengaturan Sistem
Aktor	Admin
Deskripsi	Admin mengonfigurasi parameter core (inti) sistem seperti identitas kontak bantuan, koordinat pusat peta spasial, serta Token integrasi Notifikasi Bot WhatsApp.
Pra kondisi	Admin sedang berada dalam sistem dan memiliki otorisasi penuh (Super User).
Pasca kondisi	Preferensi dan setting variabel secara internal diperbarui.
Alur	1.	Admin menekan opsi "Pengaturan" pada menu.
2.	Sistem menampilkan antarmuka form setting dasar.
3.	Admin mengubah input nilai kontak atau menyisipkan Token Fonnte terbaru.
4.	Admin menekan "Simpan".
5.	Sistem menyimpan konfigurasi pada database atau file lingkungan.
6.	Pesan konfirmasi keberhasilan tampil pada layar Admin.

Tabel 3.11     Use Case Log Out
Use case	Log Out
Aktor	Admin
Deskripsi	Proses pemutusan sesi kerja Admin untuk keluar dari lingkungan sistem secara aman.
Pra kondisi	Admin dalam keadaan login dan sedang aktif mengakses antarmuka sistem.
Pasca kondisi	Seluruh autentikasi sesi (cookie/session) Admin telah dibersihkan dan sistem mengarahkan ulang ke gerbang awal (halaman login).
Alur	1.	Admin mengklik tombol / menu "Log Out" di bagian bawah layar.
2.	Sistem memvalidasi penghapusan sesi (session destroy) dari sisi server.
3.	Sistem mengosongkan status terautentikasi pengguna tersebut.
4.	Sistem melakukan perutean (redirect) tampilan kembali ke antarmuka masuk/login.

b.	Use Case Diagram Surveyor
Use Case Diagram Surveyor difokuskan pada aktivitas pengumpulan data primer secara teknis di lapangan. Peran ini sangat krusial dalam sistem informasi kota cerdas karena citra fisik yang diunggah oleh Surveyor menjadi variabel input utama bagi Metode Convolutional Neural Network (CNN) untuk proses ekstraksi fitur otomatis guna mengenali karakteristik infrastruktur secara objektif (Mulyana & Wahyudi, 2025).
 
Gambar 3.3     Use Case Diagram Surveyor

Tabel 3.12      Use Case Login Surveyor
Use case	Login 
Aktor	Surveyor
Deskripsi	Proses autentikasi agar Surveyor dapat mengakses halaman dashboard khusus petugas lapangan.
Pra kondisi	Surveyor telah memiliki akun yang didaftarkan oleh Admin dan berada di halaman login.
Pasca kondisi	Surveyor berhasil masuk ke dalam sistem dengan hak akses penuh sebagai petugas lapangan.
Alur	1.	Surveyor memasukkan Email dan Password
2.	Sistem memvalidasi kredensial.
3.	Jika valid, sistem mengarahkan Surveyor ke halaman Dashboard.

Tabel 3.13    Use Case Mengelola Profil dan Wilayah Tugas
Use case	Mengelola Profil dan Wilayah Tugas
Aktor	Surveyor
Deskripsi	Proses memperbarui data diri, kata sandi, dan memilih kelurahan/wilayah tempat penugasan operasional.
Pra kondisi	Surveyor telah berhasil login ke dalam sistem.
Pasca kondisi	Data profil dan wilayah penugasan terbaru berhasil disimpan ke dalam basis data.
Alur	1.	Surveyor masuk ke menu Pengaturan Akun (Profil).
2.	Surveyor mengubah data diri atau memilih wilayah tugas dari daftar.
3.	Surveyor mengklik tombol simpan
4.	Sistem memperbarui data dan menampilkan notifikasi sukses.
Tabel 3.14    Use Case Melihat Dashboard (Statistik)
Use case	Melihat Dashboard
Aktor	Surveyor
Deskripsi	Surveyor melihat ringkasan aktivitas surveinya secara visual dan real-time.
Pra kondisi	Surveyor telah berhasil login ke dalam sistem.
Pasca kondisi	Sistem menampilkan informasi statistik survei secara lengkap.
Alur	1.	Surveyor menekan menu Dashboard.
2.	Sistem menghitung total laporan, status validasi, dan laporan warga.
3.	Sistem menampilkan angka-angka tersebut di layar.

Tabel 3.15    Use Case Melihat Penugasan Laporan Warga
Use case	Melihat Penugasan Laporan Warga
Aktor	Surveyor
Deskripsi	Surveyor memeriksa daftar keluhan/laporan kerusakan infrastruktur yang dikirimkan oleh warga, khusus pada wilayah tugasnya.
Pra kondisi	Surveyor telah mengatur wilayah tugas pada menu profil.
Pasca kondisi	Surveyor mendapatkan informasi titik lokasi yang harus divalidasi/disurvei.
Alur	1.	Surveyor masuk ke menu Penugasan Laporan Warga
2.	Sistem memfilter laporan warga berdasarkan kelurahan/kecamatan Surveyor.
3.	Surveyor menekan salah satu laporan untuk melihat foto dan detail keluhan.

Tabel 3.16    Use Case Data Lapangan
Use case	Mengelola Riwayat Input
Aktor	Surveyor
Deskripsi	Melihat kembali data yang pernah diinput atau melakukan koreksi jika ada kesalahan data lapangan.
Pra kondisi	Surveyor sudah login.
Pasca kondisi	Surveyor melihat status data yang sudah dikirim (Terverifikasi/Belum).
Alur	1.	Surveyor membuka menu Riwayat.
2.	Sistem menampilkan daftar data yang pernah dikirim.
3.	Surveyor memilih salah satu data untuk dilihat atau diedit.
4.	Sistem memperbarui data jika ada perubahan.

Tabel 3.17    Use Case Mengelola Riwayat Data Survei
Use case	Mengelola Riwayat Data Survei
Aktor	Surveyor
Deskripsi	Surveyor memeriksa daftar seluruh data infrastruktur yang pernah ia kumpulkan beserta status persetujuannya (Validasi Admin).
Pra kondisi	Surveyor minimal telah melakukan satu kali input data lapangan.
Pasca kondisi	Surveyor mengetahui status dari setiap laporan (Valid, Menunggu, atau Ditolak).
Alur	1.	Surveyor membuka Riwayat Data Saya.
2.	Sistem menampilkan tabel daftar riwayat.
3.	Surveyor dapat memfilter tabel berdasarkan status validasi.
Tabel 3.18    Use Case Mengedit Data Survei yang Ditolak
Use case	Mengedit Data Survei yang Ditolak
Aktor	Surveyor
Deskripsi	Proses perbaikan data survei yang dikembalikan oleh Admin karena tidak sesuai standar atau salah.
Pra kondisi	Terdapat minimal satu data survei berstatus "Ditolak/Revisi".
Pasca kondisi	Data berhasil diperbaiki dan statusnya kembali menjadi Pending (Menunggu Validasi).
Alur	1.	Surveyor melihat riwayat data berstatus "Ditolak".
2.	 Surveyor menekan tombol Edit dan membaca catatan alasan penolakan dari Admin.
3.	Surveyor mengoreksi nilai yang salah
4.	Surveyor menyimpan pembaruan data.

Tabel 3.19    Melihat Peta Sebaran
Use case	Melihat Peta Sebaran
Aktor	Surveyor
Deskripsi	Visualisasi titik-titik infrastruktur yang telah disurvei di atas antarmuka peta digital yang interaktif
Pra kondisi	Sistem memiliki data titik koordinat (Latitude/Longitude) yang valid.
Pasca kondisi	Titik koordinat berhasil dirender sebagai marker pada peta.
Alur	1.	 Surveyor membuka menu Peta Sebaran.
2.	 Sistem memuat peta menggunakan modul Leaflet JS.
3.	Sistem meletakkan marker berdasarkan koordinat dengan warna sesuai tingkat kerusakan.
4.	Surveyor dapat menekan marker untuk melihat detail popup atau mengubah jenis peta dasar (Satelit/Jalan).

Tabel 3.20    Log Out
Use case	Log Out
Aktor	Surveyor
Deskripsi	Proses mengakhiri sesi untuk mencegah akses tidak sah ke dalam akun Surveyor.
Pra kondisi	Surveyor sedang dalam status login.
Pasca kondisi	Sesi pengguna dihapus, sistem kembali menampilkan halaman Login.
Alur	1.	Surveyor menekan tombol Logout pada sidebar menu.
2.	Sistem menghapus session.
3.	Sistem mengalihkan pengguna ke halaman utama/Login.

c.	Use Case Diagram Tim Teknis
Use Case Diagram Tim Teknis menggambarkan peran strategis sebagai pengambil keputusan (decision maker) dalam lingkup kebijakan pembangunan infrastruktur. Fitur utama yang diakses adalah monitoring hasil analisis yang telah diproses secara sekuensial oleh Metode CNN dan Metode Decision Tree untuk menentukan prioritas penanganan secara akurat dan objektif (Karimah, 2025). Tim teknis bertanggung jawab atas validasi akhir data guna menjamin akuntabilitas laporan sebelum dipublikasikan sebagai kebijakan resmi.

 
Gambar 3.4     Use Case Diagram Tim teknis

Tabel 3.21     Use Case Login Tim teknis
Use case	Login 
Aktor	Tim teknis
Deskripsi	Proses autentikasi bagi Tim Teknis untuk masuk ke dalam sistem GEO-SINFRA agar mendapatkan hak akses pengambil keputusan.
Pra kondisi	Tim Teknis belum masuk ke dalam sistem dan berada di halaman login.
Pasca kondisi	Tim Teknis berhasil masuk ke sistem dan diarahkan ke halaman Dashboard utama.
Alur	1.	Tim Teknis membuka halaman login.
2.	Tim Teknis memasukkan email dan password.
3.	Tim Teknis menekan tombol login.
4.	Sistem memvalidasi data kredensial.
5.	Jika valid, sistem menampilkan halaman Dashboard.

Tabel 3.22    Use Case Memvalidasi Hasil Analisis
Use case	Memvalidasi Hasil Analisi
Aktor	Tim teknis
Deskripsi	Tim Teknis melakukan peninjauan terhadap hasil identifikasi kerusakan infrastruktur (Metode CNN) dan klasifikasi tingkat keparahannya (Metode Decision Tree). 
Pra kondisi	Tim Teknis sudah login dan sistem telah menghasilkan skor analisis AI dari data lapangan.
Pasca kondisi	Data analisis AI tervalidasi kelayakannya untuk dijadikan dasar penentuan prioritas.
Alur	1.	Tim Teknis memilih menu validasi data analisis.
2.	Sistem menampilkan daftar infrastruktur beserta hasil klasifikasi AI (CNN & Decision Tree).
3.	Tim Teknis memeriksa akurasi perhitungan sistem terhadap foto lapangan.
4.	Tim Teknis memvalidasi hasil analisis tersebut.

Tabel 3.23    Use Case Monitoring Dashboard Statistik
Use case	Monitoring Dashboard Statistik
Aktor	Tim teknis
Deskripsi	Tim Teknis melihat ringkasan statistik terkait total infrastruktur, tingkat kerusakan, dan status perbaikan dalam bentuk grafik atau angka real-time.
Pra kondisi	Tim Teknis telah berhasil login ke dalam sistem.
Pasca kondisi	Tim Teknis mendapatkan pemahaman (insight) secara umum terkait kondisi infrastruktur saat ini.
Alur	1.	Tim Teknis mengakses menu Dashboard.
2.	 Sistem mengambil data dari database.
3.	Sistem menampilkan visualisasi statistik (jumlah data tervalidasi, persentase Baik, Rusak Sedang, Rusak Berat).
4.	Tim Teknis melakukan pemantauan (monitoring) dari informasi tersebut.

Tabel 3.24     Use Case Visualisasi Peta Sebaran
Use case	Visualisasi Peta Sebaran
Aktor	Tim teknis
Deskripsi	Tim Teknis memantau titik-titik koordinat lokasi infrastruktur yang mengalami kerusakan di atas peta interaktif (Web GIS).
Pra kondisi	Tim Teknis sudah login dan infrastruktur telah memiliki titik koordinat (latitude/longitude).
Pasca kondisi	Tim Teknis mengetahui titik spesifik lokasi kerusakan untuk pertimbangan logistik dan kebijakan.
Alur	1.	Tim Teknis memilih menu Peta/Monitoring.
2.	Sistem merender peta (GIS) dan menampilkan titik-titik (marker) infrastruktur.
3.	Tim Teknis mengklik salah satu titik untuk melihat popup detail informasi lokasi.

Tabel 3.25    Use Case Melihat Laporan
Use case	Melihat Laporan 
Aktor	Tim teknis
Deskripsi	Tim Teknis membuka detail laporan infrastruktur komprehensif yang dikirim oleh surveyor (mencakup foto kondisi fisik, deskripsi, dan lokasi).
Pra kondisi	Tim Teknis sudah berada di menu daftar laporan/infrastruktur.
Pasca kondisi	Tim Teknis mendapatkan rincian informasi utuh mengenai kondisi suatu aset infrastruktur.
Alur	1.	Tim Teknis mengakses daftar laporan infrastruktur.
2.	Tim Teknis menekan tombol "Detail" pada salah satu data.
3.	Sistem menampilkan rincian laporan (foto, deskripsi, riwayat status perbaikan).
Tabel 3.26    Use Case Menyetujui Laporan
Use case	Menyetujui  Laporan 
Aktor	Tim teknis
Deskripsi	Tim Teknis memberikan validasi akhir (menyetujui) kelayakan data pelaporan agar sah dipublikasikan sebagai dasar kebijakan pembangunan.
Pra kondisi	Tim Teknis sedang melihat detail laporan dan data dianggap valid.
Pasca kondisi	Status laporan berubah menjadi Sah (Verified) / Disetujui.
Alur	1.	Tim Teknis memeriksa kesesuaian antara foto lapangan, deskripsi, dan hasil analisis AI.
2.	Tim Teknis menekan tombol "Setujui" (Approve/Verify).
3.	Sistem menyimpan perubahan status ke dalam database.
4.	Sistem memberikan notifikasi bahwa laporan telah disetujui.

Tabel 3.27    Use Case  Logout
Use case	Log Out
Aktor	Tim teknis
Deskripsi	Proses bagi Tim Teknis untuk mengakhiri sesi akses (keluar) dari sistem demi menjaga keamanan data dan mencegah akses tidak sah.
Pra kondisi	Tim Teknis sedang berada di dalam sistem (telah login).
Pasca kondisi	Sesi akun Tim Teknis berakhir dan sistem mengarahkannya kembali ke halaman login utama.
Alur	1.	Tim Teknis mengklik foto profil atau tombol Logout di sudut layar.
2.	Sistem menghapus sesi (session) pengguna yang sedang aktif.
3.	Sistem mengarahkan layar kembali ke halaman Login utama.

3.6	Rancangan Database
Desain database dilakukan untuk merancang struktur data serta relasi antar data yang dibutuhkan dalam pengembangan sistem ini. Perancangan database yang sistematis sangat penting untuk memastikan bahwa data atribut infrastruktur dan hasil pemrosesan AI dapat terintegrasi secara optimal guna mendukung pengambilan keputusan yang akurat (D. A. Wati & A. K. Garside., 2021). Desain database sistem ini terdiri dari beberapa tahapan pemodelan sebagai berikut:
1.	Entity Relationship Diagram (ERD)
ERD digunakan untuk memvisualisasikan hubungan antar entitas di dalam sistem secara teknis. Dalam penelitian ini, ERD menggambarkan keterkaitan antara data pengguna, data infrastruktur permukiman, dan hasil analisis prioritas pembangunan guna menjamin integritas informasi (Prihantara et al., 2023). Relasi antar entitas dirancang untuk mendukung alur kerja sistem, di mana setiap data infrastruktur yang diinputkan memiliki keterkaitan dengan dokumen citra teknis (DED) yang kemudian diproses menjadi hasil klasifikasi prioritas melalui Metode tertentu (Karimah, 2025).
 
Gambar 3.5     Entity Relationship Diagram

2.	Conceptual Data Model (CDM)	
Conceptual Data Model (CDM) merepresentasikan konsep entitas utama dan kebutuhan informasi secara menyeluruh tanpa detail teknis penyimpanan (Yuniarti et al., 2022). CDM ini difokuskan untuk memetakan hubungan antara pengumpulan data lapangan dan parameter evaluasi infrastruktur guna menentukan prioritas pemeliharaan (D. A. Wati & A. K. Garside., 2021).
 
Gambar 3.6     Conceptual Data Model

3.	Logical Data Model (LDM)
Logical Data Model (LDM) merupakan tahapan pengembangan dari CDM yang menyertakan atribut unik (Primary Key), kunci tamu (Foreign Key), serta tipe data secara logis guna memastikan konsistensi dan integritas data dalam sistem (Yuniarti et al., 2022). Perancangan pada tahap ini sangat krusial agar struktur penyimpanan data siap mendukung proses ekstraksi fitur visual dan klasifikasi cerdas secara sistematis (Hidayat & Prasetya, 2024).

 
Gambar 3.7     Logical Data Model
4.	Physical Data Model (PDM
Physical Data Model (PDM) merupakan representasi fisik dari basis data yang siap diimplementasikan ke dalam DBMS MySQL guna menjamin stabilitas operasional sistem (Yuniarti et al., 2022). Rancangan ini mendefinisikan 
struktur penyimpanan fisik secara spesifik, termasuk penggunaan tipe data Double untuk menjaga akurasi koordinat spasial pada peta digital (Prihantara et al., 2023). Melalui penerapan batasan kunci tamu (Foreign Key), PDM memastikan integritas relasi antar tabel terjaga demi konsistensi alur data teknis (D. A. Wati & A. K. Garside., 2021). Selain itu, struktur fisik ini disiapkan untuk menampung hasil ekstraksi fitur Metode CNN serta label prioritas dari Metode Decision Tree guna mendukung efisiensi kueri saat menampilkan visualisasi sebaran infrastruktur secara real-time (Mulyana & Wahyudi, 2025).
 
Gambar 3.8     Physical Data Mode

5.	Struktur Tabel Database
Relasi tabel database menggambarkan keterkaitan antar entitas data untuk memastikan integritas informasi dan kelancaran alur kerja sistem. Struktur ini memungkinkan data teknis dari lapangan yang dikumpulkan oleh Surveyor dapat dipetakan secara administratif dan diolah lebih lanjut menjadi dasar kebijakan (Yuniarti et al., 2022).
Tabel 3.28     Struktur Users
No	Field	Tipe Data	Size	Ket
1	id_user	bigint	20	Primary Key
2	id_kecamatan	bignit	20	Foreign Key
3	email	varchar	20	
5	no. hp	varchar	10	
6	email_verified_at	timestamp		
7	password	varchar	10	
8	otp_role	varchar	10	
11	otp_expires_at	timestamp		
12	role	enum (‘admin’, ‘surveyor’, ‘tim_teknis’)		
13	profile_photo	varchar	255	
14	remember_token	varchar	100	
15	created_at	timestamp		
16	update_at	timestamp		

Tabel 3.29     Struktur Kelurahan
No	Field	Tipe Data	Size	Ket
1	id_kelurahan	bigint	20	Primary Key
2	id_kecamatan	bignit	20	Foreign Key
3	nama_kelurahan	varchar	50	
4	geometri	json		
5	update_at	timestamp		
6	deleted_at	timestamp		

Tabel 3.30     Struktur Infrastruktur
No	Field	Tipe Data	Size	Ket
1	id_infrastruktur	bigint	20	Primary Key
2	id_user	bignit	20	Foreign Key
3	id_kelurahan	bignit	20	Foreign Key
4	nama_objek	varchar	255	
5	alamat	text		
6	latitude	decimal	10,8	
7	langitude	decimal	11,8	
8	nama_infrastruktur	varchar	100	
9	foto_terbaru	varchar	255	
10	jenis	enum(‘jalan’, ‘titian’, ‘jembatan’)		
11	foto_kondisi	text		
12	status_verifikasi	varchar 	255	
13	status_validasi	enum(‘pending’,’validate’, ‘rejected’)		
14	alasan_penolakan	text		
15	status_perbaikkan	varchar	255	
16	panjang	double		
17	lebar	double		
18	has_drainase	varchar	10	
19	has_gorong-gorong	varchar	10	
20	rencana_perbaikan	text		
21	tanggal_survey	date		
22	createad_at	timestamp		
23	update_at	timestamp		
24	deleted_at	timestamp		

Tabel 3.31     Struktur Tabel citra_cnn
No	Field	Tipe Data	Size	Ket
1	id_citra_cnn	bigint	20	Primary Key
2	id_infrastruktur	biginit	20	Foreign Key
3	id_user	bignit	20	Foreign Key
4	file_foto	varchar	255	
5	label_kondisi	varchar	255	
6	skor_cnn	decimal	5,2	
7	created_at			
8	update_at			
9	deleted_at			

Tabel 3.32     Struktur analisis_ai
No	Field	Tipe Data	Size	Ket
1	id_analisis_ai	bigint	20	Primary Key
2	id_infrastruktur	biginit	20	Foreign Key
3	id_tim_teknis	bignit	20	Foreign Key
4	param_kondisi	text	5,2	
5	param_kepadatan	varchar	50	
6	skor_dt	decimal	5,2	
7	label_prioritas	varchar	100	
8	rekomendasi	text		
9	status_validasi	varchar	255	
10	tanggal_validasi	datetime		
11	created_at	timestamp		
12	update_at	timestamp		
13	deleted_at	timestamp		

Tabel 3.33    Struktur Activity_Logs
No	Field	Tipe Data	Size	Ket
1	id_activity_logs	bigint	20	Primary Key
2	id_user	biginit	20	Foreign Key
3	id_refrence	bignit	20	Foreign Key
4	type	varchar	255	
5	description	varchar	50	
6	created_at	timestamp		
7	update_at	timestamp		


Tabel 3.34    Struktur Kecamatan
No	Field	Tipe Data	Size	Ket
1	id_kecamatan	bigint	20	Primary Key
2	nama_kecamatan	varchar	100	
3	geometri	json	20	
4	warna	varchar	7	
5	created_at	timestamp	50	
6	update_at	timestamp		
7	deleted_at	timestamp		


Tabel 3.35    Struktur Laporan_Warga
No	Field	Tipe Data	Size	Ket
1	id_laporan_warga	bignit	20	Primary Key
2	id_infrastruktur	bignit	20	Foreign Key
3	id_user	bignit	20	Foreign Key
4	nama_pelapor	varchar	50	
5	no_hp	varchar	20	
6	deskripsi	text		
7	foto	varchar	255	
8	skor_ai	decimal	5,2	
9	label_ai	varchar	100	
10	jenis_ai	varchar	100	
11	latitude	decimal	10,8	
12	longitude	decimal	11,8	
13	status	enum(‘Menunggu’, ‘ditinjau’. ‘proses’, ‘selesai’)		
14	created_at	timestamp		
15	update_at	timestamp		
16	deleted_at	timestamp		

Tabel 3.36    Struktur User_Kecamatan
No	Field	Tipe Data	Size	Ket
1	id_user_kecamatan	bigint	20	Primary Key
2	id_user	biginit	20	Foreign Key
3	id_kecamatan	bignit	20	Foreign Key
4	created_at	timestamp		
5	update_at	timestamp		

3.7	Rancangan Wireframe
Rancangan wireframe dilakukan untuk mendefinisikan kerangka antarmuka sistem secara struktural guna memastikan setiap fungsi dapat diakses dengan navigasi yang efisien (Yuniarti et al., 2022). Berikut adalah detail rancangan antarmuka untuk setiap halaman sistem: 
1.	Rancangan Tampilan Dashboard
Tampilan dashboard merupakan antarmuka utama yang menyajikan navigasi untuk mengakses berbagai fitur sistem secara sistematis.
 
Gambar 3.9     Wireframe Dashboard

2.	Rancangan Tampilan Data Statistik
Halaman ini menyajikan ringkasan data statistik infrastruktur permukiman Kota Banjarmasin secara visual guna mendukung pemantauan data secara informatif. 
 
Gambar 3.10     Wireframe Data Statistik
3.	Rancangan Tampilan Peta Sebaran
Fitur ini berfungsi memvisualisasikan persebaran infrastruktur di Kota Banjarmasin melalui titik koordinat yang presisi pada peta interaktif berbasis Leaflet.js

 
Gambar 3.11     Wireframe Peta Sebaran
4.	Rancangan Tampilan Halaman Login
Halaman ini digunakan untuk autentikasi identitas melalui input email dan password guna memastikan akses sistem sesuai dengan hak akses pengguna.
 
Gambar 3.12     Wireframe Tampilan Login
5.	Rancangan Tampilan Halaman Registrasi
Fasilitas ini disediakan bagi pengguna baru untuk mendaftarkan akun sebelum dilakukan verifikasi otoritas oleh Admin.
 
Gambar 3.13     Wireframe Tampilan Registrasi
6.	Rancangan Tampilan Halaman Lupa Password
Fitur ini disediakan untuk memfasilitasi pengguna dalam melakukan pemulihan kata sandi jika terjadi kendala autentikasi.
 
Gambar 3.14     Wireframe Tampilan Lupa Password
7.	Rancangan Tampilan Dashboard Admin
Antarmuka ini merupakan pusat kendali bagi Administrator yang menyajikan ringkasan aktivitas sistem dan manajemen data master secara menyeluruh.
 
Gambar 3.15     Wireframe Tampilan Dashboard Admin
8.	Rancangan Tampilan Halaman Manajemen Pengguna
Halaman ini digunakan Admin untuk mengelola daftar pengguna, termasuk penentuan peran aktor sebagai Surveyor atau Tim teknis.
 
Gambar 3.16     Wireframe Tampilan Manajemen Pengguna
9.	Rancangan Tampilan Halaman Edit Data Pengguna
Halaman ini khusus digunakan jika admin perlu mengubah informasi profil atau memperbarui level akses seorang pengguna.
 
Gambar 3.17     Wireframe Tampilan Edit Data Pengguna
10.	Rancangan Tampilan Halaman Data Master Wilayah
Halaman ini menyajikan daftar wilayah administratif (Kecamatan dan Kelurahan) yang menjadi referensi lokasi bagi setiap objek infrastruktur.
 
Gambar 3.18     Wireframe Tampilan Data Master Wilayah
11.	Rancangan Tampilan Halaman Tambah Data Wilayah
Formulir ini digunakan untuk menambah data wilayah baru ke dalam database sistem.
 
Gambar 3.19     Wireframe Tampilan Data Wilayah
12.	Rancangan Tampilan Halaman Edit Data Wilayah
Jika ada perubahan informasi wilayah, halaman ini digunakan untuk melakukan koreksi agar data tetap akurat.
 
Gambar 3.20     Wireframe Tampilan Edit Data Wilayah
13.	Rancangan Tampilan Halaman Manajemen Infrastruktur
Pusat pengelolaan seluruh data infrastruktur permukiman yang sudah masuk, agar admin mudah dalam memantau dan mencari data tertentu.
 
Gambar 3.21     Wireframe Tampilan Manajemen Infrastruktur


14.	Rancangan Tampilan Halaman Tambah Data Infrastruktur
Halaman ini berisi formulir untuk memasukkan atribut teknis dari infrastruktur baru yang ditemui di lapangan.
 
Gambar 3.22     Wireframe Tampilan Tambah Data Infrastruktur
15.	Rancangan Tampilan Halaman Edit Data Infrastruktur
Halaman ini berfungsi untuk memperbarui detail teknis jika ada perubahan kondisi pada infrastruktur yang sudah terdata sebelumnya.
 
Gambar 3.23     Wireframe Tampilan Edit Data Infrastruktur
16.	Rancangan Tampilan Halaman Detail Infrastruktur
Halaman ini menampilkan seluruh informasi lengkap satu objek infrastruktur secara mendalam, termasuk titik koordinat dan fotonya.
 
Gambar 3.24     Wireframe Tampilan Detail Infrastruktur
17.	Rancangan Tampilan Halaman Dashboard Surveyor
Tampilan ringkas untuk surveyor agar mereka bisa melihat berapa banyak data yang sudah mereka kumpulkan dan status surveinya.
 
Gambar 3.25     Wireframe Tampilan Detail Infrastruktur
18.	Rancangan Tampilan Halaman Input Data Infrastruktur
Ini adalah fitur utama bagi surveyor untuk memasukkan hasil survei lapangan beserta dokumen citra teknis untuk diolah lebih lanjut.
 
Gambar 3.26     Wireframe Tampilan Input Data Infrastruktur
19.	Rancangan Tampilan Halaman Surveyor Data
Halaman ini berisi daftar riwayat data yang pernah diinput oleh surveyor yang bersangkutan sebagai arsip kerja mereka.
 
Gambar 3.27     Wireframe Tampilan Surveyor Data
20.	Rancangan Tampilan Halaman Surveyor Edit Data Infrastruktur
Jika surveyor merasa ada yang perlu diperbaiki dari hasil inputnya, mereka bisa melakukan revisi melalui halaman ini sebelum data dikunci.
 
Gambar 3.28     Wireframe Tampilan Surveyor Edit Data Infrastruktur
21.	Rancangan Tampilan Halaman Dashboard Tim teknis
Dashboard khusus pimpinan yang menampilkan ringkasan visual mengenai sebaran kondisi infrastruktur di wilayahnya.
 
Gambar 3.29    Wireframe Tampilan Dashboard Tim teknis
22.	Rancangan Tampilan Halaman Monitoring Peta Tim teknis
Halaman ini menampilkan peta interaktif yang memudahkan Tim teknis memantau lokasi infrastruktur berdasarkan filter wilayah tertentu.
 
Gambar 3.30     Wireframe Tampilan Monitoring Peta Tim teknis
23.	Rancangan Tampilan Validasi Data
Halaman ini menampilkan validasi data yang memudahkan tim teknis untuk melihat foto infrastruktur (Baik/Rusak) untuk membuat suatu keputusan.
 
Gambar 3.31     Wireframe Validasi Data
24.	Rancangan Tampilan Usulan Perbaikkan
Halaman ini menampilkan usulan perbaikan yang memudahkan tim teknis untuk meninjau dan meng acc infrastruktur.
 
Gambar 3.32     Wireframe Usulan Perbaikan


25.	Rancangan Tampilan Halaman Laporan Final
Halaman ini merupakan hasil akhir sistem yang menyajikan laporan daftar prioritas penanganan infrastruktur untuk kebutuhan pengambilan kebijakan.
 
Gambar 3.33     Wireframe Tampilan Laporan Final
3.8	Implementasi
Pada tahap ini, seluruh hasil rancangan mulai dari basis data hingga wireframe dikembangkan menjadi sebuah sistem fungsional menggunakan kode program. Implementasi ini bertujuan untuk mentransformasi konsep desain menjadi aplikasi yang dapat digunakan untuk melakukan pemetaan dan analisis infrastruktur secara nyata guna mendukung efisiensi birokrasi di lingkungan instansi  (Yuniarti et al., 2022). Sistem informasi pemetaan infrastruktur permukiman ini dibangun dengan spesifikasi teknologi sebagai berikut:
1.	Lingkungan Pengembangan (Development Environment): Proses pengkodean sistem dilakukan menggunakan Visual Studio Code (VSC) sebagai Integrated Development Environment (IDE) utama. Pemilihan VSC didasarkan pada ketersediaan berbagai ekstensi pendukung yang memungkinkan penulisan kode framework Laravel dan integrasi skrip analisis AI dilakukan secara sistematis. Seluruh proses pengembangan ini dijalankan secara lokal guna memastikan keamanan dan stabilitas kode sebelum diimplementasikan ke tahap produksi (Prihantara et al., 2023).
2.	Sisi Antarmuka (Front-end): Pengembangan antarmuka menggunakan HTML, CSS, dan JavaScript untuk membangun tampilan sistem yang responsif. HTML berfungsi menyusun struktur halaman, CSS untuk mengatur estetika tampilan, dan JavaScript untuk menangani interaksi dinamis seperti pengoperasian peta digital berbasis library Leaflet.js. Implementasi ini krusial agar sistem tetap stabil dan aksesibel saat dioperasikan oleh berbagai aktor  (D. A. Wati & A. K. Garside., 2021).
3.	Sisi Logika (Back-end): Proses pengolahan data pada sisi server dikembangkan menggunakan bahasa pemrograman PHP dengan Framework Laravel. Pemilihan Laravel didasarkan pada arsitektur MVC (Model-View-Controller) yang memfasilitasi pengelolaan data infrastruktur secara terstruktur serta integrasi fitur keamanan yang optimal (Mulyana & Wahyudi, 2025).
4.	Sisi Basis Data: Pengolahan data atribut teknis, data spasial, dan informasi wilayah dikelola secara langsung menggunakan sistem manajemen basis data (DBMS) MySQL. Penggunaan MySQL menjamin integritas relasi antar tabel serta mendukung efisiensi kueri dalam pemanggilan data koordinat yang tersimpan (Prihantara et al., 2023).
5.	Integrasi Kecerdasan Buatan: Implementasi Metode CNN dan Metode Decision Tree dilakukan secara sekuensial untuk menghasilkan klasifikasi prioritas secara otomatis: 
a.	Penerapan Metode CNN: Metode ini bekerja pada saat Surveyor mengunggah citra fisik infrastruktur. Sistem melakukan pra-pemrosesan citra (dimensi 224 X 224 piksel) untuk ekstraksi fitur otomatis.
b.	Penerapan Metode Decision Tree: Fitur numerik hasil ekstraksi citra dikombinasikan dengan variabel atribut DED sebagai basis data input guna menghasilkan label kondisi infrastruktur (Baik, Rusak Sedang, Rusak Berat) yang akurat. Berdasarkan label kondisi tersebut, sistem secara otomatis menurunkan label prioritas penanganan, yaitu: Rusak Berat → Prioritas Tinggi, Rusak Sedang → Prioritas Sedang, dan Baik → Prioritas Rendah (Karimah, 2025).

3.9	Pengujian
Setelah tahap implementasi selesai dikerjakan, dilakukan pengujian secara menyeluruh untuk memastikan sistem dapat berfungsi secara stabil serta memberikan hasil klasifikasi yang akurat. Pengujian ini dilaksanakan dengan menggunakan tiga pendekatan berbeda agar setiap aspek sistem, baik dari sisi teknis algoritma maupun pengalaman pengguna, dapat dipertanggungjawabkan secara ilmiah  (Yuniarti et al., 2022).

3.9.1	Blackbox Testing
Metode ini digunakan untuk menguji fungsionalitas sistem dari sisi pengguna tanpa memeriksa detail kode program. Fokus utama pengujian adalah memastikan bahwa setiap fitur, mulai dari pengelolaan data wilayah hingga proses validasi infrastruktur, memberikan respon yang tepat sesuai dengan skenario yang diharapkan (D. A. Wati & A. K. Garside., 2021). 

Tabel 3.37     Pengujian Blackbox Testing
NO	Fitur/Halaman	Tujuan Pengujian (Fitur)	Hasil yang Diharapkan (Expect Result)
1	Landing Page (Publik)	Akses halaman utama & Peta Interaktif	Menampilkan informasi umum dan sebaran titik infrastruktur di peta digital.
2	Laporan Warga (Publik)	Input form laporan kerusakan	Menampilkan informasi umum dan sebaran titik infrastruktur di peta digital.
3	Login	Input email & password	Warga dapat mengirim foto kerusakan dan sistem AI otomatis memberikan prediksi awal.
4	Registrasi (Akun Baru)	Pendaftaran & Verifikasi OTP WA	Pengguna masuk ke dashboard sesuai hak akses (Admin/Surveyor/Tim Teknis).
5	Lupa Password	Verifikasi reset password	Sistem mengirimkan instruksi pemulihan akun kepada pengguna.
6	Dashboard Admin	Monitoring statistik keseluruhan	Menampilkan ringkasan jumlah user, data wilayah, dan infrastruktur.
7	Pengaturan Sistem (Admin)	Integrasi API Fonnte (WhatsApp)	Admin dapat mengubah Token Fonnte dan nomor tujuan notifikasi WhatsApp.
8	Manajemen Pengguna (Admin)	List & Kontrol Akun	Admin dapat menambah, menghapus, dan mengelola hak akses (role) pengguna.
9	Edit Data Pengguna (Admin)	Update Profil User	Perubahan informasi akun pengguna berhasil diperbarui di database.
10	Data Master Wilayah (Admin)	Daftar Kecamatan & Kelurahan	Menampilkan data administratif sebagai batas acuan spasial pemetaan.
11	Tambah Data Wilayah (Admin)	Input Data Wilayah Baru	Data wilayah administratif baru berhasil tersimpan ke dalam sistem.
12	Edit Data Wilayah (Admin)	Koreksi Informasi Wilayah	Perubahan nama wilayah atau data geometri berhasil diperbarui.
13	Manajemen Infrastruktur	Pengelolaan Master Data		
14	Tambah Infrastruktur (Admin)	Input atribut teknis & spasial	Data aset baru berserta titik koordinatnya masuk ke dalam database.
15	Detail Infrastruktur	Tampilan Informasi Spesifik	Menampilkan informasi lengkap, foto kondisi, dan letak peta aset.
16	Dashboard (Surveyor)	Ringkasan Tugas Lapangan	Menampilkan progres input data dan daftar laporan warga yang harus disurvei.
17	Input Survei (Surveyor)	Upload Citra & Lapangan	Sistem menerima file foto, menghitung skor, dan mengirimkan notifikasi WA ke Tim Teknis.
18	Riwayat Data (Surveyor)	Pengecekkan Riwayat Input	Surveyor dapat melihat daftar infrastruktur yang telah ia laporkan/survei.
19	Edit Data Survei (Surveyor)	Revisi Data Lapangan	Surveyor dapat memperbaiki data atribut teknis sebelum divalidasi oleh Tim Teknis.
20	Dashboard (Tim Teknis)	Monitoring Prioritas Pemeliharaan	Menampilkan grafik kerusakan dan status penanganan infrastruktur secara real-time.
21	Validasi Laporan (Tim Teknis)	Evaluasi (ACC/Tolak) Laporan	Tim teknis dapat menerima/menolak laporan dan sistem mengirimkan WA hasil validasi.
22	Monitoring Peta (Tim Teknis)	Filter Spasial Wilayah Kerja	Marker peta hanya menampilkan infrastruktur di wilayah yang menjadi tanggung jawabnya.
23	Analisis Prioritas (Tim Teknis)	Penentuan Prioritas Otomatis	Sistem menghasilkan daftar prioritas pemeliharaan (Tinggi/Sedang/Rendah) untuk diunduh/dicetak.

3.9.2	Pengujian Performa Kecepatan Halaman (GTMetrix)
Pengujian performa dilakukan menggunakan tools website automated software testing GTMetrix untuk menganalisis dan mengetahui performa kecepatan beranda website secara otomatis (Mongkau, Berelaku, & Arni, 2023). Pengujian ini dirancang untuk menampilkan performance score yaitu skor kecepatan kinerja beranda website pada pagespeed, yslow, dan page detail (Fryonanda & Ahmad; Masyhur, 2014). Melalui pengukuran analisa kualitas menggunakan tools GTMetrix, sistem dapat diuji kemampuannya dalam melaksanakan minify CSS dan JavaScript guna membantu menambah kinerja website dengan memperbaiki format dan menghapus karakter yang tidak digunakan (Nurul Hima Hidayati, 2022; Tengriano dkk., 2022). Sebuah website dapat dikatakan berkualitas jika dilihat dari segi performance dan loading time guna membantu pengguna mengakses website agar lebih cepat dan efisien (Muchali & Budiarto, 2017).
Parameter evaluasi yang menjadi tolok ukur penilaian objektif di dalam perancangan pengujian GTMetrix ini mengacu pada beberapa metrik standar yang dijabarkan berdasarkan referensi dari Ariffudin (2022), Haryanto & Elsi (2021), Laipaka, serta Suryawan & Paramitha, yang meliputi:
1.	GTMetrix Grade dan Indikator Warna: Hasil pembagian skor analisa kinerja terdiri dari grade A, B, C, D, E, dan F, di mana hasil yang diperoleh semakin menurun hingga grade F jika analisa semakin kurang baik (Haryanto & Elsi, 2021; Laipaka; Suryawan & Paramitha). Penilaian ini disesuaikan dengan Indikator Warna, yaitu Hijau (91%–100%), Hijau Muda (76%–90%), Orange (51%–75%), dan Merah (0%–50%) (Nurul Hima Hidayati, 2022; Tengriano dkk., 2022).
2.	Performances Score: Menunjukkan persentase nilai kecepatan kinerja beranda website saat dianalisa (Mongkau dkk., 2023).
3.	Structure Score: Menunjukkan nilai persentase kualitas struktur penataan komponen pada halaman website 
4.	Largest Contentful Paint (LCP): Mengukur waktu yang dibutuhkan untuk warna konten terbesar tampil dengan sempurna, dengan standar maksimal dari GTMetrix yaitu selama 1,2 detik
5.	Total Blocking Time (TBT): Mengukur lama waktu pemblokiran yang dilakukan selama waktu loading, dengan standar maksimal dari GTMetrix yaitu sebesar 150 milisekon (Ariffudin, 2022; Mongkau dkk., 2023).
6.	Cumulative Layout Shift (CLS): Menunjukkan seberapa sering halaman website mengalami pergeseran tata letak kumulatif, dengan skor standar maksimal sebesar 0,1 (Ariffudin, 2022; Mongkau dkk., 2023).

3.9.3	Evaluasi Model (*Confusion Matrix* & *F1-Score*)
Evaluasi performansi terhadap hasil klasifikasi algoritma dilakukan untuk mengukur tingkat akurasi dan reliabilitas sistem secara objektif. Instrumen pengujian yang digunakan adalah *Confusion Matrix* multi-kelas yang dirancang sesuai dengan tiga kategori kondisi infrastruktur: Baik, Rusak Sedang, dan Rusak Berat.
Matriks ini berfungsi untuk memetakan hubungan antara prediksi sistem (luaran Metode CNN dan Metode *Decision Tree*) dengan data aktual kondisi infrastruktur guna menghitung variabel *F1-Score* sebagai parameter keseimbangan *precision* dan *recall* yang terukur (Hidayat & Prasetya, 2024).
Tabel 3.38      Rancangan *Confusion Matrix* Klasifikasi Kondisi Infrastruktur
	Prediksi Baik	Prediksi Rusak Sedang	Prediksi Rusak Berat
Aktual Baik	TPB	EBS	EBBr
Aktual Rusak Sedang	ESB	TPS	ESBr
Aktual Rusak Berat	EBrB	EBrS	TPBr

3.9.4	*System Usability Scale* (SUS)
Selain pengujian fungsi, tingkat kebergunaan (*usability*) sistem dari sudut pandang pengguna akhir diukur menggunakan Metode *System Usability Scale* (SUS). SUS merupakan instrumen pengujian *usability* yang terbukti valid serta reliabel untuk mengukur persepsi kemudahan penggunaan suatu sistem (Pratama et al., 2023). Lebih lanjut, berdasarkan hasil studi literatur terkini, perolehan skor SUS tidak hanya merefleksikan kepuasan subjektif pengguna, tetapi juga memiliki korelasi empiris yang signifikan terhadap efisiensi operasional sistem, di mana skor SUS yang tinggi terbukti menurunkan beban kerja kognitif (*workload*), mempersingkat waktu penyelesaian tugas (*task time*), dan menekan tingkat kesalahan interaksi (*error rate*) pengguna saat mengoperasikan sistem (Hertzum, 2026). Instrumen ini terdiri dari 10 butir pernyataan berskala Likert 1–5 yang disusun bergantian antara pernyataan bernada positif dan negatif.
Pengujian ini melibatkan sebanyak 10 orang responden yang dipilih secara *purposive sampling* dari pihak terkait yang menjadi calon pengguna akhir sistem, terdiri dari Surveyor lapangan, Tim Teknis Disperkim Kota Banjarmasin, serta masyarakat umum yang berpotensi menggunakan fitur pelaporan warga. Setiap responden diminta memberikan penilaian terhadap 10 butir pernyataan standar SUS setelah mencoba mengoperasikan sistem GEO-SINFRA secara langsung (Prihantara et al., 2023). Prosedur perhitungan skor yang diterapkan dalam pengujian ini adalah sebagai berikut:
1.	Skor Pertanyaan Ganjil (Positif): Dihitung berdasarkan (Skor Jawaban - 1).
2.	Skor Pertanyaan Genap (Negatif): Dihitung berdasarkan (5 - Skor Jawaban).
3.	Skor Akhir: Total skor dari seluruh pernyataan dikalikan dengan 2,5 untuk mendapatkan nilai akhir dengan rentang 0-100. Secara matematis dihitung mengacu pada Persamaan (2.5) sebagai berikut:
Skor SUS = [ Σ (Skor Ganjil - 1) + Σ (5 - Skor Genap) ] * 2,5

Tabel 3.39     Tabel Pernyataan System Usability Scale (SUS)
NO	Pernyataan	Sifat
1	Saya rasa saya akan sering menggunakan sistem/website GEO-SINFRA ini dalam kehidupan sehari-hari atau untuk menunjang aktivitas pekerjaan saya.	Positif
2	Saya merasa sistem/website GEO-SINFRA ini terlalu rumit untuk digunakan, padahal alur fiturnya bisa dibuat lebih sederhana.	Negatif
3	Saya merasa sistem/website GEO-SINFRA ini sangat mudah digunakan dan menu-menunya gampang dipahami.	Positif
4	Saya rasa saya akan membutuhkan bantuan teknis atau panduan dari orang lain untuk bisa menggunakan sistem/website GEO-SINFRA ini dengan lancar.	Negatif
5	Saya merasa fitur-fitur di dalam sistem/website ini (seperti pengaduan laporan, WebGIS peta sebaran, dan dasbor data) sudah terintegrasi dan berfungsi dengan sangat baik.	Positif
6	Saya merasa sistem/website GEO-SINFRA ini memiliki terlalu banyak ketidakkonsistenan antar halaman atau fiturnya.	Negatif
7	Saya merasa (warga atau instansi) akan dapat mempelajari cara penggunaan sistem/website GEO-SINFRA ini dengan sangat cepat.	Positif
8	Saya merasa sistem/website GEO-SINFRA ini cukup membingungkan saat pertama kali dicoba atau digunakan.	Negatif
9	Saya merasa sangat percaya diri dan tanpa ragu-ragu saat mengoperasikan fungsi-fungsi yang ada pada sistem/website GEO-SINFRA ini.	Positif
10	Saya merasa perlu mempelajari banyak hal terlebih dahulu sebelum bisa mulai lancar menggunakan sistem/website GEO-SINFRA ini.	Negatif

4.	Parameter Penilaian dan Interpretasi Skor SUS:
Parameter penilaian SUS ini secara komprehensif diklasifikasikan ke dalam tiga metrik evaluasi utama, yaitu *Acceptability Ranges* (Tingkat Penerimaan), *Grade Scale* (Skala Nilai Huruf), dan *Adjective Ratings* (Peringkat Sifat). Untuk menginterpretasikan kelayakan nilai tersebut, angka 68,0 ditetapkan sebagai ambang batas nilai ukur rata-rata global (*baseline*) berdasarkan riset pengujian SUS (Pratama et al., 2023). Hal ini berarti skor di bawah angka 68,0 mengonfirmasi adanya isu *usability* di bawah standar yang patut dievaluasi, sementara skor di atas 68,0 membuktikan bahwa kelayakan sistem telah memenuhi standar operasional yang baik. Rincian klasifikasi penilaian skor SUS disajikan pada Tabel 3.40 berikut:

Tabel 3.40     Matriks Penilaian Skor System Usability Scale (SUS)
Rentang Skor SUS	Tingkat Penerimaan (Acceptability)	Skala Nilai (Grade Scale)	Peringkat Sifat (Adjective Rating)
< 51	Not Acceptable (Tidak Dapat Diterima)	F	Worst Imaginable s.d Poor
51 - 62.5	Marginal - Low (Batas Bawah)	D	OK
62.6 - 67.9	Marginal - High (Di Bawah Rata-rata)	C	OK s.d Good
68.0	Baseline (Nilai Rata-rata Global)	C	Batas Kelayakan Minimum
68.1 - 70	Marginal - High (Di Atas Rata-rata)	C	Good
71 - 85	Acceptable (Dapat Diterima)	B	Good s.d Excellent
86 - 100	Acceptable (Dapat Diterima)	A	Excellent s.d Best Imaginable

3.9.5	Jadwal Pelaksanaan
Tabel 3.41 menunjukkan rincian target waktu penyelesaian (Time Schedule) mulai dari tahap analisis awal hingga tahap perilisan akhir dan penyusunan laporan. Sesuai dengan metode Waterfall yang digunakan, jadwal ini disusun secara linier dan berurutan.

Tabel 3.41    Rencana Jadwal Pelaksanaan (Time Schedule) Metode Waterfall



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


