<?php
require_once 'includes/functions.php';
requireLogin();
$userId = (int) $_SESSION['user_id'];
$categories = fetchCategories($pdo, $userId);
$existingNotes = $pdo->prepare('SELECT id, title FROM notes WHERE user_id = ? ORDER BY title ASC');
$existingNotes->execute([$userId]);
$existingNotes = $existingNotes->fetchAll();
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
        $statement = $pdo->prepare('INSERT INTO notes (user_id, category_id, title, content, priority, status, is_favorite, is_pinned, color_label) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $statement->execute([$userId, $categoryId ?: null, $title, $content, $priority, $status, $isFavorite, $isPinned, $colorLabel]);
        $noteId = (int) $pdo->lastInsertId();
        syncNoteTags($pdo, $noteId, $userId, $tagNames);
        syncLinkedNotes($pdo, $noteId, $userId, $linkedNoteIds);
        setFlash('flash_success', 'Note created successfully.');
        header('Location: view_note.php?id=' . $noteId);
        exit;
    }
}

$pageTitle = 'Add Note';
require_once 'header.php';
include 'note_form.php';
require_once 'footer.php';
