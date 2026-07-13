<?= $this->include('layout/header') ?>
<?= $this->include('layout/sidebar') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold text-dark"><i class="fa-solid fa-book text-primary"></i> Data Buku</h4>
    <a href="<?= base_url('buku/tambah') ?>" class="btn btn-primary rounded-pill px-4 shadow-sm"><i class="fa-solid fa-plus"></i> Tambah Buku Baru</a>
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
                        <th>ID</th>
                        <th>Judul Buku</th>
                        <th>Pengarang</th>
                        <th>Penerbit</th>
                        <th>Tahun</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($buku as $b): ?>
                    <tr>
                        <td class="text-muted">BK-<?= sprintf('%03d', $b['id_buku']) ?></td>
                        <td class="fw-bold text-dark"><?= $b['judul'] ?></td>
                        <td><?= $b['pengarang'] ?></td>
                        <td><?= $b['penerbit'] ?></td>
                        <td><?= $b['tahun_terbit'] ?></td>
                        <td><span class="badge bg-secondary"><?= $b['kategori'] ?? 'Umum' ?></span></td>
                        <td>
                            <?php if($b['stok'] > 0): ?>
                                <span class="chip chip-available">Available (<?= $b['stok'] ?>)</span>
                            <?php else: ?>
                                <span class="chip chip-overdue">Reserved</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <a href="<?= base_url('buku/edit/'.$b['id_buku']) ?>" class="btn btn-sm btn-outline-primary rounded-circle"><i class="fa-solid fa-pen"></i></a>
                            <a href="<?= base_url('buku/hapus/'.$b['id_buku']) ?>" class="btn btn-sm btn-outline-danger rounded-circle" onclick="return confirm('Yakin ingin menghapus buku ini?');"><i class="fa-solid fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->include('layout/footer') ?>
