<?= $this->extend("layouts/admin") ?>

<?= $this->section("title") ?>
  Dasbor
<?= $this->endSection() ?>

<?= $this->section("content") ?>

<div class="flex items-center justify-between mb-4 sm:mb-8 pb-2 sm:pb-4 border-b border-white/10">
  <h1 class="text-xl font-display tracking-widest text-white m-0 uppercase font-semibold">Dasbor Utama</h1>
  <a href="/" class="bttn colorbttn px-4! py-2! text-xs! hover:bg-transparent! flex items-center gap-1" >
    <span>Beranda</span>
    <i data-lucide="external-link" class="w-4 h-4"></i>
  </a>
</div>

<!-- Kartu statistik -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-10">
    <div class="bg-white/5 border border-white/10 rounded-xl p-6 flex items-center justify-between relative overflow-hidden group hover:border-white/20 transition-all">
      <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:opacity-10 transition-opacity">
        <i data-lucide="film" class="w-20 h-20" style="color: var(--primary);"></i>
      </div>
      <div class="relative z-10">
        <p class="text-xs text-zinc-400 font-bold tracking-widest">Total Film</p>
        <p class="text-3xl font-display font-bold text-white mt-1"><?= $movieCount ?? 0 ?></p>
      </div>
      <div class="w-10 h-10 rounded-full flex items-center justify-center relative z-10" style="background-color: rgba(229, 9, 20, 0.2); color: var(--primary);">
        <i data-lucide="film" class="w-6 h-6"></i>
      </div>
    </div>

    <div class="bg-white/5 border border-white/10 rounded-xl p-5 flex items-center justify-between relative overflow-hidden group hover:border-white/20 transition-all">
      <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:opacity-10 transition-opacity">
        <i data-lucide="star" class="w-20 h-20" style="color: #eab308;"></i>
      </div>
      <div class="relative z-10">
        <p class="text-xs text-zinc-400 font-bold tracking-widest">Ulasan</p>
        <p class="text-3xl font-display font-bold text-white mt-1"><?= $reviewCount ?? 0 ?></p>
      </div>
      <div class="w-10 h-10 rounded-full flex items-center justify-center relative z-10" style="background-color: rgba(234, 179, 8, 0.2); color: #eab308;">
        <i data-lucide="star" class="w-6 h-6"></i>
      </div>
    </div>

    <div class="bg-white/5 border border-white/10 rounded-xl p-5 flex items-center justify-between relative overflow-hidden group hover:border-white/20 transition-all">
      <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:opacity-10 transition-opacity">
        <i data-lucide="users" class="w-20 h-20" style="color: #3b82f6;"></i>
      </div>
      <div class="relative z-10">
        <p class="text-xs text-zinc-400 font-bold tracking-widest">Pengguna</p>
        <p class="text-3xl font-display font-bold text-white mt-1"><?= $userCount ?? 0 ?></p>
      </div>
      <div class="w-10 h-10 rounded-full flex items-center justify-center relative z-10" style="background-color: rgba(59, 130, 246, 0.2); color: #3b82f6;">
        <i data-lucide="users" class="w-6 h-6"></i>
      </div>
    </div>

    <div class="bg-white/5 border border-white/10 rounded-xl p-5 flex items-center justify-between relative overflow-hidden group hover:border-white/20 transition-all">
      <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:opacity-10 transition-opacity">
        <i data-lucide="tag" class="w-20 h-20" style="color: #10b981;"></i>
      </div>
      <div class="relative z-10">
        <p class="text-xs text-zinc-400 font-bold tracking-widest">Genre</p>
        <p class="text-3xl font-display font-bold text-white mt-1"><?= $genreCount ?? 0 ?></p>
      </div>
      <div class="w-10 h-10 rounded-full flex items-center justify-center relative z-10" style="background-color: rgba(16, 185, 129, 0.2); color: #10b981;">
        <i data-lucide="tag" class="w-6 h-6"></i>
      </div>
    </div>
</div>

<!-- Ulasan terbaru -->
<div class="bg-white/5 border border-white/10 rounded-lg overflow-hidden shadow-2xl">
  <div class="px-6 py-4 border-b border-white/10 flex justify-between items-end" style="background: rgba(255,255,255,0.02);">
    <h2 class="font-display tracking-widest text-lg font-bold text-white m-0">Ulasan Terbaru</h2>
    <a href="/admin/reviews" class="text-xs! text-zinc-400 hover:text-white transition-colors font-bold">Lihat Semua</a>
  </div>

  <div class="p-0 overflow-x-auto pb-2">
    <table class="w-full text-left border-collapse">
      <thead>
        <tr class="bg-black/20 text-xs font-bold tracking-wider text-zinc-400">
          <th class="px-6 pt-4 pb-3 border-b border-white/10 whitespace-nowrap">Pengguna</th>
          <th class="px-6 pt-4 pb-3 border-b border-white/10 whitespace-nowrap">Film</th>
          <th class="px-6 pt-4 pb-3 border-b border-white/10 whitespace-nowrap">Penilaian</th>
          <th class="px-6 pt-4 pb-3 border-b border-white/10 whitespace-nowrap">Tanggal</th>
          <th class="px-6 pt-4 pb-3 border-b border-white/10 whitespace-nowrap">Aksi</th>
        </tr>
      </thead>
      <tbody class="text-xs">
        <?php if (empty($latestReviews)): ?>
          <tr>
            <td colspan="5" class="px-6 pt-5 pb-3 text-center text-zinc-500 font-bold tracking-widest text-xs! whitespace-nowrap">
              Belum ada ulasan.
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($latestReviews as $r): ?>
            <tr class="hover:bg-white/5 transition-colors group border-b border-white/5 last:border-b-0 last:mb-2">
                <td class="px-6 pt-1 pb-3 text-zinc-300 font-medium text-xs! whitespace-nowrap">
                  <?= esc($r["user_name"] ?? "—") ?>
                </td>
                <td class="px-6 pt-1 pb-3 font-bold text-white text-xs! whitespace-nowrap">
                  <a href="/movies/<?= esc($r["movie_slug"] ?? "#") ?>" target="_blank" class="text-(--primary) hover:text-white transition-colors flex items-center gap-1">
                    <?= esc($r["movie_title"] ?? "—") ?>
                    <i data-lucide="external-link" class="w-3 h-3"></i>
                  </a>
                </td>
                <td class="px-6 pt-1 pb-3 whitespace-nowrap">
                  <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs! font-bold" style="background: rgba(234,179,8,0.2); color: #eab308;">
                    <i data-lucide="star" class="w-3 h-3 fill-current"></i> <?= esc($r["rating"]) ?>
                  </span>
                </td>
                <td class="px-6 pt-1 pb-3 text-zinc-500 font-mono text-xs! whitespace-nowrap">
                  <?= esc(date("d M Y", strtotime($r["created_at"]))) ?>
                </td>
                <td class="px-6 pt-1 pb-3 text-right text-xs! whitespace-nowrap">
                  <form method="post" action="/admin/reviews/<?= $r["id"] ?>/delete" onsubmit="return confirm('Hapus ulasan ini?')">
                    <?= csrf_field() ?>
                    <button class="text-zinc-600 hover:text-red-500 transition-colors p-1 rounded hover:bg-red-500/10 flex items-center justify-center">
                      <i data-lucide="trash-2" class="w-4 h-4"></i>
                    </button>
                  </form>
                </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?= $this->endSection() ?>
