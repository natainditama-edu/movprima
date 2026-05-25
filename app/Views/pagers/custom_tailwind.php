<?php
/**
 * @var \CodeIgniter\Pager\PagerRenderer $pager
 */
?>
<?php if (isset($pager) && $pager): ?>
<?php $pager->setSurroundCount(2); ?>

<div class="flex rounded border border-(--border) overflow-hidden bg-(--bg-card)">
    <?php if ($pager->hasPrevious()): ?>
        <a href="<?= $pager->getFirst() ?>" class="px-3 py-1 text-sm border-r border-(--border) text-(--text-secondary) hover:bg-(--border) hover:text-white transition-colors">
            Awal
        </a>
        <a href="<?= $pager->getPrevious() ?>" class="px-3 py-1 text-sm border-r border-(--border) text-(--text-secondary) hover:bg-(--border) hover:text-white transition-colors">
            &laquo;
        </a>
    <?php endif; ?>

    <?php foreach ($pager->links() as $link): ?>
        <a href="<?= $link["uri"] ?? "" ?>" class="px-3 py-1 text-sm border-r border-(--border) <?= $link["active"] ?? "" ? "bg-[var(--primary)] text-white font-bold" : "text-[var(--text-secondary)] hover:bg-[var(--border)] hover:text-white" ?> transition-colors">
            <?= $link["title"] ?? "" ?>
        </a>
    <?php endforeach; ?>

    <?php if ($pager->hasNext()): ?>
        <a href="<?= $pager->getNext() ?>" class="px-3 py-1 text-sm border-r border-(--border) text-(--text-secondary) hover:bg-(--border) hover:text-white transition-colors">
            &raquo;
        </a>
        <a href="<?= $pager->getLast() ?>" class="px-3 py-1 text-sm border-(--border) text-(--text-secondary) hover:bg-(--border) hover:text-white transition-colors">
            Akhir
        </a>
    <?php endif; ?>
</div>

<?php endif; ?>
