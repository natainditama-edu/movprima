<?= $this->extend("layouts/admin") ?>

<?= $this->section("title") ?>Ulasan<?= $this->endSection() ?>

<?= $this->section("content") ?>

<div class="flex items-center justify-between mb-8 pb-4 border-b border-white/10">
    <h1 class="text-2xl font-display tracking-widest text-white m-0 uppercase">Kelola Ulasan</h1>
</div>

<div class="bg-white/5 border border-white/10 rounded-lg overflow-hidden shadow-2xl">
    <div class="py-6 px-4 overflow-x-auto">
        <table id="datatable" class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-black/20 text-xs font-bold tracking-wider text-zinc-400">
                    <th class="px-4 py-3 border-b border-white/10 whitespace-nowrap">Pengguna</th>
                    <th class="px-4 py-3 border-b border-white/10 whitespace-nowrap">Film</th>
                    <th class="px-4 py-3 border-b border-white/10 whitespace-nowrap">Penilaian</th>
                    <th class="px-4 py-3 border-b border-white/10 whitespace-nowrap">Kutipan</th>
                    <th class="px-4 py-3 border-b border-white/10 whitespace-nowrap">Spoiler</th>
                    <th class="px-4 py-3 border-b border-white/10 whitespace-nowrap">Tanggal</th>
                    <th class="px-4 py-3 border-b border-white/10 whitespace-nowrap text-right" data-orderable="false">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-xs">
                <?php if (!empty($reviews)): ?>
                <?php foreach ($reviews as $r): ?>
                <tr class="hover:bg-white/5 transition-colors group border-b border-white/5 last:border-b-0 last:mb-2">
                    <td class="px-4 py-3 text-white font-medium text-xs! whitespace-nowrap"><?= esc($r["user_name"] ?? "—") ?></td>
                    <td class="px-4 py-3 text-white font-medium text-xs! whitespace-nowrap">
                        <a href="/movies/<?= esc($r["movie_slug"] ?? "#") ?>" target="_blank" class="text-[var(--primary)] hover:text-white transition-colors flex items-center gap-1">
                            <?= esc($r["movie_title"] ?? "—") ?>
                            <i data-lucide="external-link" class="w-3 h-3"></i>
                        </a>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs! font-bold" style="background: rgba(234,179,8,0.2); color: #eab308;">
                            <i data-lucide="star" class="w-3 h-3 fill-current"></i> <?= esc($r["rating"]) ?>
                        </span>
                    </td>
                    <td class="px-4 py-3 max-w-[200px] whitespace-normal">
                        <p class="text-xs! text-zinc-400 line-clamp-2 m-0 leading-relaxed"><?= esc($r["body"] ?? "") ?></p>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <?php if ($r["is_spoiler"]): ?>
                        <span class="inline-flex items-center px-2 py-1 rounded text-xs! font-bold bg-red-500/20 text-red-500 tracking-wider uppercase">Spoiler</span>
                        <?php else: ?>
                        <span class="text-zinc-600 font-bold">—</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3 text-zinc-500 font-mono text-xs! whitespace-nowrap"><?= esc(date("d M Y", strtotime($r["created_at"]))) ?></td>
                    <td class="px-4 py-3 text-right text-xs! whitespace-nowrap">
                        <div class="flex gap-2 justify-end">
                            <form method="post" action="/admin/reviews/<?= $r["id"] ?>/delete" onsubmit="return confirm('Hapus ulasan ini?')">
                                <?= csrf_field() ?>
                                <button type="submit" class="text-zinc-600 hover:text-red-500 transition-colors p-1 rounded hover:bg-red-500/10 flex items-center justify-center">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
