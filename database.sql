CREATE DATABASE IF NOT EXISTS literia_app;
USE literia_app;

CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS buku (
    id_buku INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255) NOT NULL,
    pengarang VARCHAR(150) NOT NULL,
    penerbit VARCHAR(150) NOT NULL,
    tahun_terbit INT NOT NULL,
    stok INT NOT NULL DEFAULT 0,
    kategori VARCHAR(100) DEFAULT 'Umum',
    sinopsis TEXT,
    cover_url VARCHAR(255) DEFAULT '',
    rating DECIMAL(3,1) DEFAULT 0.0,
    jumlah_halaman INT DEFAULT 0,
    isbn VARCHAR(20) DEFAULT '',
    read_access ENUM('metadata', 'public_domain') DEFAULT 'metadata',
    source_name VARCHAR(100) DEFAULT '',
    source_url VARCHAR(255) DEFAULT '',
    reading_text LONGTEXT
);

CREATE TABLE IF NOT EXISTS anggota (
    id_anggota INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(150) NOT NULL,
    username VARCHAR(50) UNIQUE,
    nomor_induk VARCHAR(50) NOT NULL UNIQUE,
    no_telp VARCHAR(20),
    alamat TEXT,
    password VARCHAR(255),
    tier ENUM('free', 'premium') DEFAULT 'free',
    avatar_url VARCHAR(255) DEFAULT ''
);

