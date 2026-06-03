"""
=============================================================
  GEO-SINFRA - AI BRIDGE (Flask API)
  Jembatan antara Laravel dan Model AI
=============================================================
  Jalankan: python ai_bridge.py
  Endpoint: POST http://127.0.0.1:5000/predict

  Laravel mengirim foto via POST multipart/form-data,
  dan menerima respons JSON berisi jenis + kondisi infrastruktur.
=============================================================
"""

from flask import Flask, request, jsonify
import os
import sys
import json
import tempfile
import numpy as np
from datetime import datetime

# Mengatasi error [WinError 1114] / DLL PyTorch conflict di Windows
os.environ['KMP_DUPLICATE_LIB_OK'] = 'True'

app = Flask(__name__)

# ========================
# KONFIGURASI
# ========================
SCRIPT_DIR = os.path.dirname(os.path.abspath(__file__))
MODEL_DIR = os.path.join(SCRIPT_DIR, "ai_models")

CNN_MODEL_PATH = os.path.join(MODEL_DIR, "cnn_feature_extractor.pth")
DT_JENIS_PATH = os.path.join(MODEL_DIR, "dt_jenis.pkl")
DT_KONDISI_PATH = os.path.join(MODEL_DIR, "dt_kondisi.pkl")
PCA_PATH = os.path.join(MODEL_DIR, "pca_transformer.pkl")
CONFIG_PATH = os.path.join(MODEL_DIR, "model_config.json")

IMG_SIZE = (224, 224)  # Harus sama dengan train_model.py
ALLOWED_EXTENSIONS = {'jpg', 'jpeg', 'png', 'bmp', 'tiff', 'tif'}

# Model global (dimuat saat startup)
cnn_model = None
dt_jenis_model = None
dt_kondisi_model = None
pca_model = None
model_config = None
models_loaded = False


def allowed_file(filename):
    """Cek apakah ekstensi file diizinkan."""
    return '.' in filename and filename.rsplit('.', 1)[1].lower() in ALLOWED_EXTENSIONS


def load_models():
    """Muat semua model AI saat startup."""
    global cnn_model, dt_jenis_model, dt_kondisi_model, pca_model, model_config, models_loaded

    try:
        import torch
        import torchvision.models as models
        import joblib
    except ImportError as e:
        print(f"[ERROR] Library belum terinstall: {e}")
        print("        Jalankan: pip install torch torchvision scikit-learn joblib opencv-python flask")
        return False

    # Cek file model
    for path, nama in [(CNN_MODEL_PATH, "CNN"), (DT_JENIS_PATH, "DT Jenis"), (DT_KONDISI_PATH, "DT Kondisi")]:
        if not os.path.exists(path):
            print(f"[ERROR] Model {nama} tidak ditemukan: {path}")
            print("        Jalankan train_model.py terlebih dahulu!")
            return False

    try:
        # Muat konfigurasi
        if os.path.exists(CONFIG_PATH):
            with open(CONFIG_PATH, 'r') as f:
                model_config = json.load(f)
            print(f"  Konfigurasi dimuat dari: {CONFIG_PATH}")
        else:
            model_config = {}

        # Muat CNN (MobileNetV2 feature extractor)
        print("  Memuat model CNN (MobileNetV2)...")
        cnn_model = models.mobilenet_v2(weights=None)
        cnn_model.classifier = torch.nn.Identity()
        cnn_model.load_state_dict(torch.load(CNN_MODEL_PATH, map_location='cpu', weights_only=True))
        cnn_model.eval()
        print(f"  [OK] CNN dimuat!")

        # Muat Decision Trees
        print("  Memuat Decision Tree (Jenis)...")
        dt_jenis_model = joblib.load(DT_JENIS_PATH)
        print(f"  [OK] DT Jenis dimuat!")

        print("  Memuat Decision Tree (Kondisi)...")
        dt_kondisi_model = joblib.load(DT_KONDISI_PATH)
        print(f"  [OK] DT Kondisi dimuat!")

        # Muat PCA transformer (jika ada)
        if os.path.exists(PCA_PATH):
            pca_model = joblib.load(PCA_PATH)
            print(f"  [OK] PCA dimuat ({pca_model.n_components} komponen)")

        models_loaded = True
        print(f"\n  Akurasi Training Jenis  : {model_config.get('accuracy_jenis', '?')}%")
        print(f"  Akurasi Training Kondisi: {model_config.get('accuracy_kondisi', '?')}%")
        print(f"  Tanggal Training: {model_config.get('training_date', '?')}")
        return True

    except Exception as e:
        print(f"[ERROR] Gagal memuat model: {e}")
        return False


