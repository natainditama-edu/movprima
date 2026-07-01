<?= $this->extend("layouts/main") ?>

<?= $this->section("title") ?>
Daftar
<?= $this->endSection() ?>

<?= $this->section("meta_description") ?>
Daftar dan buat akun MovPrima sekarang. Bergabunglah dengan komunitas pecinta film untuk memberikan rating, menulis ulasan, dan menemukan film terbaik.
<?= $this->endSection() ?>

<?= $this->section("content") ?>
<h1 style="position:absolute; width:1px; height:1px; padding:0; overflow:hidden; clip:rect(0,0,0,0); white-space:nowrap; border:0;">Daftar Akun MovPrima</h1>

<div class="flex-container">
    <div class="page">
        <div class="pageLoginSignin">
            <h2 class="title center">Buat Akun Baru</h2>

            <?php if (session()->getFlashdata("errors")): ?>
            <div class="p-3 mb-4 rounded bg-red-900/50 border border-red-500 text-red-100 text-sm">
                <ul class="list-disc pl-5">
                <?php foreach (session()->getFlashdata("errors") ?? [] as $err): ?>
                    <li><?= esc($err ?? "Terjadi kesalahan") ?></li>
                <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>

            <form action="/auth/register" method="POST" class="w-full">
                <?= csrf_field() ?>

                <label>Nama Lengkap</label>
                <input class="text w-full mb-4" type="text" name="name" value="<?= old("name") ?? "" ?>" placeholder="Masukkan nama lengkap Anda..." required />

                <label>Alamat Email</label>
                <input class="text w-full mb-4" type="email" name="email" value="<?= old("email") ?? "" ?>" placeholder="Masukkan email Anda..." required />

                <label>Kata Sandi</label>
                <input class="text w-full mb-4" type="password" name="password" placeholder="Masukkan kata sandi..." required />

                <label>Konfirmasi Kata Sandi</label>
                <input class="text w-full mb-4" type="password" name="password_confirm" placeholder="Ulangi kata sandi..." required />

                <div class="row" style="margin-bottom: 20px;">
                    <label class="row" style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="checkbox" class="normal" required />
                        <p style="margin: 0;">Saya setuju dengan <a href="#" class="maincolor">Syarat &amp; Ketentuan</a></p>
                    </label>
                </div>

                <button type="submit" class="bttn colorbttn w-full border-0">DAFTAR SEKARANG</button>
            </form>

            <div class="mt-8 text-center" style="color: var(--text-secondary);">
                Sudah punya akun? <a href="/auth/login" class="maincolor hover:underline">Masuk Sekarang</a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
