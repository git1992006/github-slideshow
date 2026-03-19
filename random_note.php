<?php
require_once 'includes/functions.php';
requireLogin();
$userId = (int) $_SESSION['user_id'];
$statement = $pdo->prepare('SELECT id, title FROM notes WHERE user_id = ? AND is_archived = 0 ORDER BY RAND() LIMIT 1');
$statement->execute([$userId]);
$note = $statement->fetch();
if ($note) {
    setFlash('flash_success', 'Random idea selected: ' . $note['title']);
    header('Location: view_note.php?id=' . $note['id']);
    exit;
}
setFlash('flash_error', 'Add a few notes first to use random idea mode.');
header('Location: dashboard.php');
exit;
