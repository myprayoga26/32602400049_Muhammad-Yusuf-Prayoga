<?= $this->include('layout/header') ?>
<?= $this->include('layout/sidebar') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold text-dark"><i class="fa-solid fa-right-left text-primary"></i> Data Peminjaman</h4>
    <a href="<?= base_url('peminjaman/tambah') ?>" class="btn btn-primary rounded-pill px-4 shadow-sm"><i class="fa-solid fa-plus"></i> Transaksi Baru</a>
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
                        <th>ID Pinjam</th>
                        <th>Buku</th>
                        <th>Peminjam</th>
                        <th>Tanggal Pinjam</th>
                        <th>Batas Kembali</th>
                        <th>Denda</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($peminjaman as $p): ?>
                    <tr>
                        <td class="text-muted">#TRX-<?= sprintf('%03d', $p['id_pinjam']) ?></td>
                        <td class="fw-bold text-dark"><?= $p['judul'] ?></td>
                        <td><strong><?= $p['nama'] ?></strong><br><small class="text-muted"><?= $p['nomor_induk'] ?></small></td>
                        <td><?= date('d M Y', strtotime($p['tgl_pinjam'])) ?></td>
                        <td><?= date('d M Y', strtotime($p['tgl_kembali'])) ?></td>
                        <td>
                            <?php if(isset($p['denda']) && $p['denda'] > 0): ?>
                                <span class="text-danger fw-bold">Rp <?= number_format($p['denda'], 0, ',', '.') ?></span>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($p['status'] == 'Dipinjam'): ?>
                                <span class="chip chip-reserved">Reserved</span>
                            <?php else: ?>
                                <span class="chip chip-available">Returned</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <?php if($p['status'] == 'Dipinjam'): ?>
                                <a href="<?= base_url('peminjaman/kembali/'.$p['id_pinjam']) ?>" class="btn btn-sm btn-success shadow-sm" title="Kembalikan Buku" onclick="return confirm('Proses pengembalian buku?');"><i class="fa-solid fa-check"></i> Selesai</a>
                            <?php else: ?>
                                <span class="text-muted"><i class="fa-solid fa-check-double"></i></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->include('layout/footer') ?>
