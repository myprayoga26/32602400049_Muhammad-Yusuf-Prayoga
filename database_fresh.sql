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

INSERT IGNORE INTO admin (username, password, nama_lengkap) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator Literia');

INSERT IGNORE INTO anggota (id_anggota, nama, username, nomor_induk, no_telp, alamat, password, tier) VALUES
(1, 'Pembaca LITERIA', 'pembaca', 'LIT-0001', '081234567890', 'Ruang Baca Digital', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'premium');

INSERT IGNORE INTO buku (id_buku, judul, pengarang, penerbit, tahun_terbit, stok, kategori, sinopsis, cover_url, rating, jumlah_halaman, isbn, read_access, source_name, source_url, reading_text) VALUES
(1, 'Atomic Habits', 'James Clear', 'Avery', 2018, 7, 'Pengembangan Diri', 'Panduan membangun kebiasaan kecil yang konsisten, memperbaiki sistem pribadi, dan menciptakan perubahan yang bertahan lama.', 'https://covers.openlibrary.org/b/isbn/9780735211292-L.jpg', 4.7, 320, '9780735211292', 'metadata', '', '', NULL),
(2, 'Sapiens: A Brief History of Humankind', 'Yuval Noah Harari', 'Harper', 2014, 6, 'Sejarah & Masyarakat', 'Narasi besar tentang perjalanan Homo sapiens dari revolusi kognitif sampai dunia modern.', 'https://covers.openlibrary.org/b/isbn/9780062316097-L.jpg', 4.6, 443, '9780062316097', 'metadata', '', '', NULL),
(3, 'The Design of Everyday Things', 'Don Norman', 'Basic Books', 2013, 4, 'Desain & UX', 'Buku klasik tentang affordance, signifier, feedback, dan prinsip desain yang membuat sistem mudah dipahami.', 'https://covers.openlibrary.org/b/isbn/9780465067107-L.jpg', 4.6, 368, '9780465067107', 'metadata', '', '', NULL),
(4, 'Clean Code', 'Robert C. Martin', 'Prentice Hall', 2008, 5, 'Teknologi', 'Praktik menulis kode yang lebih mudah dibaca, dirawat, dan dikembangkan dalam proyek perangkat lunak.', 'https://covers.openlibrary.org/b/isbn/9780132350884-L.jpg', 4.5, 464, '9780132350884', 'metadata', '', '', NULL),
(5, 'The Pragmatic Programmer', 'David Thomas and Andrew Hunt', 'Addison-Wesley', 2019, 4, 'Teknologi', 'Kumpulan prinsip untuk membangun kebiasaan berpikir pragmatis dalam pekerjaan software engineering.', 'https://covers.openlibrary.org/b/isbn/9780135957059-L.jpg', 4.7, 352, '9780135957059', 'metadata', '', '', NULL),
(6, 'Thinking, Fast and Slow', 'Daniel Kahneman', 'Farrar, Straus and Giroux', 2011, 5, 'Psikologi', 'Eksplorasi tentang dua sistem berpikir manusia dan bagaimana bias memengaruhi keputusan.', 'https://covers.openlibrary.org/b/isbn/9780374533557-L.jpg', 4.4, 499, '9780374533557', 'metadata', '', '', NULL),
(7, 'How to Read a Book', 'Mortimer J. Adler and Charles Van Doren', 'Touchstone', 1972, 3, 'Literasi', 'Panduan klasik membaca aktif, dari inspeksi cepat sampai pembacaan analitis dan sintopis.', 'https://covers.openlibrary.org/b/isbn/9780671212094-L.jpg', 4.4, 426, '9780671212094', 'metadata', '', '', NULL),
(8, 'Brief Answers to the Big Questions', 'Stephen Hawking', 'Bantam Books', 2018, 4, 'Sains', 'Esai ringkas Stephen Hawking tentang kosmos, masa depan manusia, kecerdasan buatan, dan pertanyaan besar sains.', 'https://covers.openlibrary.org/b/isbn/9781984819192-L.jpg', 4.3, 256, '9781984819192', 'metadata', '', '', NULL),
(9, 'Bumi Manusia', 'Pramoedya Ananta Toer', 'Lentera Dipantara', 1980, 5, 'Sastra Indonesia', 'Novel pembuka Tetralogi Buru tentang Minke, kolonialisme, pendidikan, dan perubahan sosial Hindia Belanda.', 'https://covers.openlibrary.org/b/isbn/9789799731234-L.jpg', 4.7, 535, '9789799731234', 'metadata', '', '', NULL),
(10, 'Laut Bercerita', 'Leila S. Chudori', 'Kepustakaan Populer Gramedia', 2017, 6, 'Sastra Indonesia', 'Novel tentang ingatan, kehilangan, keluarga, dan aktivisme mahasiswa pada masa Orde Baru.', 'https://covers.openlibrary.org/b/isbn/9786024246945-L.jpg', 4.8, 379, '9786024246945', 'metadata', '', '', NULL),
(11, 'Cantik Itu Luka', 'Eka Kurniawan', 'Gramedia Pustaka Utama', 2002, 4, 'Sastra Indonesia', 'Saga keluarga dan sejarah Indonesia yang memadukan realisme magis, satire, dan kekerasan politik.', 'https://covers.openlibrary.org/b/isbn/9786020312583-L.jpg', 4.5, 505, '9786020312583', 'metadata', '', '', NULL),
(12, 'Man''s Search for Meaning', 'Viktor E. Frankl', 'Beacon Press', 2006, 5, 'Filsafat', 'Refleksi psikolog dan penyintas kamp konsentrasi tentang makna, penderitaan, dan kebebasan batin manusia.', 'https://covers.openlibrary.org/b/isbn/9780807014271-L.jpg', 4.7, 184, '9780807014271', 'metadata', '', '', NULL),
(13, 'Pride and Prejudice', 'Jane Austen', 'T. Egerton', 1813, 8, 'Public Domain Classics', 'Novel klasik tentang Elizabeth Bennet, keluarga, status sosial, dan penilaian yang berubah seiring pengalaman.', 'https://covers.openlibrary.org/b/isbn/9780141439518-L.jpg', 4.8, 432, '9780141439518', 'public_domain', 'Project Gutenberg', 'https://www.gutenberg.org/ebooks/1342', 'It is a truth universally acknowledged, that a single man in possession of a good fortune, must be in want of a wife. However little known the feelings or views of such a man may be on his first entering a neighbourhood, this truth is so well fixed in the minds of the surrounding families, that he is considered the rightful property of some one or other of their daughters. "My dear Mr. Bennet," said his lady to him one day, "have you heard that Netherfield Park is let at last?" Mr. Bennet replied that he had not. "But it is," returned she; "for Mrs. Long has just been here, and she told me all about it." Mr. Bennet made no answer. "Do you not want to know who has taken it?" cried his wife impatiently. "You want to tell me, and I have no objection to hearing it." This was invitation enough.'),
(14, 'Frankenstein', 'Mary Wollstonecraft Shelley', 'Lackington, Hughes, Harding, Mavor & Jones', 1818, 7, 'Public Domain Classics', 'Kisah Victor Frankenstein, penciptaan, tanggung jawab moral, dan kesepian makhluk yang ia hidupkan.', 'https://covers.openlibrary.org/b/isbn/9780141439471-L.jpg', 4.6, 280, '9780141439471', 'public_domain', 'Project Gutenberg', 'https://www.gutenberg.org/ebooks/84', 'You will rejoice to hear that no disaster has accompanied the commencement of an enterprise which you have regarded with such evil forebodings. I arrived here yesterday, and my first task is to assure my dear sister of my welfare and increasing confidence in the success of my undertaking. I am already far north of London, and as I walk in the streets of Petersburgh, I feel a cold northern breeze play upon my cheeks, which braces my nerves and fills me with delight. Do you understand this feeling? This breeze, which has travelled from the regions towards which I am advancing, gives me a foretaste of those icy climes.'),
(15, 'Alice''s Adventures in Wonderland', 'Lewis Carroll', 'Macmillan', 1865, 9, 'Public Domain Classics', 'Petualangan Alice di dunia absurd yang penuh teka-teki bahasa, logika bermain, dan karakter ikonik.', 'https://covers.openlibrary.org/b/isbn/9780141439761-L.jpg', 4.5, 192, '9780141439761', 'public_domain', 'Project Gutenberg', 'https://www.gutenberg.org/ebooks/11', 'Alice was beginning to get very tired of sitting by her sister on the bank, and of having nothing to do. Once or twice she had peeped into the book her sister was reading, but it had no pictures or conversations in it. "And what is the use of a book," thought Alice, "without pictures or conversations?" So she was considering in her own mind, as well as she could, for the hot day made her feel very sleepy and stupid, whether the pleasure of making a daisy-chain would be worth the trouble of getting up and picking the daisies.'),
(16, 'The Time Machine', 'H. G. Wells', 'William Heinemann', 1895, 6, 'Public Domain Classics', 'Fiksi ilmiah awal tentang perjalanan waktu, masa depan manusia, dan perubahan sosial yang dibayangkan secara spekulatif.', 'https://covers.openlibrary.org/b/isbn/9780141439976-L.jpg', 4.4, 128, '9780141439976', 'public_domain', 'Project Gutenberg', 'https://www.gutenberg.org/ebooks/35', 'The Time Traveller, for so it will be convenient to speak of him, was expounding a recondite matter to us. His grey eyes shone and twinkled, and his usually pale face was flushed and animated. The fire burned brightly, and the soft radiance of the incandescent lights in the lilies of silver caught the bubbles that flashed and passed in our glasses. Our chairs, being his patents, embraced and caressed us rather than submitted to be sat upon.');

INSERT IGNORE INTO user_shelf (id_anggota, id_buku, status_baca, progress_persen, halaman_terakhir) VALUES
(1, 1, 'reading', 45, 144),
(1, 3, 'wishlist', 0, 0),
(1, 10, 'finished', 100, 379);
