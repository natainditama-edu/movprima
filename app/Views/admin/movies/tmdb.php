<?php
/**
 * @var string $query
 * @var array|null $results
 * @var string|null $error
 */
?>
<?= $this->extend("layouts/admin") ?>

<?= $this->section("title") ?>
  Cari Film di TMDB
<?= $this->endSection() ?>

<?= $this->section("content") ?>

<div class="flex items-center justify-between mb-4 sm:mb-8 pb-2 sm:pb-4 border-b border-white/10">
  <div class="flex items-center gap-3">
    <a href="/admin/movies" class="text-zinc-400 hover:text-white transition-colors">
      <i data-lucide="arrow-left" class="w-5 h-5"></i>
    </a>
    <h1 class="text-xl font-display tracking-widest text-white m-0 uppercase font-semibold">Cari Film di TMDB</h1>
  </div>
</div>

<div class="bg-white/5 border border-white/10 rounded-lg p-6 mb-8 shadow-2xl max-w-2xl">
  <form action="/admin/movies/tmdb" method="get" class="flex gap-4 items-end">
    <div class="flex-1 space-y-1">
      <label for="q" class="text-xs font-bold tracking-wider text-zinc-400 uppercase">Judul Film</label>
      <input type="text" id="q" name="q" value="<?= esc($query ?? "") ?>" placeholder="Ketik judul film (misal: Spider-Man)" class="w-full bg-black/50 border border-white/10 rounded px-4 py-2 text-white focus:outline-none focus:border-(--primary) transition-colors text-sm" required autofocus>
    </div>
    <button type="submit" class="bttn colorbttn px-6 py-2 h-10.5 hover:bg-transparent! flex items-center justify-center gap-2 text-white">
      <i data-lucide="search" class="w-4 h-4"></i>
      <span class="font-bold">Cari</span>
    </button>
  </form>
</div>

<?php if (isset($error)): ?>
  <div class="bg-red-500/20 border border-red-500/50 text-red-500 rounded p-4 mb-8">
    <div class="flex gap-2 items-center mb-1">
      <i data-lucide="alert-triangle" class="w-5 h-5"></i>
      <h3 class="font-bold text-sm">Gagal Mengakses TMDB</h3>
    </div>
    <p class="text-sm opacity-90"><?= esc($error) ?></p>
  </div>
<?php endif; ?>

<?php if (isset($results)): ?>
  <?php if (empty($results)): ?>
    <div class="text-center py-12 text-zinc-500">
      <i data-lucide="search-x" class="w-12 h-12 mx-auto mb-3 opacity-50"></i>
      <p>Tidak ada film yang ditemukan untuk "<?= esc($query ?? "") ?>"</p>
    </div>
  <?php else: ?>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
      <?php foreach ($results as $movie): ?>
        <div class="bg-black/40 border border-white/10 rounded-lg overflow-hidden flex flex-col group hover:border-(--primary)/50 transition-colors">
          <div class="relative aspect-2/3 bg-zinc-900">
            <?php if (!empty($movie["poster_path"])): ?>
              <img src="https://image.tmdb.org/t/p/w500<?= $movie["poster_path"] ?>" alt="<?= esc($movie["title"]) ?>" class="w-full h-full object-cover">
            <?php else: ?>
              <div class="w-full h-full flex items-center justify-center text-zinc-600">
                <i data-lucide="image" class="w-10 h-10"></i>
              </div>
            <?php endif; ?>
            <div class="absolute inset-0 bg-linear-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
          </div>

          <div class="p-4 flex flex-col flex-1">
            <h3 class="text-white font-bold text-sm leading-tight mb-1 line-clamp-2" title="<?= esc($movie["title"]) ?>">
              <?= esc($movie["title"]) ?>
            </h3>
            <p class="text-zinc-400 text-xs mb-3">
              <?= !empty($movie["release_date"]) ? substr($movie["release_date"], 0, 4) : "Tahun tidak diketahui" ?>
            </p>

            <div class="mt-auto pt-3 border-t border-white/5">
              <form action="/admin/movies/tmdb/import" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="tmdb_id" value="<?= $movie["id"] ?>">
                <button type="submit" class="w-full py-2 bg-white/10 hover:bg-(--primary) text-white text-xs font-bold rounded transition-colors flex items-center justify-center gap-2">
                  <i data-lucide="download" class="w-3 h-3"></i>
                  Import Film
                </button>
              </form>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
<?php endif; ?>

<?= $this->endSection() ?>
