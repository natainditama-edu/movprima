<?= $this->extend("layouts/main") ?>

<?= $this->section("title") ?>
Masuk
<?= $this->endSection() ?>

<?= $this->section("content") ?>
<div class="flex-container">
    <div class="page">
        <div class="pageLoginSignin">
            <h2 class="title center">Masuk ke Akun Anda</h2>

            <form action="/auth/login" method="POST" class="w-full">
                <?= csrf_field() ?>

                <label>Alamat Email</label>
                <input class="text w-full mb-4" type="email" name="email" value="<?= old("email") ?? "" ?>" placeholder="Masukkan email Anda..." required />

                <label>Kata Sandi</label>
                <input class="text w-full mb-4" type="password" name="password" placeholder="Masukkan kata sandi..." required />

                <div class="row" style="margin-bottom: 20px;">
                    <label class="row" style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="checkbox" class="normal" name="remember" />
                        <p style="margin: 0;">Ingat Saya</p>
                    </label>
                    <a class="trouble" href="#">Lupa kata sandi?</a>
                </div>

                <button type="submit" class="bttn colorbttn w-full border-0">MASUK</button>
            </form>

            <div class="mt-8 text-center" style="color: var(--text-secondary);">
                Belum punya akun? <a href="/auth/register" class="maincolor hover:underline">Daftar Sekarang</a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
