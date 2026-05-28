<?= $this->extend("layouts/admin") ?>

<?= $this->section("title") ?>
  <?= isset($movie) ? "Ubah Film" : "Tambah Film" ?>
<?= $this->endSection() ?>

<?= $this->section("content") ?>

<div class="flex items-center gap-4 mb-8 pb-4 border-b border-white/10">
    <a href="/admin/movies" class="w-8 h-8 flex items-center justify-center text-zinc-400 hover:text-white bg-white/5 hover:bg-white/10 rounded-full transition-all" aria-label="Kembali">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
    </a>
    <h1 class="text-2xl font-display tracking-widest text-white m-0 uppercase">
        <?= isset($movie) ? "Ubah Film" : "Tambah Film Baru" ?>
    </h1>
</div>

<div class="bg-white/5 border border-white/10 rounded-xl max-w-4xl py-8 px-6 shadow-2xl">
    <form method="post" action="<?= isset($movie) ? "/admin/movies/" . $movie["id"] : "/admin/movies" ?>" class="flex flex-col gap-6" novalidate>
        <?= csrf_field() ?>

        <!-- Judul -->
        <div>
            <label class="block text-xs font-bold text-zinc-400 uppercase tracking-wider mb-2">Judul Film <span class="text-red-500">*</span></label>
            <input type="text" name="title" value="<?= esc(old("title", $movie["title"] ?? "")) ?>" placeholder="Judul film" class="w-full bg-black/50 border <?= session("errors.title") ? "border-red-500" : "border-white/20" ?> text-white px-4 py-3 rounded-lg focus:outline-none focus:border-[var(--primary)] transition-colors" required>
            <?php if (session("errors.title")): ?>
            <p class="text-red-400 text-xs font-bold mt-2"><?= esc(session("errors.title")) ?></p>
            <?php endif; ?>
        </div>

        <!-- Sinopsis -->
        <div>
            <label class="block text-xs font-bold text-zinc-400 uppercase tracking-wider mb-2">Sinopsis</label>
            <textarea name="synopsis" rows="5" placeholder="Ringkasan cerita film…" class="w-full bg-black/50 border border-white/20 text-white px-4 py-3 rounded-lg focus:outline-none focus:border-(--primary) transition-colors resize-y"><?= esc(old("synopsis", $movie["synopsis"] ?? "")) ?></textarea>
        </div>

        <!-- Tanggal rilis + Durasi -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-bold text-zinc-400 uppercase tracking-wider mb-2">Tanggal Rilis</label>
                <input type="date" name="release_year" value="<?= esc(old("release_year", $movie["release_year"] ?? "")) ?>" class="w-full bg-black/50 border border-white/20 text-white px-4 py-3 rounded-lg focus:outline-none focus:border-(--primary) transition-colors" style="color-scheme: dark;">
            </div>
            <div>
                <label class="block text-xs font-bold text-zinc-400 uppercase tracking-wider mb-2">Durasi (menit)</label>
                <input type="number" name="duration" min="1" value="<?= esc(old("duration", $movie["duration"] ?? "")) ?>" class="w-full bg-black/50 border border-white/20 text-white px-4 py-3 rounded-lg focus:outline-none focus:border-(--primary) transition-colors">
            </div>
        </div>

        <!-- Genre (checkbox) -->
        <?php if (!empty($genres)): ?>
        <div class="bg-black/30 p-6 rounded-lg border border-white/5">
            <label class="block text-xs font-bold text-zinc-400 uppercase tracking-wider mb-4 border-b border-white/5 pb-2">Kategori Genre</label>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                <?php foreach ($genres as $g): ?>
                <label class="flex items-center gap-3 cursor-pointer group">
                    <div class="relative flex items-center">
                        <input type="checkbox" name="genres[]" value="<?= $g["id"] ?>" class="w-5 h-5 rounded border-white/20 bg-black/50 text-(--primary) focus:ring-(--primary) focus:ring-offset-black transition-all" <?= in_array($g["id"], $movieGenreIds ?? []) ? "checked" : "" ?>>
                    </div>
                    <span class="text-xs font-bold text-zinc-400 group-hover:text-white transition-colors tracking-wider"><?= esc($g["name"]) ?></span>
                </label>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-2">
            <!-- Poster -->
            <div class="bg-black/30 p-6 rounded-lg border border-white/5">
                <label class="block text-xs font-bold text-zinc-400 uppercase tracking-wider mb-4 border-b border-white/5 pb-2">Poster URL (Rasio 2:3)</label>
                <?php if (!empty($movie["poster"])): ?>
                <div class="mb-4 flex items-end gap-4">
                    <img src="<?= esc($movie["poster"]) ?>" alt="Poster saat ini" class="w-24 rounded-lg object-cover border border-white/10 shadow-lg">
                    <p class="text-xs text-zinc-500 font-bold uppercase tracking-wider">Poster Saat Ini</p>
                </div>
                <?php endif; ?>
                <input type="url" name="poster" placeholder="https://..." value="<?= esc(old("poster", $movie["poster"] ?? "")) ?>" class="w-full bg-black/50 border <?= session("errors.poster") ? "border-red-500" : "border-white/20" ?> text-white px-4 py-3 rounded-lg focus:outline-none focus:border-[var(--primary)] transition-colors">
                <?php if (session("errors.poster")): ?>
                <p class="text-red-400 text-xs font-bold mt-2"><?= esc(session("errors.poster")) ?></p>
                <?php endif; ?>
            </div>

            <!-- Backdrop -->
            <div class="bg-black/30 p-6 rounded-lg border border-white/5">
                <label class="block text-xs font-bold text-zinc-400 uppercase tracking-wider mb-4 border-b border-white/5 pb-2">Backdrop URL (Rasio 16:9)</label>
                <?php if (!empty($movie["backdrop"])): ?>
                <div class="mb-4 flex items-end gap-4">
                    <img src="<?= esc($movie["backdrop"]) ?>" alt="Backdrop saat ini" class="h-24 w-40 rounded-lg object-cover border border-white/10 shadow-lg">
                    <p class="text-xs text-zinc-500 font-bold uppercase tracking-wider">Backdrop Saat Ini</p>
                </div>
                <?php endif; ?>
                <input type="url" name="backdrop" placeholder="https://..." value="<?= esc(old("backdrop", $movie["backdrop"] ?? "")) ?>" class="w-full bg-black/50 border <?= session("errors.backdrop") ? "border-red-500" : "border-white/20" ?> text-white px-4 py-3 rounded-lg focus:outline-none focus:border-[var(--primary)] transition-colors">
                <?php if (session("errors.backdrop")): ?>
                <p class="text-red-400 text-xs font-bold mt-2"><?= esc(session("errors.backdrop")) ?></p>
                <?php endif; ?>
            </div>
        </div>

        <div class="flex gap-4 pt-8 border-t border-white/10 mt-4">
            <button type="submit" class="bttn primary px-8 py-3 font-bold uppercase tracking-wider text-xs flex items-center gap-2">
                <i data-lucide="save" class="w-4 h-4"></i> <?= isset($movie) ? "Simpan Perubahan" : "Tambah Film Baru" ?>
            </button>
            <a href="/admin/movies" class="px-8 py-3 bg-white/5 hover:bg-white/10 text-white rounded font-bold uppercase tracking-wider text-xs transition-colors flex items-center">
                Batal
            </a>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