def predict_image(image_path):
    """
    Prediksi jenis dan kondisi infrastruktur dari path gambar.
    
    Returns:
        dict: Hasil prediksi lengkap
    """
    import cv2
    import torch

    # Mapping label dari konfigurasi
    infra_types = model_config.get("infra_types", ["jalan", "jembatan", "titian"])
    kondisi_labels = model_config.get("kondisi_labels", ["baik", "sedang", "berat"])
    jenis_display = model_config.get("jenis_display_map", {
        "jalan": "Jalan", "jembatan": "Jembatan", "titian": "Titian"
    })
    kondisi_display = model_config.get("kondisi_display_map", {
        "baik": "Baik", "sedang": "Sedang", "berat": "Berat"
    })

    # Baca & preprocess gambar
    img = cv2.imread(image_path)
    if img is None:
        raise ValueError(f"Tidak bisa membaca gambar: {image_path}")

    img = cv2.cvtColor(img, cv2.COLOR_BGR2RGB)
    img = cv2.resize(img, IMG_SIZE)
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

    # ===== Prediksi JENIS =====
    pred_jenis_idx = dt_jenis_model.predict(features)[0]
    prob_jenis = dt_jenis_model.predict_proba(features)[0]
    confidence_jenis = round(float(np.max(prob_jenis)) * 100, 2)

    raw_jenis = infra_types[pred_jenis_idx]
    jenis_result = jenis_display.get(raw_jenis, raw_jenis.capitalize())

    detail_jenis = {}
    for i, jenis_key in enumerate(infra_types):
        display_name = jenis_display.get(jenis_key, jenis_key.capitalize())
        detail_jenis[display_name] = round(float(prob_jenis[i]) * 100, 2) if i < len(prob_jenis) else 0.0

    # ===== Prediksi KONDISI =====
    pred_kondisi_idx = dt_kondisi_model.predict(features)[0]
    prob_kondisi = dt_kondisi_model.predict_proba(features)[0]
    confidence_kondisi = round(float(np.max(prob_kondisi)) * 100, 2)

    raw_kondisi = kondisi_labels[pred_kondisi_idx]
    kondisi_result = kondisi_display.get(raw_kondisi, raw_kondisi.capitalize())

    detail_kondisi = {}
    for i, kondisi_key in enumerate(kondisi_labels):
        display_name = kondisi_display.get(kondisi_key, kondisi_key.capitalize())
        detail_kondisi[display_name] = round(float(prob_kondisi[i]) * 100, 2) if i < len(prob_kondisi) else 0.0

    # Prioritas penanganan
    if kondisi_result == "Berat":
        prioritas = "Tinggi"
    elif kondisi_result == "Sedang":
        prioritas = "Sedang"
    else:
        prioritas = "Rendah"

    return {
        "jenis": jenis_result,
        "kondisi": kondisi_result,
        "confidence_jenis": confidence_jenis,
        "confidence_kondisi": confidence_kondisi,
        "prioritas": prioritas,
        "detail_jenis": detail_jenis,
        "detail_kondisi": detail_kondisi,
        "probability": confidence_kondisi / 100.0,
        "label": f"{jenis_result} - {kondisi_result}",
    }


# ========================
# ROUTES
# ========================
@app.route('/', methods=['GET'])
def index():
    """Halaman status."""
    status = "AKTIF (Model AI Dimuat)" if models_loaded else "STANDBY (Model Belum Dimuat)"
    return f"""
    <h2>🛰️ GEO-SINFRA AI Bridge</h2>
    <p><strong>Status:</strong> {status}</p>
    <p><strong>Endpoint:</strong> POST /predict</p>
    <p><strong>Model:</strong> CNN (MobileNetV2 fine-tuned) + PCA + Decision Tree</p>
    <p><strong>Klasifikasi:</strong></p>
    <ul>
        <li>Jenis: Jalan / Jembatan / Titian</li>
        <li>Kondisi: Baik / Sedang / Berat</li>
    </ul>
    <p><strong>Akurasi Jenis:</strong> {model_config.get('accuracy_jenis', '?')}%</p>
    <p><strong>Akurasi Kondisi:</strong> {model_config.get('accuracy_kondisi', '?')}%</p>
    """


