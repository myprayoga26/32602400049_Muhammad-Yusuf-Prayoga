<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($buku['judul']) ?> | LITERIA</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Lora:wght@500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        serif: ['Lora', 'serif'],
                    },
                    colors: {
                        ivory: { 100: 'rgb(var(--color-ivory-100) / <alpha-value>)', 200: 'rgb(var(--color-ivory-200) / <alpha-value>)', 300: 'rgb(var(--color-ivory-300) / <alpha-value>)' },
                        navy: { 900: 'rgb(var(--color-navy-900) / <alpha-value>)', 950: 'rgb(var(--color-navy-950) / <alpha-value>)' },
                        gold: { 500: '#ac554c', 600: '#c5a556' }
                    }
                }
            }
        }
    </script>
    <style>
        :root {
            --color-ivory-100: 255 254 250; /* #fffefa */
            --color-ivory-200: 247 241 227; /* #f7f1e3 */
            --color-ivory-300: 238 230 210; /* #eee6d2 */
            --color-navy-900: 0 41 37;      /* #002925 */
            --color-navy-950: 7 28 47;      /* #071c2f */
            --bg-body: #f7f1e3;
            --bg-white: #ffffff;
        }

        .dark {
            --color-ivory-100: 30 41 59;    /* slate-800 */
            --color-ivory-200: 15 23 42;    /* slate-900 */
            --color-ivory-300: 15 23 42;    /* slate-900 */
            --color-navy-900: 248 250 252;  /* slate-50 */
            --color-navy-950: 241 245 249;  /* slate-100 */
            --bg-body: #0f172a;
            --bg-white: #0f172a;
        }

        body { 
            background: var(--bg-body); 
            color: rgb(var(--color-navy-900)); 
            font-family: Inter, sans-serif; 
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .dark .bg-white { background-color: var(--bg-white); }
        .dark .border-navy-900\/10 { border-color: rgba(255,255,255,0.1); }
        .dark .border-navy-900\/15 { border-color: rgba(255,255,255,0.15); }
        .literia-mark { position: relative; width: 30px; height: 30px; color: currentColor; }
        .literia-mark span { position: absolute; display: block; background: currentColor; }
        .literia-mark .base { left: 3px; bottom: 4px; width: 22px; height: 5px; }
        .literia-mark .spine { left: 3px; bottom: 4px; width: 5px; height: 22px; }
        .literia-mark .gold-a { left: 10px; bottom: 11px; width: 18px; height: 4px; background: #c5a556; }
        .literia-mark .gold-b { left: 10px; bottom: 11px; width: 4px; height: 18px; background: #c5a556; }
    </style>
</head>
<body class="antialiased">
    <header class="border-b border-navy-900/10 bg-ivory-100">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 md:px-8">
            <a href="<?= base_url() ?>" class="inline-flex items-center gap-3 text-navy-900">
                <div class="literia-mark" aria-hidden="true">
                    <span class="base"></span>
                    <span class="spine"></span>
                    <span class="gold-a"></span>
                    <span class="gold-b"></span>
                </div>
                <span class="font-bold tracking-[0.14em]">LITERIA</span>
            </a>
            <div class="flex items-center gap-3">
                <button id="themeToggleDetail" class="grid h-10 w-10 place-items-center rounded-full border border-navy-900/15 bg-white text-navy-900 transition hover:border-gold-600 dark:border-white/15 dark:bg-slate-800 dark:text-white" aria-label="Toggle Dark Mode">
                    <i class="ph-bold ph-moon-stars text-lg dark:hidden"></i>
                    <i class="ph-bold ph-sun text-lg hidden dark:block"></i>
                </button>
                <nav class="flex items-center gap-2 text-sm">
                    <a href="<?= base_url('#katalog') ?>" class="rounded-full border border-navy-900/10 bg-white px-4 py-2 font-semibold text-navy-900 transition hover:border-gold-600 dark:border-white/15 dark:bg-slate-800 dark:text-white">Katalog</a>
                    <a href="<?= base_url('user/dashboard') ?>" class="rounded-full bg-navy-900 px-4 py-2 font-semibold text-ivory-100 transition hover:bg-[#103c37] dark:bg-gold-600 dark:text-navy-900 dark:hover:bg-gold-500">Dashboard</a>
                </nav>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-10 md:px-8 md:py-14">
        <?php if(session()->getFlashdata('success')): ?>
            <div class="mb-6 rounded-2xl border border-emerald-700/15 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php elseif(session()->getFlashdata('error')): ?>
            <div class="mb-6 rounded-2xl border border-rose-700/15 bg-rose-50 px-5 py-4 text-sm font-medium text-rose-800">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <section class="grid gap-8 lg:grid-cols-[28rem_1fr]">
            <aside class="lg:sticky lg:top-8 lg:self-start" style="perspective: 1200px;">
                <!-- 3D Book Container -->
                <div class="book-3d mx-auto max-w-[26rem]" style="transform-style: preserve-3d;">
                    <div class="relative rounded-r-xl rounded-l-sm shadow-[0_25px_60px_rgba(0,41,37,0.25)]" style="transform-style: preserve-3d;">
                        
                        <!-- Spine (left edge) -->
                        <div class="absolute inset-y-0 left-0 z-20 w-4 rounded-l-sm bg-gradient-to-r from-[#3a2a1a] via-[#5a4030] to-[#3a2a1a] shadow-[inset_-2px_0_6px_rgba(0,0,0,0.4),_inset_2px_0_4px_rgba(255,255,255,0.08)]"></div>
                        
                        <!-- Page edges (right side) -->
                        <div class="absolute inset-y-1 -right-1 z-0 w-2 rounded-r-sm" style="background: repeating-linear-gradient(to bottom, #f5f0e6 0px, #e8e0d0 1px, #f5f0e6 2px); box-shadow: 2px 0 4px rgba(0,0,0,0.1);"></div>
                        
                        <!-- Bottom page edges -->
                        <div class="absolute -bottom-1 inset-x-2 z-0 h-2 rounded-b-sm" style="background: repeating-linear-gradient(to right, #f5f0e6 0px, #e8e0d0 1px, #f5f0e6 2px); box-shadow: 0 2px 4px rgba(0,0,0,0.1);"></div>

                        <!-- Cover image -->
                        <div class="relative z-10 overflow-hidden rounded-r-xl rounded-l-sm border border-navy-900/10 bg-ivory-200">
                            <?php if(!empty($buku['cover_url'])): ?>
                                <img src="<?= esc($buku['cover_url']) ?>" alt="Sampul <?= esc($buku['judul']) ?>" class="aspect-[2/3] w-full object-cover">
                            <?php else: ?>
                                <div class="aspect-[2/3] flex items-center justify-center bg-navy-900 text-ivory-100">
                                    <i class="ph-light ph-book-open text-6xl"></i>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Paper texture overlay -->
                            <div class="pointer-events-none absolute inset-0 z-20 opacity-[0.04]" style="background-image: url('data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%224%22 height=%224%22%3E%3Crect width=%224%22 height=%224%22 fill=%22%23000%22 fill-opacity=%22.03%22/%3E%3Crect x=%221%22 y=%221%22 width=%222%22 height=%222%22 fill=%22%23fff%22 fill-opacity=%22.02%22/%3E%3C/svg%3E');"></div>
                            
                            <!-- Spine inner shadow -->
                            <div class="pointer-events-none absolute inset-y-0 left-0 z-20 w-8 bg-gradient-to-r from-black/20 via-black/5 to-transparent"></div>
                            
                            <!-- Light reflection -->
                            <div class="pointer-events-none absolute inset-0 z-20 bg-gradient-to-br from-white/10 via-transparent to-transparent"></div>
                        </div>
                    </div>
                </div>

                <style>
                    .book-3d {
                        animation: bookEntrance 0.9s cubic-bezier(0.23, 1, 0.32, 1) both;
                    }
                    @keyframes bookEntrance {
                        from {
                            opacity: 0;
                            transform: rotateY(-12deg) translateX(-30px) scale(0.95);
                        }
                        to {
                            opacity: 1;
                            transform: rotateY(0deg) translateX(0) scale(1);
                        }
                    }
                    .book-3d:hover > div {
                        transform: rotateY(-6deg) rotateX(2deg) translateZ(10px);
                        box-shadow: 18px 18px 40px rgba(0,41,37,0.2), 4px 4px 15px rgba(0,41,37,0.1);
                        transition: transform 0.7s cubic-bezier(0.23, 1, 0.32, 1), box-shadow 0.7s cubic-bezier(0.23, 1, 0.32, 1);
                    }
                    .book-3d > div {
                        transition: transform 0.7s cubic-bezier(0.23, 1, 0.32, 1), box-shadow 0.7s cubic-bezier(0.23, 1, 0.32, 1);
                    }
                </style>
            </aside>

            <section class="rounded-[1.75rem] border border-navy-900/10 bg-ivory-100 p-6 shadow-[0_18px_50px_rgba(0,41,37,0.08)] md:p-8">
                <div class="mb-6 flex flex-wrap items-center gap-2">
                    <span class="rounded-full border border-navy-900/10 bg-white px-4 py-2 text-xs font-semibold text-navy-900/70"><?= esc($buku['kategori']) ?></span>
                    <?php if((int) $buku['stok'] > 0): ?>
                        <span class="rounded-full border border-emerald-700/15 bg-emerald-50 px-4 py-2 text-xs font-semibold text-emerald-800"><?= esc($buku['stok']) ?> tersedia</span>
                    <?php else: ?>
                        <span class="rounded-full border border-rose-700/15 bg-rose-50 px-4 py-2 text-xs font-semibold text-rose-800">Sedang dipinjam</span>
                    <?php endif; ?>
                    <?php if(!empty($shelfItem)): ?>
                        <span class="rounded-full border border-gold-600/30 bg-gold-600/15 px-4 py-2 text-xs font-semibold text-navy-900">Ada di rak</span>
                    <?php endif; ?>
                    <?php if(($buku['read_access'] ?? 'metadata') === 'public_domain'): ?>
                        <span class="rounded-full border border-gold-600/30 bg-gold-600/15 px-4 py-2 text-xs font-semibold text-navy-900">Public domain</span>
                    <?php else: ?>
                        <span class="rounded-full border border-navy-900/10 bg-white px-4 py-2 text-xs font-semibold text-navy-900/55">Metadata only</span>
                    <?php endif; ?>
                </div>

                <h1 class="max-w-4xl font-serif text-4xl font-semibold leading-tight text-navy-900 md:text-6xl"><?= esc($buku['judul']) ?></h1>
                <p class="mt-4 text-lg text-navy-900/65">Oleh <span class="font-semibold text-navy-900"><?= esc($buku['pengarang']) ?></span></p>

                <dl class="mt-8 grid gap-3 border-y border-navy-900/10 py-6 sm:grid-cols-2 xl:grid-cols-4">
                    <div class="rounded-2xl bg-white p-4">
                        <dt class="text-xs font-semibold uppercase tracking-[0.12em] text-navy-900/45">Penerbit</dt>
                        <dd class="mt-2 font-semibold text-navy-900"><?= esc($buku['penerbit']) ?></dd>
                    </div>
                    <div class="rounded-2xl bg-white p-4">
                        <dt class="text-xs font-semibold uppercase tracking-[0.12em] text-navy-900/45">Tahun</dt>
                        <dd class="mt-2 font-semibold text-navy-900"><?= esc($buku['tahun_terbit']) ?></dd>
                    </div>
                    <div class="rounded-2xl bg-white p-4">
                        <dt class="text-xs font-semibold uppercase tracking-[0.12em] text-navy-900/45">Halaman</dt>
                        <dd class="mt-2 font-semibold text-navy-900"><?= esc($buku['jumlah_halaman']) ?></dd>
                    </div>
                    <div class="rounded-2xl bg-white p-4">
                        <dt class="text-xs font-semibold uppercase tracking-[0.12em] text-navy-900/45">Rating</dt>
                        <dd class="mt-2 flex items-center gap-1 font-semibold text-navy-900"><i class="ph-fill ph-star text-gold-600"></i><?= esc($buku['rating']) ?></dd>
                    </div>
                </dl>

                <div class="mt-8 grid gap-8 xl:grid-cols-[1fr_18rem]">
                    <div>
                        <h2 class="font-serif text-2xl text-navy-900">Sinopsis</h2>
                        <p class="mt-4 max-w-3xl text-base leading-8 text-navy-900/70">
                            <?= !empty($buku['sinopsis']) ? nl2br(esc($buku['sinopsis'])) : 'Sinopsis belum tersedia untuk buku ini.' ?>
                        </p>
                    </div>
                    <aside class="rounded-2xl border border-navy-900/10 bg-white p-5">
                        <h3 class="font-serif text-xl text-navy-900">Data katalog</h3>
                        <dl class="mt-4 space-y-3 text-sm">
                            <div>
                                <dt class="text-navy-900/45">ISBN</dt>
                                <dd class="font-semibold text-navy-900"><?= esc($buku['isbn'] ?? '-') ?></dd>
                            </div>
                            <div>
                                <dt class="text-navy-900/45">Nomor koleksi</dt>
                                <dd class="font-semibold text-navy-900">LIT-<?= str_pad((string) $buku['id_buku'], 4, '0', STR_PAD_LEFT) ?></dd>
                            </div>
                            <div>
                                <dt class="text-navy-900/45">Akses baca</dt>
                                <dd class="font-semibold text-navy-900"><?= ($buku['read_access'] ?? 'metadata') === 'public_domain' ? 'Teks legal tersedia' : 'Katalog & catatan' ?></dd>
                            </div>
                            <?php if(!empty($buku['source_url'])): ?>
                                <div>
                                    <dt class="text-navy-900/45">Sumber legal</dt>
                                    <dd><a href="<?= esc($buku['source_url']) ?>" class="font-semibold text-navy-900 underline decoration-gold-600 underline-offset-4" target="_blank" rel="noopener"><?= esc($buku['source_name'] ?: 'Sumber') ?></a></dd>
                                </div>
                            <?php endif; ?>
                        </dl>
                    </aside>
                </div>

                <div class="mt-9 flex flex-col gap-3 sm:flex-row">
                    <form action="<?= base_url('web/shelf/add') ?>" method="post" class="contents">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id_buku" value="<?= esc($buku['id_buku']) ?>">
                        <input type="hidden" name="status_baca" value="reading">
                        <input type="hidden" name="redirect_to" value="reader">
                        <button type="submit" class="inline-flex items-center justify-center gap-3 rounded-full bg-navy-900 px-6 py-3 text-sm font-bold text-ivory-100 transition hover:bg-[#103c37] active:scale-[0.98]">
                            <?= ($buku['read_access'] ?? 'metadata') === 'public_domain' ? 'Baca teks legal' : 'Buka catatan baca' ?>
                            <span class="grid h-8 w-8 place-items-center rounded-full bg-white/10"><i class="ph-bold ph-book-open"></i></span>
                        </button>
                    </form>
                    <form action="<?= base_url('web/shelf/add') ?>" method="post" class="contents">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id_buku" value="<?= esc($buku['id_buku']) ?>">
                        <input type="hidden" name="status_baca" value="wishlist">
                        <button type="submit" class="inline-flex items-center justify-center gap-3 rounded-full border border-navy-900/15 bg-white px-6 py-3 text-sm font-bold text-navy-900 transition hover:border-gold-600 active:scale-[0.98]">
                            Simpan ke wishlist
                            <span class="grid h-8 w-8 place-items-center rounded-full bg-navy-900/5"><i class="ph-bold ph-bookmark-simple"></i></span>
                        </button>
                    </form>
                </div>
            </section>
        </section>

        <!-- Reviews Section -->
        <section class="mt-8 rounded-[1.75rem] border border-navy-900/10 bg-ivory-100 p-6 shadow-[0_18px_50px_rgba(0,41,37,0.08)] md:p-8">
            <h2 class="font-serif text-3xl text-navy-900 mb-6">Ulasan Pembaca</h2>

            <?php if(session()->get('logged_in') && session()->get('role') == 'anggota'): ?>
                <div class="mb-8 rounded-2xl bg-white p-5 border border-navy-900/10">
                    <h3 class="font-serif text-xl text-navy-900 mb-4">Berikan Ulasan Anda</h3>
                    <form action="<?= base_url('web/submit-review') ?>" method="post" class="space-y-4">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id_buku" value="<?= esc($buku['id_buku']) ?>">
                        
                        <div>
                            <label class="block text-sm font-semibold text-navy-900 mb-2">Rating (1-5)</label>
                            <select name="rating" class="w-full md:w-32 rounded-xl border border-navy-900/10 bg-ivory-100 px-4 py-3 text-navy-900 outline-none focus:border-gold-600 focus:ring-4 focus:ring-gold-600/15" required>
                                <option value="5">5 - Luar Biasa</option>
                                <option value="4">4 - Sangat Bagus</option>
                                <option value="3">3 - Bagus</option>
                                <option value="2">2 - Cukup</option>
                                <option value="1">1 - Kurang</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-navy-900 mb-2">Komentar</label>
                            <textarea name="komentar" rows="3" class="w-full rounded-xl border border-navy-900/10 bg-ivory-100 px-4 py-3 text-navy-900 outline-none focus:border-gold-600 focus:ring-4 focus:ring-gold-600/15" placeholder="Bagaimana pendapat Anda tentang buku ini?"></textarea>
                        </div>
                        <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-full bg-navy-900 px-6 py-3 text-sm font-bold text-ivory-100 transition hover:bg-[#103c37]">
                            Kirim Ulasan <i class="ph-bold ph-paper-plane-tilt"></i>
                        </button>
                    </form>
                </div>
            <?php endif; ?>

            <div class="space-y-5">
                <?php if (empty($reviews)): ?>
                    <p class="text-navy-900/60 text-sm">Belum ada ulasan untuk buku ini.</p>
                <?php else: ?>
                    <?php foreach ($reviews as $review): ?>
                        <div class="rounded-2xl bg-white p-5 border border-navy-900/10">
                            <div class="flex items-start justify-between mb-2">
                                <div>
                                    <p class="font-semibold text-navy-900"><?= esc($review['nama_anggota']) ?></p>
                                    <div class="flex text-gold-600 text-sm mt-1">
                                        <?php for($i=0; $i<$review['rating']; $i++) echo '<i class="ph-fill ph-star"></i>'; ?>
                                    </div>
                                </div>
                                <span class="text-xs text-navy-900/40"><?= date('d M Y', strtotime($review['created_at'])) ?></span>
                            </div>
                            <?php if (!empty($review['komentar'])): ?>
                                <p class="text-sm text-navy-900/70 mt-3"><?= nl2br(esc($review['komentar'])) ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <script>
        const themeToggleBtn = document.getElementById('themeToggleDetail');
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
        themeToggleBtn.addEventListener('click', function() {
            document.documentElement.classList.toggle('dark');
            if (document.documentElement.classList.contains('dark')) {
                localStorage.setItem('theme', 'dark');
            } else {
                localStorage.setItem('theme', 'light');
            }
        });
    </script>
</body>
</html>
