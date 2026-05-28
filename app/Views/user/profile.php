<?= $this->extend("layouts/main") ?>

<?= $this->section("title") ?>
Profil Saya
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
      <div class="bg-(--bg-elevated) border border-(--border) rounded-xl p-8 mb-8 flex flex-col md:flex-row items-center md:items-start gap-8">
          <!-- Avatar -->
          <div class="shrink-0">
              <div class="w-32 h-32 rounded-full border-4 border-(--primary) overflow-hidden shadow-[0_0_20px_var(--primary-glow)]">
                  <?php $src = "https://i.pravatar.cc/150?u=" . ($user["id"] ?? ""); ?>
                  <img src="<?= $src ?>" alt="<?= esc($user["name"] ?? "Pengguna") ?>" class="w-full h-full object-cover">
              </div>
          </div>

        <!-- Info -->
        <div class="flex-1 text-center md:text-left">
            <h1 class="h1 uppercase text-white mb-1"><?= esc($user["name"] ?? "Pengguna") ?></h1>
            <p class="text-(--text-secondary) mb-4"><?= esc($user["email"] ?? "email@example.com") ?></p>

            <div class="flex flex-wrap justify-center md:justify-start gap-3 mb-6">
                <span class="px-3 py-1 bg-(--bg-card) border border-(--border) rounded text-sm text-(--text-primary) font-semibold">
                    <?= $reviewCount ?? 0 ?> Ulasan
                </span>
                <span class="px-3 py-1 bg-(--bg-card) border border-(--border) rounded text-sm text-(--text-primary) font-semibold capitalize">
                    <?= esc(($user["role"] ?? "") === "admin" ? "Administrator" : "Pengguna") ?>
                </span>
            </div>

            <a href="/profile/edit" class="bttn secondary inline-flex">
                <i data-lucide="edit" class="w-4 h-4 mr-2"></i> UBAH PROFIL
            </a>
        </div>
      </div>

      <!-- Watchlist -->
      <div class="mb-12">
          <div class="flex items-center justify-between mb-6 border-b border-(--border) pb-2">
              <h2 class="h2 text-white">DAFTAR SAYA (WATCHLIST)</h2>
          </div>

          <?php if (empty($watchlist)): ?>
          <div class="bg-(--bg-elevated) border border-(--border) rounded-lg p-12 text-center">
              <i data-lucide="bookmark" class="w-16 h-16 mx-auto mb-4 text-(--text-muted)"></i>
              <p class="text-(--text-secondary) mb-4">Daftar tontonanmu masih kosong.</p>
              <a href="/movies" class="bttn primary inline-flex">CARI FILM</a>
          </div>
          <?php else: ?>
          <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
              <?php foreach ($watchlist ?? [] as $item): ?>
              <div class="bg-(--bg-elevated) border border-(--border) rounded-lg overflow-hidden group relative">
                  <a href="/movies/<?= esc($item["movie_slug"] ?? "") ?>" class="block relative aspect-2/3 overflow-hidden">
                      <img src="<?= $item["movie_poster"] ?? "" ? esc($item["movie_poster"] ?? null) : "https://placehold.co/600x900/1a1a24/606078?font=oswald&text=Not+Found" ?>" alt="<?= esc($item["movie_title"] ?? "Judul Film") ?>" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                      <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                          <i data-lucide="play-circle" class="w-12 h-12 text-white"></i>
                      </div>
                  </a>
                  <div class="p-3 bg-(--bg-card) border-t border-(--border)">
                      <h3 class="font-bold text-white text-sm line-clamp-1 mb-2" title="<?= esc($item["movie_title"] ?? "Judul Film") ?>"><?= esc($item["movie_title"] ?? "Judul Film") ?></h3>
                      <div class="flex justify-between items-center">
                          <form action="/watchlist/<?= $item["id"] ?? "" ?>/status" method="POST" class="inline" onsubmit="this.querySelector('button[type=submit]').disabled=true; this.querySelector('button[type=submit]').innerHTML='...';">
                              <input type="hidden" name="status" value="<?= ($item["status"] ?? "none") === "watched" ? "watching" : "watched" ?>">
                              <?= csrf_field() ?>
                              <button type="submit" class="text-xs px-2 py-1 rounded <?= ($item["status"] ?? "none") === "watched" ? "bg-green-900/50 text-green-400 border border-green-500/50" : "bg-blue-900/50 text-blue-400 border border-blue-500/50" ?>" title="Ubah Status">
                                  <?= ($item["status"] ?? "none") === "watched" ? "Selesai" : "Menunggu" ?>
                              </button>
                          </form>
                          <form action="/watchlist/<?= $item["id"] ?? "" ?>/delete" method="POST" onsubmit="if(!confirm('Hapus dari watchlist?')) return false; this.querySelector('button[type=submit]').disabled=true; this.querySelector('button[type=submit]').style.opacity='0.5';" class="inline">
                              <?= csrf_field() ?>
                              <button type="submit" class="text-(--text-muted) hover:text-red-500 transition-colors bg-transparent" title="Hapus">
                                  <i data-lucide="trash-2" class="w-4 h-4"></i>
                              </button>
                          </form>
                      </div>
                  </div>
              </div>
              <?php endforeach; ?>
          </div>
          <?php endif; ?>
      </div>

      <!-- Ulasan Saya -->
      <div>
        <div class="flex items-center justify-between mb-6 border-b border-(--border) pb-2">
            <h2 class="h2 text-white">ULASAN SAYA</h2>
        </div>

        <?php if (empty($reviews)): ?>
        <div class="bg-(--bg-elevated) border border-(--border) rounded-lg p-12 text-center">
            <i data-lucide="star" class="w-16 h-16 mx-auto mb-4 text-(--text-muted)"></i>
            <p class="text-(--text-secondary) mb-4">Kamu belum menulis ulasan apapun.</p>
            <a href="/movies" class="bttn primary inline-flex">BERI ULASAN</a>
        </div>
        <?php else: ?>
        <div class="flex flex-col gap-4">
            <?php foreach ($reviews ?? [] as $review): ?>
            <div class="bg-(--bg-elevated) border border-(--border) rounded-lg p-5">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1">
                        <a href="/movies/<?= esc($review["movie_slug"] ?? "#") ?>" class="text-lg font-bold text-white hover:text-(--primary) transition-colors mb-1 block">
                            <?= esc($review["movie_title"] ?? "Film Tidak Diketahui") ?>
                        </a>
                        <div class="flex items-center gap-2 mb-3">
                            <div class="rating-badge bg-(--primary) text-white px-2 py-0.5 rounded text-sm font-bold">★ <?= esc($review["rating"] ?? 0) ?>/10</div>
                            <span class="text-xs text-(--text-muted)"><?= date("d M Y", strtotime($review["created_at"] ?? date("Y-m-d H:i:s"))) ?></span>
                        </div>
                        <h4 class="font-bold text-(--text-primary) mb-1"><?= esc($review["title"] ?? "Ulasan") ?></h4>
                        <?php if (!empty($review["body"] ?? "")): ?>
                        <p class="text-sm text-(--text-secondary) line-clamp-3"><?= esc($review["body"] ?? "") ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="flex gap-2 shrink-0">
                        <button
                            type="button"
                            class="bttn icon scale-[0.8]"
                            title="Ubah"
                            data-modal-id="edit-review-modal-<?= $review["id"] ?? "" ?>"
                            onclick="document.getElementById(this.dataset.modalId).showModal()"
                        >
                            <i data-lucide="edit" class="w-4 h-4 m-0"></i>
                        </button>
                        <form method="post" action="/reviews/<?= $review["id"] ?? "" ?>/delete" onsubmit="return confirm('Hapus ulasan ini?')">
                            <?= csrf_field() ?>
                            <button type="submit" class="bttn icon border-red-500/30 text-red-500 hover:bg-red-500 hover:text-white scale-[0.8]" title="Hapus"><i data-lucide="trash-2" class="w-4 h-4 m-0"></i></button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Modal Edit Ulasan -->
            <dialog id="edit-review-modal-<?= $review["id"] ?? "" ?>" class="bg-(--bg-card) text-white p-6 rounded-xl border border-(--border) shadow-2xl backdrop:bg-black/80 backdrop:backdrop-blur-sm w-[90%] max-w-lg m-auto">
                <div class="flex justify-between items-center mb-4 border-b border-(--border) pb-3">
                    <h3 class="text-xl font-bold m-0">Edit Ulasan</h3>
                    <button
                        type="button"
                        data-modal-id="edit-review-modal-<?= $review["id"] ?? "" ?>"
                        onclick="document.getElementById(this.dataset.modalId).close()"
                        class="text-(--text-secondary) hover:text-white bg-transparent"
                    >
                        <i data-lucide="x"></i>
                    </button>
                </div>
                <form action="/reviews/<?= $review["id"] ?? "" ?>/update" method="POST" onsubmit="this.querySelector('button[type=submit]').disabled=true; this.querySelector('button[type=submit]').innerHTML='Menyimpan...';">
                    <?= csrf_field() ?>
                    <input type="hidden" name="redirect_to" value="/profile">
                    <div class="mb-4">
                        <label class="block text-sm mb-1 text-(--text-secondary)">Rating (1-10)</label>
                        <input type="number" name="rating" min="1" max="10" value="<?= esc($review["rating"] ?? 0) ?>" required class="w-full bg-(--bg-elevated) border border-(--border) rounded px-3 py-2 text-white focus:outline-none focus:border-(--primary)">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm mb-1 text-(--text-secondary)">Judul Ulasan</label>
                        <input type="text" name="title" value="<?= esc($review["title"] ?? "Ulasan") ?>" required class="w-full bg-(--bg-elevated) border border-(--border) rounded px-3 py-2 text-white focus:outline-none focus:border-(--primary)">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm mb-1 text-(--text-secondary)">Isi Ulasan</label>
                        <textarea name="body" rows="4" required class="w-full bg-(--bg-elevated) border border-(--border) rounded px-3 py-2 text-white focus:outline-none focus:border-(--primary)"><?= esc($review["body"] ?? "") ?></textarea>
                    </div>
                    <div class="flex justify-end gap-3 mt-6">
                        <!-- prettier-ignore -->
                        <button
                            type="button"
                            data-modal-id="edit-review-modal-<?= $review["id"] ?? "" ?>"
                            onclick="document.getElementById(this.dataset.modalId).close()"
                            class="px-4 py-2 rounded text-(--text-secondary) hover:bg-(--bg-elevated)"
                        >
                            Batal
                        </button>
                        <button type="submit" class="bttn primary py-2 px-6 border-0">Simpan Perubahan</button>
                    </div>
                </form>
            </dialog>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<div class="py-6"></div>

<?php endif; ?>
<?= $this->endSection() ?>
