<?php
/**
 * @var array $movie
 * @var array $movieGenres
 * @var array $movieReviews
 * @var array $relatedMovies
 * @var bool $isMovieInWatchlist
 * @var float|string|null $avgRating
 * @var array|null $userReview
 */
?>
<?= $this->extend("layouts/main") ?>

<?= $this->section("title") ?>
<?= esc($movie["title"] ?? "Judul Tidak Diketahui") ?>
<?= $this->endSection() ?>

<?= $this->section("meta_description") ?>
<?= esc(word_limiter($movie["synopsis"] ?? "", 20)) ?>
<?= $this->endSection() ?>

<?= $this->section("content") ?>

<section class="movieDetailHero">
    <div class="flex-container">
        <div class="movieDetailHeroContainer">
            <div class="heroInfos">
                <div class="specialTags">
                    <?php if ($avgRating): ?>
                    <p class="imdb">RATING <span><?= number_format($avgRating, 1) ?></span></p>
                    <?php endif; ?>
                    <p class="trend uppercase">FILM</p>
                </div>
                <h1><?= esc($movie["title"] ?? "Judul Tidak Diketahui") ?></h1>
                <ul class="detailList">
                    <li class="h3"><?= esc(substr($movie["release_year"] ?? "", 0, 4)) ?></li>
                    <li class="h3"><?= esc($movie["duration"] ?? "") ?> MNT</li>
                </ul>
                <?php if (!empty($movieGenres)): ?>
                <ul class="categories">
                    <?php foreach ($movieGenres as $genre): ?>
                    <li><?= esc($genre["name"]) ?></li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
            </div>

            <div class="buttons mt-4 flex items-end gap-2">
                <?php if (session()->get("user_id")): ?>
                    <form action="/watchlist" method="POST" id="watchlist-form">
                        <?= csrf_field() ?>
                        <input type="hidden" name="movie_id" value="<?= $movie["id"] ?? "" ?>">
                        <button type="submit" class="bttn icon max-h-10" data-title="<?= $isMovieInWatchlist ? "Hapus dari Daftar" : "Tambah ke Daftar" ?>" id="watchlist-btn">
                            <i class="fa-solid <?= $isMovieInWatchlist ? "fa-check text-green-500" : "fa-plus" ?>"></i>
                        </button>
                    </form>
                <?php else: ?>
                    <a href="/auth/login" class="bttn icon max-h-10" data-title="Masuk untuk tambah">
                        <i class="fa-solid fa-plus"></i>
                    </a>
                <?php endif; ?>

                <?php if ($movie["trailer_url"] ?? null): ?>
                <a href="<?= esc($movie["trailer_url"] ?? null) ?>" target="_blank" class="bttn watchnow big">
                    <i class="fa-solid fa-play"></i> LIHAT TRAILER
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="backgroundCover">
        <img src="<?= $movie["backdrop"] ?? "" ? esc($movie["backdrop"] ?? null) : "https://placehold.co/600x900/1a1a24/606078?font=oswald&text=Not+Found" ?>" alt="<?= esc($movie["title"] ?? "Judul Tidak Diketahui") ?>" />
    </div>
</section>

