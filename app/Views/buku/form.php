<?= $this->include('layout/header') ?>
<?= $this->include('layout/sidebar') ?>

<div class="mb-4">
    <h4 class="fw-bold text-dark"><i class="fa-solid fa-book-medical text-primary"></i> Formulir Buku</h4>
    <p class="text-muted">Gunakan formulir ini untuk menambahkan atau mengubah data buku.</p>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <form action="<?= isset($buku) ? base_url('buku/update/'.$buku['id_buku']) : base_url('buku/simpan') ?>" method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Judul Buku</label>
                        <input type="text" name="judul" class="form-control" value="<?= isset($buku) ? $buku['judul'] : '' ?>" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Pengarang</label>
                            <input type="text" name="pengarang" class="form-control" value="<?= isset($buku) ? $buku['pengarang'] : '' ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Penerbit</label>
                            <input type="text" name="penerbit" class="form-control" value="<?= isset($buku) ? $buku['penerbit'] : '' ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Tahun Terbit</label>
                            <input type="number" name="tahun_terbit" class="form-control" value="<?= isset($buku) ? $buku['tahun_terbit'] : '' ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Jumlah Stok</label>
                            <input type="number" name="stok" class="form-control" value="<?= isset($buku) ? $buku['stok'] : '' ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">ISBN</label>
                            <input type="text" name="isbn" class="form-control" value="<?= isset($buku) ? ($buku['isbn'] ?? '') : '' ?>" placeholder="9780132350884">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Jumlah Halaman</label>
                            <input type="number" name="jumlah_halaman" class="form-control" value="<?= isset($buku) ? ($buku['jumlah_halaman'] ?? '') : '' ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Rating</label>
                            <input type="number" step="0.1" min="0" max="5" name="rating" class="form-control" value="<?= isset($buku) ? ($buku['rating'] ?? '') : '' ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">URL Sampul</label>
                            <input type="url" name="cover_url" class="form-control" value="<?= isset($buku) ? ($buku['cover_url'] ?? '') : '' ?>" placeholder="https://covers.openlibrary.org/b/isbn/...-L.jpg">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Kategori</label>
                        <select name="kategori" class="form-select" required>
                            <?php 
                            $kategoriList = ['Umum', 'Sastra Indonesia', 'Sejarah & Masyarakat', 'Desain & UX', 'Teknologi', 'Psikologi', 'Literasi', 'Sains', 'Filsafat', 'Pengembangan Diri'];
                            $current = isset($buku) ? $buku['kategori'] : 'Umum';
                            foreach($kategoriList as $k): 
                            ?>
                                <option value="<?= $k ?>" <?= $current == $k ? 'selected' : '' ?>><?= $k ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Sinopsis</label>
                        <textarea name="sinopsis" class="form-control" rows="5" placeholder="Ringkasan isi buku"><?= isset($buku) ? ($buku['sinopsis'] ?? '') : '' ?></textarea>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <a href="<?= base_url('buku') ?>" class="btn btn-light me-2 px-4 border">Batal</a>
                        <button type="submit" class="btn btn-primary px-5 shadow-sm"><i class="fa-solid fa-floppy-disk me-1"></i> Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->include('layout/footer') ?>
