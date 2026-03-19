<?php
require_once 'includes/functions.php';
requireLogin();
$userId = (int) $_SESSION['user_id'];
$noteId = (int) ($_GET['id'] ?? 0);
$statement = $pdo->prepare(baseNoteQuery() . ' WHERE n.id = ? AND n.user_id = ? GROUP BY n.id LIMIT 1');
$statement->execute([$noteId, $userId]);
$note = $statement->fetch();

if (!$note) {
    setFlash('flash_error', 'Note not found.');
    header('Location: notes.php');
    exit;
}

$linkedNotes = fetchLinkedNotes($pdo, $noteId, $userId);
$pageTitle = $note['title'];
require_once 'header.php';
?>
<div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-start gap-3 mb-4">
    <div>
        <div class="d-flex flex-wrap gap-2 mb-3">
            <span class="badge text-bg-light border"><?= h($note['category_name'] ?: 'Uncategorized') ?></span>
            <span class="badge priority-<?= strtolower($note['priority']) ?>"><?= h($note['priority']) ?></span>
            <span class="badge text-bg-light border"><?= h($note['status']) ?></span>
            <?php if ((int) $note['is_favorite'] === 1): ?><span class="badge text-bg-warning">Favorite</span><?php endif; ?>
            <?php if ((int) $note['is_pinned'] === 1): ?><span class="badge text-bg-primary">Pinned</span><?php endif; ?>
        </div>
        <h1 class="fw-bold mb-2"><?= h($note['title']) ?></h1>
        <p class="text-secondary mb-0">Created <?= date('M d, Y g:i A', strtotime($note['created_at'])) ?> · Updated <?= date('M d, Y g:i A', strtotime($note['updated_at'])) ?></p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a href="toggle_favorite.php?id=<?= (int) $note['id'] ?>" class="btn btn-outline-warning rounded-pill"><i class="bi bi-star me-2"></i>Toggle Favorite</a>
        <a href="archive_note.php?id=<?= (int) $note['id'] ?>" class="btn btn-outline-secondary rounded-pill"><i class="bi bi-archive me-2"></i><?= (int) $note['is_archived'] === 1 ? 'Unarchive' : 'Archive' ?></a>
        <a href="export_note.php?id=<?= (int) $note['id'] ?>" class="btn btn-outline-dark rounded-pill"><i class="bi bi-download me-2"></i>Export TXT</a>
        <a href="edit_note.php?id=<?= (int) $note['id'] ?>" class="btn btn-primary rounded-pill"><i class="bi bi-pencil-square me-2"></i>Edit</a>
        <a href="delete_note.php?id=<?= (int) $note['id'] ?>" class="btn btn-outline-danger rounded-pill" onclick="return confirm('Delete this note permanently?');"><i class="bi bi-trash me-2"></i>Delete</a>
    </div>
</div>
<div class="row g-4">
    <div class="col-lg-8">
        <div class="panel-card h-100">
            <div class="color-swatch mb-3" style="background: <?= h($note['color_label']) ?>;"></div>
            <article class="note-content"><?= nl2br(h($note['content'])) ?></article>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="panel-card mb-4">
            <h2 class="h5 mb-3">Tags</h2>
            <div class="d-flex flex-wrap gap-2">
                <?php foreach (array_filter(array_map('trim', explode(',', $note['tags_list'] ?? ''))) as $tagName): ?><span class="badge text-bg-light border"><?= h($tagName) ?></span><?php endforeach; ?>
                <?php if (trim((string) ($note['tags_list'] ?? '')) === ''): ?><span class="text-secondary">No tags added.</span><?php endif; ?>
            </div>
        </div>
        <div class="panel-card">
            <h2 class="h5 mb-3">Linked notes</h2>
            <?php if (!$linkedNotes): ?><div class="empty-state">No linked notes yet.</div><?php endif; ?>
            <div class="vstack gap-3">
                <?php foreach ($linkedNotes as $linked): ?>
                    <a href="view_note.php?id=<?= (int) $linked['id'] ?>" class="favorite-item text-decoration-none">
                        <div><strong class="d-block text-dark"><?= h($linked['title']) ?></strong><small class="text-secondary"><?= h($linked['status']) ?></small></div>
                        <span class="badge priority-<?= strtolower($linked['priority']) ?>"><?= h($linked['priority']) ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<?php require_once 'footer.php'; ?>
