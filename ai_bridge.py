from flask import Flask, request, jsonify
import random
import os

# Script ini adalah jembatan (bridge) untuk menghubungkan Laravel dengan Model AI Anda.
# Jalankan script ini menggunakan perintah: python ai_bridge.py

app = Flask(__name__)

@app.route('/predict', methods=['GET', 'POST'])
def predict():
    if request.method == 'GET':
        return "<h3>AI Bridge is Running!</h3><p>Server ini siap menerima data foto dari Laravel via POST request.</p>"
        
    print("Menerima request analisis dari Laravel...")
    
    if 'image' not in request.files:
        return jsonify({'error': 'No image provided'}), 400
    
    # Ambil file yang dikirim Laravel
    file = request.files['image']
    
    # -------------------------------------------------------
    # TEMPAT UNTUK MODEL ANDA (Keras/PyTorch)
    # -------------------------------------------------------
    # Contoh: 
    # model = load_model('model_cnn_jalan.h5')
    # prediction = model.predict(file)
    # -------------------------------------------------------
    
    # Simulasi hasil analisis (Ganti bagian ini dengan hasil prediksi model asli Anda)
    score = round(random.uniform(0.1, 0.95), 2)
    labels = ['Kondisi Baik', 'Rusak Ringan (Retak)', 'Rusak Sedang (Lubang)', 'Rusak Berat (Amblas)']
    
    if score > 0.8:
        label = labels[3]
    elif score > 0.5:
        label = labels[2]
    elif score > 0.3:
        label = labels[1]
    else:
        label = labels[0]

    print(f"Analisis Selesai: {label} (Skor: {score})")

    return jsonify({
        'probability': score,
        'label': label,
        'details': f'Analisis visual mendeteksi probabilitas {label} sebesar {int(score*100)}%.'
    })

if __name__ == '__main__':
    # Pastikan port 5000 tidak sedang digunakan aplikasi lain
    print("--- AI BRIDGE (CNN) AKTIF ---")
    print("Menunggu data dari Laravel di http://127.0.0.1:5000/predict")
    app.run(host='127.0.0.1', port=5000, debug=True)
