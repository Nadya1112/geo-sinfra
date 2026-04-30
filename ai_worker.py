import mysql.connector
import time
import random
from datetime import datetime

# Konfigurasi Database (Sesuai dengan Laragon & Laravel Anda)
DB_HOST = "127.0.0.1"
DB_USER = "root"
DB_PASS = ""
DB_NAME = "geo-sinfra"

def get_db_connection():
    try:
        return mysql.connector.connect(
            host=DB_HOST,
            user=DB_USER,
            password=DB_PASS,
            database=DB_NAME
        )
    except Exception as e:
        print(f"Gagal terhubung ke database: {e}")
        return None

def process_pending_images():
    conn = get_db_connection()
    if not conn:
        return
    
    cursor = conn.cursor(dictionary=True)
    
    # Cari data yang masih menunggu AI
    query = "SELECT * FROM infrastruktur WHERE kondisi = 'Menunggu AI' OR status_verifikasi = 'Pending'"
    cursor.execute(query)
    pending_data = cursor.fetchall()
    
    if not pending_data:
        # Tidak ada data, keluar dari fungsi diam-diam
        cursor.close()
        conn.close()
        return

    print(f"[{datetime.now().strftime('%Y-%m-%d %H:%M:%S')}] Ditemukan {len(pending_data)} foto infrastruktur untuk dianalisis oleh AI...")
    
    for row in pending_data:
        infra_id = row['id_infrastruktur']
        user_id = row['id_user'] # Ambil ID Surveyor
        nama_infra = row['nama_infrastruktur']
        foto = row['foto_terbaru']
        
        print(f" -> Sedang mengekstraksi piksel & menganalisis foto: {foto} untuk objek: '{nama_infra}'...")
        
        # SIMULASI PROSES AI: Waktu tunggu (delay) untuk mensimulasikan processing Computer Vision
        time.sleep(4)
        
        # SIMULASI HASIL AI: Menentukan kondisi secara acak (nanti bagian ini diganti dengan model ML asli, spt YOLO)
        kondisi_options = ['Baik', 'Rusak Ringan', 'Rusak Berat']
        hasil_kondisi = random.choice(kondisi_options)
        
        # Tingkat kepercayaan AI (Confidence Score)
        confidence = round(random.uniform(75.0, 98.5), 2)
        print(f"    [SELESAI] Hasil Deteksi AI: {hasil_kondisi} (Tingkat Akurasi: {confidence}%)\n")
        
        # Update database untuk memperbarui status di tabel utama
        update_query = """
            UPDATE infrastruktur 
            SET kondisi = %s, status_verifikasi = 'Verified' 
            WHERE id_infrastruktur = %s
        """
        cursor.execute(update_query, (hasil_kondisi, infra_id))
        
        # SIMULASI Tabel Citra CNN (Menyimpan hasil Convolutional Neural Network)
        skor_cnn = confidence / 100.0
        # Bersihkan data lama jika ada (untuk re-analisis saat edit)
        cursor.execute("DELETE FROM citra_cnn WHERE id_infrastruktur = %s", (infra_id,))
        insert_cnn = """
            INSERT INTO citra_cnn (id_infrastruktur, id_user, file_foto, skor_cnn, label_kondisi, created_at, updated_at) 
            VALUES (%s, %s, %s, %s, %s, NOW(), NOW())
        """
        cursor.execute(insert_cnn, (infra_id, user_id, foto, skor_cnn, hasil_kondisi))
        
        # SIMULASI Tabel Analisis AI (Menyimpan hasil Decision Tree)
        prioritas = "Tinggi" if hasil_kondisi == 'Rusak Berat' else ("Sedang" if hasil_kondisi == 'Rusak Ringan' else "Rendah")
        # Bersihkan data lama jika ada
        cursor.execute("DELETE FROM analisis_ai WHERE id_infrastruktur = %s", (infra_id,))
        insert_dtree = """
            INSERT INTO analisis_ai (id_infrastruktur, skor_dt, label_prioritas, status_validasi, created_at, updated_at) 
            VALUES (%s, %s, %s, 'pending', NOW(), NOW())
        """
        cursor.execute(insert_dtree, (infra_id, skor_cnn, prioritas))

        conn.commit()

    print("Semua antrean gambar telah selesai dianalisis dan disimpan ke database!\n")
    cursor.close()
    conn.close()

if __name__ == "__main__":
    print("===================================================")
    print("  GEO-SINFRA AI WORKER (SIMULASI COMPUTER VISION)  ")
    print("===================================================")
    print("Status: AKTIF")
    print("Menunggu data foto masuk dari aplikasi... (Tekan Ctrl+C untuk berhenti)\n")
    
    try:
        # Loop terus menerus setiap 3 detik untuk mengecek database
        while True:
            process_pending_images()
            time.sleep(3)
    except KeyboardInterrupt:
        print("\nAI Worker dihentikan oleh pengguna.")
