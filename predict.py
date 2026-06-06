"""
=============================================================
  GEO-SINFRA - PREDIKSI KLASIFIKASI INFRASTRUKTUR
  Script CLI untuk integrasi dengan Laravel
=============================================================
  Cara penggunaan:
    python predict.py "path/ke/gambar.jpg"

  Output (JSON ke stdout):
    {
      "success": true,
      "jenis": "Jalan",
      "kondisi": "Berat",
      "confidence_jenis": 95.32,
      "confidence_kondisi": 88.71,
      "detail_jenis": {"Jalan": 95.32, "Jembatan": 3.15, "Titian": 1.53},
      "detail_kondisi": {"Baik": 5.20, "Sedang": 6.09, "Berat": 88.71}
    }

  Integrasi Laravel (PHP):
    $result = shell_exec('python predict.py "' . $imagePath . '"');
    $data = json_decode($result, true);
=============================================================
"""

import os
import sys
import json
import numpy as np

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

IMG_SIZE = (224, 224)


def output_error(message):
    """Output error dalam format JSON dan exit."""
    result = {
        "success": False,
        "error": message
    }
    print(json.dumps(result, ensure_ascii=False))
    sys.exit(1)


def load_models():
    """Muat semua model AI yang diperlukan."""
    try:
        import torch
        import torchvision.models as models
        import joblib
    except ImportError as e:
        output_error(f"Library belum terinstall: {e}. Jalankan: pip install torch torchvision scikit-learn joblib opencv-python")

    # Cek file model
    for path, nama in [(CNN_MODEL_PATH, "CNN"), (PCA_PATH, "PCA"), (DT_JENIS_PATH, "DT Jenis"), (DT_KONDISI_PATH, "DT Kondisi")]:
        if not os.path.exists(path):
            output_error(f"Model {nama} tidak ditemukan: {path}. Jalankan train_model.py terlebih dahulu!")

    # Muat konfigurasi
    config = {}
    if os.path.exists(CONFIG_PATH):
        with open(CONFIG_PATH, 'r') as f:
            config = json.load(f)

    # Muat CNN (ResNet18 sebagai feature extractor)
    cnn = models.resnet18(weights=None)
    cnn.fc = torch.nn.Identity()
    cnn.load_state_dict(torch.load(CNN_MODEL_PATH, map_location='cpu', weights_only=True))
    cnn.eval()

    # Muat Decision Trees & PCA
    dt_jenis = joblib.load(DT_JENIS_PATH)
    dt_kondisi = joblib.load(DT_KONDISI_PATH)
    pca = joblib.load(PCA_PATH)

    return cnn, pca, dt_jenis, dt_kondisi, config


def preprocess_image(image_path):
    """Baca dan preprocess gambar untuk CNN."""
    import cv2

    if not os.path.exists(image_path):
        output_error(f"File gambar tidak ditemukan: {image_path}")

    img = cv2.imread(image_path)
    if img is None:
        output_error(f"Tidak bisa membaca gambar (file corrupt/format tidak didukung): {image_path}")

    # BGR -> RGB, resize, normalize ke [0, 1]
    img = cv2.cvtColor(img, cv2.COLOR_BGR2RGB)
    img = cv2.resize(img, IMG_SIZE)
    img = img.astype(np.float32) / 255.0

    return img


