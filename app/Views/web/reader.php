<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Membaca <?= esc($buku['judul']) ?> | LITERIA</title>
    <!-- PWA -->
    <link rel="manifest" href="<?= base_url('manifest.json') ?>">
    <meta name="theme-color" content="#fffefa">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Lora:wght@500;600;700&display=swap" rel="stylesheet">
    <!-- OpenDyslexic for accessibility -->
    <link href="https://cdn.jsdelivr.net/npm/opendyslexic@2.0.3/opendyslexic.css" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- StPageFlip — realistic page flip engine -->
    <script src="https://unpkg.com/page-flip/dist/js/page-flip.browser.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        serif: ['Lora', 'serif'],
                    },
                    colors: {
                        ivory: { 100: '#fffefa', 200: '#f7f1e3', 300: '#eee6d2' },
                        navy: { 900: '#002925', 950: '#071c2f' },
                        gold: { 500: '#ac554c', 600: '#c5a556' }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            min-height: 100vh;
            --reader-scale: 1;
            background:
                radial-gradient(circle at 20% 0%, rgba(197,165,86,0.16), transparent 24rem),
                radial-gradient(circle at 90% 18%, rgba(0,41,37,0.10), transparent 30rem),
                linear-gradient(135deg, #f6f2e9 0%, #e7deca 48%, #cdc2aa 100%);
            color: #002925;
            font-family: Inter, sans-serif;
        }

        /* ── Book wrapper ── */
        .flipbook-viewport {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 0;
        }

        .flipbook-container {
            position: relative;
            /* StPageFlip canvas draws inside this */
        }

        /* ── Each page ── */
        .page {
            padding: 2.5rem 2.8rem;
            overflow: hidden;
            background:
                radial-gradient(circle at 12% 10%, rgba(197,165,86,.06), transparent 18rem),
                linear-gradient(180deg, #fffdfa, #f9f5ec);
            box-shadow: inset 0 0 28px rgba(0, 41, 37, .04);
            font-family: Inter, sans-serif;
            display: flex;
            flex-direction: column;
        }

        /* Paper texture overlay via pseudo-element */
        .page::before {
            content: "";
            position: absolute;
            inset: 0;
            pointer-events: none;
            opacity: .14;
            background-image:
                linear-gradient(rgba(0,41,37,.02) 1px, transparent 1px),
                radial-gradient(circle at 25% 30%, rgba(0,41,37,.04) 0 1px, transparent 1px);
            background-size: 100% 1.76rem, 18px 18px;
            mix-blend-mode: multiply;
            z-index: 0;
        }

        /* Left page inner shadow (spine glow) */
        .page::after {
            content: "";
            position: absolute;
            top: 0; bottom: 0; right: 0;
            width: 40px;
            pointer-events: none;
            background: linear-gradient(90deg, transparent, rgba(0,41,37,0.04));
            z-index: 1;
        }

        .page.--right::after {
            right: auto;
            left: 0;
            background: linear-gradient(270deg, transparent, rgba(0,41,37,0.04));
        }

        /* Hard cover pages */
        .page.--hard {
            background:
                linear-gradient(135deg, #002925 0%, #0a3d38 50%, #002925 100%);
            color: #fffefa;
        }

        .page.--hard::before { display: none; }
        .page.--hard::after { display: none; }

        /* ── Page content ── */
        .page-kicker {
            color: rgba(172, 85, 76, .88);
            font-size: .68rem;
            letter-spacing: .14em;
            position: relative;
            z-index: 2;
        }

        .page-title {
            text-wrap: balance;
            position: relative;
            z-index: 2;
        }

        .page-title--number {
            color: rgba(0, 41, 37, .48);
            font-family: Inter, sans-serif;
            font-size: .78rem;
            font-weight: 700;
            letter-spacing: .16em;
            text-transform: uppercase;
        }

        .page-body {
            color: rgba(0, 41, 37, .76);
            font-size: calc(.92rem * var(--reader-scale));
            line-height: 1.82;
            text-align: justify;
            text-justify: inter-word;
            position: relative;
            z-index: 2;
        }

        .page-footer {
            margin-top: auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding-top: 2rem;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: .16em;
            text-transform: uppercase;
            color: rgba(0, 41, 37, .28);
            position: relative;
            z-index: 2;
        }

        /* ── Night mode ── */
        .reader-night {
            background:
                radial-gradient(circle at 15% 10%, rgba(197,165,86,0.12), transparent 28rem),
                linear-gradient(135deg, #071c2f 0%, #002925 50%, #12100d 100%);
            color: #fffefa;
        }

        .reader-night .page {
            background: #efe5cf;
        }

        .reader-night .page.--hard {
            background: linear-gradient(135deg, #0a1628, #071c2f, #0a1628);
        }

        .reader-night header a:first-child,
        .reader-night .reader-toolbar,
        .reader-night .progress-shell {
            background: rgba(255, 254, 250, .92);
        }

        /* ── Smooth entrance ── */
        .flipbook-container {
            animation: readerEntrance .8s cubic-bezier(0.23, 1, 0.32, 1) both;
        }
        @keyframes readerEntrance {
            from { opacity: 0; transform: scale(0.96) translateY(16px); }
            to   { opacity: 1; transform: scale(1) translateY(0); }
        }

        /* ── StPageFlip shadow enhancements ── */
        .stf__parent {
            box-shadow:
                0 28px 60px rgba(0, 41, 37, .18),
                0 8px 24px rgba(73, 48, 19, .12) !important;
            border-radius: 6px;
        }

        /* ── Zen Mode Popover ── */
        .zen-popover {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            background: #fffefa;
            border: 1px solid rgba(0, 41, 37, 0.1);
            border-radius: 16px;
            padding: 16px;
            width: 260px;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1), 0 8px 10px -6px rgba(0,0,0,0.1);
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: all 0.25s ease;
            z-index: 50;
        }
        .zen-popover.active {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        .reader-night .zen-popover {
            background: #071c2f;
            border-color: rgba(255,255,255,0.1);
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.5);
        }
        
        /* Typography overrides */
        body[data-font="serif"] { font-family: Lora, serif; }
        body[data-font="dyslexic"] { font-family: OpenDyslexic, sans-serif; }
        body[data-font="sans"] { font-family: Inter, sans-serif; }
        
        /* For headers and UI inside reader, keep sans */
        .zen-popover, .reader-toolbar, .page-header { font-family: Inter, sans-serif !important; }

        /* ── Annotations ── */
        .annotation-toolbar {
            position: absolute;
            background: #111827;
            border-radius: 8px;
            padding: 6px;
            display: flex;
            gap: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            z-index: 100;
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px) translateX(-50%);
            transition: opacity 0.2s, transform 0.2s;
        }
        .annotation-toolbar.active {
            opacity: 1;
            visibility: visible;
            transform: translateY(0) translateX(-50%);
        }
        .annotation-toolbar::after {
            content: '';
            position: absolute;
            top: 100%;
            left: 50%;
            margin-left: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: #111827 transparent transparent transparent;
        }
        .color-btn {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            cursor: pointer;
            border: 2px solid transparent;
        }
        .color-btn:hover { border-color: white; }
        .color-yellow { background: #fef08a; }
        .color-green { background: #bbf7d0; }
        .color-pink { background: #fbcfe8; }
        mark {
            background-color: transparent;
            padding: 0 2px;
            border-radius: 2px;
            cursor: pointer;
        }
        mark.yellow { background-color: rgba(254, 240, 138, 0.6); }
        mark.green { background-color: rgba(187, 247, 208, 0.6); }
        mark.pink { background-color: rgba(251, 207, 232, 0.6); }
        .reader-night mark.yellow { background-color: rgba(254, 240, 138, 0.3); color: #fef08a; }
        .reader-night mark.green { background-color: rgba(187, 247, 208, 0.3); color: #bbf7d0; }
        .reader-night mark.pink { background-color: rgba(251, 207, 232, 0.3); color: #fbcfe8; }

        @media (max-width: 760px) {
            .page { padding: 1.5rem 1.2rem; }
            .page-body { font-size: calc(.82rem * var(--reader-scale)); line-height: 1.7; }
        }
    </style>
</head>
<body class="antialiased">
    <?php
        $jsonPages = json_encode($pages, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
        $totalPages = max(1, (int) ($totalPages ?? count($pages)));
    ?>

    <header class="mx-auto flex max-w-7xl items-center justify-between px-4 py-5 md:px-8">
        <a href="<?= base_url('web/buku/'.$buku['id_buku']) ?>" class="inline-flex items-center gap-2 rounded-full border border-navy-900/10 bg-ivory-100/80 px-4 py-2 text-sm font-semibold text-navy-900 transition hover:border-gold-600">
            <i class="ph-bold ph-arrow-left"></i>
            Detail buku
        </a>
        <a href="<?= base_url('user/dashboard') ?>" class="inline-flex items-center gap-2 rounded-full bg-navy-900 px-4 py-2 text-sm font-semibold text-ivory-100 transition hover:bg-[#103c37]">
            Dashboard
            <i class="ph-bold ph-squares-four"></i>
        </a>
    </header>

    <main class="mx-auto max-w-7xl px-4 pb-12 md:px-8">
        <section class="mb-7 grid gap-4 md:grid-cols-[1fr_auto] md:items-end">
            <div>
                <p class="mb-2 text-xs font-semibold uppercase tracking-[0.16em] text-gold-500">Reading room</p>
                <h1 class="font-serif text-4xl font-semibold leading-tight text-navy-900 md:text-5xl"><?= esc($buku['judul']) ?></h1>
                <p class="mt-2 text-sm text-navy-900/60"><?= esc($buku['pengarang']) ?> &middot; <?= esc($buku['kategori']) ?></p>
            </div>
            <div class="reader-toolbar flex flex-wrap items-center gap-2 rounded-full border border-navy-900/10 bg-ivory-100/80 p-2 text-sm font-semibold text-navy-900/70">
                <span id="pageIndicator" class="px-3">Halaman 1-2</span>
                <button id="fontDown" type="button" class="grid h-9 w-9 place-items-center rounded-full border border-navy-900/10 bg-white/70 text-xs font-bold transition hover:border-gold-600" aria-label="Perkecil teks">A-</button>
                <button id="fontUp" type="button" class="grid h-9 w-9 place-items-center rounded-full border border-navy-900/10 bg-white/70 text-sm font-bold transition hover:border-gold-600" aria-label="Perbesar teks">A+</button>
                <div class="relative">
                    <button id="themeToggle" type="button" class="inline-flex h-9 items-center gap-1 rounded-full border border-navy-900/10 bg-white/70 px-3 text-xs font-bold transition hover:border-gold-600" aria-label="Ganti mode baca">
                        <i class="ph-bold ph-sliders-horizontal"></i>
                        Mode
                    </button>
                    <!-- Popover -->
                    <div id="zenPopover" class="zen-popover">
                        <div class="mb-4">
                            <p class="text-[10px] uppercase tracking-wider text-navy-900/50 dark:text-ivory-100/50 font-bold mb-2">Tema</p>
                            <div class="flex gap-2">
                                <button class="zen-btn flex-1 rounded border border-navy-900/10 bg-ivory-100 py-1.5 text-xs font-semibold text-navy-900" data-action="theme" data-value="light">Terang</button>
                                <button class="zen-btn flex-1 rounded border border-white/10 bg-[#071c2f] py-1.5 text-xs font-semibold text-white" data-action="theme" data-value="dark">Gelap</button>
                            </div>
                        </div>
                        <div class="mb-4">
                            <p class="text-[10px] uppercase tracking-wider text-navy-900/50 dark:text-ivory-100/50 font-bold mb-2">Tipografi</p>
                            <div class="flex flex-col gap-1">
                                <button class="zen-btn rounded px-2 py-1.5 text-left text-xs font-medium hover:bg-navy-900/5 dark:hover:bg-white/5" style="font-family: Inter;" data-action="font" data-value="sans">Modern (Sans)</button>
                                <button class="zen-btn rounded px-2 py-1.5 text-left text-xs font-medium hover:bg-navy-900/5 dark:hover:bg-white/5" style="font-family: Lora;" data-action="font" data-value="serif">Klasik (Serif)</button>
                                <button class="zen-btn rounded px-2 py-1.5 text-left text-xs font-medium hover:bg-navy-900/5 dark:hover:bg-white/5" style="font-family: OpenDyslexic;" data-action="font" data-value="dyslexic">OpenDyslexic</button>
                            </div>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase tracking-wider text-navy-900/50 dark:text-ivory-100/50 font-bold mb-2">Ambience</p>
                            <div class="flex flex-wrap gap-2">
                                <button class="zen-btn rounded-full border border-navy-900/10 dark:border-white/10 px-3 py-1 text-[11px] font-semibold" data-action="audio" data-value="none">Mati</button>
                                <button class="zen-btn rounded-full border border-navy-900/10 dark:border-white/10 px-3 py-1 text-[11px] font-semibold" data-action="audio" data-value="rain">Hujan 🌧</button>
                                <button class="zen-btn rounded-full border border-navy-900/10 dark:border-white/10 px-3 py-1 text-[11px] font-semibold" data-action="audio" data-value="cafe">Kafe ☕</button>
                            </div>
                        </div>
                    </div>
                </div>
                <button id="ttsToggle" type="button" class="inline-flex h-9 items-center gap-1 rounded-full border border-navy-900/10 bg-white/70 px-3 text-xs font-bold transition hover:border-gold-600" aria-label="Dengarkan buku">
                    <i class="ph-bold ph-speaker-high"></i>
                    Audio
                </button>
                <button id="bookmarkToggle" type="button" class="inline-flex h-9 items-center gap-1 rounded-full border border-navy-900/10 bg-white/70 px-3 text-xs font-bold transition hover:border-gold-600" aria-label="Simpan Bookmark">
                    <i class="ph-bold ph-bookmark-simple"></i>
                    Tandai
                </button>
                <button id="pomodoroToggle" type="button" class="inline-flex h-9 items-center gap-1 rounded-full border border-navy-900/10 bg-white/70 px-3 text-xs font-bold transition hover:border-gold-600" aria-label="Pomodoro Timer">
                    <i class="ph-bold ph-timer"></i>
                    <span id="pomodoroLabel">Fokus 25m</span>
                </button>
                <button id="offlineDownload" type="button" class="inline-flex h-9 items-center gap-1 rounded-full border border-emerald-600/20 bg-emerald-50 px-3 text-xs font-bold text-emerald-800 transition hover:border-emerald-600" aria-label="Unduh untuk Offline">
                    <i class="ph-bold ph-cloud-arrow-down"></i>
                    Unduh Offline
                </button>
            </div>
        </section>

        <!-- AI Explainer Floating Popup (hidden by default) -->
        <div id="aiExplainerPopup" class="fixed z-[9999] hidden max-w-sm rounded-2xl border border-gold-600/30 bg-white/95 p-5 shadow-2xl backdrop-blur" style="top:30%;left:50%;transform:translate(-50%,-50%)">
            <div class="mb-3 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="text-xl">🧠</span>
                    <h3 class="font-serif text-base font-bold text-navy-900">LITERIA Explainer</h3>
                </div>
                <button onclick="document.getElementById('aiExplainerPopup').classList.add('hidden')" class="grid h-7 w-7 place-items-center rounded-full bg-navy-900/5 text-xs text-navy-900 hover:bg-navy-900/10">&times;</button>
            </div>
            <div id="aiExplainerContent" class="text-sm leading-relaxed text-navy-900/80">
                <p class="text-navy-900/40 italic">Blok teks apa pun di halaman buku, lalu klik tombol "🧠 Jelaskan".</p>
            </div>
        </div>

        <!-- Text Selection Tooltip (hidden by default) -->
        <div id="selectionTooltip" class="fixed z-[9998] hidden rounded-full border border-navy-900/15 bg-white/95 shadow-xl backdrop-blur" style="padding:4px">
            <button id="btnAiExplain" class="inline-flex items-center gap-1 rounded-full bg-gradient-to-r from-gold-600 to-amber-500 px-3 py-1.5 text-[11px] font-bold text-white shadow transition hover:shadow-lg">
                🧠 Jelaskan (AI)
            </button>
            <button id="btnHighlight" class="inline-flex items-center gap-1 rounded-full bg-navy-900 px-3 py-1.5 text-[11px] font-bold text-white shadow transition hover:bg-[#103c37]">
                ✨ Sorot
            </button>
        </div>

        <section class="progress-shell mb-5 rounded-full border border-navy-900/10 bg-ivory-100/75 p-2">
            <div class="flex items-center gap-3">
                <div class="h-2 flex-1 overflow-hidden rounded-full bg-white">
                    <div id="readingProgress" class="h-full rounded-full bg-gold-600 transition-all duration-300" style="width:0%"></div>
                </div>
                <span id="progressText" class="w-14 text-right text-xs font-bold text-navy-900/55">0%</span>
            </div>
        </section>

        <!-- StPageFlip container -->
        <section class="flipbook-viewport">
            <div id="flipbook" class="flipbook-container"></div>
        </section>

        <div class="mt-7 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-center">
            <button id="prevPage" class="inline-flex items-center justify-center gap-2 rounded-full border border-navy-900/15 bg-ivory-100 px-5 py-3 text-sm font-bold text-navy-900 transition hover:border-gold-600 disabled:cursor-not-allowed disabled:opacity-40">
                <i class="ph-bold ph-arrow-left"></i>
                Halaman sebelumnya
            </button>
            <button id="nextPage" class="inline-flex items-center justify-center gap-2 rounded-full bg-navy-900 px-5 py-3 text-sm font-bold text-ivory-100 transition hover:bg-[#103c37] disabled:cursor-not-allowed disabled:opacity-40">
                Halaman berikutnya
                <i class="ph-bold ph-arrow-right"></i>
            </button>
        </div>

        <?php if(($buku['read_access'] ?? 'metadata') === 'public_domain' && !empty($buku['source_url'])): ?>
            <p class="mt-5 text-center text-xs text-navy-900/55">
                Teks public domain dari <a href="<?= esc($buku['source_url']) ?>" target="_blank" rel="noopener" class="font-bold underline decoration-gold-600 underline-offset-4"><?= esc($buku['source_name'] ?: 'Project Gutenberg') ?></a>.
            </p>
        <?php else: ?>
            <p class="mt-5 text-center text-xs text-navy-900/55">Buku modern ditampilkan sebagai catatan katalog dan ruang baca pribadi, bukan salinan isi penuh.</p>
        <?php endif; ?>

        <!-- Ambience is generated via Web Audio API (no external files needed) -->

        <!-- Annotation Toolbar -->
        <div id="annotationToolbar" class="annotation-toolbar">
            <button class="color-btn color-yellow" data-color="yellow" title="Highlight Kuning"></button>
            <button class="color-btn color-green" data-color="green" title="Highlight Hijau"></button>
            <button class="color-btn color-pink" data-color="pink" title="Highlight Pink"></button>
        </div>

    </main>

    <script>
        const initialPages = <?= $jsonPages ?>;
        const totalPages = <?= $totalPages ?>;
        const bookId = <?= (int) $buku['id_buku'] ?>;
        const pageEndpoint = `/web/read-pages/${bookId}`;
        const pageCache = new Map(initialPages.map((page, index) => [index, page]));
        let popularHighlights = [];

        // Fetch popular highlights
        fetch(`<?= base_url('web/get_popular_annotations/' . $buku['id_buku']) ?>`)
            .then(res => res.json())
            .then(data => popularHighlights = data)
            .catch(console.error);

        // DOM refs
        const refs = {
            prev: document.getElementById('prevPage'),
            next: document.getElementById('nextPage'),
            progress: document.getElementById('readingProgress'),
            progressText: document.getElementById('progressText'),
            indicator: document.getElementById('pageIndicator'),
            flipbookEl: document.getElementById('flipbook'),
            fontDown: document.getElementById('fontDown'),
            fontUp: document.getElementById('fontUp'),
            themeToggle: document.getElementById('themeToggle'),
            ttsToggle: document.getElementById('ttsToggle'),
            bookmarkToggle: document.getElementById('bookmarkToggle'),
            scale: 1
        };

        // ── Build page HTML ──
        function buildPageHTML(page, fallbackTitle = 'Catatan kosong') {
            const kicker = page?.kicker || 'CATATAN';
            const title = page?.title || fallbackTitle;
            const isNumber = title.startsWith('Halaman ');
            const body = page?.body || 'Belum ada isi pada halaman ini.';
            
            let bodyHtml = escHtml(body);
            
            // Apply popular social highlights
            if (popularHighlights && popularHighlights.length > 0) {
                popularHighlights.forEach(h => {
                    const textSafe = escHtml(h.teks_highlight);
                    if (textSafe.length > 5) {
                        const regex = new RegExp(textSafe.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&'), 'g');
                        bodyHtml = bodyHtml.replace(regex, `<span class="border-b-2 border-dashed border-gold-600/60 pb-0.5 cursor-help" title="Disorot oleh ${h.total} pembaca lain">$&</span>`);
                    }
                });
            }

            return `
                <div class="page-kicker mb-4 font-semibold uppercase">${escHtml(kicker)}</div>
                <h2 class="page-title font-serif text-[2.2rem] font-semibold leading-tight text-navy-900 ${isNumber ? 'page-title--number' : ''}">${escHtml(title)}</h2>
                <p class="page-body reading-copy mt-5 whitespace-pre-line">${bodyHtml}</p>
                <div class="page-footer">
                    <span>LITERIA</span>
                    <span>Pengetahuan, Terstruktur.</span>
                </div>
            `;
        }

        function escHtml(str) {
            const d = document.createElement('div');
            d.textContent = str;
            return d.innerHTML;
        }

        // ── Fetch pages on demand ──
        async function ensurePages(start, limit = 6) {
            const needed = [];
            for (let i = start; i < Math.min(totalPages, start + limit); i++) {
                if (!pageCache.has(i)) needed.push(i);
            }
            if (needed.length === 0) return;
            try {
                const resp = await fetch(`${pageEndpoint}?start=${needed[0]}&limit=${limit}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await resp.json();
                (data.pages || []).forEach((page, offset) => {
                    pageCache.set(needed[0] + offset, page);
                });
            } catch (e) { console.warn('Page fetch error:', e); }
        }

        // ── Initialize StPageFlip ──
        async function initFlipbook() {
            // Pre-fetch first batch
            await ensurePages(0, Math.min(totalPages, 10));

            // Determine viewport-aware sizing
            const vw = Math.min(window.innerWidth - 64, 1200);
            const pageW = Math.min(Math.floor(vw / 2), 520);
            const pageH = Math.floor(pageW * 1.42);

            const flipbook = new St.PageFlip(refs.flipbookEl, {
                width: pageW,
                height: pageH,
                size: 'stretch',
                minWidth: 280,
                maxWidth: 600,
                minHeight: 420,
                maxHeight: 900,
                maxShadowOpacity: 0.35,
                showCover: false,
                mobileScrollSupport: true,
                swipeDistance: 30,
                showPageCorners: true,
                useMouseEvents: true,
                flippingTime: 900,
                drawShadow: true,
                autoSize: true,
            });

            // Create page elements inside the container
            for (let i = 0; i < totalPages; i++) {
                const div = document.createElement('div');
                div.className = 'page' + (i % 2 === 1 ? ' --right' : '');
                const cached = pageCache.get(i);
                div.innerHTML = buildPageHTML(cached, `Halaman ${i + 1}`);
                refs.flipbookEl.appendChild(div);
            }

            flipbook.loadFromHTML(document.querySelectorAll('#flipbook .page'));

            // ── Update UI on flip ──
            flipbook.on('flip', async (e) => {
                const currentPage = e.data; // 0-indexed
                updateProgress(currentPage);
                
                // Stop TTS if playing on flip
                if (window.speechSynthesis && window.speechSynthesis.speaking) {
                    window.speechSynthesis.cancel();
                    refs.ttsToggle.innerHTML = '<i class="ph-bold ph-speaker-high"></i> Audio';
                    refs.ttsToggle.classList.remove('bg-gold-500', 'text-ivory-100');
                    refs.ttsToggle.classList.add('bg-white/70');
                }

                // Pre-fetch ahead
                await ensurePages(currentPage + 2, 6);

                // Update content of pages that weren't cached at init
                for (let i = currentPage; i < Math.min(totalPages, currentPage + 4); i++) {
                    const cached = pageCache.get(i);
                    if (cached) {
                        try {
                            const pageObj = flipbook.getPage(i);
                            const pageEl = pageObj?.element || pageObj;
                            if (pageEl && !pageEl.dataset?.loaded) {
                                pageEl.innerHTML = buildPageHTML(cached, `Halaman ${i + 1}`);
                                if (pageEl.dataset) pageEl.dataset.loaded = 'true';
                            }
                        } catch (err) {
                            console.warn('Error rendering pre-fetched page:', err);
                        }
                    }
                }
            });

            function updateProgress(pageIndex) {
                const spread = pageIndex;
                const pairStart = spread + 1;
                const pairEnd = Math.min(spread + 2, totalPages);
                refs.indicator.textContent = `Halaman ${pairStart}-${pairEnd} dari ${totalPages}`;
                const pct = Math.min(100, (pairEnd / totalPages) * 100);
                const label = pct < 10 ? pct.toFixed(1) : Math.round(pct);
                refs.progress.style.width = `${pct}%`;
                refs.progressText.textContent = `${label}%`;

                refs.prev.disabled = pageIndex <= 0;
                refs.next.disabled = pageIndex >= totalPages - 2;
            }

            // ── Button controls ──
            refs.prev.addEventListener('click', () => flipbook.flipPrev());
            refs.next.addEventListener('click', () => flipbook.flipNext());

            // ── Keyboard ──
            window.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowRight') flipbook.flipNext();
                if (e.key === 'ArrowLeft') flipbook.flipPrev();
            });

            // ── Font size ──
            refs.fontDown.addEventListener('click', () => {
                refs.scale = Math.max(.82, Number((refs.scale - .06).toFixed(2)));
                document.body.style.setProperty('--reader-scale', refs.scale);
            });
            refs.fontUp.addEventListener('click', () => {
                refs.scale = Math.min(1.22, Number((refs.scale + .06).toFixed(2)));
                document.body.style.setProperty('--reader-scale', refs.scale);
            });
            
            // ── Zen Mode Logic ──
            const themeToggle = document.getElementById('themeToggle');
            const zenPopover = document.getElementById('zenPopover');
            const zenBtns = document.querySelectorAll('.zen-btn');

            // ── Web Audio API Ambience Generator ──
            let ambienceCtx = null;
            let ambienceNodes = [];

            function stopAmbience() {
                ambienceNodes.forEach(n => { try { n.stop?.(); n.disconnect?.(); } catch(e){} });
                ambienceNodes = [];
                if (ambienceCtx) { ambienceCtx.close().catch(()=>{}); ambienceCtx = null; }
            }

            function playRainAmbience() {
                stopAmbience();
                ambienceCtx = new (window.AudioContext || window.webkitAudioContext)();
                const ctx = ambienceCtx;

                // Brown noise (filtered white noise = rain-like)
                const bufferSize = 2 * ctx.sampleRate; // 2 seconds buffer
                const buffer = ctx.createBuffer(1, bufferSize, ctx.sampleRate);
                const data = buffer.getChannelData(0);
                let lastOut = 0;
                for (let i = 0; i < bufferSize; i++) {
                    const white = Math.random() * 2 - 1;
                    lastOut = (lastOut + (0.02 * white)) / 1.02;
                    data[i] = lastOut * 3.5;
                }

                const source = ctx.createBufferSource();
                source.buffer = buffer;
                source.loop = true;

                // Ultra-calm lowpass filter (menghilangkan suara 'rusuh' / desis)
                const filter = ctx.createBiquadFilter();
                filter.type = 'lowpass';
                filter.frequency.value = 350; // Deep, distant rain
                
                const gain = ctx.createGain();
                gain.gain.value = 0.15; // Very soft volume for focus

                source.connect(filter);
                filter.connect(gain);
                gain.connect(ctx.destination);
                source.start();

                ambienceNodes = [source, filter, gain];
            }

            function playCafeAmbience() {
                stopAmbience();
                ambienceCtx = new (window.AudioContext || window.webkitAudioContext)();
                const ctx = ambienceCtx;

                // Pink noise (softer, murmur-like)
                const bufferSize = 2 * ctx.sampleRate;
                const buffer = ctx.createBuffer(1, bufferSize, ctx.sampleRate);
                const data = buffer.getChannelData(0);
                let b0=0, b1=0, b2=0, b3=0, b4=0, b5=0, b6=0;
                for (let i = 0; i < bufferSize; i++) {
                    const white = Math.random() * 2 - 1;
                    b0 = 0.99886 * b0 + white * 0.0555179;
                    b1 = 0.99332 * b1 + white * 0.0750759;
                    b2 = 0.96900 * b2 + white * 0.1538520;
                    b3 = 0.86650 * b3 + white * 0.3104856;
                    b4 = 0.55000 * b4 + white * 0.5329522;
                    b5 = -0.7616 * b5 - white * 0.0168980;
                    data[i] = (b0 + b1 + b2 + b3 + b4 + b5 + b6 + white * 0.5362) * 0.11;
                    b6 = white * 0.115926;
                }

                const source = ctx.createBufferSource();
                source.buffer = buffer;
                source.loop = true;

                // Deep Focus murmur filter
                const filter = ctx.createBiquadFilter();
                filter.type = 'lowpass';
                filter.frequency.value = 250; // Very muffled background hum

                const gain = ctx.createGain();
                gain.gain.value = 0.12; // Gentle volume

                source.connect(filter);
                filter.connect(gain);
                gain.connect(ctx.destination);
                source.start();

                ambienceNodes = [source, filter, gain];
            }

            // Load Preferences
            const savedTheme = localStorage.getItem('literia_theme') || 'light';
            const savedFont = localStorage.getItem('literia_font') || 'sans';
            const savedAudio = localStorage.getItem('literia_audio') || 'none';

            function applyTheme(theme) {
                if(theme === 'dark') {
                    document.body.classList.add('reader-night', 'dark');
                } else {
                    document.body.classList.remove('reader-night', 'dark');
                }
                localStorage.setItem('literia_theme', theme);
                updateActiveButtons('theme', theme);
            }

            function applyFont(font) {
                document.body.setAttribute('data-font', font);
                localStorage.setItem('literia_font', font);
                updateActiveButtons('font', font);
            }

            function applyAudio(audio) {
                stopAmbience();
                if (audio === 'rain') playRainAmbience();
                else if (audio === 'cafe') playCafeAmbience();
                localStorage.setItem('literia_audio', audio);
                updateActiveButtons('audio', audio);
            }

            function updateActiveButtons(action, value) {
                zenBtns.forEach(btn => {
                    if (btn.dataset.action === action) {
                        if (btn.dataset.value === value) {
                            btn.classList.add('border-gold-600', 'text-gold-600');
                            btn.classList.remove('border-navy-900/10', 'border-white/10');
                        } else {
                            btn.classList.remove('border-gold-600', 'text-gold-600');
                        }
                    }
                });
            }

            // Init Preferences
            applyTheme(savedTheme);
            applyFont(savedFont);
            updateActiveButtons('audio', savedAudio); // don't auto-play audio on load due to browser policy, just update UI

            themeToggle.addEventListener('click', (e) => {
                e.stopPropagation();
                zenPopover.classList.toggle('active');
            });

            document.addEventListener('click', (e) => {
                if (!zenPopover.contains(e.target) && e.target !== themeToggle) {
                    zenPopover.classList.remove('active');
                }
            });

            zenBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    const action = btn.dataset.action;
                    const val = btn.dataset.value;
                    if (action === 'theme') applyTheme(val);
                    if (action === 'font') applyFont(val);
                    if (action === 'audio') applyAudio(val);
                });
            });

            // Re-apply dark mode to initial pages if needed
            if(savedTheme === 'dark') document.body.classList.add('reader-night', 'dark');

            // ── Audiobook (TTS) ──
            let isPlaying = false;
            let voices = [];
            
            // Preload voices to get access to Google's online AI voices (if in Chrome)
            if (window.speechSynthesis) {
                window.speechSynthesis.onvoiceschanged = () => {
                    voices = window.speechSynthesis.getVoices();
                };
            }

            refs.ttsToggle.addEventListener('click', () => {
                if (!window.speechSynthesis) return alert('Browser Anda tidak mendukung Text-to-Speech.');

                if (isPlaying || window.speechSynthesis.speaking) {
                    window.speechSynthesis.cancel();
                    isPlaying = false;
                    refs.ttsToggle.innerHTML = '<i class="ph-bold ph-speaker-high"></i> Audio';
                    refs.ttsToggle.className = "inline-flex h-9 items-center gap-1 rounded-full border border-navy-900/10 bg-white/70 px-3 text-xs font-bold transition hover:border-gold-600";
                    return;
                }

                const currentPageIdx = flipbook.getCurrentPageIndex();
                let textToRead = '';
                
                // Ambil dari pageCache langsung yang berisi Object {kicker, title, body}
                for (let i = currentPageIdx; i < currentPageIdx + 2; i++) {
                    if (i < totalPages) {
                        const cached = pageCache.get(i);
                        if (cached) {
                            // Extract title and body from the data object
                            const titleText = cached.title ? cached.title + '. ' : '';
                            const bodyText = cached.body || '';
                            
                            // Strip HTML tags jika ada (meski umumnya body hanya teks plain di sini)
                            const temp = document.createElement('div');
                            temp.innerHTML = titleText + bodyText;
                            textToRead += temp.textContent + ' . ';
                        }
                    }
                }

                if (!textToRead.trim()) {
                    alert('Tidak ada teks di halaman ini.');
                    return;
                }

                // Deteksi bahasa sederhana (Inggris vs Indonesia)
                // Cek kata-kata bahasa inggris yang paling sering muncul
                const isEnglish = /\b(the|and|is|to|in|of|that|it|with|as)\b/i.test(textToRead);
                const targetLang = isEnglish ? 'en-US' : 'id-ID';

                const utterance = new SpeechSynthesisUtterance(textToRead);
                utterance.lang = targetLang;
                utterance.rate = 0.9;
                utterance.pitch = 1.0;

                // Coba gunakan suara AI / Google yang lebih natural jika ada
                if (voices.length === 0) voices = window.speechSynthesis.getVoices();
                
                const bestVoice = voices.find(v => v.lang.startsWith(targetLang.split('-')[0]) && (v.name.includes('Google') || v.name.includes('Online') || v.name.includes('Premium')));
                const defaultVoice = voices.find(v => v.lang.startsWith(targetLang.split('-')[0]));
                
                if (bestVoice) {
                    utterance.voice = bestVoice;
                } else if (defaultVoice) {
                    utterance.voice = defaultVoice;
                }

                utterance.onend = () => {
                    isPlaying = false;
                    refs.ttsToggle.innerHTML = '<i class="ph-bold ph-speaker-high"></i> Audio';
                    refs.ttsToggle.className = "inline-flex h-9 items-center gap-1 rounded-full border border-navy-900/10 bg-white/70 px-3 text-xs font-bold transition hover:border-gold-600";
                };

                utterance.onerror = (e) => {
                    console.warn("TTS Error: ", e);
                    isPlaying = false;
                    refs.ttsToggle.innerHTML = '<i class="ph-bold ph-speaker-high"></i> Audio';
                    refs.ttsToggle.className = "inline-flex h-9 items-center gap-1 rounded-full border border-navy-900/10 bg-white/70 px-3 text-xs font-bold transition hover:border-gold-600";
                };

                window.speechSynthesis.speak(utterance);
                isPlaying = true;
                
                refs.ttsToggle.innerHTML = '<i class="ph-bold ph-pause"></i> Pause';
                refs.ttsToggle.className = "inline-flex h-9 items-center gap-1 rounded-full border border-gold-600 bg-gold-500 text-ivory-100 px-3 text-xs font-bold transition hover:bg-[#a68944]";
            });

            // ── Bookmark ──
            function showToast(msg, isError = false) {
                const toast = document.createElement('div');
                toast.textContent = msg;
                toast.style.cssText = `
                    position: fixed; bottom: 24px; left: 50%; transform: translateX(-50%);
                    padding: 10px 20px; border-radius: 999px; font-size: 13px; font-weight: 600;
                    z-index: 9999; animation: fadeInUp .3s ease;
                    background: ${isError ? '#dc2626' : '#002925'};
                    color: #fffefa; box-shadow: 0 8px 20px rgba(0,0,0,.2);
                `;
                document.body.appendChild(toast);
                setTimeout(() => { toast.style.opacity = '0'; toast.style.transition = 'opacity .3s'; }, 2000);
                setTimeout(() => toast.remove(), 2500);
            }

            refs.bookmarkToggle.addEventListener('click', async () => {
                const currentPageIdx = flipbook.getCurrentPageIndex();
                const halaman = currentPageIdx + 1;
                
                // Visual feedback immediately
                refs.bookmarkToggle.innerHTML = '<i class="ph-bold ph-spinner"></i> Menyimpan...';
                refs.bookmarkToggle.disabled = true;
                
                try {
                    const fd = new FormData();
                    fd.append('id_buku', <?= $buku['id_buku'] ?>);
                    fd.append('halaman', halaman);
                    fd.append('catatan', 'Bookmark Halaman ' + halaman);
                    
                    const res = await fetch('<?= base_url('api/bookmark/save') ?>', {
                        method: 'POST',
                        body: fd,
                        credentials: 'same-origin'
                    });
                    
                    if (!res.ok) throw new Error('HTTP ' + res.status);
                    
                    const data = await res.json();
                    if (data.status === 'ok') {
                        refs.bookmarkToggle.innerHTML = '<i class="ph-fill ph-bookmark-simple text-gold-500"></i> Tersimpan!';
                        showToast('✓ Bookmark halaman ' + halaman + ' tersimpan');
                    } else {
                        throw new Error(data.message || 'Gagal menyimpan');
                    }
                } catch(e) {
                    console.error('Error saving bookmark:', e);
                    showToast('✗ Gagal: ' + e.message, true);
                    refs.bookmarkToggle.innerHTML = '<i class="ph-bold ph-bookmark-simple"></i> Tandai';
                } finally {
                    refs.bookmarkToggle.disabled = false;
                    setTimeout(() => {
                        refs.bookmarkToggle.innerHTML = '<i class="ph-bold ph-bookmark-simple"></i> Tandai';
                    }, 3000);
                }
            });

            // ── Log Reading Session ──
            try {
                const fd = new FormData();
                fd.append('id_buku', <?= $buku['id_buku'] ?>);
                fetch('<?= base_url('api/reading-session') ?>', {
                    method: 'POST',
                    body: fd
                });
            } catch(e) {
                console.error(e);
            }

            // Initial state
            updateProgress(0);
            
            // ── Annotations Logic ──
            const annotationToolbar = document.getElementById('annotationToolbar');
            let currentSelection = null;
            let currentRange = null;

            document.addEventListener('selectionchange', () => {
                const selection = window.getSelection();
                if (!selection.rangeCount || selection.isCollapsed) {
                    annotationToolbar.classList.remove('active');
                    return;
                }

                const range = selection.getRangeAt(0);
                
                // Only allow selection inside a page-body
                let container = range.commonAncestorContainer;
                if (container.nodeType === 3) container = container.parentNode;
                
                if (!container.closest('.page-body')) {
                    annotationToolbar.classList.remove('active');
                    return;
                }

                const rect = range.getBoundingClientRect();
                
                annotationToolbar.style.top = `${rect.top + window.scrollY - 45}px`;
                annotationToolbar.style.left = `${rect.left + window.scrollX + (rect.width / 2)}px`;
                annotationToolbar.classList.add('active');
                
                currentSelection = selection.toString();
                currentRange = range.cloneRange();
            });

            document.querySelectorAll('.color-btn').forEach(btn => {
                btn.addEventListener('click', async (e) => {
                    e.stopPropagation();
                    const color = btn.dataset.color;
                    
                    if (currentSelection && currentRange) {
                        // Create mark element
                        const mark = document.createElement('mark');
                        mark.className = color;
                        
                        try {
                            currentRange.surroundContents(mark);
                        } catch (err) {
                            console.warn("Could not highlight across multiple block elements cleanly.", err);
                            alert("Mohon blok teks dalam satu paragraf saja.");
                            annotationToolbar.classList.remove('active');
                            return;
                        }

                        // Save to API
                        const currentPageIdx = flipbook.getCurrentPageIndex();
                        const halaman = currentPageIdx + 1;
                        
                        const fd = new FormData();
                        fd.append('id_buku', <?= $buku['id_buku'] ?>);
                        fd.append('halaman', halaman);
                        fd.append('teks_highlight', currentSelection);
                        fd.append('warna', color);
                        
                        try {
                            await fetch('<?= base_url('api/annotation/save') ?>', { method: 'POST', body: fd });
                        } catch (e) {
                            console.error('Gagal simpan anotasi', e);
                        }

                        // Clear selection
                        window.getSelection().removeAllRanges();
                        annotationToolbar.classList.remove('active');
                    }
                });
            });
        }

        // Boot
        initFlipbook();

        // ══════════════════════════════════════
        // ═══ POMODORO FOCUS TIMER ═══════════
        // ══════════════════════════════════════
        (function() {
            const pomodoroBtn = document.getElementById('pomodoroToggle');
            const pomodoroLabel = document.getElementById('pomodoroLabel');
            let pomodoroInterval = null;
            let pomodoroSeconds = 25 * 60; // 25 minutes
            let pomodoroRunning = false;

            function formatTime(s) {
                const m = Math.floor(s / 60);
                const sec = s % 60;
                return m + ':' + (sec < 10 ? '0' : '') + sec;
            }

            function playBell() {
                try {
                    const ctx = new (window.AudioContext || window.webkitAudioContext)();
                    const osc = ctx.createOscillator();
                    const gain = ctx.createGain();
                    osc.type = 'sine';
                    osc.frequency.value = 528; // Solfeggio frequency for calm
                    gain.gain.setValueAtTime(0.3, ctx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 2);
                    osc.connect(gain);
                    gain.connect(ctx.destination);
                    osc.start();
                    osc.stop(ctx.currentTime + 2);
                } catch(e) {}
            }

            pomodoroBtn.addEventListener('click', () => {
                if (pomodoroRunning) {
                    // Stop
                    clearInterval(pomodoroInterval);
                    pomodoroRunning = false;
                    pomodoroSeconds = 25 * 60;
                    pomodoroLabel.textContent = 'Fokus 25m';
                    pomodoroBtn.classList.remove('!bg-gold-600', '!text-white', '!border-gold-600');
                } else {
                    // Start
                    pomodoroRunning = true;
                    pomodoroBtn.classList.add('!bg-gold-600', '!text-white', '!border-gold-600');
                    pomodoroInterval = setInterval(() => {
                        pomodoroSeconds--;
                        pomodoroLabel.textContent = formatTime(pomodoroSeconds);
                        if (pomodoroSeconds <= 0) {
                            clearInterval(pomodoroInterval);
                            pomodoroRunning = false;
                            pomodoroSeconds = 25 * 60;
                            pomodoroLabel.textContent = 'Selesai! 🎉';
                            playBell();
                            setTimeout(() => {
                                alert('⏰ Waktu fokus 25 menit selesai!\n\nIstirahatkan mata Anda sejenak (aturan 20-20-20):\nLihat objek sejauh 20 kaki (6 meter) selama 20 detik.');
                                pomodoroLabel.textContent = 'Fokus 25m';
                                pomodoroBtn.classList.remove('!bg-gold-600', '!text-white', '!border-gold-600');
                            }, 500);
                        }
                    }, 1000);
                }
            });
        })();

        // ══════════════════════════════════════
        // ═══ AI EXPLAINER (Text Selection) ═══
        // ══════════════════════════════════════
        (function() {
            const tooltip = document.getElementById('selectionTooltip');
            const btnExplain = document.getElementById('btnAiExplain');
            const btnHighlight = document.getElementById('btnHighlight');
            const popup = document.getElementById('aiExplainerPopup');
            const content = document.getElementById('aiExplainerContent');
            let selectedText = '';

            document.addEventListener('mouseup', (e) => {
                const sel = window.getSelection();
                const text = sel.toString().trim();
                if (text.length > 3 && !tooltip.contains(e.target) && !popup.contains(e.target)) {
                    selectedText = text;
                    const range = sel.getRangeAt(0);
                    const rect = range.getBoundingClientRect();
                    tooltip.style.top = (rect.top + window.scrollY - 45) + 'px';
                    tooltip.style.left = (rect.left + window.scrollX + rect.width / 2) + 'px';
                    tooltip.style.transform = 'translateX(-50%)';
                    tooltip.classList.remove('hidden');
                } else if (!tooltip.contains(e.target) && !popup.contains(e.target)) {
                    tooltip.classList.add('hidden');
                }
            });

            btnExplain.addEventListener('click', async () => {
                tooltip.classList.add('hidden');
                popup.classList.remove('hidden');
                content.innerHTML = '<p class="animate-pulse text-navy-900/40">🧠 Menganalisis teks...</p>';

                try {
                    const res = await fetch('<?= base_url('web/ai_explain') ?>', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest'},
                        body: JSON.stringify({ text: selectedText })
                    });
                    const data = await res.json();
                    content.innerHTML = `
                        <div class="mb-3 rounded-xl bg-ivory-200 p-3">
                            <p class="text-[10px] uppercase tracking-wider text-navy-900/40 font-bold mb-1">Teks Asli</p>
                            <p class="text-xs italic text-navy-900/70">"${data.original}"</p>
                        </div>
                        <div class="mb-2">
                            <p class="text-[10px] uppercase tracking-wider text-navy-900/40 font-bold mb-1">Penjelasan</p>
                            <p class="text-sm text-navy-900/80">${data.explanation}</p>
                        </div>
                    `;
                } catch (e) {
                    content.innerHTML = '<p class="text-red-500 text-sm">Gagal menganalisis teks.</p>';
                }
            });

            btnHighlight.addEventListener('click', () => {
                tooltip.classList.add('hidden');
                const sel = window.getSelection();
                if (sel.rangeCount > 0) {
                    try {
                        const range = sel.getRangeAt(0);
                        const mark = document.createElement('mark');
                        mark.className = 'bg-gold-600/20 rounded px-0.5';
                        range.surroundContents(mark);
                    } catch(e) {
                        alert('Mohon blok teks dalam satu paragraf saja.');
                    }
                    sel.removeAllRanges();
                }
            });
        })();

        // ══════════════════════════════════════
        // ═══ OFFLINE DOWNLOAD ════════════════
        // ══════════════════════════════════════
        (function() {
            const offlineBtn = document.getElementById('offlineDownload');
            offlineBtn.addEventListener('click', async () => {
                const originalHTML = offlineBtn.innerHTML;
                offlineBtn.innerHTML = '<i class="ph-bold ph-spinner animate-spin"></i> Menyimpan...';
                offlineBtn.disabled = true;

                try {
                    if ('caches' in window) {
                        const cache = await caches.open('literia-books-offline');
                        // Cache the current page
                        await cache.add(window.location.href);
                        // Cache the cover image if available
                        const coverImg = document.querySelector('img[src*="cover"]');
                        if (coverImg) await cache.add(coverImg.src).catch(()=>{});

                        offlineBtn.innerHTML = '<i class="ph-bold ph-check-circle"></i> Tersimpan!';
                        offlineBtn.classList.remove('bg-emerald-50', 'text-emerald-800', 'border-emerald-600/20');
                        offlineBtn.classList.add('bg-emerald-600', 'text-white', 'border-emerald-600');
                        
                        setTimeout(() => {
                            offlineBtn.innerHTML = '<i class="ph-bold ph-cloud-check"></i> Offline ✓';
                            offlineBtn.disabled = false;
                        }, 2000);
                    } else {
                        alert('Browser Anda tidak mendukung fitur offline.');
                        offlineBtn.innerHTML = originalHTML;
                        offlineBtn.disabled = false;
                    }
                } catch (e) {
                    alert('Gagal menyimpan untuk offline: ' + e.message);
                    offlineBtn.innerHTML = originalHTML;
                    offlineBtn.disabled = false;
                }
            });

            // Check if already cached
            if ('caches' in window) {
                caches.open('literia-books-offline').then(cache => {
                    cache.match(window.location.href).then(response => {
                        if (response) {
                            offlineBtn.innerHTML = '<i class="ph-bold ph-cloud-check"></i> Offline ✓';
                            offlineBtn.classList.remove('bg-emerald-50', 'text-emerald-800');
                            offlineBtn.classList.add('bg-emerald-100', 'text-emerald-700');
                        }
                    });
                });
            }
        })();

        // PWA Service Worker
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js').catch(err => console.log('SW failed', err));
            });
        }
    </script>
</body>
</html>
