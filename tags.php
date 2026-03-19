<?php
require_once 'includes/functions.php';
requireLogin();
$userId = (int) $_SESSION['user_id'];
$tags = $pdo->prepare('SELECT t.*, COUNT(nt.note_id) AS note_count FROM tags t LEFT JOIN note_tags nt ON nt.tag_id = t.id WHERE t.user_id = ? GROUP BY t.id ORDER BY t.name ASC');
$tags->execute([$userId]);
$tags = $tags->fetchAll();
$pageTitle = 'Tags';
require_once 'header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><span class="section-kicker">Metadata</span><h1 class="fw-bold mb-1">Tag library</h1><p class="text-secondary mb-0">See which keywords shape your knowledge system.</p></div>
    <a href="notes.php" class="btn btn-outline-secondary rounded-pill">Open Notes</a>
</div>
<div class="panel-card">
    <div class="row g-3">
        <?php if (!$tags): ?><div class="col-12"><div class="empty-state">Tags appear automatically when you add them to notes.</div></div><?php endif; ?>
        <?php foreach ($tags as $tag): ?>
            <div class="col-md-6 col-xl-4"><div class="category-card"><div class="d-flex justify-content-between align-items-center"><div><strong>#<?= h($tag['name']) ?></strong><p class="text-secondary mb-0 small"><?= (int) $tag['note_count'] ?> notes linked</p></div><a href="notes.php?tag_id=<?= (int) $tag['id'] ?>" class="btn btn-sm btn-primary">View</a></div></div></div>
        <?php endforeach; ?>
    </div>
</div>
<?php require_once 'footer.php'; ?>
