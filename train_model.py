import os
import glob
import cv2
import numpy as np
import torch
import torchvision.models as models
from torchvision.models import ResNet18_Weights
from sklearn.decomposition import PCA
from sklearn.tree import DecisionTreeClassifier
from sklearn.model_selection import train_test_split
from sklearn.metrics import accuracy_score
import joblib
import json
from datetime import datetime

# ========================
# CONFIGURATION
# ========================
DATASET_DIR = r"D:\Skripsi\infrastruktur_permukiman"
MODEL_DIR = "models"
IMG_SIZE = (224, 224)
PCA_COMPONENTS = 128

# Create models directory if not exists
if not os.path.exists(MODEL_DIR):
    os.makedirs(MODEL_DIR)

print("="*60)
print("  GEO-SINFRA AI - TRAINING SCRIPT")
print("="*60)

# ========================
# 1. LOAD DATASET
# ========================
print("\n[1/5] Membaca Dataset dari:", DATASET_DIR)
images = []
labels_jenis = []
labels_kondisi = []

# Mapping
jenis_map = {}
kondisi_map = {}
jenis_idx = 0
kondisi_idx = 0

if not os.path.exists(DATASET_DIR):
    print("ERROR: Folder dataset tidak ditemukan!")
    exit(1)

# Scan dataset
for jenis_folder in os.listdir(DATASET_DIR):
    jenis_path = os.path.join(DATASET_DIR, jenis_folder)
    if not os.path.isdir(jenis_path) or jenis_folder.startswith('.'):
        continue
        
    jenis = jenis_folder.lower()
    if jenis not in jenis_map:
        jenis_map[jenis] = jenis_idx
        jenis_idx += 1
        
    for kondisi_folder in os.listdir(jenis_path):
        kondisi_path = os.path.join(jenis_path, kondisi_folder)
        if not os.path.isdir(kondisi_path):
            continue
            
        kondisi = kondisi_folder.lower().replace("rusak ", "") # normalize (e.g. 'rusak berat' -> 'berat')
        if kondisi not in kondisi_map:
            kondisi_map[kondisi] = kondisi_idx
            kondisi_idx += 1
            
        # Read images
        img_paths = glob.glob(os.path.join(kondisi_path, "*.[jJ][pP][gG]")) + \
                    glob.glob(os.path.join(kondisi_path, "*.[pP][nN][gG]")) + \
                    glob.glob(os.path.join(kondisi_path, "*.[jJ][pP][eE][gG]"))
                    
        for img_path in img_paths:
            img = cv2.imread(img_path)
            if img is not None:
                img = cv2.cvtColor(img, cv2.COLOR_BGR2RGB)
                img = cv2.resize(img, IMG_SIZE)
                images.append(img)
                labels_jenis.append(jenis_map[jenis])
                labels_kondisi.append(kondisi_map[kondisi])

if len(images) == 0:
    print("ERROR: Tidak ada gambar ditemukan di dataset!")
    exit(1)
    
print(f"Total gambar ditemukan: {len(images)}")
print(f"Kategori Jenis ditemukan: {list(jenis_map.keys())}")
print(f"Kategori Kondisi ditemukan: {list(kondisi_map.keys())}")

X = np.array(images, dtype=np.float32) / 255.0
y_jenis = np.array(labels_jenis)
y_kondisi = np.array(labels_kondisi)


# ========================
# 2. FEATURE EXTRACTION (CNN)
# ========================
print("\n[2/5] Ekstraksi Fitur menggunakan ResNet18 (PyTorch)...")

# Load pre-trained ResNet18
weights = ResNet18_Weights.DEFAULT
cnn_model = models.resnet18(weights=weights)
# Hapus layer klasifikasi terakhir agar menjadi Feature Extractor (menghasilkan 512 fitur)
cnn_model = torch.nn.Sequential(*(list(cnn_model.children())[:-1]))
cnn_model.eval()

# Transform to tensor
X_tensor = torch.from_numpy(X).permute(0, 3, 1, 2)

# Normalize using ImageNet standard
mean = torch.tensor([0.485, 0.456, 0.406]).view(1, 3, 1, 1)
std = torch.tensor([0.229, 0.224, 0.225]).view(1, 3, 1, 1)
X_tensor = (X_tensor - mean) / std

