<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pembaca | LITERIA</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Lora:wght@500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'], serif: ['Lora', 'serif'] },
                    colors: {
                        ivory: { 100: '#fffefa', 200: '#f7f1e3', 300: '#eee6d2' },
                        navy: { 900: '#002925', 950: '#071c2f' },
                        gold: { 500: '#ac554c', 600: '#c5a556' }
                    }
                }
            }
        }
    </script>
    <style>body{background:#f7f1e3;color:#002925;font-family:Inter,sans-serif;}</style>
</head>
<body class="min-h-screen antialiased">
    <main class="mx-auto grid min-h-screen max-w-7xl gap-8 px-4 py-10 lg:grid-cols-[0.85fr_1.15fr] lg:px-8">
        <section class="flex flex-col justify-between rounded-[1.75rem] bg-navy-900 p-8 text-ivory-100">
            <a href="<?= base_url() ?>" class="font-bold tracking-[0.14em]">LITERIA</a>
            <div class="py-12">
                <p class="mb-4 text-xs font-semibold uppercase tracking-[0.16em] text-ivory-100/45">Keanggotaan pembaca</p>
                <h1 class="font-serif text-5xl font-semibold leading-tight">Bangun rak baca pribadi sejak buku pertama.</h1>
                <p class="mt-6 leading-7 text-ivory-100/60">Akun pembaca menyimpan wishlist, progress baca, dan akses ke ruang baca 3D.</p>
            </div>
            <div class="rounded-2xl border border-white/10 bg-white/5 p-5">
                <p class="text-sm text-ivory-100/55">Setelah daftar, Anda masuk sebagai anggota Free. Tier premium bisa dikembangkan nanti untuk koleksi eksklusif.</p>
            </div>
        </section>

        <section class="flex items-center">
            <div class="w-full rounded-[1.75rem] border border-navy-900/10 bg-ivory-100 p-6 shadow-[0_20px_60px_rgba(0,41,37,0.12)] md:p-8">
                <a href="<?= base_url('auth/login') ?>" class="mb-8 inline-flex items-center gap-2 text-sm font-semibold text-navy-900/60 hover:text-navy-900">
                    <i class="ph-bold ph-arrow-left"></i>
                    Sudah punya akun
                </a>
                <p class="mb-3 text-xs font-semibold uppercase tracking-[0.16em] text-gold-500">Daftar</p>
                <h2 class="font-serif text-4xl font-semibold text-navy-900">Buat akun pembaca.</h2>

                <?php if(session()->getFlashdata('error')): ?>
                    <div class="mt-6 rounded-2xl border border-rose-700/15 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-800">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('auth/processRegister') ?>" method="POST" class="mt-7 grid gap-4 md:grid-cols-2">
                    <?= csrf_field() ?>
                    <label class="block">
                        <span class="text-sm font-semibold text-navy-900">Nama lengkap</span>
                        <input type="text" name="nama" class="mt-2 w-full rounded-2xl border border-navy-900/10 bg-white px-4 py-3 text-navy-900 outline-none focus:border-gold-600 focus:ring-4 focus:ring-gold-600/15" required>
                    </label>
                    <label class="block">
                        <span class="text-sm font-semibold text-navy-900">Username</span>
                        <input type="text" name="username" class="mt-2 w-full rounded-2xl border border-navy-900/10 bg-white px-4 py-3 text-navy-900 outline-none focus:border-gold-600 focus:ring-4 focus:ring-gold-600/15" required pattern="[A-Za-z0-9_]+">
                    </label>
                    <label class="block">
                        <span class="text-sm font-semibold text-navy-900">Nomor induk</span>
                        <input type="text" name="nomor_induk" class="mt-2 w-full rounded-2xl border border-navy-900/10 bg-white px-4 py-3 text-navy-900 outline-none focus:border-gold-600 focus:ring-4 focus:ring-gold-600/15" required>
                    </label>
                    <label class="block">
                        <span class="text-sm font-semibold text-navy-900">No. telepon</span>
                        <input type="text" name="no_telp" class="mt-2 w-full rounded-2xl border border-navy-900/10 bg-white px-4 py-3 text-navy-900 outline-none focus:border-gold-600 focus:ring-4 focus:ring-gold-600/15">
                    </label>
                    <label class="block md:col-span-2">
                        <span class="text-sm font-semibold text-navy-900">Kata sandi</span>
                        <input type="password" name="password" class="mt-2 w-full rounded-2xl border border-navy-900/10 bg-white px-4 py-3 text-navy-900 outline-none focus:border-gold-600 focus:ring-4 focus:ring-gold-600/15" required minlength="6">
                    </label>
                    <label class="block md:col-span-2">
                        <span class="text-sm font-semibold text-navy-900">Alamat</span>
                        <textarea name="alamat" rows="3" class="mt-2 w-full rounded-2xl border border-navy-900/10 bg-white px-4 py-3 text-navy-900 outline-none focus:border-gold-600 focus:ring-4 focus:ring-gold-600/15"></textarea>
                    </label>
                    <button type="submit" class="mt-2 inline-flex items-center justify-center gap-3 rounded-full bg-navy-900 px-6 py-3 text-sm font-bold text-ivory-100 transition hover:bg-[#103c37] active:scale-[0.98] md:col-span-2">
                        Buat akun pembaca
                        <span class="grid h-8 w-8 place-items-center rounded-full bg-white/10"><i class="ph-bold ph-arrow-right"></i></span>
                    </button>
                </form>
            </div>
        </section>
    </main>
</body>
</html>
