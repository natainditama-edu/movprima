<?php
/**
 * @var array $movie
 */
$poster = !empty($movie["poster"] ?? null) ? esc((string) ($movie["poster"] ?? null)) : "https://placehold.co/600x900/1a1a24/606078?font=oswald&text=Not+Found";
$title = esc((string) ($movie["title"] ?? "Judul"));
$slug = esc((string) ($movie["slug"] ?? ""));
$rating = !empty($movie["avg_rating"] ?? 0) ? number_format((float) ($movie["avg_rating"] ?? 0), 1) : null;
$reviews = $movie["review_count"] ?? (0 ?? 0);
$genre = !empty($movie["top_genre"] ?? "Umum") ? esc((string) ($movie["top_genre"] ?? "Umum")) : null;
?>
<div class="item">
  <a href="/movies/<?= $slug ?>">
      <div class="movieItem">
          <div class="moviePoster">
              <?php if ($genre): ?>
              <div class="badge-genre"><?= $genre ?></div>
              <?php endif; ?>

              <?php if ($rating): ?>
              <div class="badge-rating">
                  <i class="fa-solid fa-star text-yellow-400"></i> <?= $rating ?>
              </div>
              <?php endif; ?>

              <?php if ($reviews > 0): ?>
              <div class="badge-review">
                  <i class="fa-solid fa-comment-dots" style="color: #a0a0b8;"></i> <?= esc((string) $reviews) ?>
              </div>
              <?php endif; ?>

              <img src="<?= $poster ?>" alt="<?= $title ?>" />
          </div>
          <div class="movieTitle">
              <p style="display:-webkit-box; -webkit-line-clamp:1; -webkit-box-orient:vertical; overflow:hidden; text-overflow:ellipsis;" class="line-clamp-1 text-base! opacity-70"><?= $title ?></p>
          </div>
      </div>
  </a>
</div>
