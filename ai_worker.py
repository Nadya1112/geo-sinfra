"""
=============================================================
  GEO-SINFRA AI WORKER
  Analisis Otomatis: Jenis & Kondisi Infrastruktur
=============================================================
  Worker ini berjalan di background dan secara otomatis:
  1. Mendeteksi foto baru yang diupload dari website
  2. Menganalisis JENIS infrastruktur (Jalan/Jembatan/Titian)
  3. Menganalisis KONDISI infrastruktur (Baik/Rusak/Rusak Berat)
  4. Menyimpan hasil ke database
  
  User cukup upload foto -> sistem langsung tahu jenis & kondisinya
=============================================================
"""

import mysql.connector
import time
import numpy as np
import json
import os
import sys
import random
from datetime import datetime

# ========================
# KONFIGURASI
# ========================
DB_HOST = "127.0.0.1"
DB_USER = "root"
DB_PASS = ""
DB_NAME = "geo-sinfra"

# Path ke model AI
MODEL_DIR = os.path.join(os.path.dirname(os.path.abspath(__file__)), "ai_models")
CNN_MODEL_PATH = os.path.join(MODEL_DIR, "cnn_feature_extractor.pth")
DT_JENIS_PATH = os.path.join(MODEL_DIR, "dt_jenis.pkl")
DT_KONDISI_PATH = os.path.join(MODEL_DIR, "dt_kondisi.pkl")
PCA_PATH = os.path.join(MODEL_DIR, "pca_transformer.pkl")
CONFIG_PATH = os.path.join(MODEL_DIR, "model_config.json")

# Path ke folder foto upload Laravel
FOTO_BASE_PATH = os.path.join(os.path.dirname(os.path.abspath(__file__)), "public", "storage", "infrastruktur_fotos")

# ========================
# MUAT MODEL AI
# ========================
USE_REAL_MODEL = False
cnn_model = None
dt_jenis_model = None
dt_kondisi_model = None
pca_model = None
model_config = None


def load_ai_models():
    """Muat semua model AI jika tersedia."""
    global USE_REAL_MODEL, cnn_model, dt_jenis_model, dt_kondisi_model, pca_model, model_config
    
    all_models_exist = (
        os.path.exists(CNN_MODEL_PATH) and 
        os.path.exists(DT_JENIS_PATH) and 
        os.path.exists(DT_KONDISI_PATH)
    )
    
    if not all_models_exist:
        print("[INFO] Model AI belum lengkap. Menggunakan mode SIMULASI.")
        print(f"       CNN     : {'ADA' if os.path.exists(CNN_MODEL_PATH) else 'BELUM ADA'} -> {CNN_MODEL_PATH}")
        print(f"       DT Jenis: {'ADA' if os.path.exists(DT_JENIS_PATH) else 'BELUM ADA'} -> {DT_JENIS_PATH}")
        print(f"       DT Kondisi: {'ADA' if os.path.exists(DT_KONDISI_PATH) else 'BELUM ADA'} -> {DT_KONDISI_PATH}")
        print(f"\n       Jalankan train_model.py terlebih dahulu!\n")
        USE_REAL_MODEL = False
        return
    
    try:
        import cv2
        import joblib
        import torch
        import torchvision.models as models
        
        print("Memuat model CNN (PyTorch MobileNetV2)...")
        cnn_model = models.mobilenet_v2(weights=None)
        cnn_model.classifier = torch.nn.Identity()
        cnn_model.load_state_dict(torch.load(CNN_MODEL_PATH, map_location='cpu'))
        cnn_model.eval()
        
        print("Memuat model Decision Tree (Jenis)...")
        dt_jenis_model = joblib.load(DT_JENIS_PATH)
        
        print("Memuat model Decision Tree (Kondisi)...")
        dt_kondisi_model = joblib.load(DT_KONDISI_PATH)
        
        # Muat PCA transformer (jika ada)
        if os.path.exists(PCA_PATH):
            pca_model = joblib.load(PCA_PATH)
            print(f"PCA transformer dimuat ({pca_model.n_components} komponen)")
        
        # Muat konfigurasi
        if os.path.exists(CONFIG_PATH):
            with open(CONFIG_PATH, 'r') as f:
                model_config = json.load(f)
            print(f"Konfigurasi dimuat.")
            print(f"  Akurasi Jenis  : {model_config.get('accuracy_jenis', '?')}%")
            print(f"  Akurasi Kondisi: {model_config.get('accuracy_kondisi', '?')}%")
        
        USE_REAL_MODEL = True
        print("\n[OK] Semua model AI berhasil dimuat! Mode: PREDIKSI ASLI\n")
        
    except ImportError as e:
        print(f"[WARNING] Library AI belum terinstall: {e}")
        print("          Menggunakan mode SIMULASI.\n")
        USE_REAL_MODEL = False
    except Exception as e:
        print(f"[ERROR] Gagal memuat model: {e}")
        print("        Menggunakan mode SIMULASI.\n")
        USE_REAL_MODEL = False


