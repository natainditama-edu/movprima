<?= $this->extend("layouts/main") ?>

<?= $this->section("title") ?>
Semua Genre
<?= $this->endSection() ?>

<?= $this->section("content") ?>

<section class="moviesPageHero">
    <div class="flex-container">
        <div class="movieListHeroContainer">
            <h1 class="h1 uppercase">GENRE</h1>
            <p class="text-(--text-secondary) mt-2"><?= count($genres ?? []) ?> genre tersedia</p>
        </div>
    </div>
    <div class="movieListHeroBackground">
        <img src="https://placehold.co/600x900/1a1a24/606078?font=oswald&text=Semua+Genre" alt="Genres" />
    </div>
</section>

<section class="movies" style="padding-top: 40px; padding-bottom: 60px;">
    <div class="flex-container">
        <?php if (empty($genres)): ?>
        <?php $icon = "tag"; ?>
          <div class="text-center py-20 bg-(--bg-elevated) border border-(--border) rounded-lg">
            <i data-lucide="<?= $icon ?>" class="w-16 h-16 mx-auto mb-4 text-(--text-muted)"></i>
            <h2 class="text-2xl font-bold text-white mb-2">Belum ada genre</h2>
          </div>
        <?php else: ?>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
          <?php $iconMap = [
            "action" => "swords",
            "comedy" => "smile",
            "horror" => "ghost",
            "drama" => "theater",
            "sci-fi" => "rocket",
            "romance" => "heart",
            "thriller" => "alert-triangle",
            "animation" => "clapperboard",
            "documentary" => "camera",
            "fantasy" => "wand",
            "adventure" => "map",
            "crime" => "siren",
            "mystery" => "search",
            "family" => "users",
          ]; ?>
          <?php foreach ($genres ?? [] as $g):

            $slug = strtolower($g["slug"] ?? "");
            $icon = $iconMap[$slug] ?? "tag";
            ?>
            <a href="/genres/<?= esc($g["slug"] ?? "") ?>" class="bg-(--bg-elevated) border border-(--border) rounded-lg p-6 text-center hover:border-(--primary) hover:-translate-y-1 transition-all duration-300 group">
                <i data-lucide="<?= $icon ?>" class="w-8 h-8 mx-auto mb-3 text-(--text-muted) group-hover:text-(--primary) transition-colors"></i>
                <h3 class="font-bold text-white group-hover:text-(--primary) transition-colors"><?= esc($g["name"] ?? "Kategori") ?></h3>
                <p class="text-xs text-(--text-secondary) mt-1"><?= $g["movie_count"] ?? 0 ?> film</p>
            </a>
          <?php
          endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<?= $this->endSection() ?>
