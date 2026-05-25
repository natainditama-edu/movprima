<?php
/**
 * @var array $movies
 * @var array $genres
 * @var string $searchQuery
 * @var string $selectedGenre
 * @var string $selectedSort
 * @var int $totalMovies
 * @var int $totalPages
 * @var int $currentPage
 * @var \CodeIgniter\Pager\Pager $pager
 */
?>
<?= $this->extend("layouts/main") ?>

<?= $this->section("title") ?>Daftar Film<?= $this->endSection() ?>

<?= $this->section("content") ?>
<section class="moviesPageHero">
    <div class="flex-container">
        <div class="movieListHeroContainer">
            <h1 class="h1 uppercase"><?= esc($title ?? "Koleksi Film") ?></h1>
            <?php if (!empty($subtitle)): ?>
              <p class="text-white/70 mt-2"><?= esc($subtitle ?? "Jelajahi film favoritmu") ?></p>
            <?php endif; ?>
        </div>
    </div>
    <div class="movieListHeroBackground">
        <img src="<?= !empty($movies) && !empty($movies[0]["backdrop"] ?? null) ? esc($movies[0]["backdrop"] ?? null) : "https://placehold.co/600x900/1a1a24/606078?font=oswald&text=Not+Found" ?>" alt="" />
    </div>
</section>

<section class="movies wraplist" style="padding-top: 40px; padding-bottom: 60px;">
    <div class="flex-container">
        <div class="mb-8 flex flex-col md:flex-row gap-4 justify-between items-center bg-(--bg-elevated) p-4 rounded-lg border border-(--border)">
            <form action="/movies" method="GET" class="flex flex-col md:flex-row gap-4 w-full justify-between items-center">
                <div class="flex gap-2 w-full md:w-auto lg:w-64">
                    <input type="text" name="q" value="<?= esc($searchQuery ?? "") ?>" placeholder="Cari judul..." class="text w-full">
                </div>
                <div class="grid grid-cols-2 md:flex gap-2 w-full md:w-auto items-center">
                    <select name="genre" class="select w-full md:w-auto lg:w-42">
                        <option value="">Semua Genre</option>
                        <?php foreach ($genres ?? [] as $g): ?>
                            <option value="<?= esc($g["slug"] ?? "") ?>" <?= ($selectedGenre ?? "") === ($g["slug"] ?? "") ? "selected" : "" ?>><?= esc($g["name"] ?? "Kategori") ?></option>
                        <?php endforeach; ?>
                    </select>
                    <select name="sort" class="select w-full md:w-auto lg:w-42">
                        <option value="newest" <?= ($selectedSort ?? "") === "newest" ? "selected" : "" ?>>Terbaru</option>
                        <option value="oldest" <?= ($selectedSort ?? "") === "oldest" ? "selected" : "" ?>>Terlama</option>
                        <option value="rating" <?= ($selectedSort ?? "") === "rating" ? "selected" : "" ?>>Rating Tertinggi</option>
                    </select>
                    <button type="submit" class="bttn primary py-2 px-4 col-span-2 md:col-auto w-full md:w-auto mt-2 md:mt-0">Cari</button>
                </div>
            </form>
        </div>

        <?php if (!empty($movies) && is_array($movies)): ?>
        <div class="wrapMovieList">
            <?php foreach ($movies as $movie): ?>
              <div class="wrapMovieItem">
                <?= view("components/movie_item", ["movie" => $movie]) ?>
              </div>
            <?php endforeach; ?>
        </div>
        <div class="mt-12 flex justify-center">
            <?= $pager->links("default", "custom_tailwind") ?>
        </div>
        <?php else: ?>
        <div class="text-center py-20">
            <i data-lucide="film" class="w-16 h-16 mx-auto mb-4 text-(--text-muted)"></i>
            <h2 class="text-2xl font-bold text-white mb-2">Film tidak ditemukan</h2>
            <p class="text-(--text-secondary)">Coba sesuaikan kata kunci pencarian atau filter Anda.</p>
        </div>
        <?php endif; ?>
    </div>
</section>

<?= $this->endSection() ?>
