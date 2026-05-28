<?= $this->extend("layouts/admin") ?>

<?= $this->section("title") ?>Pengguna<?= $this->endSection() ?>

<?= $this->section("content") ?>

<div class="flex items-center justify-between mb-8 pb-4 border-b border-white/10">
    <h1 class="text-2xl font-display tracking-widest text-white m-0 uppercase">Kelola Pengguna</h1>
</div>

<div class="bg-white/5 border border-white/10 rounded-lg overflow-hidden shadow-2xl">
    <div class="py-6 px-4 overflow-x-auto">
        <table id="datatable" class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-black/20 text-xs font-bold tracking-wider text-zinc-400">
                    <th class="px-4 py-3 border-b border-white/10 whitespace-nowrap">Nama</th>
                    <th class="px-4 py-3 border-b border-white/10 whitespace-nowrap">Email</th>
                    <th class="px-4 py-3 border-b border-white/10 whitespace-nowrap">Peran</th>
                    <th class="px-4 py-3 border-b border-white/10 whitespace-nowrap hidden sm:table-cell">Ulasan</th>
                    <th class="px-4 py-3 border-b border-white/10 whitespace-nowrap hidden sm:table-cell">Bergabung</th>
                    <th class="px-4 py-3 border-b border-white/10 whitespace-nowrap text-right" data-orderable="false">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-xs">
                <?php if (!empty($users)): ?>
                <?php foreach ($users as $u): ?>
                <tr class="hover:bg-white/5 transition-colors group border-b border-white/5 last:border-b-0 last:mb-2">
                    <td class="px-4 py-3 whitespace-nowrap">
                        <div class="flex items-center gap-3">
                            <span class="font-bold text-white text-xs! tracking-wide"><?= esc($u["name"]) ?></span>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-zinc-400 font-mono text-xs! whitespace-nowrap"><?= esc($u["email"]) ?></td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs! font-bold <?= $u["role"] === "admin" ? "bg-[var(--primary)] text-white shadow-lg shadow-red-900/50" : "bg-white/10 text-zinc-300" ?>">
                            <?= esc($u["role"] === "admin" ? "Admin" : "Pengguna") ?>
                        </span>
                    </td>
                    <td class="px-4 py-3 text-zinc-400 font-medium font-mono text-sm! whitespace-nowrap hidden sm:table-cell">
                        <?= $u["review_count"] ?? 0 ?>
                    </td>
                    <td class="px-4 py-3 text-zinc-500 font-mono text-xs! whitespace-nowrap hidden sm:table-cell"><?= esc(date("d M Y", strtotime($u["created_at"]))) ?></td>
                    <td class="px-4 py-3 text-right text-xs! whitespace-nowrap">
                        <div class="flex gap-2 justify-end">
                            <?php if ($u["id"] !== session()->get("user_id")): ?>
                            <form method="post" action="/admin/users/<?= $u["id"] ?>/delete" onsubmit="return confirm('Hapus pengguna <?= esc(addslashes($u["name"])) ?> secara permanen?')">
                                <?= csrf_field() ?>
                                <button type="submit" class="text-zinc-600 hover:text-red-500 transition-colors p-1 rounded hover:bg-red-500/10 flex items-center justify-center">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                            <?php else: ?>
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs! font-bold bg-white/5 text-zinc-500">Anda</span>
                            <?php endif; ?>
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
