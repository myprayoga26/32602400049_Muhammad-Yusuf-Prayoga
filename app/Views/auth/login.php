<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk | LITERIA</title>

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
    <main class="grid min-h-screen lg:grid-cols-[1fr_32rem]">
        <section class="relative hidden overflow-hidden bg-navy-900 p-10 text-ivory-100 lg:block">
            <!-- Background Image -->
            <img src="<?= base_url('assets/images/cinematic_library.png') ?>" alt="Library Background" class="absolute inset-0 h-full w-full object-cover">
            <!-- Gradient Overlay -->
            <div class="absolute inset-0 bg-gradient-to-br from-navy-900/95 via-navy-900/80 to-navy-900/40"></div>

            <div class="relative z-10 flex h-full flex-col justify-between">
                <a href="<?= base_url() ?>" class="font-bold tracking-[0.14em]">LITERIA</a>
                <div>
                    <p class="mb-4 text-xs font-semibold uppercase tracking-[0.16em] text-ivory-100/60 drop-shadow-md">Pengetahuan, Terstruktur.</p>
                    <h1 class="max-w-2xl font-serif text-6xl font-semibold leading-tight drop-shadow-lg">Masuk ke ruang baca pribadi Anda.</h1>
                    <p class="mt-6 max-w-xl leading-7 text-ivory-100/80 drop-shadow-md">Admin mengelola koleksi. Pembaca menyimpan rak, progress, dan membaca lewat reading room.</p>
                </div>
                <div class="grid grid-cols-3 gap-4 text-sm">
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-5 backdrop-blur-sm"><strong class="block text-3xl text-gold-500 drop-shadow">12</strong><span class="mt-1 block text-ivory-100/70">Buku nyata</span></div>
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-5 backdrop-blur-sm"><strong class="block text-3xl text-gold-500 drop-shadow">9</strong><span class="mt-1 block text-ivory-100/70">Kategori</span></div>
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-5 backdrop-blur-sm"><strong class="block text-3xl text-gold-500 drop-shadow">3D</strong><span class="mt-1 block text-ivory-100/70">Reader</span></div>
                </div>
            </div>
        </section>

        <section class="flex items-center justify-center px-4 py-10">
            <div class="w-full max-w-md rounded-[1.75rem] border border-navy-900/10 bg-ivory-100 p-6 shadow-[0_20px_60px_rgba(0,41,37,0.12)] md:p-8">
                <a href="<?= base_url() ?>" class="mb-8 inline-flex items-center gap-2 text-sm font-semibold text-navy-900/60 hover:text-navy-900">
                    <i class="ph-bold ph-arrow-left"></i>
                    Kembali ke katalog
                </a>

                <p class="mb-3 text-xs font-semibold uppercase tracking-[0.16em] text-gold-500">Masuk</p>
                <h2 class="font-serif text-4xl font-semibold text-navy-900">Selamat datang.</h2>
                <p class="mt-3 text-sm leading-6 text-navy-900/60">Gunakan akun admin atau akun pembaca. Demo pembaca: <strong>pembaca</strong> / <strong>password</strong>.</p>

                <?php if(session()->getFlashdata('error')): ?>
                    <div class="mt-6 rounded-2xl border border-rose-700/15 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-800">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <?php if(session()->getFlashdata('success')): ?>
                    <div class="mt-6 rounded-2xl border border-emerald-700/15 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                        <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('auth/process') ?>" method="POST" class="mt-7 space-y-4">
                    <?= csrf_field() ?>
                    <label class="block">
                        <span class="text-sm font-semibold text-navy-900">Username</span>
                        <input type="text" name="username" class="mt-2 w-full rounded-2xl border border-navy-900/10 bg-white px-4 py-3 text-navy-900 outline-none transition focus:border-gold-600 focus:ring-4 focus:ring-gold-600/15" placeholder="pembaca" required>
                    </label>
                    <label class="block">
                        <span class="text-sm font-semibold text-navy-900">Kata sandi</span>
                        <input type="password" name="password" class="mt-2 w-full rounded-2xl border border-navy-900/10 bg-white px-4 py-3 text-navy-900 outline-none transition focus:border-gold-600 focus:ring-4 focus:ring-gold-600/15" placeholder="password" required>
                    </label>
                    <button type="submit" class="inline-flex w-full items-center justify-center gap-3 rounded-full bg-navy-900 px-6 py-3 text-sm font-bold text-ivory-100 transition hover:bg-[#103c37] active:scale-[0.98]">
                        Masuk
                        <span class="grid h-8 w-8 place-items-center rounded-full bg-white/10"><i class="ph-bold ph-arrow-right"></i></span>
                    </button>
                </form>

                <p class="mt-6 text-center text-sm text-navy-900/60">Belum punya akun? <a href="<?= base_url('auth/register') ?>" class="font-bold text-navy-900 underline decoration-gold-600 underline-offset-4">Daftar sebagai pembaca</a></p>
            </div>
        </section>
    </main>
</body>
</html>
