-- Database: whyuskieee_db
-- Buat database terlebih dahulu

CREATE DATABASE IF NOT EXISTS whyuskieee_db;
USE whyuskieee_db;

-- Tabel Admin
CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel Announcements
CREATE TABLE IF NOT EXISTS announcements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    date_created DATE NOT NULL,
    status ENUM('penting', 'biasa') DEFAULT 'biasa',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default admin (username: admin, password: admin123)
-- Password sudah di-hash menggunakan password_hash()
INSERT INTO admin (username, password) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Insert sample announcements
INSERT INTO announcements (title, content, date_created, status) VALUES
('Selamat Datang di Whyuskieee', 'Website pengumuman resmi dengan tema Neubrutalism modern. Semua informasi penting akan diumumkan melalui platform ini. Tetap update ya!', CURDATE(), 'penting'),
('Jadwal Maintenance Server', 'Server akan mengalami maintenance pada tanggal 20-21 Januari 2025. Mohon simpan pekerjaan Anda sebelum waktu tersebut.', '2025-01-15', 'penting'),
('Fitur Baru Tersedia', 'Kami telah menambahkan fitur pencarian dan filter pengumuman. Sekarang Anda bisa mencari pengumuman dengan mudah!', '2025-01-10', 'biasa'),
('Update Kebijakan Privasi', 'Kebijakan privasi telah diperbarui. Silakan baca perubahan terbaru di halaman kebijakan kami.', '2025-01-05', 'biasa'),
('Pengumuman Libur Nasional', 'Kantor akan tutup pada tanggal hari libur nasional. Layanan akan resume pada hari kerja berikutnya.', '2025-01-01', 'penting');