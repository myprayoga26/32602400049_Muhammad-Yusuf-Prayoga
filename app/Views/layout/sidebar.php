    <!-- App Dashboard Header (Top Navbar) -->
    <div class="bg-white px-4 py-3 d-flex justify-content-between align-items-center border-bottom shadow-sm">
        <a href="<?= base_url('dashboard') ?>" class="text-decoration-none text-dark d-flex align-items-center">
            <!-- Simulated L Logo from image -->
            <div style="width:32px; height:32px; position:relative; margin-right:12px;">
                <div style="position:absolute; bottom:0; left:0; width:100%; height:8px; background:var(--bs-primary);"></div>
                <div style="position:absolute; bottom:0; left:0; width:8px; height:100%; background:var(--bs-primary);"></div>
                <div style="position:absolute; bottom:6px; left:6px; width:70%; height:6px; background:var(--bs-warning);"></div>
                <div style="position:absolute; bottom:6px; left:6px; width:6px; height:70%; background:var(--bs-warning);"></div>
            </div>
            <span class="fs-4 brand-font fw-bold" style="letter-spacing: 1px;">LITERIA</span>
        </a>
        <div class="d-flex align-items-center">
            <span class="me-4 text-muted"><i class="fa-regular fa-bell fs-5"></i></span>
            <img src="https://ui-avatars.com/api/?name=<?= session()->get('nama') ?? 'User' ?>&background=0B1B3D&color=fff" alt="User" class="rounded-circle" width="36">
            <a href="<?= base_url('auth/logout') ?>" class="btn btn-sm btn-outline-danger ms-3 rounded-pill px-3"><i class="fa-solid fa-power-off"></i> Keluar</a>
        </div>
    </div>
    
    <!-- Secondary Nav (Deep Navy) -->
    <div class="bg-primary px-4 topnav-dark shadow-sm">
        <ul class="nav">
            <?php if(session()->get('role') == 'admin'): ?>
                <li class="nav-item"><a href="<?= base_url('dashboard') ?>" class="nav-link <?= current_url(true)->getSegment(1) == 'dashboard' ? 'active' : '' ?>">Dashboard</a></li>
                <li class="nav-item"><a href="<?= base_url('buku') ?>" class="nav-link <?= current_url(true)->getSegment(1) == 'buku' ? 'active' : '' ?>">Catalog</a></li>
                <li class="nav-item"><a href="<?= base_url('anggota') ?>" class="nav-link <?= current_url(true)->getSegment(1) == 'anggota' ? 'active' : '' ?>">Patrons</a></li>
                <li class="nav-item"><a href="<?= base_url('peminjaman') ?>" class="nav-link <?= current_url(true)->getSegment(1) == 'peminjaman' ? 'active' : '' ?>">Reports</a></li>
            <?php else: ?>
                <li class="nav-item"><a href="<?= base_url('user/dashboard') ?>" class="nav-link active">My Dashboard</a></li>
            <?php endif; ?>
        </ul>
    </div>

    <!-- Main Content Area -->
    <div class="container-fluid py-5 px-4 main-content">