<section class="movieDetail flex-container" style="margin-bottom: 60px;">
    <div class="movieDetailContent">
        <div class="movieDetailTabCon">
            <a href="#information" class="movieDetailTab active">INFO</a>
            <a href="#reviews" class="movieDetailTab">ULASAN (<?= count($movieReviews) ?>)</a>
        </div>

        <div id="information" class="revealTabContent tabContent active">
            <div class="tabContentFrame information">
                <p class="title">SINOPSIS</p>
                <p><?= nl2br(esc($movie["synopsis"] ?? "Sinopsis belum ditambahkan.")) ?></p>

                <p class="title mt-6">SUTRADARA</p>
                <p><?= esc($movie["director"] ?? "Sutradara Tidak Diketahui") ?: "-" ?></p>
            </div>
        </div>

        <div id="reviews" class="revealTabContent tabContent">
            <div class="tabContentFrame information">
                <div class="flex justify-between items-center border-b border-(--border) pb-2 mb-4">
                    <p class="title m-0 border-0 pb-0">ULASAN PENGGUNA</p>
                    <?php if (session()->get("user_id")): ?>
                        <?php if (!$userReview): ?>
                          <button type="button" class="bttn primary py-1 px-3 text-sm" onclick="document.getElementById('review-modal').showModal()">
                              <i data-lucide="plus" class="w-4 h-4 mr-1"></i> Tulis Ulasan
                          </button>
                        <?php else: ?>
                          <span class="text-xs text-green-500 bg-green-500/10 px-2 py-1 rounded border border-green-500/20">Anda sudah mengulas</span>
                        <?php endif; ?>
                    <?php else: ?>
                        <a href="/auth/login" class="text-sm maincolor hover:underline">Masuk untuk mengulas</a>
                    <?php endif; ?>
                </div>

                <?php if ($userReview): ?>
                  <div class="bg-(--bg-elevated) p-4 rounded-lg border border-(--primary-glow) mb-6">
                    <div class="flex justify-between items-start mb-2">
                        <div class="flex items-center gap-2">
                            <div class="rating-badge bg-(--primary) text-white px-2 py-0.5 rounded text-sm font-bold">★ <?= esc($userReview["rating"] ?? 0) ?>/10</div>
                            <h4 class="font-bold text-white text-lg"><?= esc($userReview["title"] ?? "Ulasan") ?></h4>
                        </div>
                        <div class="flex gap-2">
                            <button type="button" class="text-(--text-secondary) hover:text-white bg-transparent!" title="Edit Ulasan" onclick="document.getElementById('edit-review-modal').showModal()"><i data-lucide="edit" class="w-4 h-4"></i></button>
                            <form action="/reviews/<?= $userReview["id"] ?? "" ?>/delete" method="POST" onsubmit="return confirmDelete(this);">
                                <?= csrf_field() ?>
                                <button type="submit" class="text-(--text-secondary) hover:text-red-500 bg-transparent!" title="Hapus Ulasan"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                            </form>
                        </div>
                    </div>
                    <?php if ($userReview["is_spoiler"] ?? ""): ?>
                      <div class="text-xs text-red-400 font-semibold mb-2 flex items-center gap-1"><i data-lucide="alert-triangle" class="w-3 h-3"></i> Mengandung Spoiler</div>
                    <?php endif; ?>
                      <p class="text-(--text-secondary) text-sm mb-2"><?= nl2br(esc($userReview["body"] ?? "")) ?></p>
                      <p class="text-xs text-(--text-muted)">Oleh Anda &middot; <?= date("d M Y", strtotime($userReview["created_at"] ?? "")) ?></p>
                  </div>
                <?php endif; ?>

                <?php if (empty($movieReviews) && !$userReview): ?>
                <div class="text-center py-8">
                    <i data-lucide="message-square" class="w-12 h-12 mx-auto mb-3 text-(--text-muted)"></i>
                    <p class="text-(--text-secondary)">Belum ada ulasan untuk film ini. Jadilah yang pertama!</p>
                </div>
                <?php else: ?>
                    <div class="flex flex-col gap-4">
                        <?php foreach ($movieReviews ?? [] as $rev):
                          if ($userReview && ($rev["id"] ?? "") === ($userReview["id"] ?? "")) {
                            continue;
                          } ?>
                        <div class="bg-(--bg-elevated) p-4 rounded border border-(--border)">
                            <div class="flex items-center gap-3 mb-3">
                                <img src="<?= "https://i.pravatar.cc/150?u=" . ($rev["user_id"] ?? 0) ?>" class="w-10 h-10 rounded-full object-cover" alt="<?= esc($rev["user_name"] ?? "Pengguna Anonim") ?>">
                                <div>
                                    <p class="text-white font-semibold text-sm"><?= esc($rev["user_name"] ?? "Pengguna Anonim") ?></p>
                                    <p class="text-xs text-(--text-muted)"><?= date("d M Y", strtotime($rev["created_at"] ?? date("Y-m-d H:i:s"))) ?></p>
                                </div>
                                <div class="ml-auto rating-badge bg-yellow-500 text-black px-2 py-0.5 rounded text-sm font-bold">★ <?= esc($rev["rating"] ?? 0) ?>/10</div>
                            </div>

                            <h5 class="text-white font-bold mb-1"><?= esc($rev["title"] ?? "Tanpa Judul Ulasan") ?></h5>

                            <?php if ($rev["is_spoiler"] ?? ""): ?>
                            <div class="relative mb-2">
                                <div class="absolute inset-0 z-10 flex items-center justify-center bg-(--bg-card)/80 backdrop-blur-sm rounded cursor-pointer" onclick="this.style.display='none';">
                                    <span class="text-xs font-semibold px-2 py-1 bg-red-500/20 text-red-400 rounded border border-red-500/50 flex items-center gap-1">
                                        <i data-lucide="eye-off" class="w-3 h-3"></i> Mengandung Spoiler (Klik untuk melihat)
                                    </span>
                                </div>
                                <p class="text-(--text-secondary) text-sm blur-sm"><?= nl2br(esc($rev["body"] ?? "")) ?></p>
                            </div>
                            <?php else: ?>
                            <p class="text-(--text-secondary) text-sm mb-2"><?= nl2br(esc($rev["body"] ?? "")) ?></p>
                            <?php endif; ?>

                            <!-- Tombol Like AJAX -->
                            <div class="mt-3 flex items-center gap-2">
                                <button type="button" class="btn-like-review text-xs flex items-center gap-1 px-2 py-1 rounded bg-(--bg-card) border border-(--border) hover:bg-(--border) transition-colors <?= !empty($rev["is_liked"] ?? "") ? "text-red-500 border-red-500/30" : "text-[var(--text-secondary)]" ?>" data-id="<?= $rev["id"] ?? "" ?>">
                                    <i data-lucide="heart" class="w-3 h-3 <?= !empty($rev["is_liked"] ?? "") ? "fill-current" : "" ?>"></i>
                                    <span class="like-count"><?= $rev["likes_count"] ?? "" ?></span>
                                </button>
                            </div>
                        </div>
                        <?php
                        endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="movieDetailPoster">
        <img src="<?= $movie["poster"] ?? "" ? esc($movie["poster"] ?? null) : "https://placehold.co/600x900/1a1a24/606078?font=oswald&text=Not+Found" ?>" alt="<?= esc($movie["title"] ?? "Judul Tidak Diketahui") ?>" />
    </div>
