<?php if(empty($buku)): ?>
    <div class="rounded-2xl border border-navy-900/10 bg-white/70 p-12 text-center">
        <p class="font-serif text-2xl text-navy-900">Koleksi tidak ditemukan.</p>
        <p class="mt-2 text-sm text-navy-900/60">Coba judul, penulis, atau kategori lain.</p>
    </div>
<?php else: ?>
    <div class="grid grid-cols-1 gap-5 md:grid-cols-12">
        <?php foreach($buku as $b): ?>
            <article class="group col-span-1 rounded-2xl border border-navy-900/10 bg-ivory-100 p-5 shadow-sm transition-all duration-500 ease-out hover:-translate-y-1.5 hover:border-gold-500/40 hover:shadow-[0_15px_40px_rgba(0,41,37,0.08)] md:col-span-6 xl:col-span-4">
                <div class="grid grid-cols-[9rem_1fr] gap-5 md:gap-6">
                    <!-- Book Cover with Spine and Premium Hover Effect -->
                    <a href="<?= base_url('web/buku/'.$b['id_buku']) ?>" class="relative block overflow-hidden rounded-l-sm rounded-r-md border border-navy-900/10 bg-ivory-200 shadow-[4px_0_10px_rgba(0,0,0,0.05)] transition-all duration-500 ease-[cubic-bezier(0.23,1,0.32,1)] group-hover:-translate-y-2 group-hover:-rotate-1 group-hover:scale-[1.02] group-hover:shadow-[12px_15px_25px_rgba(0,0,0,0.15)]">
                        <!-- Spine shadow simulation -->
                        <div class="absolute inset-y-0 left-0 z-10 w-2 bg-gradient-to-r from-black/25 via-black/5 to-transparent mix-blend-multiply"></div>
                        <!-- Glare effect on hover -->
                        <div class="absolute inset-0 z-10 bg-gradient-to-tr from-transparent via-white/10 to-white/30 opacity-0 transition-opacity duration-500 group-hover:opacity-100"></div>
                        
                        <?php if(!empty($b['cover_url'])): ?>
                            <img src="<?= esc($b['cover_url']) ?>" alt="Sampul <?= esc($b['judul']) ?>" class="aspect-[2/3] h-full w-full object-cover transition-transform duration-700 ease-out group-hover:scale-105" loading="lazy">
                        <?php else: ?>
                            <div class="flex aspect-[2/3] h-full w-full items-center justify-center bg-navy-900 text-ivory-100">
                                <i class="ph-light ph-book-open text-4xl"></i>
                            </div>
                        <?php endif; ?>
                    </a>

                    <div class="flex min-w-0 flex-col">
                        <div class="mb-3 flex items-start justify-between gap-3">
                            <span class="rounded-full border border-navy-900/10 bg-ivory-200 px-3 py-1 text-[11px] font-semibold text-navy-900/70"><?= esc($b['kategori'] ?? 'Umum') ?></span>
                            <?php if(($b['read_access'] ?? 'metadata') === 'public_domain'): ?>
                                <span class="rounded-full border border-gold-600/25 bg-gold-600/15 px-3 py-1 text-[11px] font-semibold text-navy-900">Bisa dibaca</span>
                            <?php elseif((int) $b['stok'] > 0): ?>
                                <span class="rounded-full border border-emerald-700/15 bg-emerald-50 px-3 py-1 text-[11px] font-semibold text-emerald-800">Katalog</span>
                            <?php else: ?>
                                <span class="rounded-full border border-rose-700/15 bg-rose-50 px-3 py-1 text-[11px] font-semibold text-rose-800">Dipinjam</span>
                            <?php endif; ?>
                        </div>

                        <a href="<?= base_url('web/buku/'.$b['id_buku']) ?>" class="font-serif text-xl leading-snug text-navy-900 transition group-hover:text-[#8a6a12]">
                            <?= esc($b['judul']) ?>
                        </a>
                        <p class="mt-1 text-sm font-medium text-navy-900/65"><?= esc($b['pengarang']) ?></p>

                        <dl class="mt-4 grid grid-cols-2 gap-x-2 gap-y-3 text-xs text-navy-900/60 xl:grid-cols-2">
                            <div class="min-w-0">
                                <dt class="font-semibold text-navy-900">Penerbit</dt>
                                <dd class="truncate" title="<?= esc($b['penerbit']) ?>"><?= esc($b['penerbit']) ?></dd>
                            </div>
                            <div class="min-w-0">
                                <dt class="font-semibold text-navy-900">Tahun</dt>
                                <dd class="truncate"><?= esc($b['tahun_terbit']) ?></dd>
                            </div>
                            <div class="min-w-0">
                                <dt class="font-semibold text-navy-900">Halaman</dt>
                                <dd class="truncate"><?= esc($b['jumlah_halaman']) ?></dd>
                            </div>
                            <div class="min-w-0">
                                <dt class="font-semibold text-navy-900">Rating</dt>
                                <dd class="flex items-center gap-1 truncate"><i class="ph-fill ph-star text-gold-500 shrink-0"></i><?= esc($b['rating']) ?></dd>
                            </div>
                        </dl>

                        <div class="mt-auto flex items-end justify-between gap-3 pt-5">
                            <span class="truncate text-[11px] text-navy-900/45"><?= ($b['read_access'] ?? 'metadata') === 'public_domain' ? esc($b['source_name'] ?? 'Public domain') : 'ISBN '.esc($b['isbn'] ?? '-') ?></span>
                            <a href="<?= base_url('web/buku/'.$b['id_buku']) ?>" class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-navy-900 text-ivory-100 transition duration-200 hover:bg-gold-500 hover:text-navy-900" aria-label="Lihat detail <?= esc($b['judul']) ?>">
                                <i class="ph-bold ph-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
