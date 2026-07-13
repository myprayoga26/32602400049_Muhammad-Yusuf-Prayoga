<?= $this->include('layout/header') ?>
<?= $this->include('layout/sidebar') ?>

<div class="mb-4">
    <h4 class="fw-bold text-dark"><i class="fa-solid fa-address-card text-primary"></i> Formulir Anggota</h4>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <form action="<?= isset($anggota) ? base_url('anggota/update/'.$anggota['id_anggota']) : base_url('anggota/simpan') ?>" method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nomor Induk Anggota (NIA)</label>
                        <input type="text" name="nomor_induk" class="form-control" value="<?= isset($anggota) ? $anggota['nomor_induk'] : '' ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" value="<?= isset($anggota) ? $anggota['nama'] : '' ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nomor Telepon / WhatsApp</label>
                        <input type="text" name="no_telp" class="form-control" value="<?= isset($anggota) ? $anggota['no_telp'] : '' ?>">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Alamat Lengkap</label>
                        <textarea name="alamat" class="form-control" rows="3"><?= isset($anggota) ? $anggota['alamat'] : '' ?></textarea>
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="<?= base_url('anggota') ?>" class="btn btn-light me-2 px-4 border">Batal</a>
                        <button type="submit" class="btn btn-primary px-5 shadow-sm"><i class="fa-solid fa-user-check me-1"></i> Simpan Anggota</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->include('layout/footer') ?>
