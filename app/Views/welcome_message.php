<?= $this->include('layout/header') ?>
<?= $this->include('layout/sidebar') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="row g-4 mb-4">
    <div class="col-lg-7">
        <div class="card border-0 h-100" style="background:#002925;color:#fffefa;border-radius:24px;box-shadow:0 22px 54px rgba(0,41,37,.16)!important;">
            <div class="card-body p-4 p-lg-5">
                <p class="text-uppercase mb-3" style="letter-spacing:.16em;color:#c5a556;font-size:.75rem;font-weight:700;">Admin control room</p>
                <h1 class="brand-font text-white mb-3" style="font-size:clamp(2.4rem,4vw,4.4rem);line-height:1.05;">Kelola arsip LITERIA dengan tenang.</h1>
                <p class="mb-0" style="max-width:680px;color:rgba(255,254,250,.66);line-height:1.8;">Pantau koleksi, anggota, peminjaman aktif, dan transaksi terbaru dari satu ruang kerja yang selaras dengan identitas LITERIA.</p>
                <div class="mt-4">
                    <button id="btnGutenberg" onclick="runGutenbergImporter()" class="btn rounded-pill px-4" style="background:#c5a556;color:#002925;font-weight:700;">
                        <i class="fa-solid fa-cloud-arrow-down me-2"></i> Auto-Import Buku Klasik (Gutenberg)
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="row g-3 h-100">
            <div class="col-6">
                <div class="card h-100 border-0" style="border-radius:20px;">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <span class="chip" style="background:#f7f1e3;color:#002925;">Catalog</span>
                            <i class="fa-solid fa-book text-primary"></i>
                        </div>
                        <h2 class="brand-font mt-4 mb-1"><?= esc($total_buku) ?></h2>
                        <p class="text-muted mb-0 small">Total buku</p>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card h-100 border-0" style="border-radius:20px;">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <span class="chip" style="background:#f7f1e3;color:#002925;">Patrons</span>
                            <i class="fa-solid fa-users text-primary"></i>
                        </div>
                        <h2 class="brand-font mt-4 mb-1"><?= esc($total_anggota) ?></h2>
                        <p class="text-muted mb-0 small">Anggota</p>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card border-0" style="border-radius:20px;background:#fffefa;">
                    <div class="card-body p-4 d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted small mb-1">Peminjaman aktif</p>
                            <h2 class="brand-font mb-0"><?= esc($total_pinjam) ?></h2>
                        </div>
                        <a href="<?= base_url('peminjaman') ?>" class="btn rounded-pill px-4" style="background:#c5a556;color:#002925;font-weight:700;">Kelola</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 table-premium" style="border-radius:22px;">
            <div class="card-header bg-white border-0 p-4 d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="brand-font fw-bold text-dark mb-1">Transaksi terbaru</h5>
                    <p class="text-muted small mb-0">Pantau status peminjaman dan pengembalian.</p>
                </div>
                <a href="<?= base_url('peminjaman') ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3">Reports</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table w-100 mb-0">
                        <thead>
                            <tr>
                                <th>Peminjam</th>
                                <th>Buku</th>
                                <th>Tgl Pinjam</th>
                                <th>Batas Kembali</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($riwayat_terbaru)): ?>
                                <tr><td colspan="5" class="text-center text-muted py-5">Belum ada transaksi.</td></tr>
                            <?php else: ?>
                                <?php foreach(array_slice($riwayat_terbaru, 0, 8) as $row): ?>
                                    <tr>
                                        <td><strong><?= esc($row['nama']) ?></strong><br><small class="text-muted"><?= esc($row['nomor_induk']) ?></small></td>
                                        <td><?= esc($row['judul']) ?></td>
                                        <td><?= date('d M Y', strtotime($row['tgl_pinjam'])) ?></td>
                                        <td><?= date('d M Y', strtotime($row['tgl_kembali'])) ?></td>
                                        <td>
                                            <?php if($row['status'] == 'Dipinjam'): ?>
                                                <span class="chip chip-reserved">Sedang dipinjam</span>
                                            <?php else: ?>
                                                <span class="chip chip-available">Dikembalikan</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 h-100" style="border-radius:22px;background:#fffefa;">
            <div class="card-body p-4">
                <h5 class="brand-font fw-bold text-dark mb-3">Aksi cepat</h5>
                <div class="d-grid gap-3">
                    <a href="<?= base_url('buku/tambah') ?>" class="btn text-start rounded-4 p-3" style="background:#f7f1e3;color:#002925;font-weight:700;"><i class="fa-solid fa-plus me-2"></i>Tambah buku</a>
                    <a href="<?= base_url('anggota') ?>" class="btn text-start rounded-4 p-3" style="background:#f7f1e3;color:#002925;font-weight:700;"><i class="fa-solid fa-id-card me-2"></i>Kelola anggota</a>
                    <a href="<?= base_url() ?>" class="btn text-start rounded-4 p-3" style="background:#f7f1e3;color:#002925;font-weight:700;"><i class="fa-solid fa-eye me-2"></i>Lihat katalog publik</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-1">
    <div class="col-lg-6">
        <div class="card border-0" style="border-radius:22px;background:#fffefa;">
            <div class="card-body p-4">
                <h5 class="brand-font fw-bold text-dark mb-3">Statistik Peminjaman (7 Hari Terakhir)</h5>
                <canvas id="borrowChart" height="200"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card border-0" style="border-radius:22px;background:#fffefa;">
            <div class="card-body p-4">
                <h5 class="brand-font fw-bold text-dark mb-3">Buku Paling Diminati</h5>
                <canvas id="popularChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const borrowData = <?= $borrowStats ?>;
        const popularData = <?= $popularBooks ?>;

        // Borrow Chart
        const ctxBorrow = document.getElementById('borrowChart').getContext('2d');
        new Chart(ctxBorrow, {
            type: 'line',
            data: {
                labels: borrowData.map(item => item.date),
                datasets: [{
                    label: 'Jumlah Peminjaman',
                    data: borrowData.map(item => item.total),
                    borderColor: '#c5a556',
                    backgroundColor: 'rgba(197, 165, 86, 0.2)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: { responsive: true, plugins: { legend: { display: false } } }
        });

        // Popular Books Chart
        const ctxPopular = document.getElementById('popularChart').getContext('2d');
        new Chart(ctxPopular, {
            type: 'bar',
            data: {
                labels: popularData.map(item => item.judul.length > 15 ? item.judul.substring(0, 15) + '...' : item.judul),
                datasets: [{
                    label: 'Di Rak Pengguna',
                    data: popularData.map(item => item.total),
                    backgroundColor: '#002925',
                    borderRadius: 4
                }]
            },
            options: { responsive: true, plugins: { legend: { display: false } } }
        });
    });

    async function runGutenbergImporter() {
        const btn = document.getElementById('btnGutenberg');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Mengunduh...';
        btn.disabled = true;

        try {
            const res = await fetch('<?= base_url('web/seed_gutenberg') ?>');
            const text = await res.text();
            alert(text);
            window.location.reload();
        } catch (e) {
            alert('Gagal mengunduh: ' + e.message);
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    }
</script>

<?= $this->include('layout/footer') ?>
