<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CameraCaptureModel;

class CameraCapture extends BaseController
{
    private string $storageDir;

    public function __construct()
    {
        // Folder penyimpanan di bawah folder project (storage/camera)
        $this->storageDir = WRITEPATH . 'faces';
        if (!is_dir($this->storageDir)) {
            mkdir($this->storageDir, 0755, true);
        }
    }

    /**
     * Simpan gambar base64 ke file.
     *
     * @param string $base64Image Data gambar dalam format data URI (e.g. data:image/jpeg;base64,....)
     * @return string Path file yang tersimpan
     * @throws \InvalidArgumentException Jika data tidak valid
     * @throws \RuntimeException       Jika gagal menulis file
     */
    public function store(string $base64Image): string
    {
        // Hapus prefix "data:image/*;base64," jika ada
        $base64Image = preg_replace('/^data:image\/\w+;base64,/', '', $base64Image);

        // Validasi karakter base64
        if (!preg_match('/^[a-zA-Z0-9\/\+]+={0,2}$/', $base64Image)) {
            throw new \InvalidArgumentException('Invalid base64 data');
        }

        $imageData = base64_decode($base64Image);
        if ($imageData === false) {
            throw new \InvalidArgumentException('Failed to decode base64');
        }

        // Buat nama file unik
        $uniqueName = 'cam_' . time() . '_' . bin2hex(random_bytes(5)) . '.jpg';
        $filePath   = $this->storageDir . DIRECTORY_SEPARATOR . $uniqueName;

        // Tulis ke file
        $bytesWritten = file_put_contents($filePath, $imageData);
        if ($bytesWritten === false) {
            throw new \RuntimeException('Unable to write file to storage directory');
        }

        return $filePath;
    }
}