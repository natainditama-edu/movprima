<?= $this->extend("layouts/main") ?>

<?= $this->section("title") ?>
<?= esc($genre["name"] ?? "Kategori") ?>
<?= $this->endSection() ?>

<?= $this->section("content") ?>

<section class="moviesPageHero">
    <div class="flex-container">
        <div class="movieListHeroContainer">
            <h1 class="h1 uppercase"><?= esc($genre["name"] ?? "Kategori") ?></h1>
            <p class="text-(--text-secondary) mt-2"><?= $total ?? 0 ?> film</p>
        </div>
    </div>
    <div class="movieListHeroBackground">
        <img src="<?= !empty($movies) && !empty($movies[0]["backdrop"] ?? null) ? esc($movies[0]["backdrop"] ?? null) : "https://placehold.co/600x900/1a1a24/606078?font=oswald&text=Not+Found" ?>" alt="Backdrop <?= esc($genre["name"] ?? "Kategori") ?>" />
    </div>
</section>

<section class="movies wraplist" style="padding-top: 40px; padding-bottom: 60px;">
    <div class="flex-container">
        <div class="mb-8 flex flex-col md:flex-row gap-4 justify-between items-center bg-(--bg-elevated) p-4 rounded-lg border border-(--border)">
            <form action="/genres/<?= esc($genre["slug"] ?? "") ?>" method="GET" class="flex flex-col md:flex-row gap-4 w-full justify-between items-center">
                <div class="flex gap-2 w-full md:w-auto lg:w-64">
                    <input type="text" name="q" value="<?= esc($searchQuery ?? "") ?>" placeholder="Cari judul..." class="text w-full">
                </div>
                <div class="grid grid-cols-1 md:flex gap-2 w-full md:w-auto items-center">
                    <select name="sort" class="select w-full md:w-auto lg:w-42">
                        <option value="newest" <?= ($sort ?? "newest") === "newest" ? "selected" : "" ?>>Terbaru</option>
                        <option value="oldest" <?= ($sort ?? "") === "oldest" ? "selected" : "" ?>>Terlama</option>
                        <option value="rating" <?= ($sort ?? "") === "rating" ? "selected" : "" ?>>Rating Tertinggi</option>
                        <option value="title"  <?= ($sort ?? "") === "title" ? "selected" : "" ?>>A – Z</option>
                    </select>
                    <button type="submit" class="bttn primary py-2 px-4 w-full md:w-auto mt-2 md:mt-0">Cari & Urutkan</button>
                </div>
            </form>
        </div>

      <?php if (empty($movies)): ?>
        <div class="text-center py-10 rounded-lg">
            <i data-lucide="film" class="w-16 h-16 mx-auto mb-4 text-(--text-muted)"></i>
            <h2 class="text-2xl font-bold text-white mb-2">Belum ada film dalam genre ini</h2>
            <p class="text-(--text-secondary)">Coba periksa lagi nanti.</p>
        </div>
      <?php else: ?>
        <div class="wrapMovieList movies">
            <?php foreach ($movies as $movie): ?>
              <div class="wrapMovieItem">
                <?= view("partials/movie_item", ["movie" => $movie]) ?>
              </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if (isset($pager)): ?>
          <div class="mt-12 flex justify-center">
              <?= $pager->links("default", "custom_tailwind") ?>
          </div>
        <?php endif; ?>
      <?php endif; ?>
    </div>
</section>

<?= $this->endSection() ?>
