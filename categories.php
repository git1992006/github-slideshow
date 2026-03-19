<?php
require_once 'includes/functions.php';
requireLogin();
$userId = (int) $_SESSION['user_id'];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $color = trim($_POST['color'] ?? '#7c3aed');
    if ($name === '') {
        $errors[] = 'Category name is required.';
    }
    if (!$errors) {
        $statement = $pdo->prepare('INSERT INTO categories (user_id, name, color) VALUES (?, ?, ?)');
        $statement->execute([$userId, $name, $color]);
        setFlash('flash_success', 'Category created successfully.');
        header('Location: categories.php');
        exit;
    }
}

if (isset($_GET['delete'])) {
    $deleteId = (int) $_GET['delete'];
    $statement = $pdo->prepare('DELETE FROM categories WHERE id = ? AND user_id = ?');
    $statement->execute([$deleteId, $userId]);
    setFlash('flash_success', 'Category deleted successfully.');
    header('Location: categories.php');
    exit;
}

$categories = $pdo->prepare('SELECT c.*, COUNT(n.id) AS note_count FROM categories c LEFT JOIN notes n ON n.category_id = c.id AND n.user_id = c.user_id WHERE c.user_id = ? GROUP BY c.id ORDER BY c.name ASC');
$categories->execute([$userId]);
$categories = $categories->fetchAll();
$pageTitle = 'Categories';
require_once 'header.php';
?>
<div class="row g-4">
    <div class="col-lg-4">
        <div class="panel-card">
            <span class="section-kicker">Organize</span>
            <h1 class="h3 fw-bold">Categories</h1>
            <p class="text-secondary">Create focused buckets for your knowledge vault.</p>
            <?php if ($errors): ?><div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $error): ?><li><?= h($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
            <form method="post" class="row g-3">
                <div class="col-12"><label class="form-label">Category Name</label><input type="text" name="name" class="form-control" required></div>
                <div class="col-12"><label class="form-label">Color</label><input type="color" name="color" class="form-control form-control-color" value="#7c3aed"></div>
                <div class="col-12 d-grid"><button type="submit" class="btn btn-primary rounded-pill">Add Category</button></div>
            </form>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="panel-card">
            <div class="d-flex justify-content-between align-items-center mb-3"><h2 class="h4 mb-0">Your categories</h2><span class="badge text-bg-light border"><?= count($categories) ?> total</span></div>
            <div class="row g-3">
                <?php foreach ($categories as $category): ?>
                    <div class="col-md-6"><div class="category-card"><div class="d-flex justify-content-between align-items-start"><div><span class="color-dot" style="background: <?= h($category['color']) ?>;"></span><strong><?= h($category['name']) ?></strong><p class="text-secondary mb-0 small"><?= (int) $category['note_count'] ?> notes</p></div><a href="categories.php?delete=<?= (int) $category['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this category?');">Delete</a></div></div></div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<?php require_once 'footer.php'; ?>