def predict_with_ai(foto_path):
    """
    Prediksi JENIS dan KONDISI infrastruktur dari foto.
    
    Returns:
        tuple: (jenis_infra, kondisi, confidence_jenis, confidence_kondisi)
               Contoh: ("Jalan", "Berat", 95.2, 88.7)
    """
    import cv2
    import torch
    
    # Konfigurasi
    img_size = tuple(model_config.get("img_size", [224, 224])) if model_config else (224, 224)
    infra_types = model_config.get("infra_types", ["jalan", "jembatan", "titian"]) if model_config else ["jalan", "jembatan", "titian"]
    kondisi_labels = model_config.get("kondisi_labels", ["baik", "sedang", "berat"]) if model_config else ["baik", "sedang", "berat"]
    jenis_display = model_config.get("jenis_display_map", {"jalan": "Jalan", "jembatan": "Jembatan", "titian": "Titian"}) if model_config else {}
    kondisi_display = model_config.get("kondisi_display_map", {"baik": "Baik", "sedang": "Sedang", "berat": "Berat"}) if model_config else {}
    
    # Baca & preprocess gambar
    img = cv2.imread(foto_path)
    if img is None:
        raise ValueError(f"Tidak bisa membaca gambar: {foto_path}")
    
    img = cv2.cvtColor(img, cv2.COLOR_BGR2RGB)
    img = cv2.resize(img, img_size)
    img = img.astype(np.float32) / 255.0
    
    # Convert ke tensor PyTorch: (H, W, C) -> (1, C, H, W)
    img_tensor = torch.from_numpy(img).permute(2, 0, 1).unsqueeze(0).float()
    
    # Normalize dengan ImageNet mean/std
    mean = torch.tensor([0.485, 0.456, 0.406]).view(1, 3, 1, 1)
    std = torch.tensor([0.229, 0.224, 0.225]).view(1, 3, 1, 1)
    img_tensor = (img_tensor - mean) / std
    
    # Ekstrak fitur dengan CNN
    with torch.no_grad():
        features = cnn_model(img_tensor).numpy()
    
    # Terapkan PCA jika tersedia
    if pca_model is not None:
        features = pca_model.transform(features)
    
    # ===== Prediksi JENIS infrastruktur =====
    pred_jenis_idx = dt_jenis_model.predict(features)[0]
    prob_jenis = dt_jenis_model.predict_proba(features)[0]
    confidence_jenis = round(float(np.max(prob_jenis)) * 100, 2)
    
    raw_jenis = infra_types[pred_jenis_idx]
    jenis_infra = jenis_display.get(raw_jenis, raw_jenis.capitalize())
    
    # ===== Prediksi KONDISI infrastruktur =====
    pred_kondisi_idx = dt_kondisi_model.predict(features)[0]
    prob_kondisi = dt_kondisi_model.predict_proba(features)[0]
    confidence_kondisi = round(float(np.max(prob_kondisi)) * 100, 2)
    
    raw_kondisi = kondisi_labels[pred_kondisi_idx]
    kondisi = kondisi_display.get(raw_kondisi, raw_kondisi.capitalize())
    
    return jenis_infra, kondisi, confidence_jenis, confidence_kondisi


def predict_simulasi():
    """Prediksi simulasi jika model belum tersedia."""
    jenis_options = ['Jalan', 'Jembatan', 'Titian']
    kondisi_options = ['Baik', 'Sedang', 'Berat']
    
    jenis = random.choice(jenis_options)
    kondisi = random.choice(kondisi_options)
    conf_jenis = round(random.uniform(75.0, 98.5), 2)
    conf_kondisi = round(random.uniform(75.0, 98.5), 2)
    
    return jenis, kondisi, conf_jenis, conf_kondisi