def predict(image_path):
    """
    Jalankan prediksi lengkap pada satu gambar.
    
    Returns:
        dict: Hasil prediksi berisi jenis, kondisi, dan confidence scores
    """
    import torch

    # Muat model
    cnn, pca, dt_jenis, dt_kondisi, config = load_models()

    # Ambil mapping label dari config
    infra_types = config.get("infra_types", ["jalan", "jembatan", "titian"])
    kondisi_labels = config.get("kondisi_labels", ["baik", "sedang", "berat"])
    jenis_display = config.get("jenis_display_map", {
        "jalan": "Jalan", "jembatan": "Jembatan", "titian": "Titian"
    })
    kondisi_display = config.get("kondisi_display_map", {
        "baik": "Baik", "sedang": "Sedang", "berat": "Berat"
    })

    # Preprocess gambar
    img = preprocess_image(image_path)

    # Convert ke tensor PyTorch: (H, W, C) -> (1, C, H, W)
    img_tensor = torch.from_numpy(img).permute(2, 0, 1).unsqueeze(0).float()

    # Normalize dengan ImageNet mean/std (sesuai training)
    mean = torch.tensor([0.485, 0.456, 0.406]).view(1, 3, 1, 1)
    std = torch.tensor([0.229, 0.224, 0.225]).view(1, 3, 1, 1)
    img_tensor = (img_tensor - mean) / std

    # Ekstrak fitur dengan CNN
    with torch.no_grad():
        features = cnn(img_tensor).numpy()

    # Reduksi dimensi dengan PCA
    features_pca = pca.transform(features)

    # ===== Prediksi JENIS infrastruktur =====
    pred_jenis_idx = dt_jenis.predict(features_pca)[0]
    prob_jenis = dt_jenis.predict_proba(features_pca)[0]
    confidence_jenis = round(float(np.max(prob_jenis)) * 100, 2)

    raw_jenis = infra_types[pred_jenis_idx]
    jenis_result = jenis_display.get(raw_jenis, raw_jenis.capitalize())

    # Detail probabilitas per kelas JENIS
    detail_jenis = {}
    for i, jenis_key in enumerate(infra_types):
        display_name = jenis_display.get(jenis_key, jenis_key.capitalize())
        if i < len(prob_jenis):
            detail_jenis[display_name] = round(float(prob_jenis[i]) * 100, 2)
        else:
            detail_jenis[display_name] = 0.0

    # ===== Prediksi KONDISI infrastruktur =====
    pred_kondisi_idx = dt_kondisi.predict(features_pca)[0]
    prob_kondisi = dt_kondisi.predict_proba(features_pca)[0]
    confidence_kondisi = round(float(np.max(prob_kondisi)) * 100, 2)

    raw_kondisi = kondisi_labels[pred_kondisi_idx]
    kondisi_result = kondisi_display.get(raw_kondisi, raw_kondisi.capitalize())

    # Detail probabilitas per kelas KONDISI
    detail_kondisi = {}
    for i, kondisi_key in enumerate(kondisi_labels):
        display_name = kondisi_display.get(kondisi_key, kondisi_key.capitalize())
        if i < len(prob_kondisi):
            detail_kondisi[display_name] = round(float(prob_kondisi[i]) * 100, 2)
        else:
            detail_kondisi[display_name] = 0.0

    # ===== Tentukan prioritas penanganan =====
    if kondisi_result == "Berat":
        prioritas = "Tinggi"
    elif kondisi_result == "Sedang":
        prioritas = "Sedang"
    else:
        prioritas = "Rendah"

    return {
        "success": True,
        "jenis": jenis_result,
        "kondisi": kondisi_result,
        "confidence_jenis": confidence_jenis,
        "confidence_kondisi": confidence_kondisi,
        "prioritas": prioritas,
        "detail_jenis": detail_jenis,
        "detail_kondisi": detail_kondisi,
        "model_info": {
            "cnn_backend": config.get("cnn_backend", "pytorch"),
            "training_date": config.get("training_date", "unknown"),
            "accuracy_jenis": config.get("accuracy_jenis", 0),
            "accuracy_kondisi": config.get("accuracy_kondisi", 0)
        }
    }


# ========================
# MAIN (CLI)
# ========================
if __name__ == "__main__":
    # Validasi argumen
    if len(sys.argv) < 2:
        output_error("Cara penggunaan: python predict.py \"path/ke/gambar.jpg\"")

    image_path = sys.argv[1]

    # Jalankan prediksi
    try:
        result = predict(image_path)
        print(json.dumps(result, ensure_ascii=False, indent=2))
    except Exception as e:
        output_error(f"Terjadi kesalahan saat prediksi: {str(e)}")
