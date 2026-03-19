<?php
require_once 'includes/functions.php';
requireLogin();
$userId = (int) $_SESSION['user_id'];
$search = trim($_GET['search'] ?? '');
$categoryId = (int) ($_GET['category_id'] ?? 0);
$priority = trim($_GET['priority'] ?? '');
$tagId = (int) ($_GET['tag_id'] ?? 0);
$sort = $_GET['sort'] ?? 'newest';

$sortOptions = [
    'newest' => 'n.created_at DESC',
    'oldest' => 'n.created_at ASC',
    'priority' => "FIELD(n.priority, 'High', 'Medium', 'Low')",
];
$orderBy = $sortOptions[$sort] ?? $sortOptions['newest'];

$sql = baseNoteQuery() . ' WHERE n.user_id = :user_id AND n.is_archived = 0';
$params = ['user_id' => $userId];

if ($search !== '') {
    $sql .= ' AND (n.title LIKE :search OR n.content LIKE :search)';
    $params['search'] = '%' . $search . '%';
}
if ($categoryId > 0) {
    $sql .= ' AND n.category_id = :category_id';
    $params['category_id'] = $categoryId;
}
if ($priority !== '') {
    $sql .= ' AND n.priority = :priority';
    $params['priority'] = $priority;
}
if ($tagId > 0) {
    $sql .= ' AND EXISTS (SELECT 1 FROM note_tags x WHERE x.note_id = n.id AND x.tag_id = :tag_id)';
    $params['tag_id'] = $tagId;
}

$sql .= ' GROUP BY n.id ORDER BY ' . $orderBy;
$statement = $pdo->prepare($sql);
$statement->execute($params);
$notes = $statement->fetchAll();
$categories = fetchCategories($pdo, $userId);
$tags = fetchTags($pdo, $userId);

$pageTitle = 'All Notes';
require_once 'header.php';
?>
<div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
    <div>
        <span class="section-kicker">Notes</span>
        <h1 class="fw-bold mb-1">Browse your knowledge vault</h1>
        <p class="text-secondary mb-0">Search and filter by content, category, tag, and priority.</p>
    </div>
    <a href="add_note.php" class="btn btn-primary rounded-pill"><i class="bi bi-plus-lg me-2"></i>Add Note</a>
</div>
<div class="panel-card mb-4">
    <form method="get" class="row g-3 align-items-end">
        <div class="col-lg-4"><label class="form-label">Search</label><input type="text" name="search" value="<?= h($search) ?>" class="form-control" placeholder="Search title or content"></div>
        <div class="col-md-6 col-lg-2"><label class="form-label">Category</label><select name="category_id" class="form-select"><option value="0">All</option><?php foreach ($categories as $category): ?><option value="<?= (int) $category['id'] ?>" <?= $categoryId === (int) $category['id'] ? 'selected' : '' ?>><?= h($category['name']) ?></option><?php endforeach; ?></select></div>
        <div class="col-md-6 col-lg-2"><label class="form-label">Tag</label><select name="tag_id" class="form-select"><option value="0">All</option><?php foreach ($tags as $tag): ?><option value="<?= (int) $tag['id'] ?>" <?= $tagId === (int) $tag['id'] ? 'selected' : '' ?>><?= h($tag['name']) ?></option><?php endforeach; ?></select></div>
        <div class="col-md-6 col-lg-2"><label class="form-label">Priority</label><select name="priority" class="form-select"><option value="">All</option><?php foreach (['Low', 'Medium', 'High'] as $level): ?><option value="<?= $level ?>" <?= $priority === $level ? 'selected' : '' ?>><?= $level ?></option><?php endforeach; ?></select></div>
        <div class="col-md-6 col-lg-2"><label class="form-label">Sort</label><select name="sort" class="form-select"><option value="newest" <?= $sort === 'newest' ? 'selected' : '' ?>>Newest</option><option value="oldest" <?= $sort === 'oldest' ? 'selected' : '' ?>>Oldest</option><option value="priority" <?= $sort === 'priority' ? 'selected' : '' ?>>Priority</option></select></div>
        <div class="col-12 d-flex gap-2"><button class="btn btn-primary" type="submit">Apply Filters</button><a href="notes.php" class="btn btn-outline-secondary">Reset</a></div>
    </form>
</div>
<div class="row g-4">
    <?php if (!$notes): ?>
        <div class="col-12"><div class="empty-state">No notes matched your filters.</div></div>
    <?php endif; ?>
    <?php foreach ($notes as $note): ?>
        <div class="col-md-6 col-xl-4">
            <div class="note-card h-100 shadow-sm">
                <div class="d-flex justify-content-between align-items-start gap-3">
                    <span class="badge text-bg-light border"><?= h($note['category_name'] ?: 'Uncategorized') ?></span>
                    <div class="d-flex gap-2 align-items-center">
                        <?php if ((int) $note['is_pinned'] === 1): ?><i class="bi bi-pin-angle-fill text-primary"></i><?php endif; ?>
                        <?php if ((int) $note['is_favorite'] === 1): ?><i class="bi bi-star-fill text-warning"></i><?php endif; ?>
                    </div>
                </div>
                <h2 class="h5 mt-3"><a href="view_note.php?id=<?= (int) $note['id'] ?>" class="text-decoration-none text-dark stretched-link"><?= h($note['title']) ?></a></h2>
                <p class="text-secondary mb-3"><?= h(mb_strimwidth(strip_tags($note['content']), 0, 150, '...')) ?></p>
                <div class="d-flex flex-wrap gap-2 mb-3">
                    <?php foreach (array_filter(array_map('trim', explode(',', $note['tags_list'] ?? ''))) as $tagName): ?><span class="badge text-bg-light border"><?= h($tagName) ?></span><?php endforeach; ?>
                </div>
                <div class="d-flex justify-content-between align-items-center small text-secondary">
                    <span class="badge priority-<?= strtolower($note['priority']) ?>"><?= h($note['priority']) ?></span>
                    <span><?= date('M d, Y', strtotime($note['updated_at'])) ?></span>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php require_once 'footer.php'; ?>
