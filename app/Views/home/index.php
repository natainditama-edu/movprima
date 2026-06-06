<?= $this->extend("layouts/main") ?>

<?= $this->section("title") ?>
Beranda
<?= $this->endSection() ?>

<?= $this->section("content") ?>

<section class="indexHero">
    <div class="owl-carousel owlHero">
        <?php if (!empty($featured)): ?>
            <?php foreach ($featured as $hero): ?>
            <div class="item">
                <div class="flex-container">
                    <div class="heroContent flex-container">
                        <ul class="detailList">
                            <li>FILM</li>
                            <?php if (!empty($hero["release_year"] ?? "2000")): ?>
                            <li><?= esc(substr($hero["release_year"] ?? "2000", 0, 4)) ?></li>
                            <?php endif; ?>
                            <?php if (!empty($hero["avg_rating"] ?? 0)): ?>
                            <li><i class="fa-solid fa-star text-yellow-400"></i> <?= number_format($hero["avg_rating"] ?? 0, 1) ?></li>
                            <?php endif; ?>
                        </ul>
                        <h2 class="maincolor"><?= esc($hero["title"] ?? "Tanpa Judul") ?></h2>
                        <?php if (!empty($hero["synopsis"] ?? "Sinopsis belum tersedia.")): ?>
                        <p class="line-clamp-3"><?= esc($hero["synopsis"] ?? "Sinopsis belum tersedia.") ?></p>
                        <?php endif; ?>
                        <div class="buttons">
                            <a href="/movies/<?= esc($hero["slug"] ?? "") ?>" class="bttn watchnow big">
                                <i class="fa-solid fa-play"></i> LIHAT DETAIL
                            </a>
                        </div>
                    </div>
                </div>
                <div class="indexHeroBackground">
                    <img src="<?= $hero["backdrop"] ?? null ? esc($hero["backdrop"]) : "https://placehold.co/600x900/1a1a24/606078?font=oswald&text=Not+Found" ?>" alt="<?= esc($hero["title"] ?? "Tanpa Judul") ?>" />
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="item">
                <div class="flex-container">
                    <div class="heroContent flex-container">
                        <h2 class="maincolor">Selamat Datang di MovPrima</h2>
                        <p>Temukan dan ulas film favoritmu.</p>
                        <div class="buttons">
                            <a href="/movies" class="bttn watchnow big">
                                <i class="fa-solid fa-play"></i> JELAJAHI FILM
                            </a>
                        </div>
                    </div>
                </div>
                <div class="indexHeroBackground">
                    <img src="https://placehold.co/600x900/1a1a24/606078?font=oswald&text=Not+Found" alt="" />
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- FILM TERBARU -->
<?php if (!empty($latest)): ?>
<section class="movies my-12">
    <div class="flex-container">
        <div class="containerLink flex items-center justify-between">
            <h3 class="text-2xl">FILM TERBARU</h3>
            <a class="h5 exploreall light" href="/movies?sort=newest">LIHAT SEMUA</a>
        </div>
        <div class="owl-carousel owlPopular owlfix">
            <?php foreach ($latest as $movie): ?>
                <?= view("partials/movie_item", ["movie" => $movie]) ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- TERPOPULER DI MovPrima -->
<?php if (!empty($topRated)): ?>
<section class="movies my-12">
    <div class="flex-container">
        <div class="containerLink flex items-center justify-between">
            <h3 class="text-2xl">TERPOPULER DI MovPrima</h3>
            <a class="h5 exploreall light" href="/movies?sort=rating">LIHAT SEMUA</a>
        </div>
        <div class="owl-carousel owlPopular owlfix">
            <?php foreach ($topRated as $movie): ?>
                <?= view("partials/movie_item", ["movie" => $movie]) ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- REKOMENDASI PILIHAN -->
<?php if (!empty($recommended)): ?>
<section class="movies my-12">
    <div class="flex-container">
        <div class="containerLink flex items-center justify-between">
            <h3 class="text-2xl">REKOMENDASI PILIHAN</h3>
            <a class="h5 exploreall light" href="/movies?sort=rating">LIHAT SEMUA</a>
        </div>
        <div class="owl-carousel owlPopular owlfix">
            <?php foreach ($recommended as $movie): ?>
                <?= view("partials/movie_item", ["movie" => $movie]) ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- FILM KLASIK TERBAIK -->
<?php if (!empty($classic)): ?>
<section class="movies my-12">
    <div class="flex-container">
        <div class="containerLink flex items-center justify-between">
            <h3 class="text-2xl">FILM KLASIK TERBAIK</h3>
            <a class="h5 exploreall light" href="/movies?sort=oldest">LIHAT SEMUA</a>
        </div>
        <div class="owl-carousel owlPopular owlfix">
            <?php foreach ($classic as $movie): ?>
                <?= view("partials/movie_item", ["movie" => $movie]) ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<div class="py-6"></div>

<?php endif; ?>

<?= $this->endSection() ?>