@app.route('/predict', methods=['GET', 'POST'])
def predict():
    """Endpoint prediksi — menerima gambar dan mengembalikan klasifikasi."""
    if request.method == 'GET':
        return jsonify({
            "status": "AI Bridge aktif",
            "models_loaded": models_loaded,
            "usage": "POST /predict dengan field 'image' berisi file gambar"
        })

    # Pastikan model sudah dimuat
    if not models_loaded:
        return jsonify({
            "success": False,
            "error": "Model AI belum dimuat. Jalankan train_model.py terlebih dahulu."
        }), 503

    # Validasi input
    if 'image' not in request.files:
        return jsonify({
            "success": False,
            "error": "Tidak ada file gambar. Kirim file dengan field name 'image'."
        }), 400

    file = request.files['image']

    if file.filename == '':
        return jsonify({
            "success": False,
            "error": "Nama file kosong."
        }), 400

    if not allowed_file(file.filename):
        return jsonify({
            "success": False,
            "error": f"Format file tidak didukung. Gunakan: {', '.join(ALLOWED_EXTENSIONS)}"
        }), 400

    # Simpan file sementara
    temp_path = None
    try:
        ext = file.filename.rsplit('.', 1)[1].lower()
        temp_fd, temp_path = tempfile.mkstemp(suffix=f'.{ext}')
        os.close(temp_fd)
        file.save(temp_path)

        print(f"[{datetime.now().strftime('%H:%M:%S')}] Menganalisis: {file.filename}...")

        # Jalankan prediksi
        result = predict_image(temp_path)
        result["success"] = True
        result["filename"] = file.filename
        result["details"] = (
            f"Terdeteksi sebagai {result['jenis']} dengan kondisi {result['kondisi']}. "
            f"Confidence jenis: {result['confidence_jenis']}%, "
            f"Confidence kondisi: {result['confidence_kondisi']}%."
        )

        print(f"  -> Hasil: {result['jenis']} - {result['kondisi']} "
              f"(Jenis: {result['confidence_jenis']}%, Kondisi: {result['confidence_kondisi']}%)")

        return jsonify(result)

    except Exception as e:
        print(f"  [ERROR] {str(e)}")
        return jsonify({
            "success": False,
            "error": f"Gagal menganalisis gambar: {str(e)}"
        }), 500

    finally:
        # Hapus file sementara
        if temp_path and os.path.exists(temp_path):
            try:
                os.remove(temp_path)
            except:
                pass


@app.route('/health', methods=['GET'])
def health():
    """Endpoint health check untuk monitoring."""
    return jsonify({
        "status": "healthy" if models_loaded else "degraded",
        "models_loaded": models_loaded,
        "model_info": {
            "accuracy_jenis": model_config.get("accuracy_jenis", 0) if model_config else 0,
            "accuracy_kondisi": model_config.get("accuracy_kondisi", 0) if model_config else 0,
            "training_date": model_config.get("training_date", "unknown") if model_config else "unknown",
        },
        "timestamp": datetime.now().isoformat()
    })


# ========================
# MAIN
# ========================
if __name__ == '__main__':
    print("=" * 60)
    print("  GEO-SINFRA AI BRIDGE")
    print("  Flask API - CNN + Decision Tree Classifier")
    print("=" * 60)
    print()

    print("[1/2] Memuat model AI...")
    success = load_models()

    if success:
        print(f"\n[OK] Semua model berhasil dimuat!")
    else:
        print(f"\n[WARNING] Model gagal dimuat. Server tetap berjalan tapi prediksi tidak tersedia.")

    print(f"\n[2/2] Menjalankan Flask server...")
    print(f"  URL: http://127.0.0.1:5000")
    print(f"  Endpoint prediksi: POST http://127.0.0.1:5000/predict")
    print(f"  Health check: GET http://127.0.0.1:5000/health")
    print()

    app.run(host='127.0.0.1', port=5000, debug=False)
