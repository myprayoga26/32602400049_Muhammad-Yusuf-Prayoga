<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rak Pribadi | LITERIA</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Lora:wght@500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://cdn.tailwindcss.com"></script>
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
        body { background: #f7f1e3; color: #002925; font-family: Inter, sans-serif; }
    </style>
</head>
<body class="antialiased">
    <?php
        $statusLabels = [
            '' => 'Semua',
            'reading' => 'Sedang dibaca',
            'wishlist' => 'Wishlist',
            'finished' => 'Selesai',
        ];
        $counts = ['reading' => 0, 'wishlist' => 0, 'finished' => 0];
        foreach (($shelf ?? []) as $item) {
            if (isset($counts[$item['status_baca']])) {
                $counts[$item['status_baca']]++;
            }
        }
    ?>

    <header class="border-b border-navy-900/10 bg-ivory-100">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 md:px-8">
            <a href="<?= base_url() ?>" class="inline-flex items-center gap-2 rounded-full border border-navy-900/10 bg-white px-4 py-2 text-sm font-semibold text-navy-900 transition hover:border-gold-600">
                <i class="ph-bold ph-arrow-left"></i>
                Kembali ke katalog
            </a>
            <span class="font-bold tracking-[0.14em] text-navy-900">LITERIA</span>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-10 md:px-8 md:py-14">
        <section class="mb-8 grid gap-6 md:grid-cols-[1fr_22rem] md:items-end">
            <div>
                <p class="mb-3 text-xs font-semibold uppercase tracking-[0.16em] text-gold-500">Private shelf</p>
                <h1 class="font-serif text-5xl font-semibold text-navy-900">Rak Pribadi</h1>
                <p class="mt-4 max-w-2xl text-base leading-7 text-navy-900/65">Kelola buku yang ingin dibaca, sedang dibaca, dan selesai tanpa meninggalkan katalog utama.</p>
            </div>

            <div class="rounded-2xl border border-navy-900/10 bg-ivory-100 p-4 shadow-[0_14px_42px_rgba(0,41,37,0.08)]">
                <div class="grid grid-cols-3 gap-2 text-center">
                    <div class="rounded-xl bg-white p-3">
                        <p class="text-2xl font-semibold text-navy-900"><?= esc($counts['reading']) ?></p>
                        <p class="text-xs text-navy-900/50">Dibaca</p>
                    </div>
                    <div class="rounded-xl bg-white p-3">
                        <p class="text-2xl font-semibold text-navy-900"><?= esc($counts['wishlist']) ?></p>
                        <p class="text-xs text-navy-900/50">Wishlist</p>
                    </div>
                    <div class="rounded-xl bg-white p-3">
                        <p class="text-2xl font-semibold text-navy-900"><?= esc($counts['finished']) ?></p>
                        <p class="text-xs text-navy-900/50">Selesai</p>
                    </div>
                </div>
            </div>
        </section>

        <?php if(session()->getFlashdata('success')): ?>
            <div class="mb-6 rounded-2xl border border-emerald-700/15 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php elseif(session()->getFlashdata('error')): ?>
            <div class="mb-6 rounded-2xl border border-rose-700/15 bg-rose-50 px-5 py-4 text-sm font-medium text-rose-800">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <nav class="mb-8 flex gap-2 overflow-x-auto pb-2">
            <?php foreach($statusLabels as $value => $label): ?>
                <a href="<?= base_url('web/shelf'.($value ? '?status='.$value : '')) ?>" class="shrink-0 rounded-full border px-4 py-2 text-sm font-semibold transition <?= ($active_status ?? '') === $value ? 'border-navy-900 bg-navy-900 text-ivory-100' : 'border-navy-900/15 bg-white text-navy-900/70 hover:border-gold-600 hover:text-navy-900' ?>">
                    <?= esc($label) ?>
                </a>
            <?php endforeach; ?>
        </nav>

        <section class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
            <?php if(empty($shelf)): ?>
                <div class="col-span-full rounded-[1.75rem] border border-navy-900/10 bg-ivory-100 p-12 text-center shadow-[0_16px_44px_rgba(0,41,37,0.08)]">
                    <i class="ph-light ph-books mb-4 block text-6xl text-navy-900/20"></i>
                    <h2 class="font-serif text-3xl text-navy-900">Rak ini masih kosong.</h2>
                    <p class="mx-auto mt-3 max-w-xl text-sm leading-6 text-navy-900/60">Pilih buku dari katalog, lalu simpan sebagai wishlist atau mulai membaca.</p>
                    <a href="<?= base_url('#katalog') ?>" class="mt-7 inline-flex items-center gap-2 rounded-full bg-navy-900 px-6 py-3 text-sm font-bold text-ivory-100 transition hover:bg-[#103c37]">
                        Jelajahi katalog <i class="ph-bold ph-arrow-right"></i>
                    </a>
                </div>
            <?php else: ?>
                <?php foreach($shelf as $item): ?>
                    <article class="rounded-[1.5rem] border border-navy-900/10 bg-ivory-100 p-4 shadow-[0_14px_42px_rgba(0,41,37,0.08)]">
                        <div class="grid grid-cols-[6.5rem_1fr] gap-4">
                            <a href="<?= base_url('web/buku/'.$item['id_buku']) ?>" class="overflow-hidden rounded-xl border border-navy-900/10 bg-white">
                                <?php if(!empty($item['cover_url'])): ?>
                                    <img src="<?= esc($item['cover_url']) ?>" alt="Sampul <?= esc($item['judul']) ?>" class="aspect-[2/3] h-full w-full object-cover">
                                <?php else: ?>
                                    <div class="aspect-[2/3] grid place-items-center bg-navy-900 text-ivory-100">
                                        <i class="ph-light ph-book-open text-4xl"></i>
                                    </div>
                                <?php endif; ?>
                            </a>

                            <div class="min-w-0">
                                <span class="rounded-full bg-white px-3 py-1 text-[11px] font-semibold text-navy-900/60">
                                    <?= $item['status_baca'] === 'reading' ? 'Sedang dibaca' : ($item['status_baca'] === 'finished' ? 'Selesai' : 'Wishlist') ?>
                                </span>
                                <h2 class="mt-3 line-clamp-2 font-serif text-xl leading-snug text-navy-900"><?= esc($item['judul']) ?></h2>
                                <p class="mt-1 truncate text-sm text-navy-900/60"><?= esc($item['pengarang']) ?></p>

                                <div class="mt-4">
                                    <div class="mb-2 flex items-center justify-between text-xs text-navy-900/55">
                                        <span>Progress</span>
                                        <span><?= esc($item['progress_persen']) ?>%</span>
                                    </div>
                                    <div class="h-2 overflow-hidden rounded-full bg-white">
                                        <div class="h-full rounded-full bg-gold-600" style="width: <?= esc($item['progress_persen']) ?>%"></div>
                                    </div>
                                </div>

                                <form action="<?= base_url('web/shelf/update/'.$item['id_rak']) ?>" method="post" class="mt-4 grid grid-cols-[1fr_auto] gap-2">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="status_baca" value="<?= esc($item['status_baca']) ?>">
                                    <input type="hidden" name="halaman_terakhir" value="<?= esc($item['halaman_terakhir'] ?? 0) ?>">
                                    <input type="number" min="0" max="100" name="progress_persen" value="<?= esc($item['progress_persen']) ?>" class="min-w-0 rounded-full border border-navy-900/10 bg-white px-4 py-2 text-sm text-navy-900 outline-none focus:border-gold-600 focus:ring-4 focus:ring-gold-600/15" aria-label="Progress membaca">
                                    <button type="submit" class="rounded-full bg-navy-900 px-4 py-2 text-sm font-bold text-ivory-100 transition hover:bg-[#103c37]">Simpan</button>
                                </form>

                                <div class="mt-4 flex items-center justify-between gap-3">
                                    <a href="<?= base_url('web/buku/'.$item['id_buku']) ?>" class="text-xs font-bold text-navy-900 underline decoration-gold-600 underline-offset-4">Detail</a>
                                    <?php if($item['status_baca'] !== 'finished'): ?>
                                        <form action="<?= base_url('web/shelf/update/'.$item['id_rak']) ?>" method="post">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="status_baca" value="finished">
                                            <input type="hidden" name="progress_persen" value="100">
                                            <input type="hidden" name="halaman_terakhir" value="<?= esc($item['halaman_terakhir'] ?? 0) ?>">
                                            <button type="submit" class="text-xs font-bold text-gold-500 hover:text-navy-900">Tandai selesai</button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