</section>

<!-- Film Serupa (Related Movies) - Optional -->
<?php if (!empty($relatedMovies)): ?>
<section class="movies padbot20 mt-8">
    <div class="flex-container">
        <div class="containerLink">
            <h3>FILM SERUPA</h3>
        </div>
        <div class="owl-carousel owlPopular owlfix">
            <?php foreach ($relatedMovies ?? [] as $rel): ?>
                <?= view("partials/movie_item", ["movie" => $rel]) ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<div class="py-6"></div>

<!-- Modals -->
<dialog id="review-modal" class="bg-(--bg-card) text-white p-6 rounded-xl border border-(--border) shadow-2xl backdrop:bg-black/80 backdrop:backdrop-blur-sm w-[90%] max-w-lg m-auto">
    <div class="flex justify-between items-center mb-4 border-b border-(--border) pb-3">
        <h3 class="text-xl font-bold m-0">Tulis Ulasan</h3>
        <button type="button" onclick="document.getElementById('review-modal').close()" class="text-(--text-secondary) hover:text-white"><i data-lucide="x"></i></button>
    </div>
    <form action="/reviews" method="POST" onsubmit="this.querySelector('button[type=submit]').disabled=true; this.querySelector('button[type=submit]').innerHTML='Mengirim...';">
        <?= csrf_field() ?>
        <input type="hidden" name="movie_id" value="<?= $movie["id"] ?? "" ?>">
        <div class="mb-4">
            <label class="block text-sm mb-1 text-(--text-secondary)">Rating (1-10)</label>
            <input type="number" name="rating" min="1" max="10" required class="w-full bg-(--bg-elevated) border border-(--border) rounded px-3 py-2 text-white focus:outline-none focus:border-(--primary)">
        </div>
        <div class="mb-4">
            <label class="block text-sm mb-1 text-(--text-secondary)">Judul Ulasan</label>
            <input type="text" name="title" required class="w-full bg-(--bg-elevated) border border-(--border) rounded px-3 py-2 text-white focus:outline-none focus:border-(--primary)">
        </div>
        <div class="mb-4">
            <label class="block text-sm mb-1 text-(--text-secondary)">Isi Ulasan</label>
            <textarea name="body" rows="4" required class="w-full bg-(--bg-elevated) border border-(--border) rounded px-3 py-2 text-white focus:outline-none focus:border-(--primary)"></textarea>
        </div>

        <div class="flex justify-end gap-3 mt-6">
            <button type="button" onclick="document.getElementById('review-modal').close()" class="px-4 py-2 rounded text-(--text-secondary) hover:bg-(--bg-elevated)">Batal</button>
            <button type="submit" class="bttn primary py-2 px-6 border-0">Kirim Ulasan</button>
        </div>
    </form>
</dialog>

