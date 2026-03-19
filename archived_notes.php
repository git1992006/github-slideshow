<?php
require_once 'includes/functions.php';
requireLogin();
$userId = (int) $_SESSION['user_id'];
$statement = $pdo->prepare(baseNoteQuery() . ' WHERE n.user_id = ? AND n.is_archived = 1 GROUP BY n.id ORDER BY n.updated_at DESC');
$statement->execute([$userId]);
$notes = $statement->fetchAll();
$pageTitle = 'Archived Notes';
require_once 'header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><span class="section-kicker">Archive</span><h1 class="fw-bold mb-1">Archived notes</h1><p class="text-secondary mb-0">Review and restore knowledge without deleting it.</p></div>
    <a href="notes.php" class="btn btn-outline-secondary rounded-pill">Back to Notes</a>
</div>
<div class="row g-4">
    <?php if (!$notes): ?><div class="col-12"><div class="empty-state">No archived notes found.</div></div><?php endif; ?>
    <?php foreach ($notes as $note): ?>
        <div class="col-md-6 col-xl-4"><div class="note-card h-100"><span class="badge text-bg-light border"><?= h($note['category_name'] ?: 'Uncategorized') ?></span><h2 class="h5 mt-3"><?= h($note['title']) ?></h2><p class="text-secondary"><?= h(mb_strimwidth(strip_tags($note['content']), 0, 120, '...')) ?></p><div class="d-flex justify-content-between align-items-center mt-3"><a href="view_note.php?id=<?= (int) $note['id'] ?>" class="btn btn-sm btn-primary">View</a><a href="archive_note.php?id=<?= (int) $note['id'] ?>" class="btn btn-sm btn-outline-secondary">Restore</a></div></div></div>
    <?php endforeach; ?>
</div>
<?php require_once 'footer.php'; ?>
