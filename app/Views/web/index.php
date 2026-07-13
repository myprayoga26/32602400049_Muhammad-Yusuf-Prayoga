<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LITERIA | Premium Digital Library</title>
    <!-- PWA -->
    <link rel="manifest" href="<?= base_url('manifest.json') ?>">
    <meta name="theme-color" content="#002925">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Lora:wght@500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
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
                        ivory: {
                            100: 'rgb(var(--color-ivory-100) / <alpha-value>)',
                            200: 'rgb(var(--color-ivory-200) / <alpha-value>)',
                            300: 'rgb(var(--color-ivory-300) / <alpha-value>)',
                        },
                        navy: {
                            900: 'rgb(var(--color-navy-900) / <alpha-value>)',
                            950: 'rgb(var(--color-navy-950) / <alpha-value>)',
                        },
                        gold: {
                            500: '#ac554c',
                            600: '#c5a556',
                        }
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

        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease-out;
        }
        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }

        .literia-mark {
            position: relative;
            width: 34px;
            height: 34px;
            color: currentColor;
        }

        .literia-mark span {
            position: absolute;
            display: block;
            background: currentColor;
        }

        .literia-mark .base { left: 3px; bottom: 4px; width: 24px; height: 5px; }
        .literia-mark .spine { left: 3px; bottom: 4px; width: 5px; height: 25px; }
        .literia-mark .gold-a { left: 10px; bottom: 11px; width: 20px; height: 4px; background: #c5a556; }
        .literia-mark .gold-b { left: 10px; bottom: 11px; width: 4px; height: 20px; background: #c5a556; }

        /* ── Editor's Pick Carousel ── */
        .editors-pick-section {
            background: linear-gradient(180deg, #002925 0%, #071c2f 100%);
            padding: 4rem 0 5rem;
            overflow: hidden;
        }
        .editors-pick-section .swiper-slide {
            width: 220px;
            transition: transform .4s cubic-bezier(.22,1,.36,1), box-shadow .4s ease;
        }
        .editors-pick-section .swiper-slide-active {
            transform: scale(1.12);
        }
        .editors-pick-section .swiper-slide img {
            width: 100%;
            height: 310px;
            object-fit: cover;
            border-radius: 10px;
            box-shadow: 0 24px 48px rgba(0,0,0,.45), 0 8px 16px rgba(0,0,0,.25);
        }
        .editors-pick-section .swiper-slide-active img {
            box-shadow: 0 32px 64px rgba(197,165,86,.35), 0 12px 24px rgba(0,0,0,.4);
        }
        .pick-card-info {
            margin-top: .75rem;
            text-align: center;
            opacity: 0;
            transform: translateY(6px);
            transition: all .35s ease;
        }
        .swiper-slide-active .pick-card-info {
            opacity: 1;
            transform: translateY(0);
        }

        .swiper-pagination-bullet {
            background: rgba(255,254,250,.3) !important;
            width: 8px; height: 8px;
        }
        .swiper-pagination-bullet-active {
            background: #c5a556 !important;
            width: 24px;
            border-radius: 4px;
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                scroll-behavior: auto !important;
                transition-duration: 0.01ms !important;
                animation-duration: 0.01ms !important;
            }
        }
    </style>
</head>
<body class="antialiased">
    <?php
        $categories = $categories ?? array_values(array_unique(array_filter(array_column($buku ?? [], 'kategori'))));
        $featured = $buku[0] ?? null;
        $previewBooks = array_slice($buku ?? [], 0, 5);
        $categoryCounts = [];
        foreach (($buku ?? []) as $item) {
            $key = $item['kategori'] ?? 'Umum';
            $categoryCounts[$key] = ($categoryCounts[$key] ?? 0) + 1;
        }
    ?>

    <header class="sticky top-0 z-40 border-b border-navy-900/10 bg-ivory-100/95 backdrop-blur">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 md:px-8">
            <a href="<?= base_url() ?>" class="flex items-center gap-3 text-navy-900">
                <div class="literia-mark" aria-hidden="true">
                    <span class="base"></span>
                    <span class="spine"></span>
                    <span class="gold-a"></span>
                    <span class="gold-b"></span>
                </div>
                <span class="text-lg font-bold tracking-[0.14em]">LITERIA</span>
            </a>

            <nav class="hidden items-center rounded-full bg-navy-900 px-2 py-1 text-sm text-ivory-100 shadow-[0_10px_24px_rgba(0,41,37,0.18)] md:flex">
                <a href="#katalog" class="rounded-full px-4 py-2 font-medium text-ivory-100/70 transition hover:text-ivory-100">Katalog</a>
                <a href="#collections" class="rounded-full px-4 py-2 text-ivory-100/70 transition hover:text-ivory-100">Koleksi</a>
            </nav>

            <div class="flex items-center gap-3">
                <button id="themeToggleMain" class="grid h-10 w-10 place-items-center rounded-full border border-navy-900/15 bg-white text-navy-900 transition hover:border-gold-600 dark:border-white/15 dark:bg-slate-800 dark:text-white" aria-label="Toggle Dark Mode">
                    <i class="ph-bold ph-moon-stars text-lg dark:hidden"></i>
                    <i class="ph-bold ph-sun text-lg hidden dark:block"></i>
                </button>
                <a href="<?= session()->get('logged_in') ? base_url('user/dashboard') : base_url('auth/login') ?>" class="inline-flex items-center gap-2 rounded-full border border-navy-900/15 bg-white px-4 py-2 text-sm font-semibold text-navy-900 transition hover:border-gold-600 dark:border-white/15 dark:bg-slate-800 dark:text-white">
                    <i class="ph-bold <?= session()->get('logged_in') ? 'ph-books' : 'ph-user' ?>"></i>
                    <?= session()->get('logged_in') ? 'Dashboard' : 'Masuk' ?>
                </a>
            </div>
        </div>
    </header>

    <main>
        <!-- Hero: Full-width 16:9 cinematic library background -->
        <section class="relative w-full" style="aspect-ratio: 16/9; min-height: 700px;">
            <!-- Background Image -->
            <img src="<?= base_url('assets/images/cinematic_library.png') ?>" alt="Cinematic Library" class="absolute inset-0 h-full w-full object-cover">
            
            <!-- Dark gradient overlay from left -->
            <div class="absolute inset-0 bg-gradient-to-r from-[#002925]/95 via-[#002925]/70 to-transparent"></div>
            <!-- Extra bottom gradient for stats -->
            <div class="absolute inset-0 bg-gradient-to-t from-[#002925]/80 via-[#002925]/20 to-transparent"></div>

            <!-- Text Content & Stats Container -->
            <div class="relative z-10 mx-auto flex h-full max-w-7xl flex-col justify-between px-6 pb-16 pt-20 md:px-12">
                <!-- Top / Middle Content -->
                <div class="my-auto max-w-xl">
                    <h1 class="font-serif text-4xl font-semibold leading-[1.1] text-[#fffefa] drop-shadow-[0_4px_24px_rgba(0,0,0,0.5)] sm:text-5xl md:text-6xl lg:text-7xl">
                        Perpustakaan digital untuk koleksi yang benar-benar terkurasi.
                    </h1>
                    
                    <p class="mt-6 max-w-md text-base leading-7 text-[#fffefa]/80 drop-shadow-[0_2px_8px_rgba(0,0,0,0.4)] md:text-lg">
                        LITERIA menata buku nyata, sampul asli, status ketersediaan, dan rak pribadi dalam pengalaman yang lebih dekat ke katalog perpustakaan privat.
                    </p>

                    <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                        <a href="#katalog" class="inline-flex items-center justify-center gap-2 rounded-full bg-[#c5a556] px-7 py-3.5 text-sm font-bold text-[#002925] shadow-[0_8px_30px_rgba(197,165,86,0.35)] transition hover:bg-[#d4b666] hover:shadow-[0_8px_40px_rgba(197,165,86,0.5)]">
                            Jelajahi katalog <i class="ph-bold ph-arrow-right"></i>
                        </a>
                        <a href="<?= base_url('user/dashboard') ?>" class="inline-flex items-center justify-center gap-2 rounded-full border border-[#fffefa]/25 bg-[#fffefa]/10 px-7 py-3.5 text-sm font-bold text-[#fffefa] backdrop-blur-sm transition hover:bg-[#fffefa]/20">
                            Buka dashboard <i class="ph-bold ph-bookmark-simple"></i>
                        </a>
                    </div>
                </div>

                <!-- Stats bar at bottom -->
                <div class="mt-8 flex items-center justify-start gap-10">
                    <div>
                        <p class="text-3xl font-semibold text-[#c5a556]"><?= count($categoryCounts) ?></p>
                        <p class="text-xs text-[#fffefa]/60">Kelompok Koleksi</p>
                    </div>
                    <div class="h-8 w-px bg-[#fffefa]/15"></div>
                    <div>
                        <p class="text-3xl font-semibold text-[#c5a556]"><?= array_sum(array_column($buku ?? [], 'stok')) ?></p>
                        <p class="text-xs text-[#fffefa]/60">Total Stok Buku</p>
                    </div>
                    <div class="h-8 w-px bg-[#fffefa]/15"></div>
                    <div>
                        <p class="text-3xl font-semibold text-[#c5a556]"><?= count($buku ?? []) ?></p>
                        <p class="text-xs text-[#fffefa]/60">Judul Terkurasi</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ═══ Editor's Pick Carousel ═══ -->
        <?php if (!empty($editorsPick)): ?>
        <section class="editors-pick-section reveal">
            <div class="mx-auto max-w-7xl px-4 md:px-8">
                <p class="mb-2 text-center text-xs font-semibold uppercase tracking-[0.2em] text-[#c5a556]">Sorotan Pilihan</p>
                <h2 class="mb-2 text-center font-serif text-3xl font-semibold text-[#fffefa] md:text-4xl">Editor's Pick</h2>
                <p class="mx-auto mb-10 max-w-lg text-center text-sm text-[#fffefa]/55">Koleksi pilihan yang dikurasi secara khusus oleh tim LITERIA untuk menemani ruang baca Anda.</p>
            </div>

            <div class="swiper editorsSwiper">
                <div class="swiper-wrapper">
                    <?php 
                    // Duplicate array elements to ensure Swiper can loop seamlessly if we have too few picks
                    $loopPicks = $editorsPick;
                    if (count($loopPicks) > 0 && count($loopPicks) < 10) {
                        $loopPicks = array_merge($loopPicks, $loopPicks, $loopPicks); // Make sure there are enough clones
                    }
                    foreach ($loopPicks as $ep): 
                    ?>
                    <div class="swiper-slide">
                        <a href="<?= base_url('web/buku/' . $ep['id_buku']) ?>">
                            <img src="<?= esc($ep['cover_url']) ?>" alt="<?= esc($ep['judul']) ?>" loading="lazy">
                        </a>
                        <div class="pick-card-info">
                            <p class="text-sm font-semibold text-[#fffefa] truncate"><?= esc($ep['judul']) ?></p>
                            <p class="text-xs text-[#fffefa]/50 truncate"><?= esc($ep['pengarang']) ?></p>
                            <?php if (($ep['rating'] ?? 0) > 0): ?>
                                <p class="mt-1 text-xs text-[#c5a556]"><i class="ph-fill ph-star"></i> <?= number_format($ep['rating'], 1) ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="swiper-pagination mt-8"></div>
            </div>
        </section>
        <?php endif; ?>

        <section id="collections" class="border-y border-navy-900/10 bg-ivory-100 reveal">
            <div class="mx-auto max-w-7xl px-4 py-10 md:px-8">
                <div class="mb-6 flex flex-col justify-between gap-4 md:flex-row md:items-end">
                    <div>
                        <h2 class="font-serif text-3xl text-navy-900">Kelompok Koleksi</h2>
                        <p class="mt-2 text-sm text-navy-900/60">Data dikelompokkan dari kategori buku di database, bukan label dekoratif.</p>
                    </div>
                    <a href="#katalog" class="text-sm font-bold text-navy-900 underline decoration-gold-600 underline-offset-4">Lihat semua buku</a>
                </div>

                <div class="grid gap-4 md:grid-cols-4">
                    <?php foreach($categoryCounts as $category => $count): ?>
                        <button class="cat-btn rounded-2xl border border-navy-900/10 bg-white p-5 text-left transition hover:-translate-y-0.5 hover:border-gold-600" data-kategori="<?= esc($category) ?>">
                            <span class="text-xs font-semibold uppercase tracking-[0.16em] text-navy-900/45"><?= esc($count) ?> buku</span>
                            <span class="mt-3 block font-serif text-2xl text-navy-900"><?= esc($category) ?></span>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <section id="katalog" class="mx-auto max-w-7xl px-4 py-12 md:px-8 md:py-16 reveal">
            <div class="mb-8 grid gap-5 md:grid-cols-[1fr_auto] md:items-end">
                <div>
                    <p class="mb-3 text-xs font-semibold uppercase tracking-[0.16em] text-gold-500">Catalog</p>
                    <h2 class="font-serif text-4xl text-navy-900">Buku nyata, sampul nyata.</h2>
                    <p class="mt-3 max-w-2xl text-sm leading-6 text-navy-900/65">Katalog ini memakai data bibliografi dan sampul berbasis ISBN. Gunakan pencarian untuk judul atau penulis, lalu filter berdasarkan kelompok koleksi.</p>
                </div>
                <div class="relative">
                    <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-navy-900/35"></i>
                    <input type="text" id="searchInput" placeholder="Cari buku atau penulis..." class="w-full rounded-full border border-navy-900/15 bg-white py-3 pl-11 pr-5 text-sm text-navy-900 outline-none transition placeholder:text-navy-900/45 focus:border-gold-600 focus:ring-4 focus:ring-gold-600/15 md:w-96">
                </div>
            </div>

            <div class="mb-4 flex gap-2 overflow-x-auto pb-2" id="access-filters">
                <button class="access-btn shrink-0 rounded-full border border-navy-900 bg-navy-900 px-4 py-2 text-sm font-semibold text-ivory-100" data-access="">Semua koleksi</button>
                <button class="access-btn shrink-0 rounded-full border border-navy-900/15 bg-white px-4 py-2 text-sm font-semibold text-navy-900/70 transition hover:border-gold-600 hover:text-navy-900" data-access="readable">Bisa dibaca legal</button>
                <span class="shrink-0 rounded-full border border-navy-900/10 bg-ivory-100 px-4 py-2 text-sm text-navy-900/55">Buku modern hanya katalog</span>
            </div>

            <div class="mb-8 flex gap-2 overflow-x-auto pb-2" id="category-filters">
                <button class="cat-btn active shrink-0 rounded-full border border-navy-900 bg-navy-900 px-4 py-2 text-sm font-semibold text-ivory-100" data-kategori="">Semua</button>
                <?php foreach($categories as $k): ?>
                    <button class="cat-btn shrink-0 rounded-full border border-navy-900/15 bg-white px-4 py-2 text-sm font-semibold text-navy-900/70 transition hover:border-gold-600 hover:text-navy-900" data-kategori="<?= esc($k) ?>"><?= esc($k) ?></button>
                <?php endforeach; ?>
            </div>

            <div id="catalog-container">
                <?= view('web/_catalog_list', ['buku' => $buku]) ?>
            </div>
        </section>
    </main>

    <footer class="border-t border-navy-900/10 bg-ivory-100">
        <div class="mx-auto flex max-w-7xl flex-col gap-4 px-4 py-8 text-sm text-navy-900/60 md:flex-row md:items-center md:justify-between md:px-8">
            <div class="flex items-center gap-3 text-navy-900">
                <div class="literia-mark scale-75" aria-hidden="true">
                    <span class="base"></span>
                    <span class="spine"></span>
                    <span class="gold-a"></span>
                    <span class="gold-b"></span>
                </div>
                <span class="font-bold tracking-[0.14em]">LITERIA</span>
            </div>
            <p>© 2026 LITERIA. Pengetahuan, Terstruktur.</p>
        </div>
    </footer>

    <script>
        // Dark Mode Toggle
        const themeToggleBtn = document.getElementById('themeToggleMain');
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

        // Scroll Reveal
        function reveal() {
            var reveals = document.querySelectorAll(".reveal");
            for (var i = 0; i < reveals.length; i++) {
                var windowHeight = window.innerHeight;
                var elementTop = reveals[i].getBoundingClientRect().top;
                var elementVisible = 100;
                if (elementTop < windowHeight - elementVisible) {
                    reveals[i].classList.add("active");
                }
            }
        }
        window.addEventListener("scroll", reveal);
        reveal(); // Trigger on load

        // Editor's Pick Swiper
        if (document.querySelector('.editorsSwiper')) {
            new Swiper('.editorsSwiper', {
                effect: 'coverflow',
                grabCursor: true,
                centeredSlides: true,
                slidesPerView: 'auto',
                loop: true,
                loopedSlides: 5,
                speed: 600,
                coverflowEffect: {
                    rotate: 8,
                    stretch: 0,
                    depth: 180,
                    modifier: 1.5,
                    slideShadows: true,
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                autoplay: {
                    delay: 3500,
                    disableOnInteraction: false,
                },
            });
        }

        const searchInput = document.getElementById('searchInput');
        const catalogContainer = document.getElementById('catalog-container');
        const catBtns = document.querySelectorAll('.cat-btn');
        const accessBtns = document.querySelectorAll('.access-btn');
        let searchTimeout;
        let activeCategory = '';
        let activeAccess = '';

        function setActiveButton(category) {
            catBtns.forEach((button) => {
                const isActive = button.getAttribute('data-kategori') === category;
                button.classList.toggle('bg-navy-900', isActive);
                button.classList.toggle('text-ivory-100', isActive);
                button.classList.toggle('border-navy-900', isActive);
                button.classList.toggle('bg-white', !isActive);
                button.classList.toggle('text-navy-900/70', !isActive);
                button.classList.toggle('border-navy-900/15', !isActive);
            });
        }

        function fetchCatalog() {
            if (!catalogContainer) return;

            const query = searchInput ? searchInput.value : '';
            catalogContainer.style.opacity = '0.55';
            catalogContainer.style.transition = 'opacity 180ms ease';

            fetch(`/web/search?q=${encodeURIComponent(query)}&cat=${encodeURIComponent(activeCategory)}&access=${encodeURIComponent(activeAccess)}`)
                .then((response) => response.text())
                .then((html) => {
                    catalogContainer.innerHTML = html;
                    catalogContainer.style.opacity = '1';
                })
                .catch(() => {
                    catalogContainer.style.opacity = '1';
                });
        }

        if (searchInput && catalogContainer) {
            searchInput.addEventListener('input', () => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(fetchCatalog, 250);
            });
        }

        catBtns.forEach((button) => {
            button.addEventListener('click', () => {
                activeCategory = button.getAttribute('data-kategori');
                setActiveButton(activeCategory);
                fetchCatalog();

                if (button.closest('#collections')) {
                    document.getElementById('katalog')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });

        accessBtns.forEach((button) => {
            button.addEventListener('click', () => {
                activeAccess = button.getAttribute('data-access');
                accessBtns.forEach((item) => {
                    const isActive = item.getAttribute('data-access') === activeAccess;
                    item.classList.toggle('bg-navy-900', isActive);
                    item.classList.toggle('text-ivory-100', isActive);
                    item.classList.toggle('border-navy-900', isActive);
                    item.classList.toggle('bg-white', !isActive);
                    item.classList.toggle('text-navy-900/70', !isActive);
                    item.classList.toggle('border-navy-900/15', !isActive);
                });
                fetchCatalog();
            });
        });
    </script>
    <script>
        // PWA Service Worker
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js').catch(err => console.log('SW failed', err));
            });
        }
    </script>
</body>
</html>
