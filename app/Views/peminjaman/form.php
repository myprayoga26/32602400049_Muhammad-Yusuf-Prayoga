<?= $this->include('layout/header') ?>
<?= $this->include('layout/sidebar') ?>

<div class="mb-4">
    <h4 class="fw-bold text-dark"><i class="fa-solid fa-cart-flatbed text-primary"></i> Formulir Peminjaman</h4>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 bg-light mb-4">
            <div class="card-body">
                <i class="fa-solid fa-circle-info text-primary me-2"></i> <strong>Aturan:</strong> Batas maksimal peminjaman adalah 7 hari. Stok buku akan otomatis berkurang saat transaksi disimpan.
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <form action="<?= base_url('peminjaman/simpan') ?>" method="POST">
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold">Pilih Buku <span class="text-danger">*</span></label>
                        <select name="id_buku" class="form-select" required>
                            <option value="">-- Cari dan Pilih Buku --</option>
                            <?php foreach($buku as $b): ?>
                                <option value="<?= $b['id_buku'] ?>"><?= $b['judul'] ?> (Stok: <?= $b['stok'] ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Pilih Anggota (Peminjam) <span class="text-danger">*</span></label>
                        <select name="id_anggota" class="form-select" required>
                            <option value="">-- Cari dan Pilih Anggota --</option>
                            <?php foreach($anggota as $a): ?>
                                <option value="<?= $a['id_anggota'] ?>"><?= $a['nomor_induk'] ?> - <?= $a['nama'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label class="form-label fw-bold">Tanggal Pinjam</label>
                            <input type="date" name="tgl_pinjam" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Batas Tanggal Kembali</label>
                            <input type="date" name="tgl_kembali" class="form-control" value="<?= date('Y-m-d', strtotime('+7 days')) ?>" required>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-2">
                        <a href="<?= base_url('peminjaman') ?>" class="btn btn-light me-2 px-4 border">Batal</a>
                        <button type="submit" class="btn btn-primary px-5 shadow-sm"><i class="fa-solid fa-paper-plane me-1"></i> Proses Transaksi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->include('layout/footer') ?>
