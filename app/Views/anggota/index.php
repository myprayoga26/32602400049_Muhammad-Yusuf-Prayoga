<?= $this->include('layout/header') ?>
<?= $this->include('layout/sidebar') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold text-dark"><i class="fa-solid fa-users text-primary"></i> Data Anggota</h4>
    <a href="<?= base_url('anggota/tambah') ?>" class="btn btn-primary rounded-pill px-4 shadow-sm"><i class="fa-solid fa-user-plus"></i> Tambah Anggota</a>
</div>

<?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-premium w-100">
                <thead>
                    <tr>
                        <th>No. Induk</th>
                        <th>Nama Lengkap</th>
                        <th>No. Telepon</th>
                        <th>Alamat</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($anggota as $a): ?>
                    <tr>
                        <td class="text-muted fw-bold"><?= $a['nomor_induk'] ?></td>
                        <td class="fw-bold text-dark"><?= $a['nama'] ?></td>
                        <td><?= $a['no_telp'] ?></td>
                        <td><?= $a['alamat'] ?></td>
                        <td class="text-center">
                            <a href="<?= base_url('anggota/edit/'.$a['id_anggota']) ?>" class="btn btn-sm btn-outline-primary rounded-circle"><i class="fa-solid fa-pen"></i></a>
                            <a href="<?= base_url('anggota/hapus/'.$a['id_anggota']) ?>" class="btn btn-sm btn-outline-danger rounded-circle" onclick="return confirm('Yakin menghapus anggota ini?');"><i class="fa-solid fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->include('layout/footer') ?>
