<?php
require_once 'includes/functions.php';
requireLogin();
$userId = (int) $_SESSION['user_id'];
$noteId = (int) ($_GET['id'] ?? 0);
$statement = $pdo->prepare('SELECT title, content, created_at, updated_at FROM notes WHERE id = ? AND user_id = ? LIMIT 1');
$statement->execute([$noteId, $userId]);
$note = $statement->fetch();
if (!$note) {
    setFlash('flash_error', 'Note not found for export.');
    header('Location: notes.php');
    exit;
}
header('Content-Type: text/plain; charset=UTF-8');
header('Content-Disposition: attachment; filename="note-' . $noteId . '.txt"');
echo "Title: {$note['title']}\n";
echo 'Created: ' . $note['created_at'] . "\n";
echo 'Updated: ' . $note['updated_at'] . "\n\n";
echo $note['content'];
exit;
