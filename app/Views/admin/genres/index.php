<?= $this->extend("layouts/admin") ?>

<?= $this->section("title") ?>Genre<?= $this->endSection() ?>

<?= $this->section("content") ?>

<div class="flex items-center justify-between mb-8 pb-4 border-b border-white/10">
    <h1 class="text-2xl font-display tracking-widest text-white m-0 uppercase">Kelola Genre</h1>
</div>

<div class="flex flex-col gap-8">

    <!-- Form tambah genre -->
    <div class="bg-white/5 border border-white/10 rounded-xl py-6 px-5 h-fit shadow-2xl">
        <h2 class="font-display tracking-widest text-base font-bold text-white mb-4 m-0 uppercase">Tambah Genre Baru</h2>
        <form method="post" action="/admin/genres" class="flex flex-col gap-2" novalidate>
            <?= csrf_field() ?>
            <div>
                <label class="block text-xs font-bold text-zinc-400 tracking-wider mb-2">Nama Genre</label>
                <input type="text" name="name" placeholder="Misal: Action" value="<?= esc(old("name")) ?>" class="text-xs! w-full bg-black/50 border <?= session("errors.name") ? "border-red-500" : "border-white/20" ?> text-white px-4 py-2 rounded-lg focus:outline-none focus:border-[var(--primary)] transition-colors" required>
                <?php if (session("errors.name")): ?>
                <p class="text-xs text-red-400 mt-2 font-bold"><?= esc(session("errors.name")) ?></p>
                <?php endif; ?>
            </div>
            <button type="submit" class="bttn primary w-full text-center pt-1 pb-2 text-xs!">
                <i data-lucide="plus" class="w-4 h-4 inline-block"></i> TAMBAH GENRE
            </button>
        </form>
    </div>

    <!-- Tabel genre -->
    <div class="bg-white/5 border border-white/10 rounded-lg overflow-hidden shadow-2xl">
        <div class="py-6 px-4 overflow-x-auto">
            <table id="datatable" class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-black/20 text-xs font-bold tracking-wider text-zinc-400">
                        <th class="px-4 py-3 border-b border-white/10 whitespace-nowrap">Nama Genre</th>
                        <th class="px-4 py-3 border-b border-white/10 whitespace-nowrap">Total Film</th>
                        <th class="px-4 py-3 border-b border-white/10 whitespace-nowrap text-right" data-orderable="false">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-xs">
                    <?php if (!empty($genres)): ?>
                    <?php foreach ($genres as $g): ?>
                    <tr class="hover:bg-white/5 transition-colors group border-b border-white/5 last:border-b-0 last:mb-2">
                        <td class="px-4 py-3 text-white font-medium text-xs! whitespace-nowrap"><?= esc($g["name"]) ?></td>
                        <td class="px-4 py-3 text-zinc-400 font-medium font-mono text-sm! whitespace-nowrap">
                            <?= $g["movie_count"] ?? 0 ?>
                        </td>
                        <td class="px-4 py-3 text-right text-xs! whitespace-nowrap">
                            <div class="flex gap-2 justify-end">
                                <button onclick="document.getElementById('edit-modal-<?= $g["id"] ?>').showModal()" class="text-zinc-600 hover:text-zinc-500 transition-colors p-1 rounded hover:bg-zinc-500/10 flex items-center justify-center">
                                    <i data-lucide="edit-2" class="w-4 h-4"></i>
                                </button>
                                <form method="post" action="/admin/genres/<?= $g["id"] ?>/delete" onsubmit="return confirm('Hapus genre ini?')">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="text-zinc-600 hover:text-red-500 transition-colors p-1 rounded hover:bg-red-500/10 flex items-center justify-center">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <!-- Dialog ubah inline -->
                    <dialog id="edit-modal-<?= $g["id"] ?>" class="bg-transparent m-auto p-0 border-none backdrop:bg-black/80 backdrop:backdrop-blur-sm w-full max-w-md rounded-xl shadow-2xl outline-none">
                        <div class="bg-[#12121c] border border-white/10 rounded-xl p-8 relative">
                            <button type="button" onclick="document.getElementById('edit-modal-<?= $g["id"] ?>').close()" class="absolute top-4 right-4 text-zinc-500 hover:text-white transition-colors">
                                <i data-lucide="x" class="w-6 h-6"></i>
                            </button>
                            <h3 class="font-display tracking-widest text-2xl font-bold text-white mb-6 uppercase border-b border-white/10 pb-4">Ubah Genre</h3>
                            <form method="post" action="/admin/genres/<?= $g["id"] ?>">
                                <?= csrf_field() ?>
                                <div class="mb-6">
                                    <label class="block text-xs font-bold text-zinc-400 uppercase tracking-wider mb-2">Nama Genre</label>
                                    <input type="text" name="name" value="<?= esc($g["name"]) ?>" class="w-full bg-black/50 border border-white/20 text-white px-4 py-3 rounded-lg focus:outline-none focus:border-[var(--primary)] transition-colors" required>
                                </div>
                                <div class="flex gap-3 justify-end mt-8">
                                    <button type="button" onclick="document.getElementById('edit-modal-<?= $g["id"] ?>').close()" class="px-6 py-2.5 bg-white/5 hover:bg-white/10 text-white rounded font-bold uppercase tracking-wider text-sm transition-colors">Batal</button>
                                    <button type="submit" class="bttn primary px-6 py-2.5 font-bold uppercase tracking-wider text-sm">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </dialog>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
