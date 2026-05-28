<?= $this->extend("layouts/admin") ?>

<?= $this->section("title") ?>
  Film
<?= $this->endSection() ?>

<?= $this->section("content") ?>

<div class="flex items-center justify-between mb-4 sm:mb-8 pb-2 sm:pb-4 border-b border-white/10">
  <h1 class="text-xl font-display tracking-widest text-white m-0 uppercase font-semibold">Kelola Film</h1>
  <a href="/admin/movies/create" class="bttn colorbttn px-4! py-2! text-xs! hover:bg-transparent! flex items-center gap-1" >
    <span>Tambah Film</span>
    <i data-lucide="plus" class="w-4 h-4"></i>
  </a>
</div>

<div class="bg-white/5 border border-white/10 rounded-lg overflow-hidden shadow-2xl">
    <div class="py-6 px-4 overflow-x-auto">
        <table id="datatable" class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-black/20 text-xs font-bold tracking-wider text-zinc-400">
                    <th class="px-4 py-3 border-b border-white/10 whitespace-nowrap">Judul</th>
                    <th class="px-4 py-3 border-b border-white/10 whitespace-nowrap">Tahun</th>
                    <th class="px-4 py-3 border-b border-white/10 whitespace-nowrap">Penilaian</th>
                    <th class="px-4 py-3 border-b border-white/10 whitespace-nowrap">Ulasan</th>
                    <th class="px-4 py-3 border-b border-white/10 whitespace-nowrap hidden md:table-cell">Genre</th>
                    <th class="px-4 py-3 border-b border-white/10 whitespace-nowrap text-right" data-orderable="false">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-xs">
                <?php if (!empty($movies)): ?>
                  <?php foreach ($movies as $m): ?>
                    <tr class="hover:bg-white/5 transition-colors group border-b border-white/5 last:border-b-0 last:mb-2">
                        <td class="px-4 py-3 text-white font-medium text-xs! whitespace-nowrap">
                          <a href="/movies/<?= esc($r["movie_slug"] ?? "#") ?>" target="_blank" class="text-(--primary) hover:text-white transition-colors flex items-center gap-1">
                            <?= esc($m["title"]) ?>
                            <i data-lucide="external-link" class="w-3 h-3"></i>
                          </a>
                        </td>
                        <td class="px-4 py-3 text-zinc-400 font-medium text-xs! whitespace-nowrap">
                          <?= esc(substr($m["release_year"] ?? "", 0, 4)) ?>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <?php if (!empty($m["avg_rating"])): ?>
                              <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs! font-bold" style="background: rgba(234,179,8,0.2); color: #eab308;">
                                <i data-lucide="star" class="w-3 h-3 fill-current"></i>
                                <?= number_format($m["avg_rating"], 1) ?>
                              </span>
                            <?php else: ?>
                              <span class="text-zinc-600 font-bold">—</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-3 text-zinc-400 font-medium font-mono text-sm! whitespace-nowrap">
                          <?= $m["review_count"] ?? 0 ?>
                        </td>
                        <td class="px-4 py-3 text-zinc-500 tracking-wider font-bold hidden md:table-cell">
                          <?= esc($m["genres"] ?? "—") ?>
                        </td>
                        <td class="px-4 py-3 text-right text-xs! whitespace-nowrap">
                            <div class="flex gap-2 justify-end">
                                <a href="/admin/movies/<?= $m["id"] ?>/edit" class="text-zinc-600 hover:text-zinc-500 transition-colors p-1 rounded hover:bg-zinc-500/10 flex items-center justify-center">
                                    <i data-lucide="edit-2" class="w-4 h-4"></i>
                                </a>
                                <form method="post" action="/admin/movies/<?= $m["id"] ?>/delete" onsubmit="return confirm('Hapus film ini?')">
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
