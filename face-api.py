# source: https://raw.githubusercontent.com/alimochtar78/absensi-wajah-laravel/refs/heads/main/face_recognition_api.py
# modified by: mdestafadilah

from flask import Flask, request, jsonify
import face_recognition
import numpy as np
import base64
import cv2
import os

app = Flask(__name__)

# Folder yang berisi wajah siswa
KNOWN_FACES_DIR = "writable/faces"
known_face_encodings = []
known_face_names = []

# Load semua wajah yang sudah didaftarkan
for filename in os.listdir(KNOWN_FACES_DIR):
    image = face_recognition.load_image_file(f"{KNOWN_FACES_DIR}/{filename}")
    encoding = face_recognition.face_encodings(image)
    if encoding:
        known_face_encodings.append(encoding[0])
        known_face_names.append(filename.split(".")[0])  # Nama file = Nama siswa

@app.route("/verify-face", methods=["POST"])
def verify_face():
    try:
        # Ambil data dari request
        data = request.json
        img_data = base64.b64decode(data["face_encoding"].split(",")[1])
        img_array = np.frombuffer(img_data, dtype=np.uint8)
        img = cv2.imdecode(img_array, cv2.IMREAD_COLOR)

        # Cari wajah dalam gambar
        face_locations = face_recognition.face_locations(img)
        face_encodings = face_recognition.face_encodings(img, face_locations)

        if not face_encodings:
            return jsonify({"status": "error", "message": "Wajah tidak terdeteksi!"}), 400

        # Bandingkan wajah yang dikirim dengan yang sudah terdaftar
        for face_encoding in face_encodings:
            matches = face_recognition.compare_faces(known_face_encodings, face_encoding)
            if True in matches:
                matched_name = known_face_names[matches.index(True)]
                return jsonify({"status": "success", "name": matched_name})

        return jsonify({"status": "error", "message": "Wajah tidak dikenali!"}), 400

    except Exception as e:
        return jsonify({"status": "error", "message": str(e)}), 500


if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5000)