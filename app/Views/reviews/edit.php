<?= $this->extend("layouts/main") ?>

<?= $this->section("title") ?>Ubah Ulasan<?= $this->endSection() ?>

<?= $this->section("content") ?>
<div class="flex-container py-12">
    <div class="max-w-2xl mx-auto bg-[var(--bg-elevated)] border border-[var(--border)] rounded-xl p-8">
        <div>
            <h1 class="h1 uppercase text-white mb-1">UBAH ULASAN</h1>
            <?php if (!empty($movie)): ?>
            <p class="text-[var(--text-secondary)] mb-6">untuk <span class="text-white font-semibold"><?= esc($movie["title"]) ?></span></p>
            <?php endif; ?>
        </div>

        <form method="post" action="/reviews/<?= esc($review["id"]) ?>/update" class="flex flex-col gap-5">
            <?= csrf_field() ?>

            <!-- Penilaian -->
            <div>
                <label class="block text-[var(--text-secondary)] mb-1">Penilaian (1 – 10)</label>
                <input type="number" name="rating" min="1" max="10" value="<?= esc(old("rating", $review["rating"] ?? 7)) ?>" class="text w-32 <?= session("errors.rating") ? "border-red-500" : "" ?>" required>
                <?php if (session("errors.rating")): ?>
                <span class="text-red-400 text-xs mt-1 block"><?= esc(session("errors.rating")) ?></span>
                <?php endif; ?>
            </div>

            <!-- Isi ulasan -->
            <div>
                <label class="block text-[var(--text-secondary)] mb-1">Ulasan (Opsional)</label>
                <textarea name="body" rows="5" class="text w-full resize-y <?= session("errors.body") ? "border-red-500" : "" ?>"><?= esc(old("body", $review["body"] ?? "")) ?></textarea>
                <?php if (session("errors.body")): ?>
                <span class="text-red-400 text-xs mt-1 block"><?= esc(session("errors.body")) ?></span>
                <?php endif; ?>
            </div>

            <!-- Toggle spoiler -->
            <div class="row" style="margin-top: 10px;">
                <label class="row" style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                    <input type="checkbox" name="is_spoiler" value="1" class="switch" <?= old("is_spoiler", $review["is_spoiler"] ?? 0) ? "checked" : "" ?>>
                    <p style="margin: 0; color: var(--text-secondary)">Mengandung spoiler</p>
                </label>
            </div>

            <div class="flex gap-3 mt-4">
                <button type="submit" class="bttn colorbttn border-0">SIMPAN PERUBAHAN</button>
                <?php if (!empty($movie)): ?>
                <a href="/movies/<?= esc($movie["slug"]) ?>" class="bttn secondary">BATAL</a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