<?php if ($userReview): ?>
<dialog id="edit-review-modal" class="bg-(--bg-card) text-white p-6 rounded-xl border border-(--border) shadow-2xl backdrop:bg-black/80 backdrop:backdrop-blur-sm w-[90%] max-w-lg m-auto">
    <div class="flex justify-between items-center mb-4 border-b border-(--border) pb-3">
        <h3 class="text-xl font-bold m-0">Edit Ulasan</h3>
        <button type="button" onclick="document.getElementById('edit-review-modal').close()" class="text-(--text-secondary) hover:text-white"><i data-lucide="x"></i></button>
    </div>
    <form action="/reviews/<?= $userReview["id"] ?? "" ?>/update" method="POST" onsubmit="this.querySelector('button[type=submit]').disabled=true; this.querySelector('button[type=submit]').innerHTML='Menyimpan...';">
        <?= csrf_field() ?>
        <div class="mb-4">
            <label class="block text-sm mb-1 text-(--text-secondary)">Rating (1-10)</label>
            <input type="number" name="rating" min="1" max="10" value="<?= esc($userReview["rating"] ?? 0) ?>" required class="w-full bg-(--bg-elevated) border border-(--border) rounded px-3 py-2 text-white focus:outline-none focus:border-(--primary)">
        </div>
        <div class="mb-4">
            <label class="block text-sm mb-1 text-(--text-secondary)">Judul Ulasan</label>
            <input type="text" name="title" value="<?= esc($userReview["title"] ?? "Ulasan") ?>" required class="w-full bg-(--bg-elevated) border border-(--border) rounded px-3 py-2 text-white focus:outline-none focus:border-(--primary)">
        </div>
        <div class="mb-4">
            <label class="block text-sm mb-1 text-(--text-secondary)">Isi Ulasan</label>
            <textarea name="body" rows="4" required class="w-full bg-(--bg-elevated) border border-(--border) rounded px-3 py-2 text-white focus:outline-none focus:border-(--primary)"><?= esc($userReview["body"] ?? "") ?></textarea>
        </div>

        <div class="flex justify-end gap-3 mt-6">
            <button type="button" onclick="document.getElementById('edit-review-modal').close()" class="px-4 py-2 rounded text-(--text-secondary) hover:bg-(--bg-elevated)">Batal</button>
            <button type="submit" class="bttn primary py-2 px-6 border-0">Simpan Perubahan</button>
        </div>
    </form>
</dialog>
<?php endif; ?>

<?= $this->endSection() ?>

<?= $this->section("scripts") ?>
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Watchlist AJAX
    const watchlistForm = document.getElementById('watchlist-form');
    if (watchlistForm) {
        watchlistForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = document.getElementById('watchlist-btn');
            const icon = btn.querySelector('i');
            const originalIcon = icon.className;
            btn.disabled = true;
            icon.className = 'fa-solid fa-spinner fa-spin text-white';

            try {
                const formData = new FormData(watchlistForm);
                const response = await fetch('/watchlist', {
                    method: 'POST',
                    body: JSON.stringify(Object.fromEntries(formData)),
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();
                if (response.ok) {
                    if (data.status === 'added') {
                        icon.className = 'fa-solid fa-check text-green-500';
                        btn.dataset.title = 'Hapus dari Daftar';
                    } else {
                        icon.className = 'fa-solid fa-plus';
                        btn.dataset.title = 'Tambah ke Daftar';
                    }
                } else {
                    alert(data.message || 'Terjadi kesalahan');
                }
            } catch (err) {
                console.error(err);
            } finally {
                btn.disabled = false;
            }
        });
    }

    window.confirmDelete = function(form) {
        if (confirm('Hapus ulasan ini?')) {
            const btn = form.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
            return true;
        }
        return false;
    };

    // Tab switching manual (kalau app.js asli bermasalah)
    const tabs = document.querySelectorAll('.movieDetailTab');
    const contents = document.querySelectorAll('.revealTabContent');

    tabs.forEach(tab => {
        tab.addEventListener('click', (e) => {
            e.preventDefault();
            const targetId = tab.getAttribute('href').substring(1);

            tabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');

            contents.forEach(c => c.classList.remove('active'));
            document.getElementById(targetId).classList.add('active');
        });
    });

    // Review Like AJAX
    document.querySelectorAll('.btn-like-review').forEach(btn => {
        btn.addEventListener('click', async () => {
            <?php if (!session()->get("user_id")): ?>
            window.location.href = '/auth/login';
            return;
            <?php endif; ?>

            const reviewId = btn.dataset.id;
            const originalHtml = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin w-3 h-3"></i> <span class="like-count">...</span>';
            try {
                const response = await fetch(`/reviews/${reviewId}/like`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    btn.innerHTML = originalHtml;
                    btn.querySelector('.like-count').textContent = data.count;
                    const svg = btn.querySelector('svg') || btn.querySelector('i');
                    if (data.liked) {
                        btn.classList.add('text-red-500', 'border-red-500/30');
                        btn.classList.remove('text-[var(--text-secondary)]');
                        icon.classList.add('fill-current');
                    } else {
                        btn.classList.remove('text-red-500', 'border-red-500/30');
                        btn.classList.add('text-[var(--text-secondary)]');
                        icon.classList.remove('fill-current');
                    }
                }
            } catch (err) {
                console.error(err);
            } finally {
                btn.disabled = false;
            }
        });
    });
});
</script>
<?= $this->endSection() ?>
