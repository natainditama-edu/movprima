<?= $this->extend("layouts/main") ?>

<?= $this->section("title") ?>
Ubah Profil
<?= $this->endSection() ?>

<?= $this->section("content") ?>

<div class="py-4"></div>

<?php if (!isset($user) || empty($user)): ?>
    <div class="flex-container py-12 text-center">
        <i data-lucide="user-x" class="w-16 h-16 mx-auto mb-4 text-(--text-muted)"></i>
        <h2 class="text-2xl text-white">Profil tidak ditemukan.</h2>
        <p class="text-(--text-secondary) mb-6">Sesi tidak valid atau user tidak ada.</p>
        <a href="/auth/login" class="bttn primary inline-flex">Login Ulang</a>
    </div>
<?php else: ?>
    <div class="flex-container py-12">
      <div class="max-w-lg mx-auto bg-(--bg-elevated) border border-(--border) rounded-xl p-8">
          <h1 class="h1 uppercase text-white mb-6 text-center">UBAH PROFIL</h1>
          <form method="post" action="/profile/edit" class="flex flex-col gap-5" onsubmit="this.querySelector('button[type=submit]').disabled=true; this.querySelector('button[type=submit]').innerHTML='MENYIMPAN...';">
              <?= csrf_field() ?>

              <!-- Nama -->
              <div>
                  <label class="block text-(--text-secondary) mb-1">Nama Lengkap</label>
                  <input type="text" name="name" value="<?= esc(old("name", $user["name"] ?? "")) ?>" class="text w-full <?= session("errors.name") ? "border-red-500" : "" ?>" required>
                  <?php if (session("errors.name")): ?>
                  <span class="text-red-400 text-xs mt-1 block"><?= esc(session("errors.name")) ?></span>
                  <?php endif; ?>
              </div>

              <!-- Email -->
              <div>
                  <label class="block text-(--text-secondary) mb-1">Email</label>
                  <input type="email" name="email" value="<?= esc(old("email", $user["email"] ?? "")) ?>" class="text w-full <?= session("errors.email") ? "border-red-500" : "" ?>" required>
                  <?php if (session("errors.email")): ?>
                  <span class="text-red-400 text-xs mt-1 block"><?= esc(session("errors.email")) ?></span>
                  <?php endif; ?>
              </div>

              <div class="hr-75 my-4 mt-10!"><span>KATA SANDI BARI (OPSIONAL)</span></div>

              <!-- Kata sandi baru -->
              <div>
                  <label class="block text-(--text-secondary) mb-1">Kata Sandi Baru</label>
                  <div class="passwordeye relative">
                      <input type="password" name="password" placeholder="Kosongkan jika tidak ingin ganti" class="text w-full pr-10 <?= session("errors.password") ? "border-red-500" : "" ?>">
                  </div>
                  <?php if (session("errors.password")): ?>
                  <span class="text-red-400 text-xs mt-1 block"><?= esc(session("errors.password")) ?></span>
                  <?php endif; ?>
              </div>

              <div class="flex gap-3 mt-6">
                  <button type="submit" class="bttn colorbttn flex-1 border-0">SIMPAN PERUBAHAN</button>
                  <a href="/profile" class="bttn secondary">BATAL</a>
              </div>
          </form>
      </div>
    </div>
<?php endif; ?>

<div class="py-6"></div>

<?= $this->endSection() ?>
