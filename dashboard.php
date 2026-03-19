<?php
require_once 'includes/functions.php';
requireLogin();
$userId = (int) $_SESSION['user_id'];

$getCount = function (string $table, string $extra = '') use ($pdo, $userId): int {
    $statement = $pdo->prepare(sprintf('SELECT COUNT(*) FROM %s WHERE user_id = ? %s', $table, $extra));
    $statement->execute([$userId]);
    return (int) $statement->fetchColumn();
};

$stats = [
    'notes' => $getCount('notes', 'AND is_archived = 0'),
    'categories' => $getCount('categories'),
    'tags' => $getCount('tags'),
    'favorites' => $getCount('notes', 'AND is_favorite = 1 AND is_archived = 0'),
];

$recentNotes = $pdo->prepare('SELECT n.*, c.name AS category_name FROM notes n LEFT JOIN categories c ON c.id = n.category_id WHERE n.user_id = ? AND n.is_archived = 0 ORDER BY n.updated_at DESC LIMIT 6');
$recentNotes->execute([$userId]);
$recentNotes = $recentNotes->fetchAll();

$favorites = $pdo->prepare('SELECT id, title, priority, updated_at FROM notes WHERE user_id = ? AND is_favorite = 1 AND is_archived = 0 ORDER BY updated_at DESC LIMIT 5');
$favorites->execute([$userId]);
$favorites = $favorites->fetchAll();

$pageTitle = 'Dashboard';
require_once 'header.php';
?>
<div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
    <div>
        <span class="section-kicker">Overview</span>
        <h1 class="fw-bold mb-1">Welcome to your knowledge vault</h1>
        <p class="text-secondary mb-0">Track your thinking system with quick insights and recent activity.</p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="add_note.php" class="btn btn-primary rounded-pill"><i class="bi bi-plus-lg me-2"></i>Add Note</a>
        <a href="random_note.php" class="btn btn-outline-secondary rounded-pill"><i class="bi bi-shuffle me-2"></i>Show Random Idea</a>
    </div>
</div>
<div class="row g-4 mb-4">
    <?php $cards = [
        ['Total Notes', $stats['notes'], 'bi-journal-text'],
        ['Categories', $stats['categories'], 'bi-collection'],
        ['Tags', $stats['tags'], 'bi-tags'],
        ['Favorites', $stats['favorites'], 'bi-star-fill'],
    ]; ?>
    <?php foreach ($cards as [$label, $value, $icon]): ?>
        <div class="col-sm-6 col-xl-3"><div class="dashboard-card h-100"><div class="dashboard-icon"><i class="bi <?= $icon ?>"></i></div><span><?= h($label) ?></span><h2><?= h((string) $value) ?></h2></div></div>
    <?php endforeach; ?>
</div>
<div class="row g-4">
    <div class="col-xl-8">
        <div class="panel-card h-100">
            <div class="d-flex justify-content-between align-items-center mb-3"><h2 class="h4 mb-0">Recent notes</h2><a href="notes.php" class="small text-decoration-none">View all</a></div>
            <div class="row g-3">
                <?php if (!$recentNotes): ?>
                    <div class="col-12"><div class="empty-state">No notes yet. Start by creating your first note.</div></div>
                <?php endif; ?>
                <?php foreach ($recentNotes as $note): ?>
                    <div class="col-md-6">
                        <div class="note-card h-100 border">
                            <div class="d-flex justify-content-between align-items-start gap-3 mb-2">
                                <div>
                                    <span class="badge text-bg-light border mb-2"><?= h($note['category_name'] ?: 'Uncategorized') ?></span>
                                    <h3 class="h5 mb-1"><a href="view_note.php?id=<?= (int) $note['id'] ?>" class="text-decoration-none stretched-link text-dark"><?= h($note['title']) ?></a></h3>
                                </div>
                                <span class="badge priority-<?= strtolower($note['priority']) ?>"><?= h($note['priority']) ?></span>
                            </div>
                            <p class="text-secondary mb-2"><?= h(mb_strimwidth(strip_tags($note['content']), 0, 140, '...')) ?></p>
                            <small class="text-secondary">Updated <?= date('M d, Y', strtotime($note['updated_at'])) ?></small>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="panel-card h-100">
            <h2 class="h4 mb-3">Pinned & favorite notes</h2>
            <?php if (!$favorites): ?>
                <div class="empty-state">Pin or favorite important notes to keep them top of mind.</div>
            <?php endif; ?>
            <div class="vstack gap-3">
                <?php foreach ($favorites as $favorite): ?>
                    <a href="view_note.php?id=<?= (int) $favorite['id'] ?>" class="favorite-item text-decoration-none">
                        <div>
                            <strong class="d-block text-dark"><?= h($favorite['title']) ?></strong>
                            <small class="text-secondary">Updated <?= date('M d, Y', strtotime($favorite['updated_at'])) ?></small>
                        </div>
                        <span class="badge priority-<?= strtolower($favorite['priority']) ?>"><?= h($favorite['priority']) ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<?php require_once 'footer.php'; ?>