# ========================
# DATABASE
# ========================
def get_db_connection():
    """Buat koneksi ke database MySQL."""
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
    """Proses semua gambar yang menunggu analisis AI."""
    conn = get_db_connection()
    if not conn:
        return
    
    cursor = conn.cursor(dictionary=True)
    
    # Cari data yang masih menunggu AI
    query = "SELECT * FROM infrastruktur WHERE kondisi = 'Menunggu AI' OR status_verifikasi = 'Pending'"
    cursor.execute(query)
    pending_data = cursor.fetchall()
    
    if not pending_data:
        cursor.close()
        conn.close()
        return
    
    print(f"[{datetime.now().strftime('%Y-%m-%d %H:%M:%S')}] Ditemukan {len(pending_data)} foto untuk dianalisis...")
    
    for row in pending_data:
        infra_id = row['id_infrastruktur']
        user_id = row['id_user']
        nama_infra = row['nama_infrastruktur']
        foto = row['foto_terbaru']
        
        print(f"\n -> Menganalisis: {foto} ({nama_infra})...")
        
        # Tentukan path gambar
        foto_path = os.path.join(FOTO_BASE_PATH, foto)
        
        try:
            if USE_REAL_MODEL and os.path.exists(foto_path):
                # ===== PREDIKSI ASLI DENGAN MODEL AI =====
                jenis_infra, hasil_kondisi, conf_jenis, conf_kondisi = predict_with_ai(foto_path)
                confidence = conf_kondisi  # Gunakan confidence kondisi untuk skor
                
                print(f"    [AI] Jenis  : {jenis_infra} (Confidence: {conf_jenis}%)")
                print(f"    [AI] Kondisi: {hasil_kondisi} (Confidence: {conf_kondisi}%)")
            else:
                # ===== PREDIKSI SIMULASI =====
                time.sleep(2)
                jenis_infra, hasil_kondisi, conf_jenis, conf_kondisi = predict_simulasi()
                confidence = conf_kondisi
                
                if not USE_REAL_MODEL:
                    print(f"    [SIMULASI] Jenis: {jenis_infra}, Kondisi: {hasil_kondisi}")
                else:
                    print(f"    [WARNING] Gambar tidak ditemukan: {foto_path}")
                    print(f"    [FALLBACK] Jenis: {jenis_infra}, Kondisi: {hasil_kondisi}")
        
        except Exception as e:
            print(f"    [ERROR] Gagal memproses: {e}")
            jenis_infra, hasil_kondisi, conf_jenis, conf_kondisi = predict_simulasi()
            confidence = conf_kondisi
            print(f"    [FALLBACK] Jenis: {jenis_infra}, Kondisi: {hasil_kondisi}")
        
        # ===== UPDATE DATABASE =====
        skor_cnn = confidence / 100.0
        
        # Update tabel utama infrastruktur (kondisi + kategori/jenis terdeteksi AI)
        update_query = """
            UPDATE infrastruktur 
            SET kondisi = %s, 
                kategori = %s,
                status_verifikasi = 'Verified' 
            WHERE id_infrastruktur = %s
        """
        cursor.execute(update_query, (hasil_kondisi, jenis_infra, infra_id))
        
        # Simpan ke tabel citra_cnn (hasil analisis visual CNN)
        cursor.execute("DELETE FROM citra_cnn WHERE id_infrastruktur = %s", (infra_id,))
        insert_cnn = """
            INSERT INTO citra_cnn (id_infrastruktur, id_user, file_foto, skor_cnn, label_kondisi, created_at, updated_at) 
            VALUES (%s, %s, %s, %s, %s, NOW(), NOW())
        """
        cursor.execute(insert_cnn, (infra_id, user_id, foto, skor_cnn, hasil_kondisi))
        
        # Simpan ke tabel analisis_ai (hasil Decision Tree)
        prioritas = "Tinggi" if hasil_kondisi == 'Berat' else ("Sedang" if hasil_kondisi == 'Sedang' else "Rendah")
        cursor.execute("DELETE FROM analisis_ai WHERE id_infrastruktur = %s", (infra_id,))
        insert_dtree = """
            INSERT INTO analisis_ai (id_infrastruktur, skor_dt, label_prioritas, status_validasi, created_at, updated_at) 
            VALUES (%s, %s, %s, 'pending', NOW(), NOW())
        """
        cursor.execute(insert_dtree, (infra_id, skor_cnn, prioritas))
        
        conn.commit()
        print(f"    [DB] Tersimpan: {jenis_infra} - {hasil_kondisi} (Prioritas: {prioritas})")
    
    print(f"\nSemua {len(pending_data)} antrean selesai diproses!\n")
    cursor.close()
    conn.close()


# ========================
# MAIN
# ========================
if __name__ == "__main__":
    print("=" * 60)
    print("  GEO-SINFRA AI WORKER")
    print("  Dual Klasifikasi: Jenis & Kondisi Infrastruktur")
    print("=" * 60)
    
    # Muat model AI
    load_ai_models()
    
    if USE_REAL_MODEL:
        mode = "PREDIKSI ASLI (CNN + Decision Tree)"
    else:
        mode = "SIMULASI (Model belum di-training)"
    
    print(f"Mode: {mode}")
    print("Status: AKTIF")
    print("Menunggu data foto masuk... (Tekan Ctrl+C untuk berhenti)\n")
    
    try:
        while True:
            process_pending_images()
            time.sleep(3)
    except KeyboardInterrupt:
        print("\nAI Worker dihentikan oleh pengguna.")
