<?php
require_once 'includes/functions.php';
requireLogin();
$userId = (int) $_SESSION['user_id'];
$noteId = (int) ($_GET['id'] ?? 0);

$statement = $pdo->prepare('SELECT * FROM notes WHERE id = ? AND user_id = ? LIMIT 1');
$statement->execute([$noteId, $userId]);
$note = $statement->fetch();
if (!$note) {
    setFlash('flash_error', 'Note not found.');
    header('Location: notes.php');
    exit;
}

$categories = fetchCategories($pdo, $userId);
$existingNotes = $pdo->prepare('SELECT id, title FROM notes WHERE user_id = ? AND id != ? ORDER BY title ASC');
$existingNotes->execute([$userId, $noteId]);
$existingNotes = $existingNotes->fetchAll();
$selectedTags = array_column(fetchNoteTags($pdo, $noteId), 'name');
$selectedLinkedNotes = array_column(fetchLinkedNotes($pdo, $noteId, $userId), 'id');
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $categoryId = (int) ($_POST['category_id'] ?? 0);
    $priority = $_POST['priority'] ?? 'Medium';
    $status = $_POST['status'] ?? 'Active';
    $isFavorite = isset($_POST['is_favorite']) ? 1 : 0;
    $isPinned = isset($_POST['is_pinned']) ? 1 : 0;
    $colorLabel = trim($_POST['color_label'] ?? '#7c3aed');
    $tagNames = array_filter(array_map('trim', explode(',', $_POST['tags'] ?? '')));
    $linkedNoteIds = $_POST['linked_notes'] ?? [];

    if ($title === '' || $content === '') {
        $errors[] = 'Title and content are required.';
    }

    if (!$errors) {
        $update = $pdo->prepare('UPDATE notes SET category_id = ?, title = ?, content = ?, priority = ?, status = ?, is_favorite = ?, is_pinned = ?, color_label = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ? AND user_id = ?');
        $update->execute([$categoryId ?: null, $title, $content, $priority, $status, $isFavorite, $isPinned, $colorLabel, $noteId, $userId]);
        syncNoteTags($pdo, $noteId, $userId, $tagNames);
        syncLinkedNotes($pdo, $noteId, $userId, $linkedNoteIds);
        setFlash('flash_success', 'Note updated successfully.');
        header('Location: view_note.php?id=' . $noteId);
        exit;
    }

    $note = array_merge($note, $_POST, ['is_favorite' => $isFavorite, 'is_pinned' => $isPinned]);
    $selectedTags = $tagNames;
    $selectedLinkedNotes = array_map('intval', $linkedNoteIds);
}

$pageTitle = 'Edit Note';
require_once 'header.php';
include 'note_form.php';
require_once 'footer.php';