# Extract features in batches to save RAM
batch_size = 32
features = []
with torch.no_grad():
    for i in range(0, len(X_tensor), batch_size):
        batch = X_tensor[i:i+batch_size]
        out = cnn_model(batch).squeeze(-1).squeeze(-1) # shape: (batch_size, 512)
        features.append(out.numpy())
        print(f"  Memproses batch {i//batch_size + 1}/{(len(X_tensor)+batch_size-1)//batch_size}...")
        
X_features = np.vstack(features)
print(f"Ekstraksi selesai. Ukuran fitur: {X_features.shape}")

# Save CNN Extractor
cnn_path = os.path.join(MODEL_DIR, "cnn_feature_extractor.pth")
torch.save(cnn_model.state_dict(), cnn_path)
print(f"-> Disimpan: {cnn_path}")


# ========================
# 3. PCA DIMENSIONALITY REDUCTION
# ========================
print(f"\n[3/5] Reduksi Dimensi dengan PCA (ke {PCA_COMPONENTS} komponen)...")
n_components = min(PCA_COMPONENTS, len(X_features))
pca = PCA(n_components=n_components, random_state=42)
X_pca = pca.fit_transform(X_features)

pca_path = os.path.join(MODEL_DIR, "pca_transformer.pkl")
joblib.dump(pca, pca_path)
print(f"-> Disimpan: {pca_path}")


# ========================
# 4. DECISION TREE TRAINING
# ========================
print("\n[4/5] Melatih Model Decision Tree (SPK)...")

# Split dataset 80% train, 20% test
X_train, X_test, yj_train, yj_test, yk_train, yk_test = train_test_split(
    X_pca, y_jenis, y_kondisi, test_size=0.2, random_state=42
)

# Train DT for Jenis
dt_jenis = DecisionTreeClassifier(max_depth=10, random_state=42)
dt_jenis.fit(X_train, yj_train)
acc_jenis = accuracy_score(yj_test, dt_jenis.predict(X_test)) * 100
print(f"  Akurasi DT Jenis  : {acc_jenis:.2f}%")

# Train DT for Kondisi
dt_kondisi = DecisionTreeClassifier(max_depth=10, random_state=42)
dt_kondisi.fit(X_train, yk_train)
acc_kondisi = accuracy_score(yk_test, dt_kondisi.predict(X_test)) * 100
print(f"  Akurasi DT Kondisi: {acc_kondisi:.2f}%")

# Save Models
joblib.dump(dt_jenis, os.path.join(MODEL_DIR, "dt_jenis.pkl"))
joblib.dump(dt_kondisi, os.path.join(MODEL_DIR, "dt_kondisi.pkl"))
print("-> Model Decision Tree berhasil disimpan!")


# ========================
# 5. EXPORT CONFIGURATION
# ========================
print("\n[5/5] Mengekspor Konfigurasi...")

# Inverse mapping
inv_jenis = {v: k for k, v in jenis_map.items()}
inv_kondisi = {v: k for k, v in kondisi_map.items()}

# Prepare order mapping expected by ai_bridge.py
infra_types = [inv_jenis[i] for i in range(len(inv_jenis))]
kondisi_labels = [inv_kondisi[i] for i in range(len(inv_kondisi))]

config = {
    "infra_types": infra_types,
    "kondisi_labels": kondisi_labels,
    "jenis_display_map": {k: k.title() for k in infra_types},
    "kondisi_display_map": {k: k.title() for k in kondisi_labels},
    "accuracy_jenis": round(acc_jenis, 2),
    "accuracy_kondisi": round(acc_kondisi, 2),
    "training_date": datetime.now().strftime("%Y-%m-%d %H:%M:%S")
}

config_path = os.path.join(MODEL_DIR, "model_config.json")
with open(config_path, "w") as f:
    json.dump(config, f, indent=4)
print(f"-> Disimpan: {config_path}")

print("\n" + "="*60)
print("  PROSES TRAINING SELESAI DENGAN SUKSES!")
print("  Model sudah siap digunakan oleh ai_bridge.py")
print("="*60)
