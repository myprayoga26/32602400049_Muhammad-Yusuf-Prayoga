<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pembaca | LITERIA</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Lora:wght@500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <!-- PWA -->
    <link rel="manifest" href="<?= base_url('manifest.json') ?>">
    <meta name="theme-color" content="#fffefa">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'], serif: ['Lora', 'serif'] },
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
        .book-spine { box-shadow: inset 10px 0 18px rgba(0,41,37,.16), 0 18px 36px rgba(0,41,37,.12); }
    </style>
</head>
<body class="antialiased">
    <?php
        $userName = session()->get('nama') ?? 'Pembaca';
        $tier = session()->get('tier') ?? 'free';
        $activeBook = $reading[0] ?? null;
        $avgProgress = count($reading) ? round(array_sum(array_column($reading, 'progress_persen')) / count($reading)) : 0;
        $totalFinished = count($finished);
        $goalProgress = $reading_goal > 0 ? min(100, round(($totalFinished / $reading_goal) * 100)) : 0;
    ?>

    <header class="sticky top-0 z-40 border-b border-navy-900/10 bg-ivory-100/95 backdrop-blur">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 md:px-8">
            <a href="<?= base_url() ?>" class="font-bold tracking-[0.14em] text-navy-900">LITERIA</a>
            <nav class="hidden items-center gap-2 text-sm md:flex">
                <a href="<?= base_url() ?>" class="rounded-full px-4 py-2 font-semibold text-navy-900/70 hover:text-navy-900">Katalog</a>
                <a href="#rak" class="rounded-full bg-navy-900 px-4 py-2 font-semibold text-ivory-100">Rak baca</a>
                <a href="#rekomendasi" class="rounded-full px-4 py-2 font-semibold text-navy-900/70 hover:text-navy-900">Rekomendasi</a>
            </nav>
            <div class="flex items-center gap-3">
                <button id="themeToggleDash" class="grid h-10 w-10 place-items-center rounded-full border border-navy-900/15 bg-white text-navy-900 transition hover:border-gold-600 dark:border-white/15 dark:bg-slate-800 dark:text-white" aria-label="Toggle Dark Mode">
                    <i class="ph-bold ph-moon-stars text-lg dark:hidden"></i>
                    <i class="ph-bold ph-sun text-lg hidden dark:block"></i>
                </button>
                <a href="<?= base_url('auth/logout') ?>" class="rounded-full border border-navy-900/15 bg-white px-4 py-2 text-sm font-semibold text-navy-900 transition hover:border-gold-600 dark:border-white/15 dark:bg-slate-800 dark:text-white">Keluar</a>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-10 md:px-8">
        <?php if(session()->getFlashdata('success')): ?>
            <div class="mb-6 rounded-2xl border border-emerald-700/15 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <section class="grid gap-6 lg:grid-cols-[1.25fr_0.75fr]">
            <div class="rounded-[1.75rem] border border-navy-900/10 bg-ivory-100 p-6 shadow-[0_18px_50px_rgba(0,41,37,0.08)] md:p-8">
                <p class="mb-3 text-xs font-semibold uppercase tracking-[0.16em] text-gold-500">Dashboard pembaca</p>
                <h1 class="font-serif text-4xl font-semibold leading-tight text-navy-900 md:text-6xl">Halo, <?= esc($userName) ?>.</h1>
                <p class="mt-4 max-w-2xl text-base leading-7 text-navy-900/65">Rak pribadi Anda menyimpan buku yang ingin dibaca, sedang dibaca, dan sudah selesai. Dari sini Anda bisa masuk langsung ke ruang baca 3D.</p>

                <div class="mt-8 grid gap-3 sm:grid-cols-4">
                    <div class="rounded-2xl bg-white p-4">
                        <p class="text-3xl font-semibold text-navy-900"><?= count($reading) ?></p>
                        <p class="mt-1 text-xs text-navy-900/50">Sedang dibaca</p>
                    </div>
                    <div class="rounded-2xl bg-white p-4">
                        <p class="text-3xl font-semibold text-navy-900"><?= count($wishlist) ?></p>
                        <p class="mt-1 text-xs text-navy-900/50">Wishlist</p>
                    </div>
                    <div class="rounded-2xl bg-white p-4">
                        <p class="text-3xl font-semibold text-navy-900"><?= count($finished) ?></p>
                        <p class="mt-1 text-xs text-navy-900/50">Selesai</p>
                    </div>
                    <div class="rounded-2xl bg-white p-4">
                        <p class="text-3xl font-semibold text-navy-900"><?= esc($avgProgress) ?>%</p>
                        <p class="mt-1 text-xs text-navy-900/50">Rata progress</p>
                    </div>
                </div>

                <!-- Reading Goal Section -->
                <div class="mt-6 rounded-2xl bg-white p-5 border border-navy-900/10">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="font-serif text-xl text-navy-900">Target Tahunan</p>
                            <?php if ($reading_goal > 0): ?>
                                <p class="text-sm text-navy-900/60"><?= $totalFinished ?> dari <?= $reading_goal ?> buku tercapai</p>
                            <?php else: ?>
                                <p class="text-sm text-navy-900/60">Belum ada target.</p>
                            <?php endif; ?>
                        </div>
                        <form action="<?= base_url('user/update-goal') ?>" method="post" class="flex gap-2">
                            <?= csrf_field() ?>
                            <input type="number" name="reading_goal" value="<?= $reading_goal ?>" min="0" max="365" class="w-20 rounded-full border border-navy-900/10 bg-ivory-100 px-3 py-2 text-sm text-navy-900 outline-none focus:border-gold-600 focus:ring-2 focus:ring-gold-600/15" placeholder="0">
                            <button type="submit" class="rounded-full bg-navy-900 px-4 py-2 text-xs font-bold text-ivory-100 transition hover:bg-[#103c37]">Set Target</button>
                        </form>
                    </div>
                    <?php if ($reading_goal > 0): ?>
                        <div class="mt-4 h-3 w-full overflow-hidden rounded-full bg-ivory-100">
                            <div class="h-full rounded-full bg-gold-600 transition-all duration-500" style="width: <?= $goalProgress ?>%"></div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- ══ Lencana Pencapaian (Badges) ══ -->
                <div class="mt-6 rounded-[1.75rem] border border-navy-900/10 bg-white p-6 shadow-sm">
                    <div class="mb-4 flex items-center gap-3">
                        <span class="text-2xl">🏅</span>
                        <div>
                            <h2 class="font-serif text-xl font-semibold text-navy-900">Lencana Pencapaian</h2>
                            <p class="text-xs text-navy-900/50">Raih pencapaian dengan membaca lebih banyak!</p>
                        </div>
                    </div>
                    <?php
                        $allBadges = [
                            'first_steps' => ['img' => 'first_steps.png', 'label' => 'First Steps', 'desc' => 'Menambahkan buku pertama ke rak'],
                            'bookworm'    => ['img' => 'bookworm.png', 'label' => 'Bookworm', 'desc' => 'Menyelesaikan 3 buku'],
                            'marathon'    => ['img' => 'marathon.png', 'label' => 'Marathon Reader', 'desc' => 'Streak membaca 3 hari berturut-turut'],
                            'night_owl'   => ['img' => 'night_owl.png', 'label' => 'Night Owl', 'desc' => 'Membaca di jam 22.00 - 04.00'],
                            'scholar'     => ['img' => 'scholar.png', 'label' => 'Scholar', 'desc' => 'Membaca total 7 hari'],
                        ];
                        $earnedNames = array_column($badges ?? [], 'badge_name');
                    ?>
                    <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-5">
                        <?php foreach ($allBadges as $key => $b): ?>
                            <?php $earned = in_array($key, $earnedNames); ?>
                            <div class="flex flex-col items-center gap-2 rounded-2xl border p-4 text-center transition-all duration-300 <?= $earned ? 'border-gold-600/30 bg-gradient-to-b from-amber-50 to-white shadow-md scale-[1.02]' : 'border-navy-900/5 bg-ivory-100/50 opacity-40 grayscale' ?>" title="<?= esc($b['desc']) ?>">
                                <img src="<?= base_url('img/badges/' . $b['img']) ?>" alt="<?= esc($b['label']) ?>" class="h-16 w-16 object-contain mix-blend-multiply <?= $earned ? 'animate-bounce' : '' ?>" style="animation-duration:2s">
                                <p class="text-xs font-bold text-navy-900"><?= esc($b['label']) ?></p>
                                <p class="text-[10px] leading-tight text-navy-900/50"><?= esc($b['desc']) ?></p>
                                <?php if ($earned): ?>
                                    <span class="mt-1 inline-flex items-center gap-1 rounded-full bg-emerald-100 px-2 py-0.5 text-[10px] font-bold text-emerald-700">
                                        <i class="ph-bold ph-check-circle"></i> Diraih!
                                    </span>
                                <?php else: ?>
                                    <span class="mt-1 text-[10px] text-navy-900/30">Terkunci</span>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- ══ Gamification Section ══ -->
                <div class="mt-6 rounded-[1.75rem] border border-navy-900/10 bg-white p-6 shadow-sm">
                    <div class="mb-5 flex flex-wrap items-center justify-between gap-4">
                        <div>
                            <h2 class="font-serif text-2xl font-semibold text-navy-900">Aktivitas Membaca</h2>
                            <p class="text-sm text-navy-900/60">Jaga streak membaca Anda!</p>
                        </div>
                        <div class="flex gap-4">
                            <div class="text-center">
                                <p class="text-3xl font-bold text-gold-600"><?= $streakData['current'] ?></p>
                                <p class="text-[10px] font-semibold uppercase tracking-wider text-navy-900/50">Streak Hari</p>
                            </div>
                            <div class="text-center">
                                <p class="text-3xl font-bold text-navy-900"><?= $streakData['longest'] ?></p>
                                <p class="text-[10px] font-semibold uppercase tracking-wider text-navy-900/50">Rekor</p>
                            </div>
                        </div>
                    </div>

                    <?php if (!empty($streakData['badges'])): ?>
                        <div class="mb-6 flex flex-wrap gap-2">
                            <?php foreach ($streakData['badges'] as $badge): ?>
                                <div class="flex items-center gap-2 rounded-full border border-gold-600/20 bg-gold-50 px-3 py-1.5" title="<?= esc($badge['desc']) ?>">
                                    <span class="text-lg"><?= $badge['icon'] ?></span>
                                    <span class="text-xs font-semibold text-gold-700"><?= esc($badge['label']) ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Minimalist Heatmap -->
                    <div class="flex flex-col gap-1 overflow-x-auto pb-2">
                        <div class="flex justify-between text-[10px] text-navy-900/40 uppercase font-semibold">
                            <span>Satu bulan terakhir</span>
                            <span>Lebih banyak</span>
                        </div>
                        <div class="flex gap-1" style="min-width: max-content;">
                            <?php 
                                // Generate last 30 days
                                for ($i = 29; $i >= 0; $i--) {
                                    $dateStr = date('Y-m-d', strtotime("-$i days"));
                                    $pages = $heatmapData[$dateStr] ?? 0;
                                    
                                    // Calculate intensity (0-4)
                                    $intensity = 0;
                                    if ($pages > 0) $intensity = 1;
                                    if ($pages >= 10) $intensity = 2;
                                    if ($pages >= 30) $intensity = 3;
                                    if ($pages >= 50) $intensity = 4;
                                    
                                    $colors = [
                                        0 => 'bg-navy-900/5',
                                        1 => 'bg-gold-600/30',
                                        2 => 'bg-gold-600/60',
                                        3 => 'bg-gold-600/80',
                                        4 => 'bg-gold-600',
                                    ];
                                    $colorClass = $colors[$intensity];
                                    
                                    echo "<div class='h-3 w-3 rounded-sm {$colorClass}' title='{$pages} halaman pada {$dateStr}'></div>";
                                }
                            ?>
                        </div>
                    </div>
                </div>

            </div>

            <aside class="flex flex-col gap-5">
                <!-- Visual Member Card matching blueprint -->
                <div class="relative w-full aspect-[1.586/1] rounded-[1.25rem] bg-[#002925] overflow-hidden shadow-[0_20px_40px_rgba(0,41,37,0.25)] text-[#fffefa]">
                    <!-- Large Gold Graphic on the right -->
                    <svg class="absolute h-[130%] w-auto top-1/2 -right-8 -translate-y-1/2 text-[#c5a556]" viewBox="0 0 100 100" fill="currentColor">
                        <!-- Layer 1 (Outer) -->
                        <path d="M25 0 h12 v88 h63 v12 h-75 z"/>
                        <!-- Layer 2 (Middle) -->
                        <path d="M47 25 h12 v63 h41 v12 h-53 z" opacity="0.6"/>
                        <!-- Layer 3 (Inner) -->
                        <path d="M69 50 h12 v38 h19 v12 h-31 z" opacity="0.3"/>
                    </svg>

                    <div class="relative h-full flex flex-col justify-between p-6 z-10">
                        <div class="flex items-center gap-2">
                            <!-- Small Logo -->
                            <svg class="h-6 w-6 text-[#c5a556]" viewBox="0 0 100 100" fill="currentColor">
                                <path d="M25 0 h12 v88 h63 v12 h-75 z"/>
                                <path d="M47 25 h12 v63 h41 v12 h-53 z" opacity="0.6"/>
                                <path d="M69 50 h12 v38 h19 v12 h-31 z" opacity="0.3"/>
                            </svg>
                            <span class="font-sans font-semibold tracking-[0.14em] text-sm">LITERIA</span>
                        </div>
                        <div>
                            <p class="font-sans font-medium text-lg tracking-wide"><?= esc($userName) ?></p>
                            <p class="text-[11px] text-[#fffefa]/60 mt-1 uppercase tracking-wider">Library Card &bull; <?= strtoupper($tier) ?></p>
                        </div>
                    </div>
                </div>

                <div class="rounded-[1.25rem] border border-navy-900/10 dark:border-white/10 bg-ivory-100 dark:bg-white/5 p-5">
                    <p class="text-xs font-semibold uppercase tracking-wider text-navy-900/50 dark:text-ivory-100/50">Kategori favorit</p>
                    <p class="mt-2 font-serif text-2xl text-navy-900 dark:text-ivory-100"><?= esc($favoriteCategory ?? 'Belum terbaca') ?></p>
                </div>

                <!-- ══ Bookmarks ══ -->
                <div class="rounded-[1.25rem] border border-navy-900/10 bg-white p-5 shadow-sm">
                    <p class="mb-4 text-xs font-semibold uppercase tracking-wider text-navy-900/50">Bookmark Tersimpan</p>
                    <?php if (empty($bookmarks)): ?>
                        <p class="text-sm text-navy-900/60">Belum ada bookmark.</p>
                    <?php else: ?>
                        <div class="flex flex-col gap-4 max-h-[300px] overflow-y-auto pr-2">
                            <?php foreach ($bookmarks as $bm): ?>
                                <div class="flex gap-3">
                                    <img src="<?= esc($bm['cover_url']) ?>" class="h-16 w-12 rounded object-cover shadow" alt="Cover">
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-navy-900 truncate" title="<?= esc($bm['judul']) ?>"><?= esc($bm['judul']) ?></p>
                                        <p class="text-xs font-medium text-gold-600">Hal. <?= $bm['halaman'] ?></p>
                                        <?php if (!empty($bm['catatan'])): ?>
                                            <p class="mt-1 text-xs text-navy-900/70 line-clamp-2"><?= esc($bm['catatan']) ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

            </aside>
        </section>

        <section id="rak" class="mt-10 grid gap-6 lg:grid-cols-[1fr_21rem]">
            <div>
                <div class="mb-5 flex items-end justify-between gap-4">
                    <div>
                        <h2 class="font-serif text-3xl text-navy-900">Rak baca aktif</h2>
                        <p class="mt-2 text-sm text-navy-900/60">Buku yang sedang Anda baca.</p>
                    </div>
                    <a href="<?= base_url('#katalog') ?>" class="hidden text-sm font-bold text-navy-900 underline decoration-gold-600 underline-offset-4 md:block">Tambah buku</a>
                </div>

                <?php if(empty($reading)): ?>
                    <div class="rounded-[1.5rem] border border-navy-900/10 bg-ivory-100 p-10 text-center">
                        <i class="ph-light ph-book-open mb-4 block text-6xl text-navy-900/20"></i>
                        <h3 class="font-serif text-2xl">Belum ada buku aktif.</h3>
                        <p class="mt-2 text-sm text-navy-900/60">Pilih buku dari katalog dan tekan Mulai membaca.</p>
                    </div>
                <?php else: ?>
                    <div class="grid gap-5 md:grid-cols-2">
                        <?php foreach($reading as $item): ?>
                            <article class="rounded-[1.5rem] border border-navy-900/10 bg-ivory-100 p-4 shadow-[0_14px_42px_rgba(0,41,37,0.08)]">
                                <div class="grid grid-cols-[7rem_1fr] gap-4">
                                    <img src="<?= esc($item['cover_url']) ?>" alt="Sampul <?= esc($item['judul']) ?>" class="book-spine aspect-[2/3] rounded-xl object-cover">
                                    <div class="min-w-0">
                                        <span class="rounded-full bg-white px-3 py-1 text-[11px] font-semibold text-navy-900/60"><?= esc($item['kategori']) ?></span>
                                        <h3 class="mt-3 line-clamp-2 font-serif text-2xl leading-snug"><?= esc($item['judul']) ?></h3>
                                        <p class="mt-1 truncate text-sm text-navy-900/60"><?= esc($item['pengarang']) ?></p>
                                        <div class="mt-5 h-2 overflow-hidden rounded-full bg-white">
                                            <div class="h-full rounded-full bg-gold-600" style="width: <?= esc($item['progress_persen']) ?>%"></div>
                                        </div>
                                        <div class="mt-2 text-xs text-navy-900/55"><?= esc($item['progress_persen']) ?>% selesai</div>
                                        <a href="<?= base_url('web/read/'.$item['id_buku']) ?>" class="mt-5 inline-flex items-center gap-2 rounded-full bg-navy-900 px-5 py-3 text-sm font-bold text-ivory-100 transition hover:bg-[#103c37]">
                                            Lanjut membaca <i class="ph-bold ph-book-open"></i>
                                        </a>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <aside class="space-y-5">
                <div class="rounded-[1.5rem] border border-navy-900/10 bg-ivory-100 p-5 shadow-[0_14px_42px_rgba(0,41,37,0.08)]">
                    <h3 class="font-serif text-2xl">Wishlist</h3>
                    <div class="mt-4 space-y-4">
                        <?php foreach(array_slice($wishlist, 0, 3) as $item): ?>
                            <a href="<?= base_url('web/buku/'.$item['id_buku']) ?>" class="flex gap-3 rounded-2xl bg-white p-3 transition hover:-translate-y-0.5">
                                <img src="<?= esc($item['cover_url']) ?>" alt="Sampul <?= esc($item['judul']) ?>" class="h-20 w-14 rounded-lg object-cover">
                                <div class="min-w-0">
                                    <p class="line-clamp-2 font-semibold leading-snug"><?= esc($item['judul']) ?></p>
                                    <p class="mt-1 truncate text-xs text-navy-900/55"><?= esc($item['pengarang']) ?></p>
                                </div>
                            </a>
                        <?php endforeach; ?>
                        <?php if(empty($wishlist)): ?>
                            <p class="text-sm text-navy-900/55">Wishlist masih kosong.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="rounded-[1.5rem] border border-navy-900/10 bg-ivory-100 p-5 shadow-[0_14px_42px_rgba(0,41,37,0.08)]">
                    <h3 class="font-serif text-2xl">Selesai</h3>
                    <div class="mt-4 space-y-3 text-sm text-navy-900/65">
                        <?php foreach(array_slice($finished, 0, 4) as $item): ?>
                            <a href="<?= base_url('web/buku/'.$item['id_buku']) ?>" class="flex items-center justify-between gap-3 rounded-xl bg-white px-3 py-2">
                                <span class="truncate"><?= esc($item['judul']) ?></span>
                                <i class="ph-fill ph-check-circle text-gold-600"></i>
                            </a>
                        <?php endforeach; ?>
                        <?php if(empty($finished)): ?>
                            <p>Belum ada buku selesai.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </aside>
        </section>

        <section id="rekomendasi" class="mt-16">
            <h2 class="font-serif text-3xl text-navy-900">Eksplorasi Katalog</h2>
            <p class="mt-2 text-sm text-navy-900/60"><?= $recommendationMessage ?? 'Rekomendasi terbaik kami untuk Anda.' ?></p>
            
            <div class="mt-6 grid gap-4 sm:grid-cols-2 md:grid-cols-4">
                <?php foreach($recommendations as $book): ?>
                    <a href="<?= base_url('web/buku/'.$book['id_buku']) ?>" class="rounded-[1.35rem] border border-navy-900/10 bg-ivory-100 p-3 shadow-[0_12px_32px_rgba(0,41,37,0.08)] transition hover:-translate-y-1">
                        <img src="<?= esc($book['cover_url']) ?>" alt="Sampul <?= esc($book['judul']) ?>" class="aspect-[2/3] w-full rounded-xl object-cover">
                        <h3 class="mt-4 line-clamp-2 font-serif text-xl leading-snug"><?= esc($book['judul']) ?></h3>
                        <p class="mt-1 truncate text-sm text-navy-900/55"><?= esc($book['pengarang']) ?></p>
                    </a>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

    <script>
        const themeToggleBtn = document.getElementById('themeToggleDash');
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
        
        // PWA Service Worker
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js').catch(err => console.log('SW failed', err));
            });
        }
    </script>
</body>
</html>