CREATE TABLE IF NOT EXISTS peminjaman (
    id_pinjam INT AUTO_INCREMENT PRIMARY KEY,
    id_buku INT NOT NULL,
    id_anggota INT NOT NULL,
    tgl_pinjam DATE NOT NULL,
    tgl_kembali DATE NOT NULL,
    status ENUM('Dipinjam', 'Dikembalikan') DEFAULT 'Dipinjam',
    FOREIGN KEY (id_buku) REFERENCES buku(id_buku) ON DELETE CASCADE,
    FOREIGN KEY (id_anggota) REFERENCES anggota(id_anggota) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS user_shelf (
    id_rak INT AUTO_INCREMENT PRIMARY KEY,
    id_anggota INT NOT NULL,
    id_buku INT NOT NULL,
    status_baca ENUM('wishlist', 'reading', 'finished') DEFAULT 'wishlist',
    progress_persen INT DEFAULT 0,
    halaman_terakhir INT DEFAULT 0,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uniq_user_book (id_anggota, id_buku),
    FOREIGN KEY (id_anggota) REFERENCES anggota(id_anggota) ON DELETE CASCADE,
    FOREIGN KEY (id_buku) REFERENCES buku(id_buku) ON DELETE CASCADE
);

-- Insert sample admin
INSERT IGNORE INTO admin (username, password, nama_lengkap) VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator Literia');
-- (Password is 'password' hashed with bcrypt)

INSERT IGNORE INTO anggota (id_anggota, nama, username, nomor_induk, no_telp, alamat, password, tier) VALUES
(1, 'Pembaca LITERIA', 'pembaca', 'LIT-0001', '081234567890', 'Ruang Baca Digital', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'premium');

INSERT IGNORE INTO buku (id_buku, judul, pengarang, penerbit, tahun_terbit, stok, kategori, sinopsis, cover_url, rating, jumlah_halaman, isbn) VALUES
(1, 'Atomic Habits', 'James Clear', 'Avery', 2018, 7, 'Pengembangan Diri', 'Panduan membangun kebiasaan kecil yang konsisten, memperbaiki sistem pribadi, dan menciptakan perubahan yang bertahan lama.', 'https://covers.openlibrary.org/b/isbn/9780735211292-L.jpg', 4.7, 320, '9780735211292'),
(2, 'Sapiens: A Brief History of Humankind', 'Yuval Noah Harari', 'Harper', 2014, 6, 'Sejarah & Masyarakat', 'Narasi besar tentang perjalanan Homo sapiens dari revolusi kognitif sampai dunia modern.', 'https://covers.openlibrary.org/b/isbn/9780062316097-L.jpg', 4.6, 443, '9780062316097'),
(3, 'The Design of Everyday Things', 'Don Norman', 'Basic Books', 2013, 4, 'Desain & UX', 'Buku klasik tentang affordance, signifier, feedback, dan prinsip desain yang membuat sistem mudah dipahami.', 'https://covers.openlibrary.org/b/isbn/9780465067107-L.jpg', 4.6, 368, '9780465067107'),
(4, 'Clean Code', 'Robert C. Martin', 'Prentice Hall', 2008, 5, 'Teknologi', 'Praktik menulis kode yang lebih mudah dibaca, dirawat, dan dikembangkan dalam proyek perangkat lunak.', 'https://covers.openlibrary.org/b/isbn/9780132350884-L.jpg', 4.5, 464, '9780132350884'),
(5, 'The Pragmatic Programmer', 'David Thomas and Andrew Hunt', 'Addison-Wesley', 2019, 4, 'Teknologi', 'Kumpulan prinsip untuk membangun kebiasaan berpikir pragmatis dalam pekerjaan software engineering.', 'https://covers.openlibrary.org/b/isbn/9780135957059-L.jpg', 4.7, 352, '9780135957059'),
(6, 'Thinking, Fast and Slow', 'Daniel Kahneman', 'Farrar, Straus and Giroux', 2011, 5, 'Psikologi', 'Eksplorasi tentang dua sistem berpikir manusia dan bagaimana bias memengaruhi keputusan.', 'https://covers.openlibrary.org/b/isbn/9780374533557-L.jpg', 4.4, 499, '9780374533557'),
(7, 'How to Read a Book', 'Mortimer J. Adler and Charles Van Doren', 'Touchstone', 1972, 3, 'Literasi', 'Panduan klasik membaca aktif, dari inspeksi cepat sampai pembacaan analitis dan sintopis.', 'https://covers.openlibrary.org/b/isbn/9780671212094-L.jpg', 4.4, 426, '9780671212094'),
(8, 'Brief Answers to the Big Questions', 'Stephen Hawking', 'Bantam Books', 2018, 4, 'Sains', 'Esai ringkas Stephen Hawking tentang kosmos, masa depan manusia, kecerdasan buatan, dan pertanyaan besar sains.', 'https://covers.openlibrary.org/b/isbn/9781984819192-L.jpg', 4.3, 256, '9781984819192'),
(9, 'Bumi Manusia', 'Pramoedya Ananta Toer', 'Lentera Dipantara', 1980, 5, 'Sastra Indonesia', 'Novel pembuka Tetralogi Buru tentang Minke, kolonialisme, pendidikan, dan perubahan sosial Hindia Belanda.', 'https://covers.openlibrary.org/b/isbn/9789799731234-L.jpg', 4.7, 535, '9789799731234'),
(10, 'Laut Bercerita', 'Leila S. Chudori', 'Kepustakaan Populer Gramedia', 2017, 6, 'Sastra Indonesia', 'Novel tentang ingatan, kehilangan, keluarga, dan aktivisme mahasiswa pada masa Orde Baru.', 'https://covers.openlibrary.org/b/isbn/9786024246945-L.jpg', 4.8, 379, '9786024246945'),
(11, 'Cantik Itu Luka', 'Eka Kurniawan', 'Gramedia Pustaka Utama', 2002, 4, 'Sastra Indonesia', 'Saga keluarga dan sejarah Indonesia yang memadukan realisme magis, satire, dan kekerasan politik.', 'https://covers.openlibrary.org/b/isbn/9786020312583-L.jpg', 4.5, 505, '9786020312583'),
(12, 'Man''s Search for Meaning', 'Viktor E. Frankl', 'Beacon Press', 2006, 5, 'Filsafat', 'Refleksi psikolog dan penyintas kamp konsentrasi tentang makna, penderitaan, dan kebebasan batin manusia.', 'https://covers.openlibrary.org/b/isbn/9780807014271-L.jpg', 4.7, 184, '9780807014271');
